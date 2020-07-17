<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regPlacaModel extends Model
{
    protected $table      = "COMB_PLACAS";
    protected $primaryKey = 'PLACA_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PLACA_ID',
        'PLACA_PLACA',
        'PLACA_DESC',
        'PLACA_SERIE',
        'PLACA_ANTERIOR',
        'PLACA_CILINDROS',
        'MARCA_ID',
        'TIPOO_ID',
        'TIPOG_ID',
        'SP_ID',
        'DEPENDENCIA_ID',
        'PLACA_MODELO',
        'PLACA_MODELO2',
        'PLACA_GASOLINA',
        'PLACA_INVENTARIO',  
        'PLACA_OBS1',
        'PLACA_OBS2',
        'PLACA_FOTO1',
        'PLACA_FOTO2',
        'PLACA_STATUS1',
        'PLACA_STATUS2',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];

    public static function ObtCodigo($id){
        return (regPlacaModel::select('PLACA_ID')->where('PLACA_ID','=',$id)
                             ->get());
    }

    public static function ObtPlaca($id){
        return (regPlacaModel::select('PLACA_PLACA')->where('PLACA_ID','=',$id)
                             ->get());
    }

    public static function ObtResguardatario($id){
        return (regPlacaModel::select('PLACA_OBS2')->where('PLACA_ID','=',$id)
                             ->get());
    }

    public static function ObtTipoOperacion($id){
        return (regPlacaModel::select('TIPOO_ID')->where('PLACA_ID','=',$id)
                             ->get());
    }

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopeName($query, $name)
    {
        if($name)
            return $query->where('PLACA_DESC', 'LIKE', "%$name%");
    }

    public function scopeCodigo($query, $codigo)
    {
        if($codigo)
            return $query->where('PLACA_ID', '=', "$codigo");
    }

    public function scopePlaca($query, $placa)
    {
        if($placa)
            return $query->where('PLACA_PLACA', 'LIKE', "%$placa%");
    } 

}