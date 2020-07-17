<?php

namespace App;
use HasRole;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\regRolModel;
use App\regPrivilegModel;

class regUserModel extends Model
{
    protected $table      = "CEN_USERS";
    protected $primaryKey = 'USER_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'USER_ID',
        'USER_NAME',
        'USER_EMAIL',
        'USER_PASSWORD',
        'USER_AP',
        'USER_AM',
        'USER_NAMES',  
        'USER_COMPLETO',
        'DEPTO_ID',
        'STATUS',
        'FECREG'
    ];
    
   //protected $fillable = [
    //    'USER_ID', 'USER_NAME', 'USER_EMAIL', 'USER_PASSWORD', 'STATUS', 'FECREG',
    //];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'USER_PASSWORD', 'remember_token',
    ];

    //Ahora hay que generar una relación many-to-many entre el User y el Role.
    //Abrir el modelo User.php y agregar el siguiente método:
    //public function roles()
    //{
    //    return $this->belongsToMany('App\regRolModel')
    //        //->withTimestamps();
    //}

    // ********** Metodos consultar https://styde.net/pivot-tables-con-eloquent-en-laravel/  ***//
    //*************************** METODOS **********************
    // Relación users->roles
    //public function roles() 
    //{   
    //    return $this->belongsToMany(regRolModel::class,'COMB_USERS_ROLES_PRIVILEGS')
    //                ->withPivot('privileg_id','status');
    //              //->withTimestamps(); 
    //}

    // Relacion user->Privilegios
    //public function privilegs() 
    //{   
    //    return $this->belongsToMany(regPrivilegModel::class,'COMB_USERS_ROLES_PRIVILEGS')
    //                ->withPivot('rol_id','status'); 
    //}

    //**********************************************************************************//
    //************ Metodos consultar https://codeday.me/es/qa/20190805/1186764.html ****//
    public function role()
    {
        return $this->belongsTo(regRolModel::class, 'ROL_ID', 'ROL_NAME');
    }

    //public function can($perm = null)
    //{
    //    if(is_null($perm)) return false;
    //    $perms = $this->role->permissions->fetch('name');
    //    return in_array($perm, $perms->toArray());
    //}

    //public function can($permiso = null)
    //{
    //    if(is_null($permiso)) return false;
    //    $permisos = $this->role->privilegs->fetch('privileg_name');
    //    return in_array($permiso, $permisos->toArray());
    //}

    //if(Auth::user->can('manage_pages')) {
    //    // Let him/her to add/edit/delete any page
    //}
    //@if(auth()->user()->is_admin == 1)
    //@if(Auth::user()->can('consult')) { 
    //***********************************************************************************//
    //***********************************************************************************//

    //public function authorizeRoles($roles){  
    //    if (is_array($roles)) {      
    //    return $this->hasAnyRole($roles) || abort(401, 'Está acción No está autorizada.');  
    //}  
    //    return $this->hasRole($roles) || abort(401, 'Está acción No está autorizada..');
    //}


    public function authorizeRoles($roles)
    {
        if ($this->hasAnyRole($roles)) {
            return true;
        }
        abort(401, 'Esta acción no está autorizada...');
    }

    //public function hasAnyRole($roles){  
    //    return null !== $this->roles()->whereIn(‘rol_name’, $roles)->first();
    //}
 
    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole($role)
    {
        if ($this->roles()->where('rol_name', $role)->first()) {
            return true;
        }
        return false;
    }

    public function hasRoles($roles){
        $roles_array = explode("|", $roles);
        if ($this->roles()->whereIn('rol_name', $roles_array)->first()) {
            return true;
        }
        return false;
    }

    public function run()    {        
        $role_employee = Role::where('name', 'employee')->first();        
        $role_manager = Role::where('name', 'manager')->first();        
    }


}