<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\placaRequest;
use App\regPlacaModel;
use App\regBitacoraModel;
use App\regMarcaModel;
use App\regTipogastoModel;
use App\regTipooperacionModel;
use App\dependenciasModel;
use App\regAsignatariosModel;

// Exportar a excel 
use App\Exports\excelPLacas;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class placasController extends Controller
{

    public function actionBuscarPlaca(Request $request)
    {
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
        $dep          = session()->get('dep');
        $ip           = session()->get('ip');

        $regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
                        ->get();   
        $regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
                        ->get();                                                 
        $regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
                        ->get();                                                                         
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $name  = $request->get('name');   
        $codigo= $request->get('codigo');  
        $placa = $request->get('placa');    
        $regplaca = regPlacaModel::orderBy('PLACA_ID', 'ASC')
                  ->name($name)           //Metodos personalizados es equvalente a ->where('IAP_DESC', 'LIKE', "%$name%");
                  ->codigo($codigo)       //Metodos personalizados
                  ->placa($placa)         //Metodos personalizados
                  ->paginate(30);
        if($regplaca->count() <= 0){
            toastr()->error('No existen registros de placas.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }            
        return view('sicinar.placas.verPlacas', compact('nombre','usuario','regplaca','regmarca','regtipogasto','regtipooper'));
    }


    public function actionVerPlacas(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $dep          = session()->get('dep');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        $regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
                        ->get();   
        $regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
                        ->get();                                                 
        $regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
                        ->get(); 
        $regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE','PLACA_ANTERIOR',
                               'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
                               'DEPENDENCIA_ID','PLACA_MODELO','PLACA_MODELO2','PLACA_GASOLINA','PLACA_INVENTARIO',
                               'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
                               'PLACA_STATUS1','PLACA_STATUS2',
                               'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('PLACA_ID','ASC')
                        ->paginate(30);
        if($regplaca->count() <= 0){
            toastr()->error('No existen registros de placas dadas de alta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.placas.verPlacas',compact('nombre','usuario','regplaca','regmarca','regtipogasto','regtipooper'));
    }


    public function actionNuevaPlaca(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');

        $regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
                        ->get();   
        $regasignatario=regAsignatariosModel::select('PLACA_ID','PLACA_PLACA','DEPENDENCIA_ID','SP_ID',
                        'SP_NOMB','STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->get();                           
        $regdepen     = dependenciasModel::select('DEPEN_ID','DEPEN_DESC','ESTRUCGOB_ID','CLASIFICGOB_ID')
                        ->where('DEPEN_ID','like','2110%')->orderBy('DEPEN_ID','ASC')
                        ->get();                        
        $regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
                        ->get();                                                 
        $regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
                        ->get(); 
        $regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE','PLACA_ANTERIOR',
                               'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
                               'DEPENDENCIA_ID','PLACA_MODELO','PLACA_MODELO2','PLACA_GASOLINA','PLACA_INVENTARIO',
                               'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
                               'PLACA_STATUS1','PLACA_STATUS2',
                               'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('PLACA_ID','asc')
                        ->get();
        //dd($unidades);
        return view('sicinar.placas.nuevaPlaca',compact('nombre','usuario','regmarca','regtipogasto','regtipooper','regdepen','regasignatario','regplaca'));
    }

    public function actionAltaNuevaPlaca(Request $request){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
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

        $placaduplicada = regPlacaModel::where('PLACA_PLACA',$request->placa_placa);
        if($placaduplicada->count() > 0)
            toastr()->error('Ya existen placas.','¡Por favor revisar!',['positionClass' => 'toast-bottom-right']);
        else{
            /************ Dar de alta *****************************/ 
            $depen    = dependenciasModel::ObtDepen($request->dependencia_id);
            $placa_id = regPlacaModel::max('PLACA_ID');
            $placa_id = $placa_id+1;

            $nuevaplaca = new regPlacaModel();
            $name1 =null;
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('placa_foto1')){
                $name1 = $placa_id.'_'.$request->file('placa_foto1')->getClientOriginalName(); 
                //$file->move(public_path().'/images/', $name1);
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('placa_foto1')->move(public_path().'/images/', $name1);
            }
            $name2 =null;
            //Comprobar  si el campo foto2 tiene un archivo asignado:        
            if($request->hasFile('placa_foto2')){
                $name2 = $placa_id.'_'.$request->file('placa_foto2')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('placa_foto2')->move(public_path().'/images/', $name2);
            }

            $nuevaplaca->PLACA_ID        = $placa_id;
            $nuevaplaca->PLACA_DESC      = strtoupper($request->placa_desc);
            $nuevaplaca->PLACA_PLACA     = strtoupper($request->placa_placa);
            $nuevaplaca->PLACA_SERIE     = strtoupper($request->placa_serie);
            $nuevaplaca->PLACA_ANTERIOR  = strtoupper($request->placa_anterior);
            $nuevaplaca->PLACA_CILINDROS = $request->placa_cilindros;
            $nuevaplaca->MARCA_ID        = $request->marca_id;
            $nuevaplaca->TIPOG_ID        = $request->tipog_id;
            $nuevaplaca->TIPOO_ID        = $request->tipoo_id;
            $nuevaplaca->SP_ID           = $request->sp_id;
            $nuevaplaca->DEPENDENCIA_ID  = $request->dependencia_id;
            $nuevaplaca->PLACA_MODELO2   = $request->placa_modelo2;
            $nuevaplaca->PLACA_GASOLINA  = substr(trim(strtoupper($request->placa_gasolina))  ,0,29);
            $nuevaplaca->PLACA_INVENTARIO= substr(trim(strtoupper($request->placa_inventario)),0,19);
            $nuevaplaca->PLACA_OBS1      = substr(trim($depen[0]->depen_desc)                 ,0,149);
            $nuevaplaca->PLACA_OBS2      = substr(trim(strtoupper($request->placa_obs2))      ,0,149);
            $nuevaplaca->PLACA_FOTO1     = $name1;
            //$nuevaplaca->PLACA_FOTO2   = $name2;
            $nuevaplaca->IP              = $ip;
            $nuevaplaca->LOGIN           = $nombre;         // Usuario ;
            $nuevaplaca->save();

            if($nuevaplaca->save() == true){
                toastr()->success('Placas registradas.','Placa dada de alta!',['positionClass' => 'toast-bottom-right']);
                //return redirect()->route('nuevaIap');
                //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','estructura','id_estructura','rango','preguntas','apartados'));

                //**************** actualizar asignatario ******************************/
                $duplicado = regAsignatariosModel::where(['DEPENDENCIA_ID' => $request->dependencia_id,'PLACA_PLACA' => $request->placa_placa]);
                if($duplicado->count() <= 0){
                    /******************* Dar de alta asignatario ********************/
                    $nuevasignada = new regAsignatariosModel();
                    $nuevasignada->DEPENDENCIA_ID= $request->dependencia_id;
                    $nuevasignada->PLACA_ID      = $placa_id;
                    $nuevasignada->PLACA_PLACA   = strtoupper($request->placa_placa);

                    $nuevasignada->SP_NOMB       = substr(trim(strtoupper($request->placa_obs2)),0,149);
                    
                    $nuevasignada->IP            = $ip;
                    $nuevasignada->LOGIN         = $nombre;         // Usuario ;
                    $nuevasignada->save();
                    if($nuevasignada->save() == true)
                        toastr()->success('Placas asignadas.','Placa dada de alta!',['positionClass' => 'toast-bottom-right']);

                }else{   
                    //****************** Actualizar ************************/
                    $regasignatario=regAsignatariosModel::where(['DEPENDENCIA_ID' => $request->dependencia_id,
                                                                'PLACA_PLACA'    => $request->placa_placa])
                                    ->update([                
                                              'SP_NOMB'  => substr(trim(strtoupper($request->placa_obs2)),0,149),

                                              'IP_M'     => $ip,
                                              'LOGIN_M'  => $nombre,
                                              'FECHA_M'  => date('Y/m/d')    //date('d/m/Y')                                
                                              ]);
                    toastr()->success('Placas - asignatario actualizadas.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }       //************ Validar duplicado **************************/

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         6;
                $xfuncion_id  =      6001;
                $xtrx_id      =       145;    //Alta 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $placa_id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $placa_id;         // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);

                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $placa_id])
                                ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                   'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $placa_id])
                                   ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M' => $regbitacora->IP           = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 

            }else
                toastr()->error('Error al dar de alta las placas. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //****************************** Dar de alta ************************/
        }   //************************** validar duplicado de placas ************/

        return redirect()->route('verPlacas');
    }


    public function actionEditarPlaca($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $dep           = session()->get('dep');        

        $regasignatario=regAsignatariosModel::select('PLACA_ID','PLACA_PLACA','DEPENDENCIA_ID','SP_ID',
                        'SP_NOMB','STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->get();                           
        $regdepen     = dependenciasModel::select('DEPEN_ID','DEPEN_DESC','ESTRUCGOB_ID','CLASIFICGOB_ID')
                        ->where('DEPEN_ID','like','2110%')->orderBy('DEPEN_ID','ASC')
                        ->get();          
        $regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
                        ->get();   
        $regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
                        ->get();                                                 
        $regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
                        ->get(); 
        $regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE','PLACA_ANTERIOR',
                               'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
                               'DEPENDENCIA_ID','PLACA_MODELO','PLACA_MODELO2','PLACA_GASOLINA','PLACA_INVENTARIO',
                               'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
                               'PLACA_STATUS1','PLACA_STATUS2',
                               'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('PLACA_ID',$id)
                        ->orderBy('PLACA_ID','ASC')
                        ->first();
        if($regplaca->count() <= 0){
            toastr()->error('No existe registros.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.placas.editarPlaca',compact('nombre','usuario','regmarca','regtipogasto','regtipooper','regasignatario','regdepen','regplaca'));
    }

    public function actionActualizarPlaca(placaRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $dep           = session()->get('dep');        
        $ip            = session()->get('ip');

        // **************** actualizar ******************************
        $regplaca = regPlacaModel::where('PLACA_ID',$id);
        if($regplaca->count() <= 0)
            toastr()->error('No existe placa.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $name1 =null;
            //if (isset($request->iap_foto1)||empty($request->iap_foto1)||is_null($reques->iap_foto1)) {
            //if (isset($request->iap_foto1)||empty($request->iap_foto1)||is_null($reques->iap_foto1)) {
            //if(isset($_PUT['submit'])){
            //   if(!empty($_PUT['iap_foto1'])){
            if(isset($request->placa_foto1)){
                if(!empty($request->placa_foto1)){
                    //Comprobar  si el campo foto1 tiene un archivo asignado:
                    if($request->hasFile('placa_foto1')){
                      $name1 = $id.'_'.$request->file('placa_foto1')->getClientOriginalName(); 
                      //sube el archivo a la carpeta del servidor public/images/
                      $request->file('placa_foto1')->move(public_path().'/images/', $name1);
                    }
                }
            }
            $depen     = dependenciasModel::ObtDepen($request->dependencia_id);
            $regplaca  = regPlacaModel::where('PLACA_ID',$id)        
            ->update([                
                'PLACA_DESC'      => strtoupper($request->placa_desc),
                'PLACA_PLACA'     => strtoupper($request->placa_placa),
                'PLACA_CILINDROS' => $request->placa_cilindros,
                'DEPENDENCIA_ID'  => $request->dependencia_id,
                'PLACA_OBS1'      => substr(trim($depen[0]->depen_desc)           ,0,149),
                'PLACA_OBS2'      => substr(trim(strtoupper($request->placa_obs2)),0,149),
                'MARCA_ID'        => $request->marca_id,                
                'TIPOG_ID'        => $request->tipog_id,
                'TIPOO_ID'        => $request->tipoo_id,
                'SP_ID'           => $request->sp_id,
                'PLACA_SERIE'     => strtoupper($request->placa_serie),
                'PLACA_ANTERIOR'  => strtoupper($request->placa_anterior),
                'PLACA_MODELO2'   => $request->placa_modelo2,
                'PLACA_GASOLINA'  => substr(trim(strtoupper($request->placa_gasolina))  ,0,29),
                'PLACA_INVENTARIO'=> substr(trim(strtoupper($request->placa_inventario)),0,19),
                'PLACA_STATUS1'   => $request->placa_status1, 
                //'PLACA_FOTO1'   => $name1, 
                'IP_M'            => $ip,
                'LOGIN_M'         => $nombre,
                'FECHA_M'         => date('Y/m/d')    //date('d/m/Y')                                
            ]);
            toastr()->success('Placa actualizada correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //**************** actualizar asiganario ******************************/
            $duplicado = regAsignatariosModel::where(['DEPENDENCIA_ID' => $request->dependencia_id,'PLACA_PLACA' => $request->placa_placa]);
            if($duplicado->count() <= 0)
                toastr()->error('No existe asignatario.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
            else{   
                $regasignatario=regAsignatariosModel::where(['DEPENDENCIA_ID' => $request->dependencia_id,'PLACA_PLACA' => $request->placa_placa])     
                                ->update([                
                                          'SP_NOMB'  => substr(trim(strtoupper($request->placa_obs2)),0,149),

                                          'IP_M'            => $ip,
                                          'LOGIN_M'         => $nombre,
                                          'FECHA_M'         => date('Y/m/d')    //date('d/m/Y')                                
                                            ]);
                toastr()->success('Asignatario actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         6;
            $xfuncion_id  =      6001;
            $xtrx_id      =       146;    //Actualizar        

            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
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
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                             'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                            ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' =>     $id])
                ->update([
                    'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                    'IP_M' => $regbitacora->IP           = $ip,
                    'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                    'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }
            /************ Bitacora termina *************************************/                     
        }

        return redirect()->route('verPlacas');
    }


    public function actionBorrarPlaca($id){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');
        //echo 'Ya entre aboorar registro..........';

        /************ Elimina la IAP **************************************/
        $regplaca = regPlacaModel::where('PLACA_ID',$id);
        //                    ->find('RUBRO_ID',$id);
        if($regplaca->count() <= 0)
            toastr()->error('No existen Placas.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regplaca->delete();
            toastr()->success('Placas eliminadas.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
           setlocale(LC_TIME, "spanish");        
           $xip          = session()->get('ip');
           $xperiodo_id  = (int)date('Y');
           $xprograma_id = 1;
           $xmes_id      = (int)date('m');
           $xproceso_id  =         6;
           $xfuncion_id  =      6001;
           $xtrx_id      =       146;     // Baja 

           $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID','FUNCION_ID', 
                        'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
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

        }
        /************* Termina de eliminar  **********************************/
        return redirect()->route('verPlacas');
    }    


    // exportar a formato excel
    public function actionExportPlacasExcel(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');
        
        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         6;
        $xfuncion_id  =      6001;
        $xtrx_id      =       148;            // Exportar a formato Excel
        $id           =         0;

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
                'IP_M'     => $regbitacora->IP       = $ip,
                'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/  
        return Excel::download(new excelPlacas, 'Padron_Placas_'.date('d-m-Y').'.xlsx');
    }


    // exportar a formato PDF
    public function ActionExportPlacasPdf(){
        set_time_limit(0);
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 0);

        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         6;
        $xfuncion_id  =      6001;
        $xtrx_id      =       149;       //Exportar a formato PDF
        $id           =         0;

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

        //$regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
        //                ->get();   
        //$regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
        //                ->get();                                                 
        //$regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
        //                ->get(); 
        //$regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE','PLACA_ANTERIOR',
        //                       'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
        //                       'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
        //                       'PLACA_STATUS1','PLACA_STATUS2',
        //                       'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
        //                ->orderBy('PLACA_ID','ASC')
        //                ->get();
        $regplaca=regPlacaModel::join('COMB_CAT_MARCAS'        ,'COMB_CAT_MARCAS.MARCA_ID'       ,'=','COMB_PLACAS.MARCA_ID')
                               ->join('COMB_CAT_TIPOGASTO'     ,'COMB_CAT_TIPOGASTO.TIPOG_ID'    ,'=','COMB_PLACAS.TIPOG_ID')
                               ->join('COMB_CAT_TIPOOPERACION' ,'COMB_CAT_TIPOOPERACION.TIPOO_ID','=','COMB_PLACAS.TIPOO_ID')
                             ->select('COMB_PLACAS.PLACA_ID'   ,'COMB_PLACAS.PLACA_PLACA'   ,'COMB_PLACAS.PLACA_DESC',
                                      'COMB_PLACAS.PLACA_SERIE','COMB_PLACAS.PLACA_ANTERIOR','COMB_PLACAS.PLACA_CILINDROS',
                                      'COMB_CAT_MARCAS.MARCA_DESC',
                                      'COMB_CAT_TIPOGASTO.TIPOG_DESC',
                                      'COMB_CAT_TIPOOPERACION.TIPOO_DESC',
                                      'COMB_PLACAS.DEPENDENCIA_ID',
                                      'COMB_PLACAS.PLACA_MODELO',
                                      'COMB_PLACAS.PLACA_MODELO2',
                                      'COMB_PLACAS.PLACA_GASOLINA',
                                      'COMB_PLACAS.PLACA_INVENTARIO',
                                      'COMB_PLACAS.PLACA_OBS1',      'COMB_PLACAS.PLACA_OBS2',
                                      'COMB_PLACAS.PLACA_STATUS1',   'COMB_PLACAS.FECREG')
                            ->orderBy('COMB_PLACAS.PLACA_ID','ASC')
                            ->get();         
        if($regplaca->count() <= 0){
            toastr()->error('No existen registros en el padron de placas.','Uppss!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verPlacas');
        }
        //$pdf = PDF::loadView('sicinar.pdf.placasPdf', compact('nombre','usuario','regmarca','regtipogasto','regtipooper','regplaca'));
        $pdf = PDF::loadView('sicinar.pdf.placasPdf', compact('nombre','usuario','regplaca'));
        //$options = new Options();
        //$options->set('defaultFont', 'Courier');
        //$pdf->set_option('defaultFont', 'Courier');
        //******** Horizontal ***************
        //$pdf->setPaper('A4', 'landscape');      
        //$pdf->set('defaultFont', 'Courier');          
        //$pdf->setPaper('A4','portrait');
        // Output the generated PDF to Browser
        //******** vertical *************** 
        //El tamaño de hoja se especifica en page_size puede ser letter, legal, A4, etc.         
        $pdf->setPaper('letter','portrait');
        return $pdf->stream('Padron_De_Placas');
    }

    // Gráfica de Padron de placas por marca
    public function GplacasxMarca(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');        

        $regtotxmarca=regPlacaModel::join('COMB_CAT_MARCAS','COMB_CAT_MARCAS.MARCA_ID','=','COMB_PLACAS.MARCA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXMARCA')
                      ->get();
        $regplaca    =regPlacaModel::join('COMB_CAT_MARCAS','COMB_CAT_MARCAS.MARCA_ID','=','COMB_PLACAS.MARCA_ID')
                      ->selectRaw('COMB_PLACAS.MARCA_ID, COMB_CAT_MARCAS.MARCA_DESC AS MARCA, COUNT(*) AS TOTAL')
                      ->groupBy('COMB_PLACAS.MARCA_ID','COMB_CAT_MARCAS.MARCA_DESC')
                      ->orderBy('COMB_PLACAS.MARCA_ID','asc')
                      ->get();
        return view('sicinar.numeralia.gplacasxmarca',compact('regplaca','regtotxmarca','nombre','usuario','rango'));
    }

    // Gráfica de placas por tipo de gasto
    public function GplacasxTipog(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');        

        $regtotxtipog=regPlacaModel::join('COMB_CAT_TIPOGASTO','COMB_CAT_TIPOGASTO.TIPOG_ID','=',
                                                                'COMB_PLACAS.TIPOG_ID')
                        ->selectRaw('COUNT(*) AS TOTALXTIPOG')
                        ->get();
        $regplaca=regPlacaModel::join('COMB_CAT_TIPOGASTO','COMB_CAT_TIPOGASTO.TIPOG_ID','=','COMB_PLACAS.TIPOG_ID')
                      ->selectRaw('COMB_PLACAS.TIPOG_ID,COMB_CAT_TIPOGASTO.TIPOG_DESC AS TIPO_GASTO,COUNT(*) AS TOTAL')
                        ->groupBy('COMB_PLACAS.TIPOG_ID','COMB_CAT_TIPOGASTO.TIPOG_DESC')
                        ->orderBy('COMB_PLACAS.TIPOG_ID','asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.gplacasxtipog',compact('regplaca','regtotxtipog','nombre','usuario','rango'));
    }

    // Gráfica de placas por tipo de operación admon.
    public function GplacasxTipoo(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');        

        $regtotxtipoo =regPlacaModel::join('COMB_CAT_TIPOOPERACION','COMB_CAT_TIPOOPERACION.TIPOO_ID','=',
                                                                            'COMB_PLACAS.TIPOO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXTIPOO')
                      ->get();
        $regplaca=regPlacaModel::join('COMB_CAT_TIPOOPERACION','COMB_CAT_TIPOOPERACION.TIPOO_ID','=',
                                                               'COMB_PLACAS.TIPOO_ID')
                      ->selectRaw('COMB_PLACAS.TIPOO_ID,COMB_CAT_TIPOOPERACION.TIPOO_DESC, 
                                   COUNT(*) AS TOTAL')
                      ->groupBy('COMB_PLACAS.TIPOO_ID','COMB_CAT_TIPOOPERACION.TIPOO_DESC')
                      ->orderBy('COMB_PLACAS.TIPOO_ID','asc')
                      ->get();
        return view('sicinar.numeralia.gplacasxtipoo',compact('regplaca','regtotxtipoo','nombre','usuario','rango'));
    }

    // Gráfica demanda de transacciones (Bitacora)
    public function Gbitacora(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');        
        // http://www.chartjs.org/docs/#bar-chart
        $regbitatxmes = regBitacoraModel::join('COMB_CAT_PROCESOS','COMB_CAT_PROCESOS.PROCESO_ID' ,'=','COMB_BITACORA.PROCESO_ID')
                        ->join('COMB_CAT_FUNCIONES','COMB_CAT_FUNCIONES.FUNCION_ID','=','COMB_BITACORA.FUNCION_ID')
                        ->join('COMB_CAT_TRX'      ,'COMB_CAT_TRX.TRX_ID'          ,'=','COMB_BITACORA.TRX_ID')
                        ->join('COMB_CAT_MESES'    ,'COMB_CAT_MESES.MES_ID'        ,'=','COMB_BITACORA.MES_ID')
                        ->select('COMB_BITACORA.MES_ID','COMB_CAT_MESES.MES_DESC')
                        ->selectRaw('COUNT(*) AS TOTALGENERAL')
                        ->groupBy('COMB_BITACORA.MES_ID','COMB_CAT_MESES.MES_DESC')
                        ->orderBy('COMB_BITACORA.MES_ID','asc')
                        ->get();        
        $regbitatot=regBitacoraModel::join('COMB_CAT_PROCESOS','COMB_CAT_PROCESOS.PROCESO_ID' ,'=',
                                           'COMB_BITACORA.PROCESO_ID')
                                    ->join('COMB_CAT_FUNCIONES','COMB_CAT_FUNCIONES.FUNCION_ID','=',
                                           'COMB_BITACORA.FUNCION_ID')
                                    ->join('COMB_CAT_TRX', 'COMB_CAT_TRX.TRX_ID' ,'=','COMB_BITACORA.TRX_ID')
                         ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 1 THEN 1 END) AS M01')  
                         ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 2 THEN 1 END) AS M02')
                         ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 3 THEN 1 END) AS M03')
                         ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 4 THEN 1 END) AS M04')
                         ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 5 THEN 1 END) AS M05')
                         ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 6 THEN 1 END) AS M06')
                         ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 7 THEN 1 END) AS M07')
                         ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 8 THEN 1 END) AS M08')
                         ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 9 THEN 1 END) AS M09')
                         ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID =10 THEN 1 END) AS M10')
                         ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID =11 THEN 1 END) AS M11')
                         ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID =12 THEN 1 END) AS M12')
                         ->selectRaw('COUNT(*) AS TOTALGENERAL')
                         ->get();

        $regbitacora=regBitacoraModel::join('COMB_CAT_PROCESOS' ,'COMB_CAT_PROCESOS.PROCESO_ID' ,'=',
                                            'COMB_BITACORA.PROCESO_ID')
                                     ->join('COMB_CAT_FUNCIONES','COMB_CAT_FUNCIONES.FUNCION_ID','=',
                                            'COMB_BITACORA.FUNCION_ID')
                                     ->join('COMB_CAT_TRX'  ,'COMB_CAT_TRX.TRX_ID'   ,'=','COMB_BITACORA.TRX_ID')
                    ->select('COMB_BITACORA.PERIODO_ID', 'COMB_BITACORA.PROGRAMA_ID', 'COMB_BITACORA.PROCESO_ID', 
                             'COMB_CAT_PROCESOS.PROCESO_DESC', 'COMB_BITACORA.FUNCION_ID', 
                             'COMB_CAT_FUNCIONES.FUNCION_DESC', 
                             'COMB_BITACORA.TRX_ID', 'COMB_CAT_TRX.TRX_DESC')
                    ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 1 THEN 1 END) AS ENE')  
                    ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 2 THEN 1 END) AS FEB')
                    ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 3 THEN 1 END) AS MAR')
                    ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 4 THEN 1 END) AS ABR')
                    ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 5 THEN 1 END) AS MAY')
                    ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 6 THEN 1 END) AS JUN')
                    ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 7 THEN 1 END) AS JUL')
                    ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 8 THEN 1 END) AS AGO')
                    ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID = 9 THEN 1 END) AS SEP')
                    ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID =10 THEN 1 END) AS OCT')
                    ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID =11 THEN 1 END) AS NOV')
                    ->selectRaw('SUM(CASE WHEN COMB_BITACORA.MES_ID =12 THEN 1 END) AS DIC')                   
                    ->selectRaw('COUNT(*) AS SUMATOTAL')
                    ->groupBy('COMB_BITACORA.PERIODO_ID','COMB_BITACORA.PROGRAMA_ID','COMB_BITACORA.PROCESO_ID', 
                              'COMB_CAT_PROCESOS.PROCESO_DESC', 'COMB_BITACORA.FUNCION_ID',
                              'COMB_CAT_FUNCIONES.FUNCION_DESC','COMB_BITACORA.TRX_ID','COMB_CAT_TRX.TRX_DESC')
                    ->orderBy('COMB_BITACORA.PERIODO_ID','asc')
                    ->orderBy('COMB_BITACORA.PROCESO_ID','asc') 
                    ->orderBy('COMB_BITACORA.FUNCION_ID','asc')
                    ->orderBy('COMB_BITACORA.TRX_ID'    ,'asc')
                    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.gbitacora',compact('regbitatxmes','regbitacora','regbitatot','nombre','usuario','rango'));
    }


}
