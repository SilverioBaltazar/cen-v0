<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
//use App\Http\Requests\usuarioRequest;
use App\Http\Requests\altaUsuarioRequest;
use App\Http\Requests\userRequest;
use App\Http\Requests\loginuserRequest;

//use App\regUserModel;
//use App\usuarioModel;
use App\User;
use App\regRolModel;
use App\regPrivilegModel;
use App\regUserRolModel;
use App\regUserPrivilegModel;
use App\regPivoteModel;

use App\dependenciasModel;
use App\regBitacoraModel;

class usuariosController extends Controller
{


    protected function create(array $data)
    {
        $user = regUsersModel::create([
            'user_name'     => $data['user_name'],
            'user_email'    => $data['user_email'],
            'user_password' => bcrypt($data['user_password']),
        ]);
        $user
            ->roles()
            ->attach(regRolModel::where('rol_name', 'user')->first());
        return $user;
    }    

    
    /*
    public function someAdminStuff(Request $request)
    {
        $request->user()->authorizeRoles(‘admin’);
        return view(‘some.view’);
    }
    */

    //public function __construct()
    //{
    //    $this->middleware('auth');
    //}

    //public function index(Request $request)
    //public function actionLogin(usuarioRequest $request)
    //{
    //    $nombre = $request->usuario;
    //    $request->user()->authorizeRoles(['user', 'admin']);
    //    session(['userlog' => $request->usuario,'passlog' => $request->password,'usuario' => $usuario, 'ip' => $ip, 'rango' => $rango ]);
    //    toastr()->info($nombre,'Bienvenido ');
    //    //return view('home');
    //    return view('sicinar.menu.menuInicio',compact('usuario','nombre','rango'));
    //}
    //************************** Termina *************************//
    //************************************************************//

    public function actionLogin(loginuserRequest $request){
        //dd($request->all());
        //$request->user()->authorizeRoles(['user', 'admin','superadmin']);
        $existe = User::select('USER_ID','USER_NAME','USER_EMAIL','USER_PASSWORD','STATUS')
                  ->where('USER_NAME','like','%'.$request->usuario.'%')
                  ->where('USER_PASSWORD','like','%'.$request->password.'%')
                  ->where('STATUS','S')
                  ->get();
        //dd($existe);
        if($existe->count()>=1){
            //dd('Entra if.');
            if(strcmp($existe[0]->user_name,$request->usuario) == 0){
                if(strcmp($existe[0]->user_password,$request->password) == 0){
                    //dd('Entro......');
                }else{
                    return back()->withInput()->withErrors(['USER_PASSWORD' => 'Contraseña incorrecta.']);
                }
            }else{
                return back()->withInput()->withErrors(['USER_NAME' => 'Usuario -'.$request->usuario.'- incorrecto.']);
            }
        }

        if($existe->count()>=1){
            //****************** Obtener la IAP ****************
            //$estruc = regIapModel::ObtIap($existe[0]->cve_arbol);
            //if($estruc->count()<1){
              //  //$estructura = $existe[0]->cve_arbol;
              //  $estructura = 0;
            //}else{
              //  $estructura = $estruc[0]->iap_id;
            //}
            //***************************************************
            //***************** Obtiene la IP *****************//
            if (getenv('HTTP_CLIENT_IP')) {
              $ip = getenv('HTTP_CLIENT_IP');
            } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
              $ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_X_FORWARDED')) {
              $ip = getenv('HTTP_X_FORWARDED');
            } elseif (getenv('HTTP_FORWARDED_FOR')) {
              $ip = getenv('HTTP_FORWARDED_FOR');
            } elseif (getenv('HTTP_FORWARDED')) {
              $ip = getenv('HTTP_FORWARDED');
            } else {
              $ip = $_SERVER['REMOTE_ADDR'];
            }

            //****************** Obtener rol ****************
            // Ver link https://ajgallego.gitbooks.io/laravel-5/content/capitulo_4_control_de_usuarios.html
            $userrr = Auth::user();
            //$email = Auth::user()->user_email;
            $rol_idd   = regUserRolModel::ObtRolid($existe[0]->user_id);
            $role_name = regPivoteModel::ObtPivoteRol($existe[0]->user_id);
            //$rol            = regRolModel::ObtRol($rol_idd);
            $roles     = regUserRolModel::join('COMB_ROLES','COMB_ROLES.ROL_ID','=','COMB_USERS_ROLES.ROL_ID')
                          ->select('COMB_ROLES.ROL_NAME')
                          ->where('COMB_USERS_ROLES.USER_ID',$existe[0]->user_id)
                          ->get();
            $privilegios= regPivoteModel::join('COMB_PRIVILEGS','COMB_PRIVILEGS.PRIVILEG_ID','=',
                                                                'COMB_USERS_ROLES_PRIVILEGS.PRIVILEG_ID')
                          ->select('COMB_USERS_ROLES_PRIVILEGS.USER_ID'    ,'COMB_USERS_ROLES_PRIVILEGS.USER_NAME',
                                   'COMB_USERS_ROLES_PRIVILEGS.ROL_ID'     ,'COMB_USERS_ROLES_PRIVILEGS.ROL_NAME',
                                   'COMB_USERS_ROLES_PRIVILEGS.PRIVILEG_ID','COMB_PRIVILEGS.PRIVILEG_NAME')
                          ->where('COMB_USERS_ROLES_PRIVILEGS.USER_ID',$existe[0]->user_id)
                          ->get();                          
            $role_user      = regRolModel::Roluser();
            $role_admin     = regRolModel::Roladmin();
            $role_superadmin= regRolModel::Rolsuperadmin();
            $usuario        = $role_name;

            $nombre = $request->usuario;
            $status = $existe[0]->status;

            //Para comprobar si el usuario actual se ha autenticado en la aplicación podemos utilizar el método Auth::check() de la forma:
            // Ver link https://ajgallego.gitbooks.io/laravel-5/content/capitulo_4_control_de_usuarios.html
            if (Auth::check()) 
                 toastr()->success('autenticado','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('No autenticado.','Ups!',['positionClass' => 'toast-bottom-right']);
            
            
            //dd('user: '.$user);
            //dd('role: '.$role_name);
            //$request->user()->authorizeRoles(['user', 'admin','superadmin']);
            //$students = regUserModel::whereHas('roles', function($q){ $q->where('rol_name', 'superadmin'); })
            //            ->get();
            //dd('students: '.$students.' - role_user: '.$rol_user.' - role_admin: '.$role_admin.'- role_superadmin: '.$role_superadmin.' - rol: '.$rol);
            //dd('role_user: '.$role_user.' - role_admin: '.$role_admin.'- role_superadmin: '.$role_superadmin);
            //dd('students: '.$request->user()->authorizeRoles(['user', 'admin','superadmin']));
            session(['userlog' => $request->usuario,'passlog' => $request->password,'usuario' => $usuario,'ip' => $ip, 'status' => $status, 'role' => $role_name ]);
            //dd('Usuario: '.$usuario.' - Rango: '.$rango.' - Estructura: '.$estructura.'- Dependencia: '.$dependencia.' - Nombre dependencia: '.$nombre_dependencia);
            toastr()->info($nombre,'Bienvenido ');
            return view('sicinar.menu.menuInicio',compact('usuario','nombre','ip','role'));
        }else{
            return back()->withInput()->withErrors(['USER_NAME' => 'El usuario no esta dado de alta.']);
        }
    }

    //public function actionLogin(usuarioRequest $request){
    //	//dd($request->all());
    //    //$request->user()->authorizeRoles(['user', 'admin','superadmin']);
    //    $existe = usuarioModel::select('LOGIN','PASSWORD','TIPO_USUARIO','ESTRUCGOB_ID','CVE_DEPENDENCIA','CVE_ARBOL','STATUS_1')
    //        ->where('LOGIN','like','%'.$request->usuario.'%')
    //        ->where('PASSWORD','like','%'.$request->password.'%')
    //        ->where('STATUS_2',1)
    //        ->get();
    //	//dd($existe);
    //    if($existe->count()>=1){
    //        //dd('Entra if.');
    //	    if(strcmp($existe[0]->login,$request->usuario) == 0){
    //	        if(strcmp($existe[0]->password,$request->password) == 0){
    //                //dd('Entro.');
    //            }else{
    //                return back()->withInput()->withErrors(['PASSWORD' => 'Contraseña incorrecta.']);
    //            }
    //        }else{
    //            return back()->withInput()->withErrors(['LOGIN' => 'Usuario -'.$request->usuario.'- incorrecto.']);
    //        }
    //    }

    //    $estructura        ="";
    //    $dependencia       ="";
    //    $nombre_dependencia="";
    //    if($existe->count()>=1){
    //        //$estruc = estructurasModel::ObtEstruc($existe[0]->estrucgob_id);            
    //        //******** Obtener la dependencia ***********
    //        $dep = dependenciasModel::select('DEPEN_DESC')
    //                ->where('DEPEN_ID','like',$existe[0]->cve_dependencia.'%')->get();
    //        if($dep->count()<1){
    //            //$dependencia = $existe[0]->cve_dependencia;
    //            $nombre_dependencia = "Particular";
    //        }else{
    //            $nombre_dependencia = $dep[0]->depen_desc;
    //        }

            //****************** Obtener la IAP ****************
            //$estruc = regIapModel::ObtIap($existe[0]->cve_arbol);
            //if($estruc->count()<1){
              //  //$estructura = $existe[0]->cve_arbol;
              //  $estructura = 0;
            //}else{
              //  $estructura = $estruc[0]->iap_id;
            //}
            //***************************************************

    //		if($existe[0]->status_1 == '4'){  //Super administrador
    //			$usuario           = "SuperAdministrador";
    //			//$estructura        = $estruc[0]->iap_id;
    //            $id_estructura     = $existe[0]->estrucgob_id;
    //            $dependencia       = $existe[0]->cve_dependencia;
    //            //$nombre_dependencia= "Particular";
    //		}else{
    //            if($existe[0]->status_1 == '3'){ //Administrador
    //                $usuario           = "Administrador";
    //                //$estructura        = $estruc[0]->iap_id;
    //                $id_estructura     = $existe[0]->estrucgob_id;
    //                $dependencia       = $existe[0]->cve_dependencia;
    //                //$nombre_dependencia= "Particular";
    //            }else{
    //                if($existe[0]->status_1 == '2'){ //Particular
    //                    $usuario            = "Particular";
    //                    //$estructura         = $estruc[0]->iap_id;
    //                    $id_estructura      = $existe[0]->estrucgob_id;
    //                    $dependencia        = $existe[0]->cve_dependencia;
    //                    //$nombre_dependencia = "Particular";
    //                }else{
    //                    if($existe[0]->status_1 == '1'){ //operativo UNIDADES ADMINISTRATIVAS
    //                        $usuario       = "Operativo";
    //                        //$estructura    = $estruc[0]->iap_id;
    //                        $id_estructura = $existe[0]->estrucgob_id;
    //                        $dependencia   = $existe[0]->cve_dependencia;
    //                    }else{
    //                        return back()->withInput()->withErrors(['LOGIN' => 'Usuario o password incorrecto.']);
    //                    }
    //                }
    //            }
    //		}
    //		$nombre = $request->usuario;
    //        $rango = $existe[0]->status_1;
    //		if (getenv('HTTP_CLIENT_IP')) {
    //          $ip = getenv('HTTP_CLIENT_IP');
    //        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
    //          $ip = getenv('HTTP_X_FORWARDED_FOR');
    //        } elseif (getenv('HTTP_X_FORWARDED')) {
    //          $ip = getenv('HTTP_X_FORWARDED');
    //        } elseif (getenv('HTTP_FORWARDED_FOR')) {
    //          $ip = getenv('HTTP_FORWARDED_FOR');
    //        } elseif (getenv('HTTP_FORWARDED')) {
    //          $ip = getenv('HTTP_FORWARDED');
    //        } else {
    //          $ip = $_SERVER['REMOTE_ADDR'];
    //        }
    //		session(['userlog' => $request->usuario,'passlog' => $request->password,'usuario' => $usuario,'estructura' => $estructura, 'ip' => $ip, 'rango' => $rango, 'id_estructura' => $id_estructura, 'dependencia' => $dependencia,'nombre_dependencia'=>$nombre_dependencia]);
    //        //dd('Usuario: '.$usuario.' - Rango: '.$rango.' - Estructura: '.$estructura.'- Dependencia: '.$dependencia.' - Nombre dependencia: '.$nombre_dependencia);
    //		toastr()->info($nombre,'Bienvenido ');
    //		return view('sicinar.menu.menuInicio',compact('usuario','nombre','estructura','rango'));
    //	}else{
    //		return back()->withInput()->withErrors(['LOGIN' => 'El usuario no esta dado de alta.']);
    //	}
    //}

    public function actionVerUser(){
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario    = session()->get('usuario');
        $estructura = session()->get('estructura');
        //$id_estruc = session()->get('id_estructura');
        //$id_estructura = rtrim($id_estruc," ");
        $rango      = session()->get('rango');
        //$ip = session()->get('ip');

        $roles      = regUserRolModel::join('COMB_ROLES','COMB_ROLES.ROL_ID','=','COMB_USERS_ROLES.ROL_ID')
                      ->select('COMB_USERS_ROLES.ID','COMB_USERS_ROLES.USER_ID','COMB_USERS_ROLES.ROL_ID',
                               'COMB_ROLES.ROL_NAME', 'COMB_ROLES.ROL_DESC')
                      ->get();
        $users        = User::select('USER_ID','USER_NAME','USER_EMAIL','USER_PASSWORD','STATUS')
                        ->orderBy('USER_ID'  ,'ASC')
                        ->orderBy('USER_NAME','ASC' )
                        ->paginate(30);        
        //dd($usuarios->all());
        return view('sicinar.BackOffice.verUsers',compact('nombre','usuario','rango','roles','users'));
        //dd($usuarios->all());
    }


    public function actionNuevoUser(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        //$estructura   = session()->get('estructura');
        //$id_estruc  = session()->get('id_estructura');
        //$id_estructura = rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        //$ip = session()->get('ip');
        $roles        = regRolModel::select('ROL_ID','ROL_NAME','ROL_DESC')->orderBy('ROL_ID','ASC')
                        ->get();
        $users        = User::select('USER_ID','USER_NAME','USER_EMAIL','USER_PASSWORD','STATUS')
                        ->get();
        return view('sicinar.BackOffice.nuevoUser',compact('nombre','usuario','rango','roles','users'));
    }

    public function actionAltaUser(userRequest $request){
        //dd($request->all());
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario    = session()->get('usuario');
        //$estructura = session()->get('estructura');
        //$id_estruc= session()->get('id_estructura');
        //$id_estructura = rtrim($id_estruc," ");
        $rango      = session()->get('rango');
        $ip         = session()->get('ip');

        /************ Obtenemos la IP ***************************/                
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }                

        $existe = User::select('USER_ID','USER_NAME','USER_PASSWORD','STATUS')
                  ->where(trim('USER_NAME')    ,trim($request->user_name))
                  ->where(trim('USER_PASSWORD'),trim($request->user_password))
                  ->get();
                  //->where('USER_NAME','like','%'.$request->user_name.'%')
                  //->where('USER_PASSWORD','like','%'.$request->user_password.'%')                  
        //dd($existe);
        if($existe->count()>=1){
            //dd('Entra if.');
            if(strcmp($existe[0]->user_name,$request->user_name) == 0 AND 
              strcmp($existe[0]->user_password,$request->user_password) == 0){
                //dd('Procedo a dar de alta nuevo registro........');
            }else{
                toastr()->error('Usuario ya existe.','Por favor revisar!',['positionClass' => 'toast-bottom-right']);
                //return back()->withInput()->withErrors(['LOGIN' => 'Usuario -'.$request->usuario.'- incorrecto.']);
                return redirect()->route('altaUser');
            }
        }

        $user_id      = user::max('USER_ID');
        $user_id      = $user_id + 1;

        $nuevoUser = new user();

        $nuevoUser->USER_ID       = $user_id;
        $nuevoUser->USER_NAME     = strtolower($request->user_name);
        $nuevoUser->USER_EMAIL    = strtolower($request->user_name);
        $nuevoUser->USER_PASSWORD = $request->user_password;
        $nuevoUser->FECREG        = date('Y/m/d');
        if($nuevoUser->save() == true){
            toastr()->success('El Usuario ha sido registrado correctamente.','Ok!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('verUser');

            // ************** dar de alta rol en la tabla COMB_USERS_ROLES *************
            $id      = regUserRolModel::max('ID');
            $id      = $id + 1;
            $nuevoUserRol = new regUserRolModel();
            $nuevoUserRol->ID            = $id;
            $nuevoUserRol->USER_ID       = $user_id;
            $nuevoUserRol->ROL_ID        = $request->rol_id;
            if($nuevoUserRol->save() == true){
                toastr()->success('El Usuario - rol ha sido registrado correctamente.','Ok!',['positionClass' => 'toast-bottom-right']);
                //return redirect()->route('verUsuarios');
            }else{
                toastr()->error('Usuario - rol no registrado.','Por favor revisar!',['positionClass' => 'toast-bottom-right']);
                //return redirect()->route('altaUsuario');
            }

            // ************** dar de alta privilegios segun el rol en la tabla COMB_USERS_PRIVILEGS *************
            // ************** dar de alta en la tabla pivote COMB_USERS_ROLES_PRIVILEGS *************
            $tot_privilegios = regPrivilegModel::max('PRIVILEG_ID');
            if($request->rol_id == '3'){   //user [1..3]
                for($i=1;$i<=3;$i++){
                    // asignar privilegios todos x el rol de user (user)
                    $idd      = regUserPrivilegModel::max('ID');
                    $idd      = $idd + 1;
                    $nuevoUserPrivileg = new regUserPrivilegModel();
                    $nuevoUserPrivileg->ID            = $idd;
                    $nuevoUserPrivileg->USER_ID       = $user_id;
                    $nuevoUserPrivileg->PRIVILEG_ID   = $i;      
                    $nuevoUserPrivileg->save();    

                    // pivote asignar user-roles-privilegios (user)
                    //$user_name = regUserModel::ObtUser($user_id);            
                    $rol_name      = regRolModel::ObtRol($request->rol_id);
                    $privileg_name = regPrivilegModel::ObtPrivileg($i);

                    $pivote_id     = regPivoteModel::max('PIVOTE_ID');
                    $pivote_id     = $pivote_id + 1;

                    $nuevoPivote   = new regPivoteModel();
                    $nuevoPivote->PIVOTE_ID     = $pivote_id;
                    $nuevoPivote->USER_ID       = $user_id;
                    $nuevoPivote->USER_NAME     = strtolower($request->user_name);
                    $nuevoPivote->ROL_ID        = $request->rol_id;
                    $nuevoPivote->ROL_NAME      = $rol_name[0]->rol_name;            
                    $nuevoPivote->PRIVILEG_ID   = $i;      
                    $nuevoPivote->PRIVILEG_NAME = $privileg_name[0]->privileg_name; 
                    $nuevoPivote->save();                
                }
                if($nuevoPivote->save() == true){
                    toastr()->success('La matriz pivote de Usuario - rol - privilegio 1..3, ha sido registrada correctamente.','Ok!',['positionClass' => 'toast-bottom-right']);
                    //return redirect()->route('verUsuarios');
                }else{
                    toastr()->error('La matriz pivote de Usuario - rol - privilegio 1..3 no registrados.','Por favor revisar!',['positionClass' => 'toast-bottom-right']);
                    //return redirect()->route('altaUsuario');
                }            
            }else{                        //superadmin, admin [1..N]
                for($i=1;$i<=$tot_privilegios;$i++){
                    // asignar privilegios todos x el rol de user (user)
                    $idd      = regUserPrivilegModel::max('ID');
                    $idd      = $idd + 1;
                    $nuevoUserPrivileg = new regUserPrivilegModel();
                    $nuevoUserPrivileg->ID            = $idd;
                    $nuevoUserPrivileg->USER_ID       = $user_id;
                    $nuevoUserPrivileg->PRIVILEG_ID   = $i;  
                    $nuevoUserPrivileg->save();    

                    // pivote asignar user-roles-privilegios (user)
                    //$user_name = regUserModel::ObtUser($user_id);            
                    $rol_name      = regRolModel::ObtRol($request->rol_id);
                    $privileg_name = regPrivilegModel::ObtPrivileg($i);

                    $pivote_id     = regPivoteModel::max('PIVOTE_ID');
                    $pivote_id     = $pivote_id + 1;

                    $nuevoPivote   = new regPivoteModel();
                    $nuevoPivote->PIVOTE_ID     = $pivote_id;
                    $nuevoPivote->USER_ID       = $user_id;
                    $nuevoPivote->USER_NAME     = strtolower($request->user_name);
                    $nuevoPivote->ROL_ID        = $request->rol_id;
                    $nuevoPivote->ROL_NAME      = $rol_name[0]->rol_name;            
                    $nuevoPivote->PRIVILEG_ID   = $i;      
                    $nuevoPivote->PRIVILEG_NAME = $privileg_name[0]->privileg_name; 
                    $nuevoPivote->save();
                }  // Termina for
                if($nuevoPivote->save() == true){
                    toastr()->success('La matriz pivote de Usuario - rol - privilegio 1..N, ha sido registrada correctamente.','Ok!',['positionClass' => 'toast-bottom-right']);
                    //return redirect()->route('verUsuarios');
                }else{
                    toastr()->error('La matriz pivote de Usuario - rol - privilegio 1..N no registrados.','Por favor revisar!',['positionClass' => 'toast-bottom-right']);
                    //return redirect()->route('altaUsuario');
                }
            }  // Termina if

        }else{
            toastr()->error('Usuario no registrado.','Por favor revisar!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('altaUser');
        }

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         6;
        $xfuncion_id  =      6004;
        $xtrx_id      =        99;    //Alta

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $user_id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $user_id;        // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                'TRX_ID' => $xtrx_id, 'FOLIO' => $user_id])
                         ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         

            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                    'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,
                                    'TRX_ID' => $xtrx_id,'FOLIO' => $user_id])
                           ->update([
                    'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                    'IP_M'     => $regbitacora->IP       = $ip,
                    'LOGIN_M'  => $regbitacora->LOGIN_M   = $nombre,
                    'FECHA_M'  => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                    ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }


        /************ Bitacora termina *************************************/ 
        return redirect()->route('verUser');
    }


    public function actionEditarUser($id){
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario    = session()->get('usuario');
        $estructura = session()->get('estructura');
        $rango      = session()->get('rango');

        $roles      = regUserRolModel::join('COMB_ROLES','COMB_ROLES.ROL_ID','=','COMB_USERS_ROLES.ROL_ID')
                      ->select('COMB_USERS_ROLES.ID','COMB_USERS_ROLES.USER_ID','COMB_USERS_ROLES.ROL_ID',
                               'COMB_ROLES.ROL_NAME', 'COMB_ROLES.ROL_DESC')
                      ->get();
        $users      = User::select('USER_ID','USER_NAME','USER_EMAIL','USER_PASSWORD','STATUS')
                      ->where('USER_ID',$id)
                      ->first();        
        return view('sicinar.BackOffice.editarUser',compact('nombre','usuario','rango','roles','users'));
    }

    public function actionActualizarUser(userRequest $request, $id){
        //dd($request->all());
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        /************ Obtenemos la IP ***************************/                
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }         

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         6;
        $xfuncion_id  =      6004;
        $xtrx_id      =       100;    //Actualizar 

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $id;             // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/         

        if($request->perfil == '4')
            $tp = 'SA';
        else
            if($request->perfil == '3')
                $tp = 'AD';
            else
                if($request->perfil == '2')
                    $tp = 'EJ';
                else
                    if($request->perfil == '1')
                        $tp = 'CA';
                    else
                        $tp = 'PG';
        // ************** actualizar usuario tabla COMB_USERS *************                    
        $users = User::where('USER_ID',$id)
                 ->update([
                           'USER_NAME'     => strtolower($request->user_name),
                           'USER_EMAIL'    => strtolower($request->user_name),                
                           'USER_PASSWORD' => $request->user_password,
                           'STATUS'        => $request->status
                          ]);
        toastr()->success('Usuario actualizado.','Ok!',['positionClass' => 'toast-bottom-right']);
        
        // ************** actualizar usuario - rol tabla COMB_USERS_ROLES *************
        $userroles = RegUserRolModel::where('USER_ID',$id)
                 ->update([
                           'ROL_ID'        => $request->rol_id
                          ]);
        toastr()->success('Usuario - rol actualizado.','Ok!',['positionClass' => 'toast-bottom-right']);

        // ************** actualizar usuario - privilegios tabla COMB_USERS_PRIVILEG *************
            $id      = regUserRolModel::max('ID');
            $id      = $id + 1;
            $nuevoUserRol = new regUserRolModel();
            $nuevoUserRol->ID            = $id;
            $nuevoUserRol->USER_ID       = $user_id;
            $nuevoUserRol->ROL_ID        = $request->rol_id;

        return redirect()->route('verUser');
    }


    public function actionBorrarUser($id){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        //$estructura   = session()->get('estructura');
        //$id_estruc    = session()->get('id_estructura');
        //$id_estructura= rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

          /************ Obtenemos la IP ***************************/                
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }         
        //echo 'Ya entre aboorar registro..........';
        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         9;
        $xfuncion_id  =      9001;
        $xtrx_id      =       146;     // Baja de IAP

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $id;             // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         

            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/     

        /************ Eliminar user ****************************************/
        $users  = user::where('USER_ID',$id);
        if($users->count() <= 0)
            toastr()->error('No existe el usuario.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $users->delete();
            toastr()->success('Usuario ha sido eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }

        /************ Eliminar user - rol ****************************************/
        $userrol  = regUserRolModel::where('USER_ID',$id);
        if($userrol->count() <= 0)
            toastr()->error('No existe usuario - rol .','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $userrol->delete();
            toastr()->success('Usuario - rol ha sido eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }

        // ************** elimiar user - privilegs tabla COMB_USERS_PRIVILEGS *************
        $userprivilegs  = regUserPrivilegModel::where('USER_ID',$id);        
        if($userprivilegs->count() <= 0)
            toastr()->error('No existe usuario - privilegios .','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $userprivilegs->delete();
            toastr()->success('Usuario - privilegios han sido eliminados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }

        // ************** eliminar matriz pivote user-rol-privilegs COMB_USERS_ROLES_PRIVILEGS *************
        $pivote = regPivoteModel::where('USER_ID');
        if($pivote->count() <= 0)
            toastr()->error('No existe matriz pivote de usuario - rol - privilegios .','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $pivote->delete();
            toastr()->success('Matriz pivote usuario - rol - privilegios han sido eliminados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }

        /************* Termina de eliminar  el usuario **********************************/
        return redirect()->route('verUser');
    }    

    public function actionCerrarSesion(){
        session()->forget('userlog');
        session()->forget('passlog');
        session()->forget('usuario','ip','rango','plan_id');
        //session()->forget('userlog','passlog','usuario','estructura','ip','rango','id_estructura','plan_id');
        //REGRESA AL LOGIN PRINCIPAL
        //return view('sicinar.login.terminada');
        return view('sicinar.login.loginInicio');
    }

    public function actionExpirada(){
    	return view('sicinar.login.expirada');
    }

    public function actionNuevoUsuario(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        //$estructura   = session()->get('estructura');
        //$id_estruc  = session()->get('id_estructura');
        //$id_estructura = rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        //$ip = session()->get('ip');
        //$regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','DESC')
        //                ->get();
        $dependencias = dependenciasModel::select('DEPEN_ID','DEPEN_DESC')->orderBy('DEPEN_ID','DESC')
                        ->get();
        return view('sicinar.BackOffice.nuevoUsuario',compact('nombre','usuario','rango','dependencias'));
    }

    public function actionAltaUsuario(altaUsuarioRequest $request){
        //dd($request->all());
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario    = session()->get('usuario');
        //$estructura = session()->get('estructura');
        //$id_estruc= session()->get('id_estructura');
        //$id_estructura = rtrim($id_estruc," ");
        $rango      = session()->get('rango');
        $ip         = session()->get('ip');

        /************ Obtenemos la IP ***************************/                
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }                

        if($request->perfil == '4')
            $tp = 'SA';
        else
            if($request->perfil == '3')
                $tp = 'AD';
            else
                if($request->perfil == '2')
                    $tp = 'EJ';
                else
                    if($request->perfil == '1')
                        $tp = 'CA';
                    else
                        $tp = 'PG';

        //if($request->perfil == '1' AND $request->unidad == '0'){
        //    return back()->withErrors(['unidad' => 'No puedes elegir la Unidad Administrativa: ADMINISTRADOR si tiene rol OPERATIVO.']);
        //}
        //dd($request->all());
        $folio        = usuarioModel::max('FOLIO');
        $folio        = $folio+1;
        $nuevoUsuario = new usuarioModel();
        $nuevoUsuario->N_PERIODO = date('Y');
        $nuevoUsuario->FOLIO     = $folio;
        //$nuevoUsuario->ESTRUCGOB_ID    = '21500';
        $nuevoUsuario->CVE_DEPENDENCIA = $request->unidad;
        //$nuevoUsuario->CVE_ARBOL  = $request->iap_id;
        $nuevoUsuario->LOGIN      = strtolower($request->usuario);
        $nuevoUsuario->PASSWORD   = $request->password;
        $nuevoUsuario->AP_PATERNO = strtoupper($request->paterno);
        $nuevoUsuario->AP_MATERNO = strtoupper($request->materno);
        $nuevoUsuario->NOMBRES    = strtoupper($request->nombre);
        $nuevoUsuario->NOMBRE_COMPLETO = strtoupper($request->nombre.' '.$request->paterno.' '.$request->materno);
        $nuevoUsuario->TIPO_USUARIO = $tp;
        $nuevoUsuario->STATUS_1 = $request->perfil;
        $nuevoUsuario->STATUS_2 = 1;
        $nuevoUsuario->EMAIL    = strtolower($request->usuario);
        $nuevoUsuario->IP       = $ip;
        $nuevoUsuario->FECHA_REGISTRO = date('Y/m/d');
        if($nuevoUsuario->save() == true){
            toastr()->success('El Usuario ha sido creado correctamente.','Ok!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('verUsuarios');
        }else{
            toastr()->error('El Usuario no ha sido creado.','Ha ocurrido algo inesperado!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('altaUsuario');
        }

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         6;
        $xfuncion_id  =      6004;
        $xtrx_id      =        99;    //Alta

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $folio])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $folio;          // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $folio])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         

            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $folio])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/ 
        return redirect()->route('verUsuarios');
    }

    //public function actionVerUsuario(){
    //    $nombre     = session()->get('userlog');
    //    $pass       = session()->get('passlog');
    //    if($nombre == NULL AND $pass == NULL){
    //        return view('sicinar.login.expirada');
    //    }
    //    $usuario    = session()->get('usuario');
    //    $estructura = session()->get('estructura');
    //    //$id_estruc = session()->get('id_estructura');
    //    //$id_estructura = rtrim($id_estruc," ");
    //    $rango      = session()->get('rango');
    //    //$ip = session()->get('ip');

    //    //$regiap       = regIapModel::select('IAP_ID','IAP_DESC','IAP_STATUS')->get();            
    //    $dependencias = dependenciasModel::select('DEPEN_ID','DEPEN_DESC')->get();
    //    $usuarios   = usuarioModel::select('FOLIO','NOMBRE_COMPLETO','EMAIL','CVE_DEPENDENCIA','CVE_ARBOL','LOGIN','PASSWORD','STATUS_1','STATUS_2')
    //        ->orderBy('STATUS_1','DESC')
    //        ->orderBy('FOLIO','ASC')
    //        ->paginate(15);        
    //    //dd($usuarios->all());
    //    return view('sicinar.BackOffice.verUsuarios',compact('nombre','usuario','rango','usuarios','dependencias'));
    //    //dd($usuarios->all());
    //}

    public function actionEditarUsuario($id){
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario    = session()->get('usuario');
        $estructura = session()->get('estructura');
        //$id_estruc = session()->get('id_estructura');
        //$id_estructura = rtrim($id_estruc," ");
        $rango      = session()->get('rango');
            //            ->where('ESTRUCGOB_ID','like','%21500%')
            //->where('CLASIFICGOB_ID','=',1)
        $dependencias= dependenciasModel::select('DEPEN_ID','DEPEN_DESC')
                       ->orderBy('DEPEN_ID','ASC')
                       ->get();
        //$regiap      = regIapModel::select('IAP_ID','IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','ASC')->get();            
        $user        = usuarioModel::select('FOLIO','NOMBRES','AP_PATERNO','AP_MATERNO','LOGIN','PASSWORD','STATUS_1','STATUS_2','EMAIL','CVE_DEPENDENCIA','CVE_ARBOL')
            ->where('FOLIO',$id)
            ->first();        
        return view('sicinar.BackOffice.editarUsuario',compact('nombre','usuario','rango','user','dependencias'));
    }

    public function actionActualizarUsuario(altaUsuarioRequest $request, $id){
        //dd($request->all());
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        /************ Obtenemos la IP ***************************/                
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }         

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         6;
        $xfuncion_id  =      6004;
        $xtrx_id      =       100;    //Actualizar 

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $id;             // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/         

        if($request->perfil == '4')
            $tp = 'SA';
        else
            if($request->perfil == '3')
                $tp = 'AD';
            else
                if($request->perfil == '2')
                    $tp = 'EJ';
                else
                    if($request->perfil == '1')
                        $tp = 'CA';
                    else
                        $tp = 'PG';
        $actUser = usuarioModel::where('FOLIO',$id)
            ->update([
                'CVE_DEPENDENCIA' => $request->unidad,
                //'CVE_ARBOL'       => $request->iap_id,
                'LOGIN'           => strtolower($request->usuario),
                'PASSWORD'        => $request->password,
                'AP_PATERNO'      => strtoupper($request->paterno),
                'AP_MATERNO'      => strtoupper($request->materno),
                'NOMBRES'         => strtoupper($request->nombre),
                'NOMBRE_COMPLETO' => strtoupper($request->nombre.' '.$request->paterno.' '.$request->materno),
                'EMAIL'           => strtolower($request->usuario),
                'TIPO_USUARIO'    => $tp,
                'STATUS_1'        => $request->perfil,
                'STATUS_2'        => $request->status_2
            ]);
        toastr()->success('El Usuario ha sido actualizado correctamente.','Ok!',['positionClass' => 'toast-bottom-right']);
        return redirect()->route('verUsuarios');

    }


    public function actionBorrarUsuario($id){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        //$estructura   = session()->get('estructura');
        //$id_estruc    = session()->get('id_estructura');
        //$id_estructura= rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

          /************ Obtenemos la IP ***************************/                
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }         
        //echo 'Ya entre aboorar registro..........';
        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         9;
        $xfuncion_id  =      9001;
        $xtrx_id      =       146;     // Baja de IAP

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $id;             // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         

            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/     
        /************ Elimina el usuario **************************************/
        $usuarios=usuarioModel::select('FOLIO','NOMBRE_COMPLETO','EMAIL','CVE_DEPENDENCIA','LOGIN','PASSWORD','STATUS_1','STATUS_2')->where('FOLIO',$id);
        if($usuarios->count() <= 0)
            toastr()->error('No existe el usuario.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $usuarios->delete();
            toastr()->success('Usuario ha sido eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************* Termina de eliminar  el usuario **********************************/
        return redirect()->route('verUsuarios');
    }    


    public function actionActivarUsuario($id){
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $activar = usuarioModel::where('FOLIO',$id)
            ->update([
                'STATUS_2' => '1'
            ]);
        return redirect()->route('verUsuarios');
    }

    public function actionDesactivarUsuario($id){
        $nombre = session()->get('userlog');
        $pass   = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $activar = usuarioModel::where('FOLIO',$id)
            ->update([
                'STATUS_2' => '0'
            ]);
        return redirect()->route('verUsuarios');
    }
}
