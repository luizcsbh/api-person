<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Pessoas",
 *     type="object",
 *     title="Pessoa",
 *     description="Modelo de Pessoa",
 *     required={"pes_nome","pes_data_nascimento","pes_sexo","pes_mae","pes_pai"},
 *     @OA\Property(
 *         property="pes_id",
 *         type="integer",
 *         description="ID do Pessoa",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="pes_nome",
 *         type="string",
 *         description="Nome da pessoa",
 *         example="João da Silva"
 *     ),
 *     @OA\Property(
 *         property="pes_data_nascimento",
 *         type="string",
 *         description="Data de nascimento",
 *         example="1978-08-23"
 *     ),
 *     @OA\Property(
 *         property="pes_sexo",
 *         type="string",
 *         description="Sexo da pessoa",
 *         example="Masculino"
 *     ),
 *     @OA\Property(
 *         property="pes_mae",
 *         type="string",
 *         description="Nome da mãe",
 *         example="Maria Aparecida da Silva"
 *     ),
 *     @OA\Property(
 *         property="pes_pai",
 *         type="string",
 *         description="Nome da pai",
 *         example="Cícero Joaquim da Silva"
 *     ),
 * )
 */
class Pessoas extends Model
{
    use HasFactory;

    protected $table = 'pessoas';
    protected $primaryKey = 'pes_id';

    protected $fillable = [
        'pes_nome',
        'pes_data_nascimento',
        'pes_sexo',
        'pes_mae',
        'pes_pai',
    ];

    public function fotos()
    {
        return $this->hasMany(FotoPessoa::class, 'pes_id', 'pes_id');
    }

    public function lotacoes()
    {
        return $this->hasMany(Lotacao::class, 'pes_id', 'pes_id');
    }

    public function servidorTemporario()
    {
        return $this->hasOne(ServidorTemporario::class, 'pes_id', 'pes_id');
    }

    public function servidorEfetivo()
    {
        return $this->hasOne(ServidorEfetivo::class, 'pes_id', 'pes_id');
    }

    public function enderecos()
    {
        return $this->belongsToMany(Endereco::class, 'pessoas_enderecos', 'pes_id', 'end_id')
            ->withTimestamps();
    }
}
