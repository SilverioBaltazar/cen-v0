<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regdeptosModel extends Model
{
    protected $table = "CEN_CAT_DEPTOS";
    protected  $primaryKey = 'DEPTO_ID';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
	    'DEPTO_ID', 
	    'DEPTO_DESC',
	    'DEPTO_STATUS',
        'DEPTO_FECREG'
    ];

    public static function ObtDepto($id){
        return (regdeptosModel::select('DEPTO_DESC')->where('DEPTO_ID','=',$id)
                                ->get());
    }
}
