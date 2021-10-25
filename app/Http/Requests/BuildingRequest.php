<?php

namespace App\Http\Requests;

use App\Models\Building;
use App\Rules\Utils;
use App\Rules\ValuesIn;
use Illuminate\Foundation\Http\FormRequest;

class BuildingRequest extends FormRequest
{
    use HasErrorResponse;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge([
                'companies' => ['array'],
                'sort' => ['array', 'size:2'],
                'with' => ['array', new ValuesIn(Building::$relationships)]
            ], Searchable::validationRules());
    }

    protected function prepareForValidation()
    {
        $toArray = $this->only(['companies', 'sort', 'with']);
        foreach ($toArray as $key => $value) {
            $this->merge([$key => Utils::stringToArray($value)]);
        }

        Searchable::prepareForValidation($this);
    }
}
