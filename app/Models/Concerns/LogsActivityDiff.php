<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait LogsActivityDiff
{
    public static function bootLogsActivityDiff(): void
    {
        static::updating(function (Model $model) {
            // Don’t log ActivityLog updates to avoid recursion.
            if ($model instanceof ActivityLog) {
                return;
            }

            $dirty = $model->getDirty();
            if (empty($dirty)) {
                return;
            }

            // Apply allow/deny filters
            $dirty = self::filterDirtyKeys($model, $dirty);
            if (empty($dirty)) {
                return;
            }

            $tableTitle = property_exists($model, 'tableTitle')
                ? $model::$tableTitle
                : str(class_basename($model))->headline()->toString();

            // "name" of the record for human-readable logs
            $nameAttr = property_exists($model, 'logNameAttribute') ? $model->logNameAttribute : null;
            $recordName = $nameAttr && $model->getAttribute($nameAttr)
                ? (string) $model->getAttribute($nameAttr)
                : (string) ($model->getAttribute('name') ?? $model->getAttribute('title') ?? $model->getKey());

            $userId = auth()->id() ?? null;

            foreach ($dirty as $field => $newValue) {
                $oldValue = $model->getOriginal($field);

                // Optionally mask sensitive values
                if (self::shouldMask($model, $field)) {
                    $oldValue = self::masked($oldValue);
                    $newValue = self::masked($newValue);
                }

                // Normalize for text storage
                [$oldText, $newText] = [self::stringify($oldValue), self::stringify($newValue)];
                $fieldLabel = self::fieldLabel($model, $field);

                // Build text chunks
                $fieldNames = $fieldLabel; // you can join multiple if you later aggregate
                $dashboardText = "updated the {$tableTitle} {$fieldNames}";
                $descText = "updated the {$tableTitle} {$fieldNames} of {$recordName} from {$oldText} to {$newText}";

                // Avoid firing events from ActivityLog itself
                ActivityLog::withoutEvents(function () use (
                    $userId, $dashboardText, $descText, $model, $oldText, $newText
                ) {
                    ActivityLog::create([
                        'created_by'         => $userId,
                        'activity_type'      => 'update',
                        'dashboard_activity' => $dashboardText,
                        'activity_desc'      => $descText,
                        'activity_date'      => now()->format('Y-m-d H:i:s'),
                        'db_table'           => $model->getTable(),
                        'old_value'          => $oldText,
                        'new_value'          => $newText,
                        'reference'          => $model->getKey(),
                    ]);
                });
            }
        });

        static::deleting(function (Model $model) {
            // snapshot full original state before it disappears
            $model->__activity_originals_for_delete = $model->getOriginal();
        });

        static::deleted(function (Model $model) {
            $orig = $model->__activity_originals_for_delete ?? null;
            unset($model->__activity_originals_for_delete);

            // Determine soft vs force delete
            $action = method_exists($model, 'isForceDeleting') && $model->isForceDeleting()
                ? 'force_delete'
                : (in_array('Illuminate\\Database\\Eloquent\\SoftDeletes', class_uses_recursive($model)) ? 'soft_delete' : 'delete');

            $tableTitle = property_exists($model, 'tableTitle')
                ? $model::$tableTitle
                : str(class_basename($model))->headline()->toString();

            $recordName = $model->getAttribute($model->logNameAttribute ?? 'name')
                ?? $model->getAttribute('title')
                ?? ($orig['name'] ?? $orig['title'] ?? $model->getKey());

            ActivityLog::withoutEvents(function () use ($model, $orig, $action, $tableTitle, $recordName) {
                ActivityLog::create([
                    'created_by'         => auth()->id(),
                    'activity_type'      => $action, // 'soft_delete' | 'force_delete' | 'delete'
                    'dashboard_activity' => "{$action} the {$tableTitle}",
                    'activity_desc'      => "{$action} the {$tableTitle} {$recordName}",
                    'activity_date'      => now()->format('Y-m-d H:i:s'),
                    'db_table'           => $model->getTable(),
                    'old_value'          => $orig ? json_encode($orig, JSON_UNESCAPED_UNICODE) : null,
                    'new_value'          => null,
                    'reference'          => $model->getKey(),
                ]);
            });
        });

        static::restored(function (Model $model) {
            $tableTitle = property_exists($model, 'tableTitle')
                ? $model::$tableTitle
                : str(class_basename($model))->headline()->toString();

            $recordName = $model->getAttribute($model->logNameAttribute ?? 'name')
                ?? $model->getAttribute('title')
                ?? $model->getKey();

            ActivityLog::withoutEvents(function () use ($model, $tableTitle, $recordName) {
                ActivityLog::create([
                    'created_by'         => auth()->id(),
                    'activity_type'      => 'restore',
                    'dashboard_activity' => "restored the {$tableTitle}",
                    'activity_desc'      => "restored the {$tableTitle} {$recordName}",
                    'activity_date'      => now()->format('Y-m-d H:i:s'),
                    'db_table'           => $model->getTable(),
                    'old_value'          => null,
                    'new_value'          => null,
                    'reference'          => $model->getKey(),
                ]);
            });
        });

    }

    protected static function filterDirtyKeys(Model $model, array $dirty): array
    {
        $only   = property_exists($model, 'logOnly')   ? (array) $model->logOnly   : [];
        $except = property_exists($model, 'logExcept') ? (array) $model->logExcept : ['updated_at'];

        if (!empty($only)) {
            $dirty = Arr::only($dirty, $only);
        }
        if (!empty($except)) {
            $dirty = Arr::except($dirty, $except);
        }
        return $dirty;
    }

    protected static function shouldMask(Model $model, string $field): bool
    {
        $mask = property_exists($model, 'logMask') ? (array) $model->logMask : ['password', 'token', 'secret', 'api_key'];
        return in_array($field, $mask, true);
    }

    protected static function masked($value): string
    {
        return is_null($value) ? 'NULL' : '••••••';
    }

    protected static function stringify($value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_array($value) || is_object($value)) {
            // Be careful with huge payloads
            $json = substr(json_encode($value, JSON_UNESCAPED_UNICODE), 0, 2000);
            return $json === '' ? '(empty)' : $json;
        }
        $str = (string) $value;
        return $str === '' ? '(empty)' : mb_strimwidth($str, 0, 2000, '…');
    }

    protected static function fieldLabel(Model $model, string $field): string
    {
        // Optional per-model map: protected array $fieldLabels = ['status' => 'Status'];
        $labels = property_exists($model, 'fieldLabels') ? (array) $model->fieldLabels : [];
        return $labels[$field] ?? str($field)->headline()->toString(); // e.g., "first_name" -> "First Name"
    }
}
