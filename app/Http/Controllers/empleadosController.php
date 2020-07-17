<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\empleadoRequest;
use App\regEmpleadosModel;
use App\regBitacoraModel;
use App\regMunicipioModel;
use App\regEntidadesModel; 
use App\regSaldosempModel;
use App\regDiarioModel;

// Exportar a excel 
use App\Exports\ExportEmpleadosExcel;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class empleadosController extends Controller
{

    public function actionBuscarEmpleado(Request $request)
    {
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip'); 

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')
                        ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                        ->get();
        $regmunicipio = regMunicipioModel::join('CEN_CAT_ENTIDADES_FED',
                                                'CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
                                                'CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID')
                        ->select( 'CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID',
                                  'CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC','CEN_CAT_MUNICIPIOS.MUNICIPIOID',
                                  'CEN_CAT_MUNICIPIOS.MUNICIPIONOMBRE')
                        ->wherein('CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID',[15])
                        ->orderBy('CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('CEN_CAT_MUNICIPIOS.MUNICIPIONOMBRE','DESC')
                        ->get();
        //$regperiodos  = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
        //                ->get(); 
        //      
        //                           
        //$regservicios = regServicioModel::join('CEN_CAT_RUBROS','CEN_CAT_RUBROS.RUBRO_ID', '=', 
        //                                                       'CEN_CAT_SERVICIOS.RUBRO_ID')
        //                ->select('CEN_CAT_SERVICIOS.SERVICIO_ID','CEN_CAT_SERVICIOS.SERVICIO_DESC',
        //                         'CEN_CAT_RUBROS.RUBRO_DESC')
        //                ->get();                          
        //$regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->get();                              
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $name  = $request->get('name');   
        //$email = $request->get('email');  
        //$bio   = $request->get('bio');    
        $regempleado= regempleadosModel::orderBy('EMP_ID', 'ASC')
                      ->name($name)           //Metodos personalizados es equvalente a ->where('IAP_DESC', 'LIKE', "%$name%");
                     //->email($email)         //Metodos personalizados
                     //->bio($bio)             //Metodos personalizados
                     ->paginate(30);
        if($regempleado->count() <= 0){
            toastr()->error('No existen empleados.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoPadron');
        }            
        return view('sicinar.empleados.verEmpleados', compact('nombre','usuario','regempleado','regentidades','regmunicipio'));
    }


    public function actionnuevoEmpleado(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip'); 

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')                    
                        ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                        ->get();
        $regmunicipio = regMunicipioModel::join('CEN_CAT_ENTIDADES_FED','CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
                                                                       'CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID')
                        ->select('CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID',
                                 'CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
                                 'CEN_CAT_MUNICIPIOS.MUNICIPIOID',
                                 'CEN_CAT_MUNICIPIOS.MUNICIPIONOMBRE')
                        ->wherein('CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID',[15])
                        ->orderBy('CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('CEN_CAT_MUNICIPIOS.MUNICIPIONOMBRE','DESC')
                        ->get();
        //$regperiodos  = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
        //                ->get();               
        $regestadocta = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                        'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                        'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                        'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                        'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                        ->get();                        
        $regempleado  = regEmpleadosModel::select('PERIODO_ID','PROGRAMA_ID','EMP_ID',
                        'DEPTO_ID','EMP_AP','EMP_AM','EMP_NOMBRES','EMP_NOMBRECOMPLETO','EMP_CURP','EMP_FECING','EMP_FECING2',
                        'EMP_FECNAC','EMP_FECNAC2','EMP_SEXO','EMP_RFC','EMP_IDOFICIAL','EMP_DOM','EMP_COL','EMP_CP','EMP_ENTRECALLE',
                        'EMP_YCALLE','EMP_OTRAREF','EMP_TEL','EMP_CEL','EMP_EMAIL','ENTIDADNAC_ID','ENTIDADFED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDOCIVIL_ID','GRADOESTUDIOS_ID','EMP_PUESTO','TIPOEMP_ID','CLASEEMP_ID','EMP_SUELDO',
                        'EMP_OBS1','EMP_OBS2','EMP_FOTO1','EMP_FOTO2','EMP_STATUS1','EMP_STATUS2',
                        'FECREG','IP','USU','FECHA_M','IP_M','USU_M')
                        //->orderBy('PERIODO_ID','asc')
                        ->orderBy('EMP_ID','asc')
                        ->get();
        //dd($unidades);
        return view('sicinar.empleados.nuevoEmpleado',compact('regmunicipio','regentidades','regempleado','regestadocta','nombre','usuario'));
    }

    public function actionAltanuevoEmpleado(Request $request){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
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

        // **************** validar duplicidad ******************************/
        setlocale(LC_TIME, "spanish");        
        $xperiodo_id  = (int)date('Y');        
        //if(!empty($request->periodo_d1) and !empty($request->mes_d1) and !empty($request->dia_d1) ){
        //    //toastr()->error('muy bien 1....................','¡ok...........!',['positionClass' => 'toast-bottom-right']);
        //    $mes1 = regMesesModel::ObtMes($request->mes_id1);
        //    $dia1 = regDiasModel::ObtDia($request->dia_id1);                
        //    //xiap_feccons = $dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1;
        //}   //endif
        //if(!empty($request->periodo_d2) and !empty($request->mes_d2) and !empty($request->dia_d2) ){
        //    $mes2 = regMesesModel::ObtMes($request->mes_id2);
        //    $dia2 = regDiasModel::ObtDia($request->dia_id2);        
        //}
        //$mes1 = regMesesModel::ObtMes($request->mes_id1);
        //$dia1 = regDiasModel::ObtDia($request->dia_id1);                
        //$mes2 = regMesesModel::ObtMes($request->mes_id2);
        //$dia2 = regDiasModel::ObtDia($request->dia_id2);                        

        $xnombre_completo = substr(strtoupper(trim($request->emp_ap)).' '.strtoupper(trim($request->emp_am)).' '.strtoupper(trim($request->emp_nombres)),0,99);
        //$mes1 = regMesesModel::ObtMes($request->mes_id1);
        //$dia1 = regDiasModel::ObtDia($request->dia_id1);
        //$mes2 = regMesesModel::ObtMes($request->mes_id2);
        //$dia2 = regDiasModel::ObtDia($request->dia_id2);        
        // *************** Validar triada ***********************************/
        $triada = regEmpleadosModel::where(['EMP_NOMBRECOMPLETO' => $xnombre_completo, 
                                           'EMP_CURP'            => substr(strtoupper(trim($request->emp_curp)),0,17),
                                           'MUNICIPIO_ID'        => $request->municipio_id])
                  ->get();
        if($triada->count() >= 1)
            //toastr()->error('Ya existe un beneficiario:'.$nombre_completo.' CURP:'.$request->curp.' Municipio:'.$request->municipio_id.'--','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
            //return back()->withInput()->withErrors(['PRIMER_APELLIDO' => 'El PRIMER APELLIDO '.$request->PRIMER_APELLIDO.' contiene caracteres inválidos. Favor de verificar.']);
            //return back()->withInput()->withErrors(['NOMBRE_COMPLETO' => 'Beneficiario '.$xnombre_completo.'CURP' => 'Con CURP '.$request->curp.'MUNICIPIO_ID' => 'Clave de municipio '.$request->municipio_id.' Ya existe. Por favor verificar.']);
            return back()->withInput()->withErrors(['EMP_NOMBRECOMPLETO' => 'Empleado '.$xnombre_completo.' Ya existe empleado, curp y municipio (triada). Por favor verificar.']);
        else{        
            // *************** Validar curp ***********************************/
            $dupcurp = regEmpleadosModel::where('EMP_CURP',substr(strtoupper(trim($request->emp_curp)),0,17))
                       ->get();
            if($dupcurp->count() >= 1)
                return back()->withInput()->withErrors(['EMP_CURP' => ' EMP_CURP '.$request->emp_curp.' Ya existe otro empleado con el mismo curp. Por favor verificar.']);
            else{                    
                //**************************** Alta ********************************/
                $xperiodo_id= (int)date('Y');              
                $emp_id = regEmpleadosModel::max('EMP_ID');
                $emp_id = $emp_id + 1;

                $name1 =null;
                //Comprobar  si el campo foto1 tiene un archivo asignado:
                if($request->hasFile('emp_foto1')){
                    $name1 = $emp_id.'_'.$request->file('emp_foto1')->getClientOriginalName(); 
                    //$file->move(public_path().'/images/', $name1);
                    //sube el archivo a la carpeta del servidor public/images/
                    $request->file('emp_foto1')->move(public_path().'/images/', $name1);
                }

                $nuevoPadron = new regEmpleadosModel();
                $nuevoPadron->PERIODO_ID        = $xperiodo_id;
                $nuevoPadron->EMP_ID            = $emp_id;
                $nuevoPadron->DEPTO_ID          = $request->depto_id;
                $nuevoPadron->EMP_AP            = substr(strtoupper(trim($request->emp_ap))     ,0,79);
                $nuevoPadron->EMP_AM            = substr(strtoupper(trim($request->emp_am))     ,0,79);
                $nuevoPadron->EMP_NOMBRES       = substr(strtoupper(trim($request->emp_nombres)),0,79);
                $nuevoPadron->EMP_NOMBRECOMPLETO= substr(strtoupper(trim($request->emp_ap)).' '.strtoupper(trim($request->emp_am)).' '.strtoupper(trim($request->emp_nombres)),0,99);
                $nuevoPadron->EMP_CURP          = substr(strtoupper(trim($request->emp_curp)),0,17);
                $nuevoPadron->EMP_FECING        = $request->input('emp_fecing');
                $nuevoPadron->EMP_FECING2       = $request->input('emp_fecing');
                //$nuevoPadron->FECHA_NACIMIENTO = date('Y/m/d', strtotime($request->fecha_nacimiento));
                //$nuevoPadron->FECHA_NACIMIENTO = date('Y/m/d', strtotime(trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2) ));
                //$nuevoPadron->FECHA_NACIMIENTO2= trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2);
                $nuevoPadron->EMP_SEXO          = $request->emp_sexo;

                $nuevoPadron->EMP_DOM           = substr(strtoupper(trim($request->emp_dom))    ,0,149);        
                $nuevoPadron->EMP_COL           = substr(strtoupper(trim($request->emp_col))    ,0, 79);        
                $nuevoPadron->LOCALIDAD         = substr(strtoupper(trim($request->localidad))  ,0,149);                
                $nuevoPadron->EMP_CP            = $request->emp_cp; 
                $nuevoPadron->EMP_OTRAREF       = substr(strtoupper(trim($request->emp_otraref)),0, 99);        
                $nuevoPadron->MUNICIPIO_ID      = $request->municipio_id;
                $nuevoPadron->ENTIDADNAC_ID     = $request->entidadnac_id;
                $nuevoPadron->ENTIDADFED_ID     = 15;  // $request->entidadfed_id;
                //$nuevoPadron->FECHA_INGRESO   = date('Y/m/d', strtotime($request->fecha_ingreso));
                //$nuevoPadron->FECHA_INGRESO   = date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) ));
                //$nuevoPadron->FECHA_INGRESO2  = trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);
                $nuevoPadron->EMP_TEL           = substr(strtoupper(trim($request->emp_tel))  ,0,29);        
                $nuevoPadron->EMP_CEL           = substr(strtoupper(trim($request->emp_cel))  ,0,29);        
                $nuevoPadron->EMP_EMAIL         = substr(strtolower(trim($request->emp_email)),0,59);                
                //$nuevoPadron->CLIENTE_GEOREFLATITUD = $request->emp_georeflatitud;
                //$nuevoPadron->CLIENTE_GEOREFLONGITUD= $request->emp_georeflongitud;
                $nuevoPadron->EMP_FOTO1         = $name1;

                $nuevoPadron->IP                = $ip;
                $nuevoPadron->USU               = $nombre;         // Usuario ;
                $nuevoPadron->save();
                if($nuevoPadron->save() == true){
                    toastr()->success('Empleado dado de alta.','ok!',['positionClass' => 'toast-bottom-right']);

                    /************ Estado de cuenta del empleado *************************************/ 
                    $xperiodo_id  = (int)date('Y');              
                    $regestadocta = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                            'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                            'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                            'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                            'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                            ->where('EMP_ID', $emp_id)
                            ->get();
                    if($regestadocta->count() <= 0){              // Alta
                        $nuevoedocta = new regSaldosempModel();              

                        $nuevoedocta->PERIODO_ID = $xperiodo_id;            
                        $nuevoedocta->EMP_ID     = $emp_id;        

                        $nuevoedocta->IP         = $ip;
                        $nuevoedocta->USU        = $nombre;         // Usuario ;
                        $nuevoedocta->save();

                        if($nuevoedocta->save() == true)
                            toastr()->success('Estado de cuenta del empleado creado.',' dada de alta!',['positionClass' => 'toast-bottom-right']);
                    }   /************ Estado de cuenta del empleado termina *************************************/                                 


                    /************ Bitacora inicia *************************************/ 
                    setlocale(LC_TIME, "spanish");        
                    $xip          = session()->get('ip');
                    $xperiodo_id  = (int)date('Y');
                    $xprograma_id = 1;
                    $xmes_id      = (int)date('m');
                    $xproceso_id  =         3;
                    $xfuncion_id  =      3007;
                    $xtrx_id      =         2;    //Alta
                    $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                                                            'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 
                                                            'FECHA_M', 'IP_M', 'LOGIN_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,
                                            'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO' => $emp_id])
                                   ->get();
                    if($regbitacora->count() <= 0){              // Alta
                        $nuevoregBitacora = new regBitacoraModel();              
                        $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                        $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                        $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                        $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                        $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                        $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                        $nuevoregBitacora->FOLIO      = $emp_id;          // Folio    
                        $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                        $nuevoregBitacora->IP         = $ip;             // Folio
                        $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                        $nuevoregBitacora->save();
                        if($nuevoregBitacora->save() == true)
                            toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                        else
                            toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                    }else{                   
                        //*********** Obtine el no. de veces *****************************
                        $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                              'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO' => $emp_id])
                                     ->max('NO_VECES');
                        $xno_veces = $xno_veces+1;                        
                        //*********** Termina de obtener el no de veces *****************************         
                        $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                       ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $emp_id])
                                       ->update([
                                                 'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                                 'IP_M'    => $regbitacora->IP       = $ip,
                                                 'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                                 'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                                ]);
                        toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    }
                    /************ Bitacora termina *************************************/ 

                    //return redirect()->route('nuevoPadron');
                    //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','estructura','id_estructura','rango','preguntas','apartados'));
                }else{
                    toastr()->error('Error al dar de alta empleado. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                    //return back();
                    //return redirect()->route('nuevoProceso');
                }   /**************** Termina de dar de alta ********************************/
            }       /**************** Termina de validar duplicidad del CURP ****************/
        }           /**************** Termina de validar duplicidad triada *****************/

        return redirect()->route('verEmpleados');
    }

    
    public function actionverEmpleados(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');    

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')
                        ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                        ->get();
        $regmunicipio = regMunicipioModel::join('CEN_CAT_ENTIDADES_FED','CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
                                                                       'CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID')
                        ->select( 'CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID','CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
                                  'CEN_CAT_MUNICIPIOS.MUNICIPIOID','CEN_CAT_MUNICIPIOS.MUNICIPIONOMBRE')
                        ->wherein('CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID',[15])
                        ->orderBy('CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('CEN_CAT_MUNICIPIOS.MUNICIPIONOMBRE','DESC')
                        ->get();
        //$regperiodos  = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
        //                ->get();     
        $regestadocta = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                        'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                        'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                        'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                        'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                        ->get();                        
        //********* Validar rol de usuario **********************/
        if($role->rol_name == 'user'){          
            $regempleado=regEmpleadosModel::select('PERIODO_ID','EMP_ID',
                        'DEPTO_ID','EMP_AP','EMP_AM','EMP_NOMBRES','EMP_NOMBRECOMPLETO','EMP_CURP','EMP_FECING','EMP_FECING2',
                        'EMP_FECNAC','EMP_FECNAC2','EMP_SEXO','EMP_RFC','EMP_IDOFICIAL','EMP_DOM','EMP_COL','EMP_CP','EMP_ENTRECALLE',
                        'EMP_YCALLE','EMP_OTRAREF','EMP_TEL','EMP_CEL','EMP_EMAIL','ENTIDADNAC_ID','ENTIDADFED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDOCIVIL_ID','GRADOESTUDIOS_ID','EMP_PUESTO','TIPOEMP_ID','CLASEEMP_ID','EMP_SUELDO',
                        'EMP_OBS1','EMP_OBS2','EMP_FOTO1','EMP_FOTO2','EMP_STATUS1','EMP_STATUS2',
                        'FECREG','IP','USU','FECHA_M','IP_M','USU_M')
                        ->where(  'USU' ,$nombre)
                        //->orderBy('PERIODO_ID','asc')
                        ->orderBy('EMP_ID','asc')
                        ->paginate(50);
        }else{                          
            $regempleado=regEmpleadosModel::select('PERIODO_ID','EMP_ID',
                        'DEPTO_ID','EMP_AP','EMP_AM','EMP_NOMBRES','EMP_NOMBRECOMPLETO','EMP_CURP','EMP_FECING','EMP_FECING2',
                        'EMP_FECNAC','EMP_FECNAC2','EMP_SEXO','EMP_RFC','EMP_IDOFICIAL','EMP_DOM','EMP_COL','EMP_CP','EMP_ENTRECALLE',
                        'EMP_YCALLE','EMP_OTRAREF','EMP_TEL','EMP_CEL','EMP_EMAIL','ENTIDADNAC_ID','ENTIDADFED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDOCIVIL_ID','GRADOESTUDIOS_ID','EMP_PUESTO','TIPOEMP_ID','CLASEEMP_ID','EMP_SUELDO',
                        'EMP_OBS1','EMP_OBS2','EMP_FOTO1','EMP_FOTO2','EMP_STATUS1','EMP_STATUS2',
                        'FECREG','IP','USU','FECHA_M','IP_M','USU_M')
                        ->orderBy('EMP_ID','ASC')
                         ->paginate(50);            
        }
        if($regempleado->count() <= 0){
            toastr()->error('No existe empleado.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoPadron');
        }
        return view('sicinar.empleados.verEmpleados',compact('nombre','usuario','role','regentidades','regmunicipio','regempleado','regestadocta'));

    }


    public function actionEditarEmpleado($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');        

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')         
                        ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                        ->get();
        $regmunicipio = regMunicipioModel::join('CEN_CAT_ENTIDADES_FED','CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
                                                                       'CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID')
                        ->select( 'CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID',
                                  'CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
                                  'CEN_CAT_MUNICIPIOS.MUNICIPIOID','CEN_CAT_MUNICIPIOS.MUNICIPIONOMBRE')
                        ->wherein('CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID',[15])
                        ->orderBy('CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('CEN_CAT_MUNICIPIOS.MUNICIPIONOMBRE','DESC')
                        ->get();
        //$regperiodos  = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
        //                ->get();                                       
        $regestadocta = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                        'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                        'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                        'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                        'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                        ->where('EMP_ID', $id)
                        ->get();                        
        $regempleado  = regEmpleadosModel::select('PERIODO_ID','EMP_ID',
                        'DEPTO_ID','EMP_AP','EMP_AM','EMP_NOMBRES','EMP_NOMBRECOMPLETO','EMP_CURP','EMP_FECING','EMP_FECING2',
                        'EMP_FECNAC','EMP_FECNAC2','EMP_SEXO','EMP_RFC','EMP_IDOFICIAL','EMP_DOM','EMP_COL','EMP_CP','EMP_ENTRECALLE',
                        'EMP_YCALLE','EMP_OTRAREF','EMP_TEL','EMP_CEL','EMP_EMAIL','ENTIDADNAC_ID','ENTIDADFED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDOCIVIL_ID','GRADOESTUDIOS_ID','EMP_PUESTO','TIPOEMP_ID','CLASEEMP_ID','EMP_SUELDO',
                        'EMP_OBS1','EMP_OBS2','EMP_FOTO1','EMP_FOTO2','EMP_STATUS1','EMP_STATUS2',
                        'FECREG','IP','USU','FECHA_M','IP_M','USU_M')
                         ->where(  'EMP_ID' ,$id)
                         ->orderBy('EMP_ID' ,'asc')
                         //->orderBy('IAP_ID','asc')
                         ->first();
        if($regempleado->count() <= 0){
            toastr()->error('No existe empleado.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoPadron');
        }
        return view('sicinar.empleados.editarEmpleado',compact('nombre','usuario','regentidades','regmunicipio','regempleado','regestadocta'));

    }

    public function actionActualizarEmpleado(empleadoRequest $request, $id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');         

        // **************** actualizar ******************************
        $regempleado   = regEmpleadosModel::where('EMP_ID',$id);
        if($regempleado->count() <= 0)
            toastr()->error('No existe empleado.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //*************** Actualizar ********************************/
            //if(!empty($request->periodo_d1) and !empty($request->mes_d1) and !empty($request->dia_d1) ){
            //    //toastr()->error('muy bien 1....................','¡ok...........!',['positionClass' => 'toast-bottom-right']);
            //    $mes1 = regMesesModel::ObtMes($request->mes_id1);
            //    $dia1 = regDiasModel::ObtDia($request->dia_id1);                
            //    //xiap_feccons = $dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1;
            //}   //endif
            //if(!empty($request->periodo_d2) and !empty($request->mes_d2) and !empty($request->dia_d2) ){
            //    $mes2 = regMesesModel::ObtMes($request->mes_id2);
            //    $dia2 = regDiasModel::ObtDia($request->dia_id2);        
            //}
            //$mes1 = regMesesModel::ObtMes($request->mes_id1);
            //$dia1 = regDiasModel::ObtDia($request->dia_id1);                
            //$mes2 = regMesesModel::ObtMes($request->mes_id2);
            //$dia2 = regDiasModel::ObtDia($request->dia_id2);                

            //'PERIODO_ID','CLIENTE_ID','CLIENTE_FOLIO','CLIENTE_AP','CLIENTE_AM','CLIENTE_NOMBRES',
            //            'CLIENTE_NOMBRECOMPLETO','CLIENTE_CURP','CLIENTE_FECING','CLIENTE_FECING2','CLIENTE_FECNAC','CLIENTE_FECNAC2',
            //            'CLIENTE_SEXO','CLIENTE_RFC','CLIENTE_IDOFICIAL','CLIENTE_DOM','CLIENTE_COL','CLIENTE_CP',
            //            'CLIENTE_ENTRECALLE','CLIENTE_YCALLE','CLIENTE_OTRAREF','CLIENTE_TEL','CLIENTE_CEL','CLIENTE_EMAIL',
            //            'ENTIDADNAC_ID','ENTIDADFED_ID','MUNICIPIO_ID','LOCALIDAD_ID','LOCALIDAD','EDOCIVIL_ID','GRADOESTUDIOS_ID',
            //            'CLIENTE_PUESTO','TIPOCLIENTE_ID','CLASECLIENTE_ID','CLIENTE_OBS1','CLIENTE_OBS2','CLIENTE_FOTO1','CLIENTE_FOTO2',
            //            'CLIENTE_STATUS1','CLIENTE_STATUS2','CLIENTE_GEOREFLATITUD','CLIENTE_GEOREFLONGITUD',
            //            'FECREG','IP','USU','FECHA_M','IP_M','USU_M'
            $xfecha_ing   = str_replace("/", "", trim($request->emp_fecing));
            $xdia         = substr($xfecha_ing, 0, 2);
            $xmes         = substr($xfecha_ing, 2, 2);
            $xanio        = substr($xfecha_ing, 4, 4);
            //$fecha_okr    = $anio."/".$mes."/".$dia;
            $xfecha_ingok = $xdia."/".$xmes."/".$xanio;

            $yfecha_nac   = str_replace("/", "", trim($request->emp_fecnac));
            $ydia         = substr($yfecha_nac, 0, 2);
            $ymes         = substr($yfecha_nac, 2, 2);
            $yanio        = substr($yfecha_nac, 4, 4);
            $yfecha_nacok = $ydia."/".$ymes."/".$yanio;            
            //dd('fecha nac:'.$request->cliente_fecnac,'-año:'.$yanio,'-mes:'.$ymes,'-dia:'.$ydia,'-Fecha_ok:'.$yfecha_nacok);            
            $regempleado = regEmpleadosModel::where('EMP_ID',$id)        
                           ->update([                
                'DEPTO_ID'           => $request->depto_id,
                //'CLIENTE_FECING'   => $xfecha_ingok,
                //'CLIENTE_FECING2'  => $xfecha_ingok,   //$request->input('emp_fecing'),
                'EMP_AP'             => substr(strtoupper(trim($request->emp_ap))     ,0,79),
                'EMP_AM'             => substr(strtoupper(trim($request->emp_am))     ,0,79),
                'EMP_NOMBRES'        => substr(strtoupper(trim($request->emp_nombres)),0,79),
                'EMP_NOMBRECOMPLETO' => substr(strtoupper(trim($request->emp_ap)).' '.strtoupper(trim($request->emp_am)).' '.strtoupper(trim($request->emp_nombres)),0,99),
                'EMP_CURP'           => substr(strtoupper(trim($request->emp_curp)),0,17),
                'EMP_SEXO'           => $request->emp_sexo,
                //'EMP_FECNAC'       => $yfecha_nacok,
                //'EMP_FECNAC2'      => $yfecha_nacok,

                'ENTIDADNAC_ID'      => $request->entidadnac_id,                
                //'ENTIDADFED_ID'    => $request->entidad_fed_id,                
                'MUNICIPIO_ID'       => $request->municipio_id,
                //'SERVICIO_ID'      => $request->servicio_id,

                'EMP_DOM'            => substr(strtoupper(trim($request->emp_dom))    ,0,149),
                'EMP_COL'            => substr(strtoupper(trim($request->emp_col))    ,0, 79),
                'LOCALIDAD'          => substr(strtoupper(trim($request->localidad))  ,0,149),
                'EMP_OTRAREF'        => substr(strtoupper(trim($request->emp_otraref)),0, 99),                
                'EMP_CP'             => $request->emp_cp,
                
                'EMP_TEL'            => substr(strtoupper(trim($request->emp_tel))  ,0,29),
                'EMP_CEL'            => substr(strtoupper(trim($request->emp_cel))  ,0,29),
                'EMP_EMAIL'          => substr(strtolower(trim($request->emp_email)),0,59),
                //'EMP_GEOREFLONGITUD' => $request->emp_georeflongitud,               
                //'EMP_GEOREFLATITUD'  => $request->emp_georeflatitud,               
                'EMP_STATUS1'        => $request->emp_status1,                

                'IP_M'               => $ip,
                'USU_M'              => $nombre,
                'FECHA_M'            => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
            toastr()->success('Empleado actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Estado de cuenta del empleado *************************************/ 
            $xperiodo_id  = (int)date('Y');              
            $regestadocta = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                            'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                            'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                            'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                            'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                            ->where('EMP_ID', $id)
                            ->get();
            if($regestadocta->count() <= 0){              // Alta
                $nuevoedocta = new regSaldosempModel();              

                $nuevoedocta->PERIODO_ID = $xperiodo_id;            
                $nuevoedocta->EMP_ID     = $id;        

                $nuevoedocta->IP         = $ip;
                $nuevoedocta->USU        = $nombre;         // Usuario ;
                $nuevoedocta->save();

                if($nuevoedocta->save() == true)
                    toastr()->success('Estado de cuenta del empleado creado.',' dada de alta!',['positionClass' => 'toast-bottom-right']);
            }   /************ Estado de cuenta del empleado termina *************************************/                                 


            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3007;
            $xtrx_id      =         3;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                                                    'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 
                                                    'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                    'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
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
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/

        return redirect()->route('verEmpleados');

    }


    public function actionBorrarEmpleado($id){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip'); 

        /************ Diario de movimientos *************************************/               
        $regdiario    = regDiarioModel::where('EMP_ID',$id);
        if($regdiario->count() > 0)
            toastr()->error('El empleado tiene movimientos.','¡No se puede eliminar!',['positionClass' => 'toast-bottom-right']);
        else{                                 
            //$regdiario->delete();
            //toastr()->success('Diario de movimientos del empleado eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //********* validar si tiene saldo  > 0 ****************************//
            $regestadocta = regSaldosempModel::selectRaw(
                        'Sum(CARGO_M01+CARGO_M02+CARGO_M03+CARGO_M04+CARGO_M05+CARGO_M06+CARGO_M07+CARGO_M08+CARGO_M09+CARGO_M10+CARGO_M11+CARGO_M12)-
                         Sum(ABONO_M01+ABONO_M02+ABONO_M03+ABONO_M04+ABONO_M05+ABONO_M06+ABONO_M07+ABONO_M08+ABONO_M09+ABONO_M10+ABONO_M11+ABONO_M12)
                         as SALDOEMP')
                           ->where('EMP_ID',$id)
                           ->get();
              //dd($regapor );
            if($regestadocta->count() <= 0)
                toastr()->error('No existe estado de cuenta del empleado.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
            else{        
                $xsaldo   = $regestadocta[0]->saldoemp;            
                //dd('xsaldo:'.$xsaldo,'--arreglo de saldo:'.$regestadocta[0]->saldoemp);
                if($xsaldo > 0)
                    toastr()->error('El empleado tiene saldo.','¡No es posible eliminar!',['positionClass' => 'toast-bottom-right']);
                else{        

                    /************ Eliminar empleado **************************************/
                    $regempleado   = regEmpleadosModel::where('EMP_ID',$id);
                    if($regempleado->count() <= 0)
                        toastr()->error('No existe empleado.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
                    else{        
                        $regempleado->delete();
                        toastr()->success('empleado eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                        //********************* Elimina estado de cuenta del empleado //
                        $regedoctaemp = regSaldosempModel::where('EMP_ID',$id);
                        if($regedoctaemp->count() <= 0)
                            toastr()->error('No existe estado de cuenta del cliente.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
                        else{        
                            //******************* Elimina estado de cuenta del cliente ***************//                        
                            $regedoctaemp->delete();
                            toastr()->success('Estado de cuenta del empleado eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                        }
                        
                        /************ Bitacora inicia *************************************/ 
                        setlocale(LC_TIME, "spanish");        
                        $xip          = session()->get('ip');
                        $xperiodo_id  = (int)date('Y');
                        $xprograma_id = 1;
                        $xmes_id      = (int)date('m');
                        $xproceso_id  =         3;
                        $xfuncion_id  =      3007;
                        $xtrx_id      =         4;     // Baja 
                        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                                       'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                                       ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
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
                            $xno_veces   = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                                    'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                           ->max('NO_VECES');
                            $xno_veces   = $xno_veces+1;                        
                            //*********** Termina de obtener el no de veces *****************************         
                            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,
                                                    'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                                           ->update([
                                                     'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                                     'IP_M'     => $regbitacora->IP       = $ip,
                                                     'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                                     'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                                    ]);
                            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                        }   /************ Bitacora termina *************************************/                 
                    }       /************* Termina de eliminar empleado *********************************/
                }           /************* Termina de eliminar saldo del empleado ***********************/
            }               /************* Termina validar saldo ****************************************/
        }                   /************* Termina de validar diario de movimientos *********************/

        return redirect()->route('verEmpleados');
    }    

    // exportar a formato excel
    public function actionExportEmpleadosExcel(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip'); 

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         3;
        $xfuncion_id  =      3007;
        $xtrx_id      =         5;            // Exportar a formato Excel
        $id           =         0;
        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                                                'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 
                                                'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                 'TRX_ID' => $xtrx_id,'FOLIO' => $id])
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
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                  'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************                
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                           ->update([
                                     'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M'     => $regbitacora->IP       = $ip,
                                     'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                     'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/  

        return Excel::download(new ExportEmpleadosExcel, 'Empleados_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato PDF
    public function actionExportEmpleadosPdf(){
        set_time_limit(0);
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 0);

        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');       

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         3;
        $xfuncion_id  =      3007;
        $xtrx_id      =         6;       //Exportar a formato PDF
        $id           =         0;
        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                       'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                       ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
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
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                  'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                         ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                    'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                           ->update([
                                     'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M'     => $regbitacora->IP       = $ip,
                                     'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                     'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/ 

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')     
                                           ->get();
        $regmunicipio = regMunicipioModel::select('ENTIDADFEDERATIVAID', 'MUNICIPIOID', 'MUNICIPIONOMBRE')
                                           ->wherein('ENTIDADFEDERATIVAID',[15])
                                           ->get(); 
        //$regperiodos  = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
        //                ->get();                                    
        $regempleado   = regEmpleadosModel::select('PERIODO_ID','EMP_ID',
                        'DEPTO_ID','EMP_AP','EMP_AM','EMP_NOMBRES','EMP_NOMBRECOMPLETO','EMP_CURP','EMP_FECING','EMP_FECING2',
                        'EMP_FECNAC','EMP_FECNAC2','EMP_SEXO','EMP_RFC','EMP_IDOFICIAL','EMP_DOM','EMP_COL','EMP_CP','EMP_ENTRECALLE',
                        'EMP_YCALLE','EMP_OTRAREF','EMP_TEL','EMP_CEL','EMP_EMAIL','ENTIDADNAC_ID','ENTIDADFED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDOCIVIL_ID','GRADOESTUDIOS_ID','EMP_PUESTO','TIPOEMP_ID','CLASEEMP_ID','EMP_SUELDO',
                        'EMP_OBS1','EMP_OBS2','EMP_FOTO1','EMP_FOTO2','EMP_STATUS1','EMP_STATUS2',
                        'FECREG','IP','USU','FECHA_M','IP_M','USU_M')
                        ->orderBy('EMP_ID','asc')
                        ->get();                               
        if($regempleado->count() <= 0){
            toastr()->error('No existen empleados.','Uppss!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verClientes');
        }
        $pdf = PDF::loadView('sicinar.pdf.empleadsPdf', compact('nombre','usuario','regentidades','regmunicipio','regempleado'));
        //$options = new Options();
        //$options->set('defaultFont', 'Courier');
        //$pdf->set_option('defaultFont', 'Courier');
        $pdf->setPaper('A4', 'landscape');      
        //$pdf->set('defaultFont', 'Courier');          
        //$pdf->setPaper('A4','portrait');

        // Output the generated PDF to Browser
        return $pdf->stream('CatalogoDeEmpleados');
    }

    // Gráfica por estado
    public function actionEmpleadoxEdo(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');               

        $regtotxedo = regEmpleadosModel::join('CEN_CAT_ENTIDADES_FED','CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=',
                                                                'CEN_EMPLEADOS.ENTIDAD_FED_ID')
                      ->selectRaw('COUNT(*) AS TOTALXEDO')
                      ->get();
        $regempleado= regEmpleadosModel::join('CEN_CAT_ENTIDADES_FED','CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=',
                                                                      'CEN_EMPLEADOS.ENTIDADFED_ID')
                      ->selectRaw('CEN_EMPLEADOS.ENTIDADFED_ID, 
                                   CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC AS ESTADO, COUNT(*) AS TOTAL')
                      ->groupBy(  'CEN_EMPLEADOS.ENTIDADFED_ID','CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC')
                      ->orderBy(  'CEN_EMPLEADOS.ENTIDADFED_ID','asc')
                      ->get();
        //dd($procesos);
        return view('sicinar.numeralia.empleadoxedo',compact('regempleado','regtotxedo','nombre','usuario','rango'));
    }

    
    // Gráfica por municipio
    public function actionEmpleadoxMpio(){
        $nombre      = session()->get('userlog');
        $pass        = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario     = session()->get('usuario');
        $role        = session()->get('role');
        $rango       = session()->get('rango');
        $dep         = session()->get('dep');        
        $ip          = session()->get('ip'); 

        $regtotxmpio = regEmpleadosModel::join('CEN_CAT_MUNICIPIOS',
                                     [['CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID','=',15],
                                      ['CEN_CAT_MUNICIPIOS.MUNICIPIOID','=','CEN_EMPLEADOS.MUNICIPIO_ID']
                                      ])
                       ->selectRaw('COUNT(*) AS TOTALXMPIO')
                       ->get();
        $regempleado  = regEmpleadosModel::join('CEN_CAT_MUNICIPIOS',
                                      [['CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID','=',15],
                                       ['CEN_CAT_MUNICIPIOS.MUNICIPIOID','=','CEN_EMPLEADOS.MUNICIPIO_ID']
                                      ])
                       ->selectRaw('CEN_EMPLEADOS.MUNICIPIO_ID, CEN_CAT_MUNICIPIOS.MUNICIPIONOMBRE AS MUNICIPIO,COUNT(*) AS TOTAL')
                       ->groupBy(  'CEN_EMPLEADOS.MUNICIPIO_ID', 'CEN_CAT_MUNICIPIOS.MUNICIPIONOMBRE')
                       ->orderBy(  'CEN_EMPLEADOS.MUNICIPIO_ID','asc')
                       ->get();
        //dd($procesos);
        return view('sicinar.numeralia.empleadoxmpio',compact('regempleado','regtotxmpio','nombre','usuario','rango'));
    }

    // Gráfica por Servicio
    public function actionClientexServicio(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip'); 
        $arbol_id     = session()->get('arbol_id');               

        $regtotales =  regEmpleadosModel::join('CEN_CAT_SERVICIOS','CEN_CAT_SERVICIOS.SERVICIO_ID',  '=',
                                                                   'CEN_EMPLEADOS.SERVICIO_ID')
                       ->selectRaw('COUNT(*) AS TOTAL')
                       ->get();
        $regempleado = regEmpleadosModel::join('CEN_CAT_SERVICIOS','CEN_CAT_SERVICIOS.SERVICIO_ID','=',
                                                                   'CEN_EMPLEADOS.SERVICIO_ID')
                       ->selectRaw('CEN_EMPLEADOS.SERVICIO_ID, 
                                    CEN_CAT_SERVICIOS.SERVICIO_DESC AS SERVICIO, COUNT(*) AS TOTAL')
                       ->groupBy('CEN_EMPLEADOS.SERVICIO_ID','CEN_CAT_SERVICIOS.SERVICIO_DESC')
                       ->orderBy('CEN_EMPLEADOS.SERVICIO_ID','asc')
                       ->get();
        //dd($regempleados);
        return view('sicinar.numeralia.empleadoxservicio',compact('nombre','usuario','rango','regempleado','regtotales'));
    }

    // Gráfica x sexo
    public function actionEmpleadoxSexo(){
        $nombre      = session()->get('userlog');
        $pass        = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario     = session()->get('usuario');
        $role        = session()->get('role');
        $rango       = session()->get('rango');
        $dep         = session()->get('dep');        
        $ip          = session()->get('ip');              

        // http://www.chartjs.org/docs/#bar-chart
        $regtotal   = regEmpleadosModel::selectRaw('COUNT(*) AS TOTAL')
                      ->get();
        $regempleado= regEmpleadosModel::selectRaw('EMP_SEXO, COUNT(*) AS TOTAL')
                      ->groupBy('EMP_SEXO')
                      ->orderBy('EMP_SEXO','asc')
                      ->get();
        //dd($procesos);
        return view('sicinar.numeralia.empleadoxsexo',compact('regtotal','regempleado','nombre','usuario','rango'));
    }   

    // Gráfica x edad
    public function actionClientexedad(){
        $nombre      = session()->get('userlog');
        $pass        = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario     = session()->get('usuario');
        $role        = session()->get('role');
        $rango       = session()->get('rango');
        $dep         = session()->get('dep');        
        $ip          = session()->get('ip');            

        // http://www.chartjs.org/docs/#bar-chart
        $regtotal    = regempleadosModel::selectRaw('COUNT(*) AS TOTAL')
                       ->get();
        //$regempleado=regempleadosModel::selectRaw('EXTRACT(YEAR FROM SYSDATE) - TO_NUMBER(SUBSTR(FECHA_NACIMIENTO2,7,4)) EDAD,
        //                                      COUNT(1) AS TOTAL')
        //             ->groupBy('EXTRACT(YEAR FROM SYSDATE) - TO_NUMBER(SUBSTR(FECHA_NACIMIENTO2,7,4))')
        $regempleado = regempleadosModel::select('PERIODO_ID2')
                       ->selectRaw('EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2 EDAD,
                                    COUNT(1) AS TOTAL')
                       ->groupBy('PERIODO_ID2')                   
                       ->orderBy('TOTAL','asc')
                       ->get();
        //dd($procesos);
        return view('sicinar.numeralia.clientexedad',compact('regtotal','regempleado','nombre','usuario','rango'));
    }   

    // Gráfica x rango de edad
    public function actionClientexRangoedad(){
        $nombre      = session()->get('userlog');
        $pass        = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario     = session()->get('usuario');
        $role        = session()->get('role');
        $rango       = session()->get('rango');
        $dep         = session()->get('dep');        
        $ip          = session()->get('ip'); 

        // http://www.chartjs.org/docs/#bar-chart
        $regtotal    = regempleadosModel::selectRaw('COUNT(*) AS TOTAL')
                       ->get();
        $regempleado  = regempleadosModel::select('PERIODO_ID')
                   ->selectRaw('SUM(CASE WHEN (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) <= 5                               THEN 1 ELSE 0 END) EMENOSDE5')  
                   ->selectRaw('SUM(CASE WHEN (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) >= 6 AND (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) <=10 THEN 1 ELSE 0 END) E06A10')
                   ->selectRaw('SUM(CASE WHEN (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) >=11 AND (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) <=17 THEN 1 ELSE 0 END) E11A17')
                   ->selectRaw('SUM(CASE WHEN (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) >=18 AND (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) <=30 THEN 1 ELSE 0 END) E18A30')
                   ->selectRaw('SUM(CASE WHEN (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) >=31 AND (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) <=60 THEN 1 ELSE 0 END) E31A60')
                   ->selectRaw('SUM(CASE WHEN (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) >=61                                                    THEN 1 ELSE 0 END) E61YMAS')
                    ->selectRaw('COUNT(*) AS TOTAL')
                    ->groupBy('PERIODO_ID')
                    ->orderBy('PERIODO_ID','asc')
                    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.clientexrangoedad',compact('regtotal','regempleado','nombre','usuario','rango'));
    }        

}
