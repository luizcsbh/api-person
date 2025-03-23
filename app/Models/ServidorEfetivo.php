<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="ServidorEfetivo",
 *     type="object",
 *     title="Servidor Efetivo",
 *     description="Modelo de Servidor Efetivo",
 *     required={"pes_id","se_matricula"},
 *     @OA\Property(
 *         property="pes_id",
 *         type="integer",
 *         description="ID da pessoa",
 *         example="2"
 *     ),
 *     @OA\Property(
 *         property="se_matricula",
 *         type="string",
 *         description="Matricula do Servidor",
 *         example="2003456788467"
 *     ),
 * )
 */
class ServidorEfetivo extends Model
{
    use HasFactory;

    protected $table = 'servidores_efetivos';
    protected $primaryKey = 'pes_id';
    public $incrementing = false;
    protected $fillable = ['pes_id', 'se_matricula'];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pes_id', 'pes_id');
    }
    
    public function lotacoes()
    {
        return $this->hasMany(Lotacao::class, 'pes_id');
    }
  
}
