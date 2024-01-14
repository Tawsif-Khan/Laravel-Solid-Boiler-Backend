<?php

namespace App\Services\Model;

use Illuminate\Database\Eloquent\Collection;

class DataTableService
{
    private array $columns = [];

    private array $actions = [];

    private array $classes = [
        'Actions' => [
            'view' => 'green',
            'edit' => 'cyan',
            'delete' => 'red',
        ],
    ];

    private function setColumns(?array $columns): void
    {
        $this->columns = $columns ? $columns : [];
    }

    private function setActions(?array $actions): void
    {
        $this->actions = $actions ? $actions : [];
        if (! empty($this->actions)) {
            $this->columns[] = 'Actions';
        }
    }

    private function formatColumns(array $columns): array
    {
        return collect($columns)->map(fn ($column) => ucwords(str_replace('_', ' ', $column)))->all();
    }

    private function getActionButtons(string $column, $value): array
    {
        return collect($this->actions)->map(function ($action) use ($column, $value) {
            return [
                'column' => $column,
                'id' => $value,
                'label' => $action,
                'class' => $this->classes[$column][$action] ?? '',
            ];
        })->all();
    }

    private function formatCell(string $column, $value): array
    {
        return $column === 'Actions'
            ? $this->getActionButtons($column, $value)
            : [
                'column' => $column,
                'class' => $this->classes[$column][$value] ?? '',
                'value' => $value,
            ];
    }

    private function formatRow(mixed $item): array
    {
        return collect($this->columns)->map(function ($column) use ($item) {
            return $column === 'Actions'
            ? $this->formatCell($column, $item[$column] ?? $item['id'])
            : $this->formatCell($column, $item[$column] ?? null);
        })->all();
    }

    public function configureTable(array $columns = null, array $actions = null): DataTableService
    {
        $this->setColumns($columns);
        $this->setActions($actions);

        return $this;
    }

    public function getFormattedTableData(Collection|array $collections): array
    {
        if (! $collections instanceof Collection) {
            $collections = collect($collections);
        }

        return [
            'headings' => $this->formatColumns($this->columns),
            'rows' => $collections->map(function ($item) {
                return $this->formatRow($item);
            })->all(),
        ];
    }
}
