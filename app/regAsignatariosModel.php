<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regAsignatariosModel extends Model
{
    protected $table      = "COMB_PLACAS_UADMON_ASIGNADA";
    protected $primaryKey = ['DEPENDENCIA_ID','PLACA_ID'];
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PLACA_ID',
        'PLACA_PLACA',
        'DEPENDENCIA_ID', 
        'SP_ID',
        'SP_NOMB',
        'STATUS',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];
    
}
