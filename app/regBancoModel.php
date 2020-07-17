<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regBancoModel extends Model
{
    protected $table      = "CEN_CAT_BANCOS";
    protected $primaryKey = 'BANCO_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'BANCO_ID',
        'BANCO_DESC',
        'BANCO_STATUS', //S ACTIVO      N INACTIVO
        'BANCO_FECREG'
    ];
}

