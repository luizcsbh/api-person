<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $table = 'enderecos';
    protected $primaryKey = 'end_id';

    protected $fillable = [
        'end_tipo_logradouro',
        'end_logradouro',
        'end_numero',
        'end_bairro',
    ];

    public function pessoas()
    {
        return $this->belongsToMany(Pessoas::class, 'pessoas_enderecos', 'end_id', 'pes_id')
            ->withTimestamps();
    }

    public function unidades()
    {
        return $this->belongsToMany(Unidade::class, 'unidades_enderecos', 'uni_id', 'end_id')
            ->withTimestamps();
    }

    public function cidades()
    {
        return $this->belongsTo(Cidade::class, 'cid_id', 'cid_id');
    }

}
