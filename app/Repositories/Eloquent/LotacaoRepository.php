<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Models\Lotacao;
use Illuminate\Support\Facades\DB;
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
        try {
            DB::beginTransaction();
            $lotacao = $this->model->create($data);
            DB::commit();
            return $lotacao;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(array $data, $id)
    {
        try {
            DB::beginTransaction();
            $lotacao = $this->model->find($id);
            if(!$lotacao) {
                throw new Exception('Lotação não encontrada.');
            }
            $lotacao->update($data);
            DB::commit();
            return $lotacao;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $lotacao = $this->model->find($id);
            if(!$lotacao) {
                throw new Exception('Lotação não encontrada.');
            }
            $lotacao->delete();
            DB::commit();
            return $lotacao;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}