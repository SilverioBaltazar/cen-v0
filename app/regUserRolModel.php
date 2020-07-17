<?php

namespace App;
use HasRole;

use Illuminate\Database\Eloquent\Model;
//use App\regRolModel;

class regUserRolModel extends Model
{
    protected $table      = "CEN_USERS_ROLES";
    protected $primaryKey = 'ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'ID',
        'USER_ID',
        'ROL_ID',
        'STATUS',
        'FECREG',
        'USER_ROL'
    ];

    public static function ObtRolid($id){
        return (regUserRolModel::select('ROL_ID')->where('USER_ID','=',$id)
                                 ->get());
    }

}