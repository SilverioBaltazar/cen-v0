<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//class Venta extends Model
//{
//    public function productos()
//    {
//        return $this->hasMany("App\ProductoVendido", "id_venta");
//    }

//    public function cliente()
//    {
//        return $this->belongsTo("App\Cliente", "id_cliente");
//    }
//}

//********************************************************************//
// **** Clase:  regEfacturaModel                        **************//
// **** Objeto: CEN_FACTURAS                            **************//
// **** Atributos: Campos y/o propiedades del objeto    **************//
//********************************************************************//
class regEfacturaModel extends Model
{
    protected $table      = "CEN_VENTAS";
    protected $primaryKey = 'FACTURA_FOLIO';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'SUCURSAL_ID',
        'FACTURA_FOLIO',
        'CLIENTE_ID',
        'EMP_ID',
        'TIPOCREDITO_ID',
        'TIPOCREDITO_DIAS',
        'PERIODO_ID',
        'MES_ID',
        'DIA_ID',
        'EFACTURA_MONTOSUBSIDIO',
        'EFACTURA_MONTOAPORTACIONES',
        'EFACTURA_NUMAPORTACIONES',
        'EFACTURA_FECAPORTACION1',
        'EFACTURA_FECAPORTACION2',
        'EFACTURA_IMPORTE',
        'EFACTURA_IVA',
        'EFACTURA_OTRO',
        'EFACTURA_TOTALNETO',
        'EFACTURA_MONTOPAGOS',
        'EFACTURA_SALDO',
        'EFACTURA_STATUS1',
        'EFACTURA_STATUS2',
        'MUNICIPIO_ID',
        'ENTIDADFED_ID',
        'CLIENTE_COL',
        'LOCALIDAD',       
        'CREATE_AT',
        'UPDATE_AT',
 		'FECREG',
 		'IP',
 		'LOGIN',
 		'FECHA_M',
 		'IP_M',
 		'LOGIN_M'
    ];

    //********************************************************************//
    // ***************** Como se usa el query scope         **************//
    // ***************** Metodos: del objeto CEN_FACTURAS,  **************//
    // *****************         funciones o procedimientos **************//
    //********************************************************************//
    public static function Obtfactcliente($id){
        return (regEfacturaModel::select('CLIENTE_ID')->where('FACTURA_FOLIO','=',$id)
                                  ->get());
    }

    public static function Obtfactsaldo($id){
        return (regEfacturaModel::select('EFACTURA_SALDO')->where('FACTURA_FOLIO','=',$id)
                                  ->get());
    }

    //Relaciones uno a muchos con hasMany.
    public function productos()
    {
        //return $this->hasMany("App\ProductoVendido", "id_venta");
        return $this->hasMany('App\regDfacturaModel', 'FACTURA_FOLIO');
    }

    //Relaciones uno a muchos con hasMany
    public function cliente()
    {
        //return $this->belongsTo("App\Cliente", "id_cliente");
        return $this->belongsTo('App\regClientesModel', 'CLIENTE_ID');
    }

    public function scopePerr($query, $perr)
    {
        if($perr)
            return $query->orwhere('PERIODO_ID', '=', "$perr");
    }

    public function scopeMess($query, $mess)
    {
        if($mess)
            return $query->orwhere('MES_ID', '=', "$mess");
    }

    public function scopeDiaa($query, $diaa)
    {
        if($diaa)
            return $query->orwhere('DIA_ID', '=', "$diaa");
    } 

    public function scopeEmpp($query, $empp)
    {
        if($empp)
            return $query->orwhere('EMP_ID', '=', "$empp");
    } 
    
    public function scopeCliee($query, $cliee)
    {
        if($cliee)
            return $query->orwhere('CLIENTE_ID', '=', "$cliee");
    }

    public function scopeFolioo($query, $folioo)
    {
        if($folioo)
            return $query->orwhere('FACTURA_FOLIO', '=', "$folioo");
    }

    // ******** Metodo para buscar por status de factura ***********//
    public function scopeStatuss($query, $statuss)
    {
        if($statuss)
            return $query->orwhere('EFACTURA_STATUS2', '=', "$statuss");
    }    

    //****************************************//
    // QUERY PARA EXPORTAR                    //
    //****************************************//
    public function scopePerr2($query, $perr)
    {
        if($perr)
            return $query->orwhere('CEN_VENTAS.PERIODO_ID', '=', "$perr");
    }

    public function scopeMess2($query, $mess)
    {
        if($mess)
            return $query->orwhere('CEN_VENTAS.MES_ID', '=', "$mess");
    }

    public function scopeDiaa2($query, $diaa)
    {
        if($diaa)
            return $query->orwhere('CEN_VENTAS.DIA_ID', '=', "$diaa");
    } 

    public function scopeEmpp2($query, $empp)
    {
        if($empp)
            return $query->orwhere('CEN_VENTAS.EMP_ID', '=', "$empp");
    } 
    
    public function scopeCliee2($query, $cliee)
    {
        if($cliee)
            return $query->orwhere('CEN_VENTAS.CLIENTE_ID', '=', "$cliee");
    }

    public function scopeFolioo2($query, $folioo)
    {
        if($folioo)
            return $query->orwhere('CEN_VENTAS.FACTURA_FOLIO', '=', "$folioo");
    }

    // ******** Metodo para buscar por status de factura ***********//
    public function scopeStatuss2($query, $statuss)
    {
        if($statuss)
            return $query->orwhere('CEN_VENTAS.EFACTURA_STATUS2', '=', "$statuss");
    }    


    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopeBio($query, $bio)
    {
        if($bio)
            return $query->where('IAP_OBJSOC', 'LIKE', "%$bio%");
    } 

}
