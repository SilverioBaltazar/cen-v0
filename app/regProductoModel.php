<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regProductoModel extends Model
{
    //protected $table      = "productos"; 
    protected $table      = "CEN_PRODUCTOS"; 
    //protected $primaryKey = 'BANCO_ID';
    public $timestamps    = false;
    public $incrementing  = false;   
    // Atributos de la clase productos  
    protected $fillable = ['id','codigo_barras', 'descripcion', 'precio_compra', 'precio_venta', 'existencia','prod_foto1','prod_status','prod_fecreg'];

    //**************** Metodos de la clase productos *************************
    //******** Obtiner descripción del producto ************************//
    public static function Obtproducto($id){
        return (regProductoModel::select('DESCRIPCION')->where('CODIGO_BARRAS','=',$id)
                                  ->get());
    }

    //******** Obtiner precio de venta del producto ********************//
    public static function Obtprecioventa($id){
        return (regProductoModel::select('PRECIO_VENTA')->where('CODIGO_BARRAS','=',$id)
                                  ->get());
    }    
 
    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopeName($query, $name)
    {
        if($name)
            return $query->where('DESCRIPCION', 'LIKE', "%$name%");
    }

    public function scopeEmail($query, $email)
    {
        if($email)
            return $query->where('IAP_EMAIL', 'LIKE', "%$email%");
    }

    public function scopeBio($query, $bio)
    {
        if($bio)
            return $query->where('IAP_OBJSOC', 'LIKE', "%$bio%");
    } 

}

