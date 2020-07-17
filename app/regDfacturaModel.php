<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regDfacturaModel extends Model
{
    protected $table      = "CEN_PRODUCTOS_VENDIDOS";
    protected $primaryKey = 'FACTURA_FOLIO';
    public $timestamps    = false;
    public $incrementing  = false;
    // Atributos de la clase detalle de factura 
    protected $fillable   = [
 		'FACTURA_FOLIO',
 		'DFACTURA_NPARTIDA',
 		'DESCRIPCION',
 		'CODIGO_BARRAS',
 		'PRECIO',
 		'CANTIDAD',
 		'CLIENTE_ID',
 		'EMP_ID',
 		'DFACTURA_CANTIDAD',
 		'DFACTURA_PRECIO',
 		'DFACTURA_IMPORTE',
 		'DFACTURA_IVA',
 		'DFACTURA_OTRO',
 		'DFACTURA_TOTALNETO',
 		'PERIODO_ID',
 		'MES_ID',
 		'DIA_ID',
 		'CREATE_AT',
 		'UPDATE_AT',
 		'FECREG',
 		'IP',
 		'LOGIN',
 		'FECHA_M',
 		'IP_M',
 		'LOGIN_M'
    ];
}
