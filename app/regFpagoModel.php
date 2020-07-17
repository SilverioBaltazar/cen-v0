<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regFpagoModel extends Model
{
    protected $table      = "CEN_CAT_FORMASPAGO";
    protected $primaryKey = 'FPAGO_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'FPAGO_ID',
        'FPAGO_DESC',
        'FPAGO_STATUS', //S ACTIVO      N INACTIVO
        'FPAGO_FECREG'
    ];
}

