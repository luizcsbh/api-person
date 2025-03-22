<?php

namespace App\Repositories\Eloquent;

use App\Models\Unidade;
use App\Repositories\UnidadeRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UnidadeRepository implements UnidadeRepositoryInterface
{
    protected $model;

    public function __construct(Unidade $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->orderBy('unid_id', 'asc')->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with(['enderecos','lotacoes'])
            ->paginate($perPage);
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);     
    }

    public function update(array $data)
    {
        return $this->model->update($data);
    }

    public function delete($id)
    {
        return $this->model->findOrFail($id)->delete();
    }
}