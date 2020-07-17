<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regAportacionesModel extends Model
{
    protected $table      = "CEN_APORTACIONES";
    protected $primaryKey = 'APOR_FOLIO';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PERIODO_ID',
        'APOR_FOLIO',
        'APOR_RECIBO',
        'FACTURA_FOLIO',
        'CLIENTE_ID',
        'EMP_ID',
        'FPAGO_ID',
        'BANCO_ID',
        'APOR_FECHA',
        'APOR_FECHA2',
        'APOR_FECHA3',
        'MES_ID',
        'DIA_ID',
        'APOR_FECPROXPAGO',
        'APOR_FECPROXPAGO2',
        'APOR_FECPROXPAGO3',
        'APOR_NOCHEQUE',
        'APOR_CONCEPTO',
        'APOR_IMPORTE',
        'APOR_IVA',
        'APOR_OTRO',
        'APOR_TOTALNETO',
        'FACTURA_SALDO',
        'APOR_OBS1',
        'APOR_OBS2',
        'APOR_FOTO1',
        'APOR_FOTO2',
        'APOR_STATUS1',
        'APOR_STATUS2',
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
    public function scopePerr($query, $perr)
    {
        if($perr)
            return $query->where('PERIODO_ID', '=', "$perr");
    }

    public function scopeMess($query, $mess)
    {
        if($mess)
            return $query->where('MES_ID', '=', "$mess");
    }

    public function scopeCliee($query, $cliee)
    {
        if($cliee)
            return $query->where('CLIENTE_ID', '=', "$cliee");
    }

    public function scopeEmpp($query, $empp)
    {
        if($empp)
            return $query->where('EMP_ID', '=', "$empp");
    }

    public function scopeBio($query, $bio)
    {
        if($bio)
            return $query->where('IAP_OBJSOC', 'LIKE', "%$bio%");
    } 

}
