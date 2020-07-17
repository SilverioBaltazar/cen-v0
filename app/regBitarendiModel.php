<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regBitarendiModel extends Model
{
    protected $table      = "COMB_BITACORA_RENDCOMB";
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
        'BITACO_FECHA',
        'BITACO_FECHA2',
        'PERIODO_ID1',
        'MES_ID1',        
        'DIA_ID1',         
        'SP_ID1',
        'SP_NOMB1',
        'SP_ID2',
        'SP_NOMB2',
        'BITACO_FOTO1',
        'BITACO_FOTO2',
        'BITACO_FOTO3',
        'BITACO_FOTO4',
        'BITACO_FOTO5',
        'BITACO_OBS1',
        'BITACO_OBS2',
        'BITACO_STATUS1',
        'BITACO_STATUS2',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];

    
}