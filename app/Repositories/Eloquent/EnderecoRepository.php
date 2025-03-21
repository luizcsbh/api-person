<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Models\Endereco;
use Illuminate\Support\Facades\DB;
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
        try {
            DB::beginTransaction();
            $endereco = $this->model->create($data);
            DB::commit();
            return $endereco;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(array $data, $id)
    {
        try {
            DB::beginTransaction();
            $endereco = $this->model->find($id);
            if (!$endereco) {
                throw new Exception('Endereço não encontrada.');
            }
            $endereco->update($data);
            DB::commit();
            return $endereco;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $endereco = $this->model->find($id);
            if (!$endereco) {
                throw new Exception('Endereço não encontrada.');
            }
            $endereco->delete();
            DB::commit();
            return $endereco;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}