<?php

namespace App\Http\Controllers\Api\V1\Model;

use App\Http\Controllers\Controller;
use App\Traits\Validation\HasRules;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    use HasRules;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate(
            [
                'params' => ['required', 'array'],
                'model' => ['required', Rule::in($this->commonRules()['model'])],
            ]
        );

        $requestedModel = $request->model;
        $requestedParams = $request->params ?? null;

        $validator = Validator::make($requestedParams, $this->storeRules()[$requestedModel]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $model = config('models.'.$requestedModel);
            $result = $model::create($validator->validated());

            $modelName = str($requestedModel)->ucfirst();

            return response()->json(
                [
                    'message' => "{$modelName} stored successfully.",
                    'data' => $result,
                ], Response::HTTP_CREATED);
        } catch (UniqueConstraintViolationException) {
            return response()->json([
                'message' => 'Provided information already exists, please try again.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
