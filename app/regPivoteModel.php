<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regPivoteModel extends Model
{
    protected $table      = "CEN_USERS_ROLES_PRIVILEGS";
    protected $primaryKey = 'PIVOTE_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PIVOTE_ID',
        'USER_ID',
        'USER_NAME',
        'ROL_ID',
        'ROL_NAME',
        'PRIVILEG_ID',
        'PRIVILEG_NAME',
        'STATUS',
        'FECREG',
        'PIVOTE'
    ];

    public static function ObtPivoteRol($id){
        return (regPivoteModel::select('ROL_NAME')->where('USER_ID','=',$id)
                                 ->first());
    }

}  