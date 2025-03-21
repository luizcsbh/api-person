<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Models\Pessoas;
use Illuminate\Support\Facades\DB;
use App\Repositories\PessoaRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PessoaRepository implements PessoaRepositoryInterface
{
    protected $model;

    public function __construct(Pessoas $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with(['enderecos','lotacoes','servidorEfetivo'])
            ->paginate($perPage);
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            $pessoa = $this->model->create($data);
            DB::commit();
            return $pessoa;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(array $data, $id)
    {
        try {
            DB::beginTransaction();
            $pessoa = $this->model->find($id);
            if (!$pessoa) {
                throw new Exception('Pessoa não encontrada.');
            }
            $pessoa->update($data);
            DB::commit();
            return $pessoa;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $pessoa = $this->model->find($id);
            if (!$pessoa) {
                throw new Exception('Pessoa não encontrada.');
            }
            $pessoa->delete();
            DB::commit();
            return $pessoa;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}