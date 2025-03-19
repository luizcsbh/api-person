<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lotacao extends Model
{
    use HasFactory;

    protected $table = 'lotacoes';
    protected $primaryKey = 'lot_id';

    protected $fillable = [
        'pes_id',
        'uni_id',
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
        return $this->belongsTo(Unidade::class, 'uni_id', 'uni_id');
    }
}
