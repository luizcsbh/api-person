<?php

namespace App\Http\Requests\Endereco;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnderecoRequest extends FormRequest
{
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'cid_id' => 'sometimes|required|exists:cidades,cid_id',
            'end_tipo_logradouro' => 'sometimes|required|string|max:50|min:3',
            'end_logradouro' => 'sometimes|required|string|max:200|min:3',
            'end_numero' => 'sometimes|required|integer|min:0',
            'end_bairro' => 'sometimes|required|string|max:100|min:3',
        ];
    }

    /**
     * Get the validation messages for the defined rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
        // CID_ID
        'cid_id.required' => 'O campo cidade é obrigatório',
        'cid_id.exists' => 'A cidade selecionada não existe',

        // END_TIPO_LOGRADOURO
        'end_tipo_logradouro.required' => 'O tipo de logradouro é obrigatório',
        'end_tipo_logradouro.string' => 'O tipo de logradouro deve ser texto',
        'end_tipo_logradouro.max' => 'O tipo de logradouro não pode exceder 50 caracteres',
        'end_tipo_logradouro.min' => 'O tipo de logradouro deve ter pelo menos 3 caracteres',

        // END_LOGRADOURO
        'end_logradouro.required' => 'O logradouro é obrigatório',
        'end_logradouro.string' => 'O logradouro deve ser texto',
        'end_logradouro.max' => 'O logradouro não pode exceder 200 caracteres',
        'end_logradouro.min' => 'O logradouro deve ter pelo menos 3 caracteres',

        // END_NUMERO
        'end_numero.required' => 'O número é obrigatório',
        'end_numero.integer' => 'O número deve ser um valor inteiro',
        'end_numero.min' => 'O número não pode ser negativo', 

        // END_BAIRRO
        'end_bairro.required' => 'O bairro é obrigatório',
        'end_bairro.string' => 'O bairro deve ser texto',
        'end_bairro.max' => 'O bairro não pode exceder 100 caracteres',
        'end_bairro.min' => 'O bairro deve ter pelo menos 3 caracteres',
        ];
    }
}