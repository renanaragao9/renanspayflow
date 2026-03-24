<?php

namespace App\Http\V1\Requests\Global;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Dados inválidos.',
            'messages' => $validator->errors()->all(),
            'errors' => $validator->errors()->toArray(),
        ], 422));
    }

    public function filtersDate(): array
    {
        return [
            'start_date' => ['required_with:end_date', 'date_format:Y-m-d'],
            'end_date' => ['required_with:start_date', 'date_format:Y-m-d', 'after:start_date'],
        ];
    }

    public function orderBy(): array
    {
        return [
            'order_by_column' => ['nullable', 'string'],
            'order_by_direction' => ['nullable', 'string', 'in:asc,desc'],
        ];
    }

    public function paginate(): array
    {
        return [
            'paginate' => ['nullable', 'string', 'in:true,false,1,0'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function sanitizeJsonData($fields)
    {
        foreach ($fields as $key => $value) {
            if (is_string($value)) {
                $fields[$key] = $this->removeHtmlTags($value);
                $fields[$key] = $this->removeJavaScriptFunctions($fields[$key]);
            }
            if (! strlen($fields[$key]) > 0) {
                unset($fields[$key]);
            }
        }

        return $fields;
    }

    public function removeHtmlTags($string): string
    {
        return strip_tags($string);
    }

    public function removeJavaScriptFunctions($string): string
    {
        $patterns = [
            '/<script.*?>(.*?)<\/script>/is',                     // Remove tags <script> e seu conteúdo
            '/javascript:/i',                                     // Remove qualquer uso de javascript em URLs
            '/on[a-z]+=[\'"][^\'"]*[\'"]/i',                      // Remove atributos de eventos (ex: onmouseover, onclick, etc)
            '/[a-z]+\([^)]*\)/i',                                 // Remove funções com parênteses (ex: alert(), prompt(), etc)
            '/<.*?javascript:.*?>/i',                             // Remove tags com 'javascript:' em qualquer atributo
            '/style\s*=\s*["\'][^"\']*javascript:[^"\']*["\']/i', // Remove uso de javascript em atributos style
            '/\s*;\s*/',                                          // Remove ponto e vírgula extra
        ];

        $cleanedString = preg_replace($patterns, '', $string);

        return trim($cleanedString);
    }
}
