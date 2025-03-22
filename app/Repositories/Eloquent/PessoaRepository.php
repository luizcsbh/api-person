<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Models\Pessoas;
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
        return $this->model->with(['enderecos','lotacoes'])
            ->paginate($perPage);
    }

    public function findById($id)
    {
        return $this->model->with(['enderecos'])->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);     
    }

    public function update(array $data, $id)
    {
        return $this->model->update($data);
    }

    public function findWithRelations($id, array $relations)
    {
        return $this->model->with($relations)->find($id);
    }

    public function delete($id)
    {
        return $this->model->findOrFail($id)->delete();
    }
}