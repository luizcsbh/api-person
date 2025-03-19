<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    use HasFactory;

    protected $table = 'unidades';
    protected $primaryKey = 'uni_id';

    protected $fillable = [
        'uni_nome',
        'uni_sigla',
    ];

    public function enderecos()
    {
        return $this->belongsToMany(Endereco::class, 'unidades_enderecos', 'end_id', 'uni_id')
            ->withTimestamps();
    }

    public function lotacoes()
    {
        return $this->hasMany(Lotacao::class, 'uni_id', 'uni_id');
    }
}
