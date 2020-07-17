<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regReciboModel extends Model
{
    protected $table      = "COMB_RECIBO_BIPADECO";
    protected $primaryKey = 'RECIBO_FOLIO';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'RECIBO_FOLIO',
        'PLACA_ID',
        'PLACA_PLACA',
        'RECIBO_KI',
        'RECIBO_KF',
        'QUINCENA_ID',
        'RECIBO_IR',
        'RECIBO_I18',
        'RECIBO_I14',
        'RECIBO_I12',
        'RECIBO_I34',
        'RECIBO_IF',
        'RECIBO_FR',
        'RECIBO_F18',
        'RECIBO_F14',
        'RECIBO_F12',
        'RECIBO_F34',
        'RECIBO_FF',
        'RECIBO_FECINI',
        'RECIBO_FECINI2',
        'PERIODO_ID1',
        'MES_ID1',
        'DIA_ID1',
        'RECIBO_FECFIN',
        'RECIBO_FECFIN2',
        'PERIODO_ID2',
        'MES_ID2',
        'DIA_ID2',
        'TIPOO_ID',
        'TARJETA_NO',
        'PERIODO_ID',        
        'MES_ID',
        'SP_ID',
        'SP_NOMB',
        'MONTO_ADISPERSAR',
        'RECIBO_RFOTO1',
        'RECIBO_RFOTO2',
        'RECIBO_RFOTO3',
        'RECIBO_RFOTO4',
        'RECIBO_RFOTO5',
        'RECIBO_BFOTO1',
        'RECIBO_BFOTO2',
        'RECIBO_BFOTO3',
        'RECIBO_BFOTO4',
        'RECIBO_BFOTO5',
        'RECIBO_OBS1',
        'RECIBO_OBS2',
        'RECIBO_STATUS1',
        'RECIBO_STATUS2',
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

    public function scopefQuin($query, $fquin)
    {
        if($fquin)
            return $query->orwhere('QUINCENA_ID', '=', "$fquin");
    } 

    public function scopefSemaforo($query, $fsemaforo)
    {
        if($fsemaforo) 
            return $query->orwhere('RECIBO_STATUS2', '=', "$fsemaforo");
    } 

    public function scopeFolio($query, $folio)
    {
        if($folio)
            return $query->orwhere('RECIBO_FOLIO', '=', "$folio");
    }


    public function scopeCodigo($query, $codigo)
    {
        if($codigo)
            return $query->orwhere('PLACA_ID', '=', "$codigo");
    }

    public function scopePlaca($query, $placa)
    {
        if($placa)
            return $query->orwhere('PLACA_PLACA', 'LIKE', "%$placa%");
    } 

}