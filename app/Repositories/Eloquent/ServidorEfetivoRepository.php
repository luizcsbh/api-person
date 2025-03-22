<?php

namespace App\Repositories\Eloquent;

use App\Models\ServidorEfetivo;
use App\Repositories\ServidorEfetivoRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ServidorEfetivoRepository implements ServidorEfetivoRepositoryInterface
{
    protected $model;

    public function __construct(ServidorEfetivo $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with('pessoa')->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with(['pessoa'])
            ->paginate($perPage);
    }

    public function findById($id)
    {
        return $this->model->with('pessoa')->where('pes_id',$id)->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);  
    }

    public function update(array $data, $id)
    {
        return $this->model->where('pes_id',$id)->update($data, $id);
    }

    public function delete($id)
    {
        return $this->model->findOrFail($id)->delete();
    }
}