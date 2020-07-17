<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regAgendaeventoModel extends Model
{
    protected $table      = "COMB_AGENDA_EVENTOS";
    protected $primaryKey = 'EVENTO_FOLIO';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PERIODO_ID',
        'EVENTO_ID',
        'EVENTO_FOLIO',
        'EVENTO_DESC',
        'EVENTO_HORA',
        'EVENTO_NOMB',
        'EVENTO_FECHA',
        'EVENTO_FECHA2',
        'PERIODO_ID1',
        'MES_ID1',
        'DIA_ID1',
        'EVENTO_OBS',
        'EVENTO_STATUS1',
        'EVENTO_STATUS2',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopefPer($query, $fper)
    {
        if($fper)
            return $query->orwhere('PERIODO_ID', '=', "$fper");
    }

    public function scopefMes($query, $fmes)
    {
        if($fmes)
            return $query->orwhere('MES_ID', '=', "$fmes");
    }

    public function scopefDia($query, $fdia)
    {
        if($fdia)
            return $query->orwhere('DIA_ID', '=', "$fdia");
    } 

    public function scopefIap($query, $fiap)
    {
        if($fiap)
            return $query->orwhere('IAP_ID', '=', "$fiap");
    } 

    
}
