<?php

namespace App\Http\Controllers;

use App\EcommerceModel\SalesPayment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SalesPaymentFileController extends Controller
{
    protected const UNSAFE_CHARS_REGEX = '[#%&?]';

    protected const DISK = 'public';
    protected const DEFAULT_FOLDER = 'payments';
    
    public function index(Request $request)
    {
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        if (!$start_date || !$end_date) {
            return response()->json([
                'error' => 'Both start_date and end_date query parameters are required in YYYY-MM-DD format.',
            ], 400);
        }

        $records = SalesPayment::query()
            ->whereNotNull('file_url')
            ->where('file_url', '!=', '')
            ->whereRaw("file_url REGEXP '" . self::UNSAFE_CHARS_REGEX . "'")
            ->whereBetween('payment_date', [$start_date, $end_date])
            ->orderBy('id')
            ->get(['id', 'sales_header_id', 'file_url','payment_date']);

        $results = $records->map(function (SalesPayment $record) {
            $relativePath = $this->extractRelativePath($record->file_url);
            $exists = Storage::disk(self::DISK)->exists($relativePath);

            $dirname = trim(dirname($relativePath), '.');
            $basename = basename($relativePath);
            $sanitizedBasename = $this->sanitizeFilename($basename);

            return [
                'id' => $record->id,
                'payment_date' => $record->payment_date,
                'sales_header_id' => $record->sales_header_id,
                'file_url' => $record->file_url,
                'resolved_storage_path' => $relativePath,
                'file_exists_in_storage' => $exists,
                'proposed_sanitized_filename' => $sanitizedBasename,
                'proposed_new_path' => ($dirname !== '' ? $dirname . '/' : '') . $sanitizedBasename,
            ];
        });

        return response()->json([
            'total_found' => $results->count(),
            'records' => $results,
        ]);
    }

    public function fix(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['sometimes', 'string'],
            'dry_run' => ['sometimes'],
        ]);
 
        $dryRun = true;
        if (array_key_exists('dry_run', $validated)) {
            $dryRun = filter_var($validated['dry_run'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true;
        }
 
        $ids = [];
        if (!empty($validated['ids'])) {
            $ids = array_filter(
                array_map('trim', explode(',', $validated['ids'])),
                fn ($v) => $v !== '' && is_numeric($v)
            );
            $ids = array_map('intval', $ids);
        }
 
        $query = SalesPayment::query()
            ->whereNotNull('file_url')
            ->where('file_url', '!=', '')
            ->whereRaw("file_url REGEXP '" . self::UNSAFE_CHARS_REGEX . "'");
 
        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }
 
        $records = $query->orderBy('id')->get();
 
        $fixed = [];
        $skipped = [];
        $failed = [];
 
        foreach ($records as $record) {
            try {
                $relativePath = $this->extractRelativePath($record->file_url);
 
                if (!Storage::disk(self::DISK)->exists($relativePath)) {
                    $skipped[] = [
                        'id' => $record->id,
                        'file_url' => $record->file_url,
                        'reason' => 'File not found in storage at expected path: ' . $relativePath,
                    ];
                    continue;
                }
 
                $dirname = trim(dirname($relativePath), '.');
                $basename = basename($relativePath);
                $sanitizedBasename = $this->sanitizeFilename($basename);
 
                if ($sanitizedBasename === $basename) {
                    $skipped[] = [
                        'id' => $record->id,
                        'file_url' => $record->file_url,
                        'reason' => 'Sanitized name is identical to current name, nothing to do.',
                    ];
                    continue;
                }
 
                $folder = $dirname !== '' ? $dirname : self::DEFAULT_FOLDER;
                $newRelativePath = $this->uniquePath($folder, $sanitizedBasename);
 
                if ($dryRun) {
                    $fixed[] = [
                        'id' => $record->id,
                        'old_file_url' => $record->file_url,
                        'old_storage_path' => $relativePath,
                        'new_storage_path' => $newRelativePath,
                        'new_file_url' => $this->rebuildFileUrl($record->file_url, $relativePath, $newRelativePath),
                        'dry_run' => true,
                    ];
                    continue;
                }
 
                DB::transaction(function () use ($record, $relativePath, $newRelativePath, &$fixed) {
                    Storage::disk(self::DISK)->copy($relativePath, $newRelativePath);
 
                    $newFileUrl = $this->rebuildFileUrl($record->file_url, $relativePath, $newRelativePath);
                    $oldFileUrl = $record->file_url;
 
                    $record->file_url = $newFileUrl;
                    $record->save();
 
                    $fixed[] = [
                        'id' => $record->id,
                        'old_file_url' => $oldFileUrl,
                        'old_storage_path' => $relativePath,
                        'new_storage_path' => $newRelativePath,
                        'new_file_url' => $newFileUrl,
                        'dry_run' => false,
                    ];
                });
            } catch (\Throwable $e) {
                $failed[] = [
                    'id' => $record->id,
                    'file_url' => $record->file_url,
                    'error' => $e->getMessage(),
                ];
            }
        }
 
        return response()->json([
            'dry_run' => $dryRun,
            'total_matched' => $records->count(),
            'fixed_count' => count($fixed),
            'skipped_count' => count($skipped),
            'failed_count' => count($failed),
            'fixed' => $fixed,
            'skipped' => $skipped,
            'failed' => $failed,
        ]);
    }

    protected function extractRelativePath(string $fileUrl): string
    {
        $marker = '/storage/';
        $pos = strpos($fileUrl, $marker);
 
        if ($pos !== false) {
            return ltrim(substr($fileUrl, $pos + strlen($marker)), '/');
        }
 
        return ltrim($fileUrl, '/');
    }
 
    protected function rebuildFileUrl(string $originalFileUrl, string $oldRelativePath, string $newRelativePath): string
    {
        $pos = strrpos($originalFileUrl, $oldRelativePath);
 
        if ($pos === false) {
            return $newRelativePath;
        }
 
        return substr_replace($originalFileUrl, $newRelativePath, $pos, strlen($oldRelativePath));
    }
 
    protected function sanitizeFilename(string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
 
        $clean = preg_replace('/[^A-Za-z0-9\-_]+/', '_', $name);
        $clean = trim(preg_replace('/_+/', '_', $clean), '_');
 
        if ($clean === '') {
            $clean = 'file';
        }
 
        return $extension !== '' ? "{$clean}.{$extension}" : $clean;
    }
 
    protected function uniquePath(string $folder, string $filename): string
    {
        $candidate = trim($folder, '/') . '/' . $filename;
 
        if (!Storage::disk(self::DISK)->exists($candidate)) {
            return $candidate;
        }
 
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $counter = 1;
 
        do {
            $newFilename = $extension !== '' ? "{$name}_{$counter}.{$extension}" : "{$name}_{$counter}";
            $candidate = trim($folder, '/') . '/' . $newFilename;
            $counter++;
        } while (Storage::disk(self::DISK)->exists($candidate));
 
        return $candidate;
    }
}