<?php

namespace App\Http\Requests\Pessoa;

use Illuminate\Foundation\Http\FormRequest;

class StorePessoaRequest extends FormRequest
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
            'pes_nome' => 'required|string|max:200|min:3',
            'pes_data_nascimento' => 'required|date',
            'pes_sexo' => 'required|string|max:9',
            'pes_mae' => 'required|string|max:200|min:3',
            'pes_pai' => 'required|string|max:200|min:3',
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
            'pes_nome.required' => 'O nome é obrigatório.',
            'pes_nome.string' => 'O nome deve ser um texto.',
            'pes_nome.max' => 'O nome pode ter no máximo 200 caracteres.',
            'pes_nome.min' => 'O nome deve ter no mínimo 3 caracteres.',
    
            'pes_data_nascimento.required' => 'A data de nascimento é obrigatória.',
            'pes_data_nascimento.date' => 'A data de nascimento deve ser uma data válida.',
    
            'pes_sexo.required' => 'O campo sexo é obrigatório.',
            'pes_sexo.string' => 'O sexo deve ser um texto.',
            'pes_sexo.max' => 'O sexo pode ter no máximo 9 caracteres.',
    
            'pes_mae.required' => 'O nome da mãe é obrigatório.',
            'pes_mae.string' => 'O nome da mãe deve ser um texto.',
            'pes_mae.max' => 'O nome da mãe pode ter no máximo 200 caracteres.',
            'pes_mae.min' => 'O nome da mãe deve ter no mínimo 3 caracteres.',
    
            'pes_pai.required' => 'O nome do pai é obrigatório.',
            'pes_pai.string' => 'O nome do pai deve ser um texto.',
            'pes_pai.max' => 'O nome do pai pode ter no máximo 200 caracteres.',
            'pes_pai.min' => 'O nome do pai deve ter no mínimo 3 caracteres.',
        ];
    }

}