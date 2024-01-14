<?php

namespace App\Http\Controllers\Api\V1\Model;

use App\Http\Controllers\Controller;
use App\Services\Model\DataTableService;
use App\Traits\Model\HasWhere;
use App\Traits\Validation\HasRules;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class GetController extends Controller
{
    use HasRules, HasWhere;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, DataTableService $dataTable)
    {
        $request->validate(
            [
                'actions' => ['nullable', 'array'],
                'columns' => ['required', 'array'],
                'where' => ['nullable', 'array'],
                'model' => ['required', Rule::in($this->commonRules()['model'])],
            ]
        );

        $perPage = $request->query('per_page', 10);
        $pageNumber = $request->query('page_number', null);

        $requestedModel = $request->model;
        $requestedColumns = $request->columns;
        $requestedActions = $request?->actions;
        $requestedWhereConditions = $request?->where;

        $model = config('models.'.$requestedModel);

        $query = $model::query();

        if ($requestedWhereConditions) {
            $query = $this->buildWhereQuery(
                query: $query,
                whereConditions: $requestedWhereConditions
            );
        }

        $paginatedData = $query->latest()->paginate($perPage, ['*'], 'page', $pageNumber);

        $collections = $dataTable->configureTable(
            columns: $requestedColumns,
            actions: $requestedActions
        )->getFormattedTableData(
            collections: $paginatedData->items()
        );

        $pages = $paginatedData->toArray();
        unset($pages['data']);

        return response()->json(['data' => $collections, 'pages' => $pages], Response::HTTP_OK);
    }
}
