<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;

trait LogsActivityDiff
{
    public static function bootLogsActivityDiff(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CREATE
        |--------------------------------------------------------------------------
        */
        static::created(function (Model $model) {
            if ($model instanceof ActivityLog) {
                return;
            }

            $tableTitle = property_exists($model, 'tableTitle')
                ? $model::$tableTitle
                : str(class_basename($model))->headline()->toString();

            $recordName = $model->getAttribute($model->logNameAttribute ?? 'name')
                ?? $model->getAttribute('title')
                ?? $model->getKey();

            $newValues = Arr::only(
                $model->getAttributes(),
                $model->getFillable()
            );

            ActivityLog::withoutEvents(function () use ($model, $tableTitle, $recordName, $newValues) {
                ActivityLog::create([
                    'created_by'         => auth()->id(),
                    'activity_type'      => 'create',
                    'dashboard_activity' => "created a {$tableTitle}",
                    'activity_desc'      => "created the {$tableTitle} {$recordName}",
                    'activity_date'      => now()->format('Y-m-d H:i:s'),
                    'db_table'           => $model->getTable(),
                    'old_value'          => null,
                    'new_value'          => json_encode($newValues, JSON_UNESCAPED_UNICODE),
                    'reference'          => $model->getKey(),
                    'subject_type'       => get_class($model),
                    'subject_id'         => $model->getKey(),
                    'ip_address'         => Request::ip(),
                ]);
            });
        });

        /*
        |--------------------------------------------------------------------------
        | UPDATE (FIELD DIFF)
        |--------------------------------------------------------------------------
        */
        static::updating(function (Model $model) {
            if ($model instanceof ActivityLog) {
                return;
            }

            $dirty = $model->getDirty();
            if (empty($dirty)) {
                return;
            }

            $dirty = self::filterDirtyKeys($model, $dirty);
            if (empty($dirty)) {
                return;
            }

            $tableTitle = property_exists($model, 'tableTitle')
                ? $model::$tableTitle
                : str(class_basename($model))->headline()->toString();

            $nameAttr = property_exists($model, 'logNameAttribute') ? $model->logNameAttribute : null;
            $recordName = $nameAttr && $model->getAttribute($nameAttr)
                ? (string) $model->getAttribute($nameAttr)
                : (string) ($model->getAttribute('name')
                    ?? $model->getAttribute('title')
                    ?? $model->getKey());

            foreach ($dirty as $field => $newValue) {
                $oldValue = $model->getOriginal($field);

                if (self::shouldMask($model, $field)) {
                    $oldValue = self::masked($oldValue);
                    $newValue = self::masked($newValue);
                }

                [$oldText, $newText] = [
                    self::stringify($oldValue),
                    self::stringify($newValue),
                ];

                $fieldLabel = self::fieldLabel($model, $field);

                ActivityLog::withoutEvents(function () use (
                    $model,
                    $tableTitle,
                    $recordName,
                    $fieldLabel,
                    $oldText,
                    $newText
                ) {
                    ActivityLog::create([
                        'created_by'         => auth()->check() ? auth()->id() : null,
                        'activity_type'      => 'update',
                        'dashboard_activity' => "updated the {$tableTitle} {$fieldLabel}",
                        'activity_desc'      => "updated the {$tableTitle} {$fieldLabel} of {$recordName} from {$oldText} to {$newText}",
                        'activity_date'      => now()->format('Y-m-d H:i:s'),
                        'db_table'           => $model->getTable(),
                        'old_value'          => $oldText,
                        'new_value'          => $newText,
                        'reference'          => $model->getKey(),
                        'subject_type'       => get_class($model),
                        'subject_id'         => $model->getKey(),
                        'ip_address'         => Request::ip(),
                        'email'              => auth()->check() ? auth()->user()?->email : null,
                        'role'               => auth()->check() ? auth()->user()?->user_role?->name : null,
                    ]);
                });
            }
        });

        /*
        |--------------------------------------------------------------------------
        | DELETE (SOFT / FORCE)
        |--------------------------------------------------------------------------
        */
        static::deleting(function (Model $model) {
            $model->__activity_originals_for_delete = $model->getOriginal();
        });

        static::deleted(function (Model $model) {
            $orig = $model->__activity_originals_for_delete ?? null;
            unset($model->__activity_originals_for_delete);

            $action = method_exists($model, 'isForceDeleting') && $model->isForceDeleting()
                ? 'force_delete'
                : (in_array('Illuminate\\Database\\Eloquent\\SoftDeletes', class_uses_recursive($model))
                    ? 'soft_delete'
                    : 'delete');

            $tableTitle = property_exists($model, 'tableTitle')
                ? $model::$tableTitle
                : str(class_basename($model))->headline()->toString();

            $recordName = $model->getAttribute($model->logNameAttribute ?? 'name')
                ?? $model->getAttribute('title')
                ?? ($orig['name'] ?? $orig['title'] ?? $model->getKey());

            ActivityLog::withoutEvents(function () use (
                $model,
                $orig,
                $action,
                $tableTitle,
                $recordName
            ) {
                ActivityLog::create([
                    'created_by'         => auth()->check() ? auth()->id() : null,
                    'activity_type'      => $action,
                    'dashboard_activity' => "{$action} the {$tableTitle}",
                    'activity_desc'      => "{$action} the {$tableTitle} {$recordName}",
                    'activity_date'      => now()->format('Y-m-d H:i:s'),
                    'db_table'           => $model->getTable(),
                    'old_value'          => $orig ? json_encode($orig, JSON_UNESCAPED_UNICODE) : null,
                    'new_value'          => null,
                    'reference'          => $model->getKey(),
                    'subject_type'       => get_class($model),
                    'subject_id'         => $model->getKey(),
                    'ip_address'         => Request::ip(),
                    'email'              => auth()->check() ? auth()->user()?->email : null,
                    'role'               => auth()->check() ? auth()->user()?->user_role?->name : null,
                ]);
            });
        });

        /*
        |--------------------------------------------------------------------------
        | RESTORE
        |--------------------------------------------------------------------------
        */
        if (in_array(
            \Illuminate\Database\Eloquent\SoftDeletes::class,
            class_uses_recursive(static::class)
        )) {
            static::restored(function (Model $model) {
                $tableTitle = property_exists($model, 'tableTitle')
                    ? $model::$tableTitle
                    : str(class_basename($model))->headline()->toString();

                $recordName = $model->getAttribute($model->logNameAttribute ?? 'name')
                    ?? $model->getAttribute('title')
                    ?? $model->getKey();

                ActivityLog::withoutEvents(function () use ($model, $tableTitle, $recordName) {
                    ActivityLog::create([
                        'created_by'         => auth()->check() ? auth()->id() : null,
                        'activity_type'      => 'restore',
                        'dashboard_activity' => "restored the {$tableTitle}",
                        'activity_desc'      => "restored the {$tableTitle} {$recordName}",
                        'activity_date'      => now()->format('Y-m-d H:i:s'),
                        'db_table'           => $model->getTable(),
                        'reference'          => $model->getKey(),
                        'subject_type'       => get_class($model),
                        'subject_id'         => $model->getKey(),
                        'ip_address'         => request()->ip(),
                        'email'              => auth()->check() ? auth()->user()?->email : null,
                        'role'               => auth()->check() ? auth()->user()?->user_role?->name : null,
                    ]);
                });
            });
        }

    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */
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
        $mask = property_exists($model, 'logMask')
            ? (array) $model->logMask
            : ['password', 'token', 'secret', 'api_key'];

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
            return mb_strimwidth(
                json_encode($value, JSON_UNESCAPED_UNICODE),
                0,
                2000,
                '…'
            );
        }

        $str = (string) $value;
        return $str === '' ? '(empty)' : mb_strimwidth($str, 0, 2000, '…');
    }

    protected static function fieldLabel(Model $model, string $field): string
    {
        $labels = property_exists($model, 'fieldLabels')
            ? (array) $model->fieldLabels
            : [];

        return $labels[$field] ?? str($field)->headline()->toString();
    }
}
