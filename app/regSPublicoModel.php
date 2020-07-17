<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regSPublicoModel extends Model
{
    protected $table      = "COMB_SPUBLICOS";
    protected $primaryKey = 'SP_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'SP_ID',
        'SP_NOMB',
        'DEPEN_ID',
        'SP_STATUS1',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];
}  