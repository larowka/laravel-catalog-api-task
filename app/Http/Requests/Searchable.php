<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

trait Searchable
{
    public static function validationRules(): array
    {
        return [
            'lat'    => ['numeric', 'between:-180,180'],
            'lon'    => ['numeric', 'between:-90,90'],
            'lat2'   => ['numeric', 'between:-180,180'],
            'lon2'   => ['numeric', 'between:-90,90'],
            'radius' => ['numeric', 'gt:0'],
            'width'  => ['numeric', 'gt:0'],
            'height' => ['numeric', 'gt:0'],
            'metric' => ['string']
        ];
    }

    public static function prepareForValidation(FormRequest &$request): void
    {
        $toFloat = $request->only(['lat', 'lon', 'lat2', 'lon2', 'radius', 'width', 'height']);
        foreach ($toFloat as $key => $value) {
            if (is_numeric($value))
                $request->merge([$key => floatval($value)]);
        }
    }
}
