<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Models\Unidade;
use Illuminate\Support\Facades\DB;
use App\Repositories\UnidadeRepositoryInterface;

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
            $pessoa->delete();
            DB::commit();
            return $pessoa;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}