<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regMesesModel extends Model
{
    protected $table      = "CEN_CAT_MESES";
    protected $primaryKey = 'MES_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'MES_ID',
        'MES_DESC',
        'MES_MES',
        'MES_STATUS', //S ACTIVO      N INACTIVO
        'MES_FECREG'
    ];

    public static function obtmes($id){
        return regMesesModel::select('MES_ID','MES_MES')
                           ->where('MES_ID','=',$id )
                           ->get();
    }   

}