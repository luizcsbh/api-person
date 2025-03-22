<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Lotação",
 *     type="object",
 *     title="Lotação",
 *     description="Modelo de Lotação",
 *     required={"pes_id","unid_id","lot_data_lotacao","lot_data_remocao","lot_portaria"},
  *     @OA\Property(
 *         property="lot_id",
 *         type="integer",
 *         description="ID do Lotação",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="pes_id",
 *         type="integer",
 *         description="ID da Pessoa",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="unid_id",
 *         type="integer",
 *         description="ID da Unidade",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="lot_data_lotacao",
 *         type="datetime",
 *         description="Data da lotação",
 *         example="2021-05-01"
 *     ),
 *     @OA\Property(
 *         property="lot_data_remocao",
 *         type="datetime",
 *         description="Datada remoção",
 *         example="2024-10-15"
 *     ),
 *     @OA\Property(
 *         property="lot_portaria",
 *         type="string",
 *         description="Portaria",
 *         example="Portaria 23.0001/24"
 *     ),
 * )
 */
class Lotacao extends Model
{
    use HasFactory;

    protected $table = 'lotacoes';
    protected $primaryKey = 'lot_id';

    protected $fillable = [
        'pes_id',
        'unid_id',
        'lot_data_lotacao',
        'lot_data_remocao',
        'lot_portaria'
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoas::class, 'pes_id', 'pes_id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unid_id', 'unid_id');
    }
}
