<?php

namespace App\Repositories\Eloquent;

use App\Models\Lotacao;
use App\Repositories\LotacaoRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class LotacaoRepository implements LotacaoRepositoryInterface
{
    protected $model;

    public function __construct(Lotacao $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['unidade', 'pessoa'])->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with(['unidade','pessoa'])
            ->paginate($perPage);
    }

    public function findById($id)
    {
        return $this->model->with(['unidade', 'pessoa'])->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);     
    }

    public function update(array $data, $id)
    {
        return $this->model->findOrFail($id)->update($data, $id);
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