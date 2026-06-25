<?php

namespace App\Support\Filament;

use Closure;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;

class ResourceTableActions
{
    public static function replicateAction(): ReplicateAction
    {
        return ReplicateAction::make()
            ->label('复制')
            ->modalHeading('复制记录')
            ->modalSubmitActionLabel('确认复制')
            ->successNotificationTitle('复制成功')
            ->excludeAttributes(['id', 'created_at', 'updated_at'])
            ->mutateRecordDataUsing(fn (array $data): array => self::mutateReplicaData($data));
    }

    public static function deleteAction(?Closure $configure = null): DeleteAction
    {
        $action = DeleteAction::make()->label('删除');

        if ($configure) {
            $configure($action);
        }

        return $action;
    }

    public static function editAction(?string $label = null): EditAction
    {
        $action = EditAction::make();

        if ($label) {
            $action->label($label);
        }

        return $action;
    }

    /**
     * @param  array<int, \Filament\Actions\Action>  $prepend
     */
    public static function recordActions(
        array $prepend = [],
        bool $replicate = true,
        bool $delete = true,
        ?Closure $configureDelete = null,
        ?Closure $configureReplicate = null,
        ?Closure $configureEdit = null,
        ?string $editLabel = null,
    ): array {
        $actions = $prepend;
        $edit = self::editAction($editLabel);

        if ($configureEdit) {
            $configureEdit($edit);
        }

        $actions[] = $edit;

        if ($replicate) {
            $replicateAction = self::replicateAction();

            if ($configureReplicate) {
                $configureReplicate($replicateAction);
            }

            $actions[] = $replicateAction;
        }

        if ($delete) {
            $actions[] = self::deleteAction($configureDelete);
        }

        return $actions;
    }

    /**
     * @return array<int, BulkActionGroup>
     */
    public static function toolbarActions(bool $bulkDelete = true, ?Closure $configureBulkDelete = null): array
    {
        if (! $bulkDelete) {
            return [];
        }

        $bulkDeleteAction = DeleteBulkAction::make()->label('批量删除');

        if ($configureBulkDelete) {
            $configureBulkDelete($bulkDeleteAction);
        }

        return [
            BulkActionGroup::make([
                $bulkDeleteAction,
            ]),
        ];
    }

    public static function mutateReplicaData(array $data): array
    {
        $suffix = '-copy-'.strtolower(substr(uniqid(), -5));

        foreach (['slug', 'key', 'menu_key', 'setting_key'] as $field) {
            if (! empty($data[$field]) && is_string($data[$field])) {
                $data[$field] = $data[$field].$suffix;
            }
        }

        foreach (['title', 'name', 'label', 'meta_title', 'intro_title', 'page_title'] as $field) {
            if (! empty($data[$field]) && is_string($data[$field])) {
                $data[$field] = $data[$field].' (副本)';
            }
        }

        if (! empty($data['email']) && is_string($data['email'])) {
            $parts = explode('@', $data['email'], 2);

            if (count($parts) === 2) {
                $data['email'] = $parts[0].'+copy'.substr(uniqid(), -4).'@'.$parts[1];
            }
        }

        return $data;
    }
}
