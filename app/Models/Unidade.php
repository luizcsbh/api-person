<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Unidade",
 *     type="object",
 *     title="Unidade",
 *     description="Modelo da Unidade",
 *     required={"unid_nome","unid_sigla"},
 *     @OA\Property(
 *         property="unid_id",
 *         type="integer",
 *         description="ID da Unidade",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="unid_nome",
 *         type="string",
 *         description="Nome da unidade",
 *         example="Secretária de Planejamento"
 *     ),
 *     @OA\Property(
 *         property="unid_sigla",
 *         type="string",
 *         description="Sigla da unidade",
 *         example="SEPLAG"
 *     ),
 * )
 */
class Unidade extends Model
{
    use HasFactory;

    protected $table = 'unidades';
    protected $primaryKey = 'unid_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['unid_nome', 'unid_sigla'];

    public function enderecos()
    {
        return $this->belongsToMany(Endereco::class, 'unidades_enderecos', 'end_id', 'unid_id')
            ->withTimestamps();
    }

    public function lotacoes()
    {
        return $this->hasMany(Lotacao::class, 'unid_id', 'unid_id');
    }
}
