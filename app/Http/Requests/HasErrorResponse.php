<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

trait HasErrorResponse
{
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $this->respondWithErrors($errors);
    }

    protected function passedValidation()
    {
        $allParams = $this->request->all();
        $validParams = $this->validated();

        $invalidParams = array_diff_key($allParams, $validParams);

        if (count($invalidParams)) {
            $errors = array_map(fn($a) => 'Invalid query parameter', $invalidParams);
            $this->respondWithErrors($errors);
        }
    }

    private function respondWithErrors(array $errors)
    {
        throw new HttpResponseException(
            response()->json(['error' => [$errors]], 422)
        );
    }
}
