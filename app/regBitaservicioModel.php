<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regBitaservicioModel extends Model
{
    protected $table      = "COMB_BITACORA_SERVICIOS";
    protected $primaryKey = 'BITACO_FOLIO';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
		'BITACO_FOLIO',
		'PLACA_ID',
		'PLACA_PLACA',
		'PERIODO_ID',
		'MES_ID',
		'QUINCENA_ID',
		'SERVICIO',
		'SERVICIO_FECHA',
		'SERVICIO_FECHA2',				
		'PERIODO_ID1',
		'MES_ID1',
		'DIA_ID1',				
		'SP_ID',
		'SP_NOMB',
		'SERVICIO_DOTACION',
		'SERVICIO_R',		
		'SERVICIO_18',
		'SERVICIO_14',
		'SERVICIO_12',
		'SERVICIO_34',
		'SERVICIO_F',		
		'KM_INICIAL',
		'KM_FINAL',		
		'SERVICIO_LUGAR',
		'SERVICIO_HRSALIDA',						
		'SERVICIO_HRREGRESO',		
		'SERVICIO_OBS',
		'SBITACO_STATUS1',
		'SBITACO_STATUS2',
		'FECREG',
		'IP',
		'LOGIN',
		'FECHA_M',
		'IP_M',
		'LOGIN_M'
    ];

}
