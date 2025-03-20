<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Models\Unidade;
use Illuminate\Support\Facades\DB;
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
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            $unidade = $this->model->create($data);
            DB::commit();
            return $unidade;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(array $data, $id)
    {
        try {
            DB::beginTransaction();
            $unidade = $this->model->find($id);
            if (!$unidade) {
                throw new Exception('Unidade não encontrada.');
            }
            $unidade->update($data);
            DB::commit();
            return $unidade;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $unidade = $this->model->find($id);
            if (!$unidade) {
                throw new Exception('Unidade não encontrada.');
            }
            $unidade->deleteOrFail();
            DB::commit();
            return $unidade;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}