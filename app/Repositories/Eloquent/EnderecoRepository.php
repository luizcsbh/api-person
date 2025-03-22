<?php

namespace App\Repositories\Eloquent;

use App\Models\Endereco;
use App\Repositories\EnderecoRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class EnderecoRepository implements EnderecoRepositoryInterface
{
    protected $model;

    public function __construct(Endereco $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with(['cidades'])
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

    public function update(array $data, $id)
    {
        return $this->model->where('end_id',$id)->update($data, $id);
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