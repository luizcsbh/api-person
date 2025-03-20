<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Cidade",
 *     type="object",
 *     title="Cidade",
 *     description="Modelo de Cidade",
 *     required={"cid_nome","cid_uf"},
 *     @OA\Property(
 *         property="cid_id",
 *         type="integer",
 *         description="ID do Cidade",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="cid_nome",
 *         type="string",
 *         description="Nome da cidade",
 *         example="Camaçari"
 *     ),
 *     @OA\Property(
 *         property="cid_uf",
 *         type="string",
 *         description="Unidade Federativa",
 *         example="BA"
 *     ),
 * )
 */
class Cidade extends Model
{
    use HasFactory;

    protected $table = 'cidades';
    protected $primaryKey = 'cid_id';

    protected $fillable = [
        'cid_nome',
        'cid_uf',
    ];

    public function enderecos()
    {
        return $this->hasMany(Endereco::class, 'cid_id', 'cid_id');
    }
}
