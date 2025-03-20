<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Models\ServidorEfetivo;
use Illuminate\Support\Facades\DB;
use App\Repositories\ServidorEfetivoRepositoryInterface;

class ServidorEfetivoRepository implements ServidorEfetivoRepositoryInterface
{
    protected $model;

    public function __construct(ServidorEfetivo $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            $servidorEfetivo = $this->model->create($data);
            DB::commit();
            return $servidorEfetivo;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(array $data, $id)
    {
        try {
            DB::beginTransaction();
            $servidorEfetivo = $this->model->find($id);
            if (!$servidorEfetivo) {
                throw new Exception('Servidor Efetivo não encontrado.');
            }
            $servidorEfetivo->update($data);
            DB::commit();
            return $servidorEfetivo;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $servidorEfetivo = $this->model->find($id);
            if (!$servidorEfetivo) {
                throw new Exception('Servidor Efetivo não encontrado.');
            }
            $servidorEfetivo->delete();
            DB::commit();
            return $servidorEfetivo;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}