<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoPessoa extends Model
{
    use HasFactory;

    protected $table = 'fotos_pessoas';
    protected $primaryKey = 'fp_id';

    protected $fillable = [
        'pes_id',
        'fp_data',
        'fp_bucket',
        'ft_hash'
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pes_id', 'pes_id');
    }
}
