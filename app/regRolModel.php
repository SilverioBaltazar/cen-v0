<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
//use App\regUserModel;
use App\User;
use App\regPrivilegModel;

class regRolModel extends Model
{
    protected $table      = "CEN_ROLES";
    protected $primaryKey = 'ROL_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'ROL_ID',
        'ROL_NAME',
        'ROL_DESC',
        'STATUS',
        'FECREG'
    ];

    // ********** Metodos consultar https://styde.net/pivot-tables-con-eloquent-en-laravel/  ***//
    //public function users() 
    //{   
    //    return $this->belongsToMany(regUserModel::class,'COMB_USERS_ROLES_PRIVILEGS')
    //                ->withPivot('privileg_id','status'); 
    //}

    //public function privilegs() 
    //{   
    //    return $this->belongsToMany(regPrivilegModel::class,'COMB_USERS_ROLES_PRIVILEGS')
    //                ->withPivot('user_id','status'); 
    //}

    // ************ Metodos consultar https://codeday.me/es/qa/20190805/1186764.html ****//
    public function users()
    {
        return $this->hasMany(User::class,'ROL_ID','ROL_NAME');
        //return $this->hasMany(regUserModel::class,'ROL_ID','ROL_NAME');
    }

    public function privilegs()
    {
        return $this->belongsToMany(regPrivilegModel::class);
    }

    //public function users(){
    //    return $this->belongsToMany('\App\User','menu_task_user')
    //                ->withPivot('menu_id','status'); 
    //} 

    //public function menu(){ 
    //    return $this->belongsToMany('\App\Menu','menu_task_user')
    //                ->withPivot('user_id','status'); 
    //}

    //Ahora hay que generar una relación many-to-many entre el User y el Role.
    //Abrir el modelo User.php y agregar el siguiente método:
    //Hacer lo mismo con el modelo Role.php:
    //public function users()
    //{
    //    return $this
    //        ->belongsToMany('App\regUsersModel');
    //        //->withTimestamps();
    //}

    public static function Roluser(){
        return ($role_user = regRolModel::where('ROL_NAME','LIKE','%'.'user'.'%')->get());
    }    

    public static function Roladmin(){
        return $role_admin = regRolModel::where('ROL_NAME', 'admin')->first();
    }   
    
    public static function Rolsuperadmin(){
        return $role_superadmin = regRolModel::where('ROL_NAME','=','superadmin')->first();
    } 

    public static function ObtRol($id){
        return (regRolModel::select('ROL_NAME')->where('ROL_ID','=',$id)
                             ->get());
    }
  
    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopeUser($role_user, $userr)
    {
        if($userr)
            return $role_user = regRolModel::where('ROL_NAME', 'user')->first();
    }
   
   public function scopeAdmin($role_admin, $adminn)
    {
        if($adminn)
            return $role_admin = regRolModel::where('ROL_NAME', 'admin')->first();
    }

    public function scopeCaptur($role_captur, $capturr)
    {
        if($capturr)
            return $role_captur = regRolModel::where('ROL_NAME', 'captur')->first();
    }

}