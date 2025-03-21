<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServidorTemporario extends Model
{
    use HasFactory;

    protected $table = 'servidores_temporarios';
    
    protected $fillable = ['pes_id','st_data_admissao','st_data_demissao' ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoas::class, 'pes_id');
    }

    public function lotacoes()
    {
        return $this->hasMany(Lotacao::class, 'pes_id');
    }
}
