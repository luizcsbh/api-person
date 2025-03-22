<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PessoaEndereco extends Pivot
{
    protected $table = 'pessoas_enderecos';
    
    public $incrementing = false;
    
    protected $primaryKey = ['pes_id', 'end_id'];
    
    protected $fillable = [
        'pes_id',
        'end_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}