<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="ServidorTemporario",
 *     type="object",
 *     title="Servidor Temporario",
 *     description="Modelo de Servidor Temporario",
 *     required={"pes_id","st_data_admissao", "st_data_demissao"},
 *     @OA\Property(
 *         property="pes_id",
 *         type="integer",
 *         description="ID da pessoa",
 *         example="2"
 *     ),
 *     @OA\Property(
 *         property="st_data_admissao",
 *         type="datetime",
 *         description="Data de admissão",
 *         example="2024-05-01T00:00:00"
 *     ),
 *      @OA\Property(
 *         property="st_data_demissao",
 *         type="datetime",
 *         description="Data de demissão",
 *         example="2024-05-01T00:00:00"
 *     ),
 * )
 */
class ServidorTemporario extends Model
{
    use HasFactory;

    protected $table = 'servidores_temporarios';
    
    protected $fillable = ['pes_id','st_data_admissao','st_data_demissao' ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pes_id');
    }

    public function lotacoes()
    {
        return $this->hasMany(Lotacao::class, 'pes_id');
    }
}
