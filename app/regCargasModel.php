<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regCargasModel extends Model
{
    protected $table      = "COMB_RECIBO_CARGAS";
    protected $primaryKey = 'CARGA';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'RECIBO_FOLIO',
        'PLACA_ID',
        'PLACA_PLACA',
        'PERIODO_ID',
        'MES_ID',
        'CARGA',
        'TKPAG_FOLAPROB',
        'TKPAG_TARJETA',
        'TKPAG_FECHA',
        'TKPAG_FECHA2',
        'PERIODO_ID1',
        'MES_ID1',
        'DIA_ID1',
        'TKPAG_HORA',
        'TKPAG_IMPORTE',
        'BANCO_ID',
        'TKBOMBA_TICKET',
        'TKBOMBA_CODIGO',
        'TKBOMBA_RFC',
        'TKBOMBA_FECHA',
        'TKBOMBA_FECHA2',
        'PERIODO_ID2',
        'MES_ID2',
        'DIA_ID2',
        'TKBOMBA_HORA',
        'TKBOMBA_IMPORTE',
        'FP_ID',
        'OBS_1',
        'CARGA_FOTO1',
        'STATUS_1',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];
}