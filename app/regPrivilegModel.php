<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

//use App\regRolModel;

class regPrivilegModel extends Model
{
    protected $table      = "CEN_PRIVILEGS";
    protected $primaryKey = 'PRIVILEG_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PRIVILEG_ID',
        'PRIVILEG_NAME',
        'PRIVILEG_DESC',
        'STATUS',
        'FECREG'
    ];

   public static function ObtPrivileg($id){
        return (regPrivilegModel::select('PRIVILEG_NAME')->where('PRIVILEG_ID','=',$id)
                             ->get());
    }

    // ********** Metodos consultar https://styde.net/pivot-tables-con-eloquent-en-laravel/  ***//
    //public function users() 
    //{   
    //    return $this->belongsToMany(regUserModel::class,'COMB_USERS_ROLES_PRIVILEGS')
    //                ->withPivot('rol_id','status'); 
    //}

    //public function roles() 
    //{   
    //    return $this->belongsToMany(regRolModel::class,'COMB_USERS_ROLES_PRIVILEGS')
    //                ->withPivot('user_id','status'); 
    //}

    // ************ Metodos consultar https://codeday.me/es/qa/20190805/1186764.html ****//
    public function roles()
    {
        return $this->belongsToMany(regRolModel::class);
    }
    // Relación privilegs->users
    //public function users() 
    //{   
    //    return $this->belongsToMany(regUserModel::class); 
    //}

    // Relación privilegs->roles
    //public function roles() 
    //{   
    //    return $this->belongsToMany(regRolModel::class); 
    //}

}