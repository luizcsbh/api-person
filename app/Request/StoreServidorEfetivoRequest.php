<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServidorEfetivoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'required|string',
            'matricula' => 'required|string',
            'cpf' => 'required|string',
            'email' => 'required|email',
            'telefone' => 'required|string',
            'endereco' => 'required|string',
            'cidade' => 'required|string',
            'estado' => 'required|string',
            'cep' => 'required|string',
            'cargo' => 'required|string',
            'lotacao' => 'required|string',
            'data_admissao' => 'required|date',
            'data_nascimento' => 'required|date'
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O campo nome é obrigatório',
            'nome.string' => 'O campo nome deve ser uma string',
            'matricula.required' => 'O campo matrícula é obrigatório',
            'matricula.string' => 'O campo matrícula deve ser uma string',
            'cpf.required' => 'O campo CPF é obrigatório',
            'cpf.string' => 'O campo CPF deve ser uma string',
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um email válido',
            'telefone.required' => 'O campo telefone é obrigatório',
            'telefone.string' => 'O campo telefone deve ser uma string',
            'endereco.required' => 'O campo endereço é obrigatório',
            'endereco.string' => 'O campo endereço deve ser uma string',
            'cidade.required' => 'O campo cidade é obrigatório',
            'cidade.string' => 'O campo cidade deve ser uma string',
            'estado.required' => 'O campo estado é obrigatório',
            'estado.string' => 'O campo estado deve ser uma string',
            'cep.required' => 'O campo CEP é obrigatório',
            'cep.string' => 'O campo CEP deve ser uma string',
            'cargo.required' => 'O campo cargo é obrigatório',
            'cargo.string' => 'O campo cargo deve ser uma string',
            'lotacao.required' => 'O campo lotação é obrigatório',
            'lotacao.string' => 'O campo lotação deve ser uma string',
            'data_admissao.required' => 'O campo data de admissão é obrigatório',
            'data_admissao.date' => 'O campo data de admissão deve ser uma data',
            'data_nascimento.required' => 'O campo data de nascimento é obrigatório',
            'data_nascimento.date' => 'O campo data de nascimento deve ser uma data'
        ];
    }
}