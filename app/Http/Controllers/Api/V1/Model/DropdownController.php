<?php

namespace App\Http\Controllers\Api\V1\Model;

use App\Http\Controllers\Controller;
use App\Traits\Model\HasWhere;
use App\Traits\Validation\HasRules;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class DropdownController extends Controller
{
    use HasRules, HasWhere;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate(
            [
                'where' => ['nullable', 'array'],
                'label' => ['required', 'string'],
                'model' => ['required', Rule::in($this->commonRules()['model'])],
            ]
        );

        $requestedModel = $request->model;
        $requestedLabel = $request->label;
        $requestedWhereConditions = $request?->where;

        $model = config('models.'.$requestedModel);

        $query = $model::query();
        if ($requestedWhereConditions) {
            $query = $this->buildWhereQuery(
                query: $query,
                whereConditions: $requestedWhereConditions
            );
        }

        $query = $query->select(['id as value', $requestedLabel.' as label']);

        $data[$requestedModel] = $query->get()->toArray();

        array_unshift(
            $data[$requestedModel],
            ['value' => null, 'label' => '-- Select --']
        );

        return response()->json($data, Response::HTTP_OK);
    }
}
