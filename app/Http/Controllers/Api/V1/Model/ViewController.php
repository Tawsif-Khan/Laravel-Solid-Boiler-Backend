<?php

namespace App\Http\Controllers\Api\V1\Model;

use App\Http\Controllers\Controller;
use App\Traits\Validation\HasRules;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ViewController extends Controller
{
    use HasRules;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $id)
    {
        $request->validate(
            [
                'model' => ['required', Rule::in($this->commonRules()['model'])],
            ]
        );

        $requestedModel = $request->model;

        try {
            $model = config('models.'.$requestedModel);
            $model = $model::findOrFail($id);

            return response()->json(['data' => $model], Response::HTTP_OK);
        } catch (ModelNotFoundException) {
            return response()->json('Data not found.', Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
