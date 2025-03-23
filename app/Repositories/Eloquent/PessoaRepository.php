<?php

namespace App\Repositories\Eloquent;

use App\Models\Pessoa;
use App\Repositories\PessoaRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PessoaRepository implements PessoaRepositoryInterface
{
    protected $model;

    public function __construct(Pessoa $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with(['enderecos','lotacoes'])
            ->paginate($perPage);
    }

    public function findById($id)
    {
        return $this->model->with(['enderecos'])->find($id);
    }

    public function findByIdWithRelations($id)
    {
        return $this->model->with(['pessoa,enderecos'])
            ->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);     
    }

    public function update(array $data, $id)
    {
        return $this->model->update($data);
    }

    public function delete($id)
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function attachEndereco(int $pesId, int $enderecoId)
    {
        $pessoa = $this->model->findOrFail($pesId);
        $pessoa->enderecos()->attach($enderecoId);
    }

}