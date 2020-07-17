<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regDiarioModel extends Model
{
    protected $table      = "CEN_DIARIO_MOVTOS";
    protected $primaryKey = ['PERIODO_ID','DIARIO_ID'];
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PERIODO_ID',
        'DIARIO_ID',
        'DIARIO_FOLIO',
        'FACTURA_FOLIO',
        'CLIENTE_ID',
        'EMP_ID',
        'DIARIO_FECHA',
        'DIARIO_FECHA2',
        'MES_ID',
        'DIA_ID',
        'DIARIO_TIPO',
        'DIARIO_CONCEPTO',
        'DIARIO_IMPORTE',
        'DIARIO_IVA',
        'DIARIO_OTRO',
        'DIARIO_TOTALNETO',
        'DIARIO_OBS1',
        'DIARIO_OBS2',
        'DIARIO_STATUS1',
        'DIARIO_STATUS2',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];
}
