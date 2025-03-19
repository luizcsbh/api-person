<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServidorEfetivo extends Model
{
    use HasFactory;

    protected $table = 'servidores_efetivos';

    protected $fillable = [
        'pes_id',
        'se_matricula'
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoas::class, 'pes_id', 'pes_id');
    }
  
}
