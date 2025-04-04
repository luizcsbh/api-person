<?php

namespace App\Repositories\Eloquent;

use App\Models\ServidorEfetivo;
use App\Models\ServidorTemporario;
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

    public function 
    paginate(int $perPage = 10): LengthAwarePaginator
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

     /**
     * Check if pessoa has active temporary link
     * 
     * @param int $pesId
     * @return bool
     */
    public function hasActiveTemporaryLink(int $pesId): bool
    {
        return ServidorTemporario::where('pes_id', $pesId)
            ->whereNull('st_data_demissao')
            ->exists();
    }

    /**
     * Check if servidor efetivo has pessoa associated
     * 
     * @param ServidorEfetivo $servidorEfetivo
     * @return bool
     */
    public function hasPessoa(ServidorEfetivo $servidorEfetivo): bool
    {
        return $servidorEfetivo->pessoa()->exists();
    }

    /**
     * Check if servidor efetivo has lotacoes associated
     * 
     * @param ServidorEfetivo $servidorEfetivo
     * @return bool
     */
    public function hasLotacoes(ServidorEfetivo $servidorEfetivo): bool
    {
        return $servidorEfetivo->lotacoes()->exists();
    }

}