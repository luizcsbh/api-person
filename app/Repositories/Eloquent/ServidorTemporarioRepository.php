<?php

namespace App\Repositories\Eloquent;

use App\Models\ServidorTemporario;
use App\Repositories\ServidorTemporarioRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ServidorTemporarioRepository implements ServidorTemporarioRepositoryInterface
{
    protected $model;

    public function __construct(ServidorTemporario $model)
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
        return $this->model->where('pes_id',$id)->update($data);
    }

    public function delete($id)
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function findByIdWithPessoa(int $id)
    {
        return $this->model->with('pessoa')->findOrFail($id);
    }

    public function updateMatricula($pesId, string $matricula)
    {
        return $this->model->where('pes_id', $pesId)
            ->update(['se_matricula' => $matricula]);
    }

}