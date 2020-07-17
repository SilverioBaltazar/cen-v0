<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regTipocreditoModel extends Model
{
    protected $table      = "CEN_CAT_TIPOSCREDITO";
    protected $primaryKey = 'TIPOCREDITO_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'TIPOCREDITO_ID',
        'TIPOCREDITO_DESC',
        'TIPOCREDITO_DIAS',
        'TIPOCREDITO_STATUS', //S ACTIVO      N INACTIVO
        'TIPOCREDITO_FECREG'
    ];

    public static function Obtcreditodias($id){
        return (regTipocreditoModel::select('TIPOCREDITO_DIAS')->where('TIPOCREDITO_ID','=',$id)
                                     ->get());
    }

}
