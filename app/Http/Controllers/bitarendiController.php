<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\bitarendiRequest;
use App\Http\Requests\bitarendi1Request;
use App\Http\Requests\bitarendi2Request;
use App\Http\Requests\bitaservicioRequest;
use App\regDiasModel;
use App\regMesesModel;
use App\regPfiscalesModel;
use App\regQuincenaModel;
use App\regPlacaModel;
use App\regBitacoraModel;
use App\regMarcaModel;
use App\regBitarendiModel;
use App\regBitaservicioModel;
//use App\regTipogastoModel;
//use App\regTipooperacionModel;

// Exportar a excel 
//use App\Exports\ExcelExportPLacas;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class bitarendiController extends Controller
{


    public function actionVerBitaRendi(){
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

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
        $regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
                        ->get();   
        //$regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
        //                ->get();                                                 
        //$regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
        //                ->get(); 
        $regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE',
                        'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
                        'DEPENDENCIA_ID','PLACA_MODELO','PLACA_MODELO2','PLACA_GASOLINA','PLACA_INVENTARIO',                        
                        'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
                        'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','ASC')
                        ->get();
       $regbitaservi  = regBitaservicioModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA',
                        'PERIODO_ID','MES_ID','QUINCENA_ID','SERVICIO',
                        'SERVICIO_FECHA','SERVICIO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','SP_ID','SP_NOMB',
                        'SERVICIO_DOTACION','SERVICIO_R','SERVICIO_18','SERVICIO_14','SERVICIO_12',
                        'SERVICIO_34','SERVICIO_F','KM_INICIAL','KM_FINAL','SERVICIO_LUGAR',
                        'SERVICIO_HRSALIDA','SERVICIO_HRREGRESO',                          
                        'SERVICIO_OBS','SBITACO_STATUS1','SBITACO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('PERIODO_ID'  ,'ASC')
                        ->orderBy('BITACO_FOLIO','ASC') 
                        ->orderBy('SERVICIO'    ,'ASC') 
                        ->get();                        
        if($role->rol_name == 'user'){                                                
            $totservicios=regBitaservicioModel::join('COMB_BITACORA_RENDCOMB','COMB_BITACORA_RENDCOMB.BITACO_FOLIO', '=', 
                                                                        'COMB_BITACORA_SERVICIOS.BITACO_FOLIO')
                        ->select('COMB_BITACORA_RENDCOMB.PERIODO_ID','COMB_BITACORA_RENDCOMB.BITACO_FOLIO')
                        ->selectRaw('COUNT(*) AS SERVICIOS')
                        ->where('COMB_BITACORA_RENDCOMB.LOGIN', $nombre)
                        ->groupBy('COMB_BITACORA_RENDCOMB.PERIODO_ID','COMB_BITACORA_RENDCOMB.BITACO_FOLIO')
                        ->get();            
            $regbitarendi=regBitarendiModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA',
                          'PERIODO_ID','MES_ID','QUINCENA_ID',
                          'BITACO_FECHA','BITACO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                          'SP_ID1','SP_NOMB1','SP_ID2','SP_NOMB2',
                          'BITACO_FOTO1','BITACO_FOTO2','BITACO_FOTO3','BITACO_FOTO4','BITACO_FOTO5',
                          'BITACO_OBS1','BITACO_OBS2','BITACO_STATUS1','BITACO_STATUS2',
                          'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                          ->where('LOGIN',$nombre)
                          ->orderBy('BITACO_FOLIO','ASC')                        
                          ->paginate(30);
        }else{
            $totservicios=regBitaservicioModel::join('COMB_BITACORA_RENDCOMB','COMB_BITACORA_RENDCOMB.BITACO_FOLIO', '=', 
                                                                        'COMB_BITACORA_SERVICIOS.BITACO_FOLIO')
                        ->select('COMB_BITACORA_RENDCOMB.PERIODO_ID','COMB_BITACORA_RENDCOMB.BITACO_FOLIO')
                        ->selectRaw('COUNT(*) AS SERVICIOS')
                        ->groupBy('COMB_BITACORA_RENDCOMB.PERIODO_ID','COMB_BITACORA_RENDCOMB.BITACO_FOLIO')
                        ->get();                  
            $regbitarendi=regBitarendiModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA',
                          'PERIODO_ID','MES_ID','QUINCENA_ID',
                          'BITACO_FECHA','BITACO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                          'SP_ID1','SP_NOMB1','SP_ID2','SP_NOMB2',
                          'BITACO_FOTO1','BITACO_FOTO2','BITACO_FOTO3','BITACO_FOTO4','BITACO_FOTO5',
                          'BITACO_OBS1','BITACO_OBS2','BITACO_STATUS1','BITACO_STATUS2',
                          'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                          ->orderBy('BITACO_FOLIO','ASC')                        
                          ->paginate(30);            
        }                  
        if($regbitarendi->count() <= 0){
            toastr()->error('No existen registros de bitacora de rendimiento de combustible.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.bitacorarendi.verBitarendi',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regmarca','regbitarendi','totservicios','regbitaservi'));
    }

    public function actionNuevaBitarendi(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
        //$regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
        //                ->get();   
        //$regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
        //                ->get();                                                 
        //$regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
        //                ->get(); 
        $regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE',
                        'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
                        'DEPENDENCIA_ID','PLACA_MODELO','PLACA_MODELO2','PLACA_GASOLINA','PLACA_INVENTARIO',                        
                        'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
                        'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','asc')
                        ->get();
        $regbitarendi = regBitarendiModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA','PERIODO_ID','MES_ID',
                        'QUINCENA_ID','BITACO_FECHA','BITACO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'SP_ID1','SP_NOMB1','SP_ID2','SP_NOMB2',
                        'BITACO_FOTO1','BITACO_FOTO2','BITACO_FOTO3','BITACO_FOTO4','BITACO_FOTO5',
                        'BITACO_OBS1','BITACO_OBS2','BITACO_STATUS1','BITACO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('BITACO_FOLIO','ASC')            
                        ->get();                        
        //dd($unidades);
        return view('sicinar.bitacorarendi.nuevaBitarendi',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regbitarendi'));
    }

    public function actionAltaNuevaBitarendi(Request $request){
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

        /************ ALTA  *****************************/ 
        //*********** Se obtiene la placa y el resguardatario   *****/
        $placa_placa = regPlacaModel::ObtPlaca($request->placa_id);
        $sp_nomb2    = regPlacaModel::ObtResguardatario($request->placa_id);
        //$tipoo_id    = regPlacaModel::ObtTipoOperacion($request->placa_id);

        $mes1 = regMesesModel::ObtMes($request->mes_id1);
        $dia1 = regDiasModel::ObtDia($request->dia_id1);
        //$mes2 = regMesesModel::ObtMes($request->mes_id2);
        //$dia2 = regDiasModel::ObtDia($request->dia_id2);

        $bitaco_folio = regBitarendiModel::max('BITACO_FOLIO');
        $bitaco_folio = $bitaco_folio+1;

        $nuevabitaco  = new regBitarendiModel();
        $name1 =null;
        //Comprobar  si el campo foto1 tiene un archivo asignado:
        if($request->hasFile('bitaco_foto1')){
           $name1 = $bitaco_folio.'_'.$request->file('bitaco_foto1')->getClientOriginalName(); 
           //$file->move(public_path().'/images/', $name1);
           //sube el archivo a la carpeta del servidor public/images/
           $request->file('bitaco_foto1')->move(public_path().'/images/', $name1);
        }
        $name2 =null;
        //Comprobar  si el campo foto2 tiene un archivo asignado:        
        if($request->hasFile('bitaco_foto2')){
           $name2 = $bitaco_folio.'_'.$request->file('bitaco_foto2')->getClientOriginalName(); 
           //sube el archivo a la carpeta del servidor public/images/
           $request->file('bitaco_foto2')->move(public_path().'/images/', $name2);
        }

        $nuevabitaco->BITACO_FOLIO  = $bitaco_folio;
        $nuevabitaco->PLACA_ID      = $request->placa_id;
        $nuevabitaco->PLACA_PLACA   = strtoupper($placa_placa[0]->placa_placa);
        //$nuevabitaco->PERIODO_ID    = $request->periodo_id;
        //$nuevabitaco->MES_ID        = $request->mes_id;
        $nuevabitaco->PERIODO_ID    = $request->periodo_id1;
        $nuevabitaco->MES_ID        = $request->mes_id1;        
        $nuevabitaco->QUINCENA_ID   = $request->quincena_id;
        $nuevabitaco->BITACO_FECHA  = date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) ));
        $nuevabitaco->BITACO_FECHA2 = trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);
        $nuevabitaco->PERIODO_ID1   = $request->periodo_id1;
        $nuevabitaco->MES_ID1       = $request->mes_id1;
        $nuevabitaco->DIA_ID1       = $request->dia_id1;

        $nuevabitaco->SP_ID1        = $request->sp_id1;
        $nuevabitaco->SP_NOMB1      = substr(trim(strtoupper($sp_nomb2[0]->placa_obs2)),0,79);
        $nuevabitaco->SP_ID2        = $request->sp_id2;
        $nuevabitaco->SP_NOMB2      = substr(trim(strtoupper($request->sp_nomb2)),0,79);
        $nuevabitaco->BITACO_OBS1   = substr(trim(strtoupper($request->bitaco_obs1)),0,3999);
        
        $nuevabitaco->BITACO_FOTO1  = $name1;
        $nuevabitaco->BITACO_FOTO2  = $name2;

        $nuevabitaco->IP            = $ip;
        $nuevabitaco->LOGIN         = $nombre;         // Usuario ;
        $nuevabitaco->save();

        if($nuevabitaco->save() == true){
            
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       160;    //Alta 
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                           'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 
                           'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                    'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                    'TRX_ID' => $xtrx_id, 'FOLIO' => $bitaco_folio])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $bitaco_folio;   // Folio    
                $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                $nuevoregBitacora->IP         = $ip;             // IP
                $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

                $nuevoregBitacora->save();
                if($nuevoregBitacora->save() == true)
                    toastr()->success('Trx de recibo de bitacora registrada en la bitacora.','OK!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error en bitacora al registrar trx de recibo de bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                             'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $bitaco_folio])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         

                $regbitacora=regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                             ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                      'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,
                                      'TRX_ID' => $xtrx_id,'FOLIO' => $bitaco_folio])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de bitacora de rendimiento actualizada en bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }
            /************ Bitacora termina *************************************/ 

        }else{
            toastr()->error('Error en alta de trx de bitacora de rendimiento de combustible. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //return back();
            //return redirect()->route('nuevoProceso');
        }

        return redirect()->route('verBitarendi');
    }


    public function actionEditarBitarendi($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
        $regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
                        ->get();   
        //$regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
        //                ->get();                                                 
        //$regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
        //                ->get(); 
        $regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE','PLACA_ANTERIOR',
                        'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
                        'DEPENDENCIA_ID','PLACA_MODELO','PLACA_MODELO2','PLACA_GASOLINA','PLACA_INVENTARIO',                        
                        'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
                        'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','asc')
                        ->get();
        $regbitarendi = regBitarendiModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA','PERIODO_ID','MES_ID',
                        'QUINCENA_ID','BITACO_FECHA','BITACO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'SP_ID1','SP_NOMB1','SP_ID2','SP_NOMB2',
                        'BITACO_FOTO1','BITACO_FOTO2','BITACO_FOTO3','BITACO_FOTO4','BITACO_FOTO5',
                        'BITACO_OBS1','BITACO_OBS2','BITACO_STATUS1','BITACO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('BITACO_FOLIO'  ,$id)
                        ->orderBy('PERIODO_ID'  ,'ASC')      
                        ->orderBy('BITACO_FOLIO','ASC')
                        ->first();
        if($regbitarendi->count() <= 0){
            toastr()->error('No existe registro de bitacora.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.bitacorarendi.editarBitarendi',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regmarca','regbitarendi'));
    }

    public function actionActualizarBitarendi(BitarendiRequest $request, $id){
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
        $regbitarendi = regBitarendiModel::where('BITACO_FOLIO',$id);
        if($regbitarendi->count() <= 0)
            toastr()->error('No existe folio en bitacora de rendimiento.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $name1 =null;
            //   if(!empty($_PUT['recibo_rfoto1'])){
            if(isset($request->bitaco_foto1)){
                if(!empty($request->bitaco_foto1)){
                    //Comprobar  si el campo foto1 tiene un archivo asignado:
                    if($request->hasFile('bitaco_foto1')){
                      $name1 = $id.'_'.$request->file('bitaco_foto1')->getClientOriginalName(); 
                      //sube el archivo a la carpeta del servidor public/images/
                      $request->file('bitaco_foto1')->move(public_path().'/images/', $name1);
                    }
                }
            }

            //*********** Se obtiene la placa y el resguardatario   *****/
            $placa_placa = regPlacaModel::ObtPlaca($request->placa_id);
            $sp_pub1     = regPlacaModel::ObtResguardatario($request->placa_id);
            //$tipoo_id    = regPlacaModel::ObtTipoOperacion($request->placa_id);

            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);

            $regbitarendi = regBitarendiModel::where('BITACO_FOLIO',$id)        
                            ->update([                
                //'PERIODO_ID'     => $request->periodo_id,                
                //'MES_ID'         => $request->mes_id,
                'PERIODO_ID'       => $request->periodo_id1,                
                'MES_ID'           => $request->mes_id1,                
                'QUINCENA_ID'      => $request->quincena_id,                
                'BITACO_FECHA'     => date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) )),
                'BITACO_FECHA2'    => trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1),
                'PERIODO_ID1'      => $request->periodo_id1,                
                'MES_ID1'          => $request->mes_id1,
                'DIA_ID1'          => $request->dia_id1,

                'SP_NOMB2'         => substr(trim(strtoupper($request->sp_nomb2)),0,79),  
                'BITACO_OBS1'      => substr(trim(strtoupper($request->bitaco_obs1)),0,3999),
                //'RECIBO_OBS2'   => strtoupper($recibo_obs2[0]->placa_obs2),
                //'BITACO_STATUS1'=> $request->bitaco_status1, 
                //'PLACA_FOTO1'   => $name1, 

                'IP_M'            => $ip,
                'LOGIN_M'         => $nombre,
                'FECHA_M'         => date('Y/m/d')    //date('d/m/Y')                                
            ]);
            toastr()->success('Bitacora de rendimiento actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       161;    //Actualizar        
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                           'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 
                           'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id])
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
                    toastr()->success('Trx de bitacora de rendimiento dada de alta en bitcora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de trx de bitacora de rendimiento en alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP           = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M   = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                toastr()->success('Trx de bitacora de rendimiento actualizada en bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }
            /************ Bitacora termina *************************************/         
        }

        return redirect()->route('verBitarendi');
    }


    public function actionEditarBitarendi1($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
        //$regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
        //                ->get();   
        //$regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
        //                ->get();                                                 
        //$regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
        //                ->get(); 
        $regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE','PLACA_ANTERIOR',
                        'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
                        'DEPENDENCIA_ID','PLACA_MODELO','PLACA_MODELO2','PLACA_GASOLINA','PLACA_INVENTARIO',                        
                        'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
                        'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','asc')
                        ->get();
        $regbitarendi = regBitarendiModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA','PERIODO_ID','MES_ID',
                        'QUINCENA_ID','BITACO_FECHA','BITACO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'SP_ID1','SP_NOMB1','SP_ID2','SP_NOMB2',
                        'BITACO_FOTO1','BITACO_FOTO2','BITACO_FOTO3','BITACO_FOTO4','BITACO_FOTO5',
                        'BITACO_OBS1','BITACO_OBS2','BITACO_STATUS1','BITACO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('BITACO_FOLIO'  ,$id)
                        ->orderBy('PERIODO_ID'  ,'ASC')      
                        ->orderBy('BITACO_FOLIO','ASC')
                        ->first();
        if($regbitarendi->count() <= 0){
            toastr()->error('No existe registro de bitacora.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.bitacorarendi.editarBitarendi1',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regbitarendi'));
    }

    public function actionActualizarBitarendi1(Bitarendi1Request $request, $id){
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
        $regbitarendi = regBitarendiModel::where('BITACO_FOLIO',$id);
        if($regbitarendi->count() <= 0)
            toastr()->error('No existe bitacora.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $name1 =null;
            //   if(!empty($_PUT['recibo_rfoto1'])){
            if(isset($request->bitaco_foto1)){
                if(!empty($request->bitaco_foto1)){
                    //Comprobar  si el campo foto1 tiene un archivo asignado:
                    if($request->hasFile('bitaco_foto1')){
                      $name1 = $id.'_'.$request->file('bitaco_foto1')->getClientOriginalName(); 
                      //sube el archivo a la carpeta del servidor public/images/
                      $request->file('bitaco_foto1')->move(public_path().'/images/', $name1);

                      //*********** Se obtiene la placa y el resguardatario   *****/
                      //$placa_placa = regPlacaModel::ObtPlaca($request->placa_id);
                      //$sp_pub1     = regPlacaModel::ObtResguardatario($request->placa_id);

                      //$mes1 = regMesesModel::ObtMes($request->mes_id1);
                      //$dia1 = regDiasModel::ObtDia($request->dia_id1);

                      $regbitarendi = regBitarendiModel::where('BITACO_FOLIO',$id)        
                                      ->update([                
                        //'PERIODO_ID'     => $request->periodo_id,                
                        //'MES_ID'         => $request->mes_id,
                        //'QUINCENA_ID'    => $request->quincena_id,                
                        //'BITACO_FECHA'   => date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) )),
                        //'BITACO_FECHA2'  => trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1),
                        //'PERIODO_ID1'    => $request->periodo_id1,                
                        //'MES_ID1'        => $request->mes_id1,
                        //'DIA_ID1'        => $request->dia_id1,

                        //'SP_NOMB2'       => substr(trim(strtoupper($request->sp_nob2)),0,79),  
                        //'BITACO_OBS1'    => substr(trim(strtoupper($request->bitaco_obs1)),0,499),
                        //'RECIBO_OBS2'  => strtoupper($recibo_obs2[0]->placa_obs2),
                        //'BITACO_STATUS1' => $request->bitaco_status1, 
                        'BITACO_FOTO1'   => $name1, 

                        'IP_M'           => $ip,
                        'LOGIN_M'        => $nombre,
                        'FECHA_M'        => date('Y/m/d')    //date('d/m/Y')                                
                                            ]);
                        toastr()->success('Bitacora PDF 1 actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    }
                }
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       161;    //Actualizar        
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                           'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 
                           'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id])
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
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP           = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M   = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }
            /************ Bitacora termina *************************************/         
        }

        return redirect()->route('verBitarendi');
    }

    public function actionEditarBitarendi2($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
        //$regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
        //                ->get();   
        //$regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
        //                ->get();                                                 
        //$regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
        //                ->get(); 
        $regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE','PLACA_ANTERIOR',
                        'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
                        'DEPENDENCIA_ID','PLACA_MODELO','PLACA_MODELO2','PLACA_GASOLINA','PLACA_INVENTARIO',                        
                        'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
                        'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','asc')
                        ->get();
        $regbitarendi = regBitarendiModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA','PERIODO_ID','MES_ID',
                        'QUINCENA_ID','BITACO_FECHA','BITACO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'SP_ID1','SP_NOMB1','SP_ID2','SP_NOMB2',
                        'BITACO_FOTO1','BITACO_FOTO2','BITACO_FOTO3','BITACO_FOTO4','BITACO_FOTO5',
                        'BITACO_OBS1','BITACO_OBS2','BITACO_STATUS1','BITACO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('BITACO_FOLIO'  ,$id)
                        ->orderBy('PERIODO_ID'  ,'ASC')      
                        ->orderBy('BITACO_FOLIO','ASC')
                        ->first();
        if($regbitarendi->count() <= 0){
            toastr()->error('No existe registro de bitacora.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.bitacorarendi.editarBitarendi2',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regbitarendi'));
    }

    public function actionActualizarBitarendi2(Bitarendi2Request $request, $id){
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
        $regbitarendi = regBitarendiModel::where('BITACO_FOLIO',$id);
        if($regbitarendi->count() <= 0)
            toastr()->error('No existe bitacora.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $name2 =null;
            //   if(!empty($_PUT['recibo_rfoto1'])){
            if(isset($request->bitaco_foto2)){
                if(!empty($request->bitaco_foto2)){
                    //Comprobar  si el campo foto1 tiene un archivo asignado:
                    if($request->hasFile('bitaco_foto2')){
                      $name2 = $id.'_'.$request->file('bitaco_foto2')->getClientOriginalName(); 
                      //sube el archivo a la carpeta del servidor public/images/
                      $request->file('bitaco_foto2')->move(public_path().'/images/', $name2);

                      //*********** Se obtiene la placa y el resguardatario   *****/
                      //$placa_placa = regPlacaModel::ObtPlaca($request->placa_id);
                      //$sp_pub1     = regPlacaModel::ObtResguardatario($request->placa_id);

                      //$mes1 = regMesesModel::ObtMes($request->mes_id1);
                      //$dia1 = regDiasModel::ObtDia($request->dia_id1);

                      $regbitarendi = regBitarendiModel::where('BITACO_FOLIO',$id)        
                                      ->update([                
                        //'PERIODO_ID'     => $request->periodo_id,                
                        //'MES_ID'         => $request->mes_id,
                        //'QUINCENA_ID'    => $request->quincena_id,                
                        //'BITACO_FECHA'   => date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) )),
                        //'BITACO_FECHA2'  => trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1),
                        //'PERIODO_ID1'    => $request->periodo_id1,                
                        //'MES_ID1'        => $request->mes_id1,
                        //'DIA_ID1'        => $request->dia_id1,

                        //'SP_NOMB2'       => substr(trim(strtoupper($request->sp_nob2)),0,79),  
                        //'BITACO_OBS1'    => substr(trim(strtoupper($request->bitaco_obs1)),0,499),
                        //'RECIBO_OBS2'  => strtoupper($recibo_obs2[0]->placa_obs2),
                        //'BITACO_STATUS1' => $request->bitaco_status1, 
                        'BITACO_FOTO2'   => $name2, 

                        'IP_M'           => $ip,
                        'LOGIN_M'        => $nombre,
                        'FECHA_M'        => date('Y/m/d')    //date('d/m/Y')                                
                                            ]);
                        toastr()->success('Bitacora PDF 2 actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    }
                }
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       161;    //Actualizar        
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                           'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 
                           'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id])
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
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP           = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M   = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }
            /************ Bitacora termina *************************************/         
        }

        return redirect()->route('verBitarendi');
    }

    public function actionBorrarBitarendi($id){
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

        /******************** Eliminar ***************************/
        $regbitarendi = regBitarendiModel::where('BITACO_FOLIO',$id);
        //              ->find('RUBRO_ID',$id);
        if($regbitarendi->count() <= 0)
            toastr()->error('No existe bitacora.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regbitarendi->delete();
            toastr()->success('BBitacora de rendimiento eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Eliminar registro **************************************/
            $regbitaservi = regBitaservicioModel::where('BITACO_FOLIO', $id);
            if($regbitaservi->count() <= 0)
                toastr()->error('No existen servicios en bitacora de rendimiento.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
            else{        
                $regbitaservi->delete();
                toastr()->success('Servicios eliminados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       162;     // Baja 
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id])
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
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                            ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                        'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M' => $regbitacora->IP           = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/     
        }       /************* Termina de eliminar  **********************************/

        return redirect()->route('verBitarendi');
    }    

    //************************************************************************//
    //******* SERVICIOS DE LA BITACORA DE RENDIMIENTO DE COMBUSTIBLE *********//
    //************************************************************************//
    public function actionVerServicios($id, $id2){
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

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
        //$regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
        //                ->get();   
        //$regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
        //                ->get();                                                 
        //$regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
        //                ->get(); 
        //$regbancos    = regBancosModel::select('BANCO_ID','BANCO_DESC')->orderBy('BANCO_ID','asc')
        //                ->get(); 
        //$regfpagos    = regFpagosModel::select('FP_ID','FP_DESC')->orderBy('FP_ID','asc')
        //                ->get();                               
        $regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE',
                        'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
                        'DEPENDENCIA_ID','PLACA_MODELO','PLACA_MODELO2','PLACA_GASOLINA','PLACA_INVENTARIO',                        
                        'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
                        'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','ASC')
                        ->get();
        $regbitarendi = regBitarendiModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA','PERIODO_ID','MES_ID',
                        'QUINCENA_ID','BITACO_FECHA','BITACO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'SP_ID1','SP_NOMB1','SP_ID2','SP_NOMB2',
                        'BITACO_FOTO1','BITACO_FOTO2','BITACO_FOTO3','BITACO_FOTO4','BITACO_FOTO5',
                        'BITACO_OBS1','BITACO_OBS2','BITACO_STATUS1','BITACO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id,'BITACO_FOLIO' => $id2])                       
                        ->get();
        //dd($unidades);
        if($role->rol_name == 'user'){                        
            //dd($role->rol_name,$nombre,'ya entre al rol de user');
            $regbitaservi=regBitaservicioModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA',
                          'PERIODO_ID','MES_ID','QUINCENA_ID','SERVICIO',
                          'SERVICIO_FECHA','SERVICIO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','SP_ID','SP_NOMB',
                          'SERVICIO_DOTACION','SERVICIO_R','SERVICIO_18','SERVICIO_14','SERVICIO_12',
                          'SERVICIO_34','SERVICIO_F','KM_INICIAL','KM_FINAL','SERVICIO_LUGAR',
                          'SERVICIO_HRSALIDA','SERVICIO_HRREGRESO',                          
                          'SERVICIO_OBS','SBITACO_STATUS1','SBITACO_STATUS2',
                          'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                          ->where(['PERIODO_ID' => $id,'BITACO_FOLIO' => $id2, 'LOGIN' => $nombre])
                          ->orderBy('PERIODO_ID'  ,'ASC')
                          ->orderBy('BITACO_FOLIO','ASC')                        
                          ->orderBy('SERVICIO','ASC') 
                          ->paginate(30);
        }else{
            // dd($role->rol_name,'Rol distinto de user');
            $regbitaservi=regBitaservicioModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA',
                          'PERIODO_ID','MES_ID','QUINCENA_ID','SERVICIO',
                          'SERVICIO_FECHA','SERVICIO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','SP_ID','SP_NOMB',
                          'SERVICIO_DOTACION','SERVICIO_R','SERVICIO_18','SERVICIO_14','SERVICIO_12',
                          'SERVICIO_34','SERVICIO_F','KM_INICIAL','KM_FINAL','SERVICIO_LUGAR',
                          'SERVICIO_HRSALIDA','SERVICIO_HRREGRESO',                          
                          'SERVICIO_OBS','SBITACO_STATUS1','SBITACO_STATUS2',
                          'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                          ->where( ['PERIODO_ID' => $id,'BITACO_FOLIO' => $id2])
                          ->orderBy('PERIODO_ID'  ,'ASC')
                          ->orderBy('BITACO_FOLIO','ASC') 
                          ->orderBy('SERVICIO'    ,'ASC') 
                          ->paginate(30);            
        }
        //dd($regbitaservi,'id:'.$id,' id2:'.$id2);
        if($regbitaservi->count() <= 0){
            toastr()->error('No existen servicios en bitacora de rendimiento seleccionado.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.bitacorarendi.verServicios',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regbitarendi','regbitaservi'));
    }
  
    public function actionNuevoServicio($id){
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

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
        //$regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
        //                ->get();   
        //$regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
        //                ->get();                                                 
        //$regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
        //                ->get(); 
        $regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE',
                        'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
                        'DEPENDENCIA_ID','PLACA_MODELO','PLACA_MODELO2','PLACA_GASOLINA','PLACA_INVENTARIO',                        
                        'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
                        'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','asc')
                        ->get();
        $regbitarendi = regBitarendiModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA','PERIODO_ID','MES_ID',
                        'QUINCENA_ID','BITACO_FECHA','BITACO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'SP_ID1','SP_NOMB1','SP_ID2','SP_NOMB2',
                        'BITACO_FOTO1','BITACO_FOTO2','BITACO_FOTO3','BITACO_FOTO4','BITACO_FOTO5',
                        'BITACO_OBS1','BITACO_OBS2','BITACO_STATUS1','BITACO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('BITACO_FOLIO'  , $id)
                        ->get();                        
        if($role->rol_name == 'user'){                        
            $regbitaservi=regBitaservicioModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA',
                          'PERIODO_ID','MES_ID','QUINCENA_ID','SERVICIO',
                          'SERVICIO_FECHA','SERVICIO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','SP_ID','SP_NOMB',
                          'SERVICIO_DOTACION','SERVICIO_R','SERVICIO_18','SERVICIO_14','SERVICIO_12',
                          'SERVICIO_34','SERVICIO_F','KM_INICIAL','KM_FINAL','SERVICIO_LUGAR',
                          'SERVICIO_HRSALIDA','SERVICIO_HRREGRESO',                          
                          'SERVICIO_OBS','SBITACO_STATUS1','SBITACO_STATUS2',
                          'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                          ->where(['BITACO_FOLIO' => $id, 'LOGIN' => $nombre])
                          ->orderBy('PERIODO_ID'  ,'ASC')
                          ->orderBy('BITACO_FOLIO','ASC')                        
                          ->get();
        }else{
            // dd($role->rol_name,'Rol distinto de user');
            $regbitaservi=regBitaservicioModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA',
                          'PERIODO_ID','MES_ID','QUINCENA_ID','SERVICIO',
                          'SERVICIO_FECHA','SERVICIO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','SP_ID','SP_NOMB',
                          'SERVICIO_DOTACION','SERVICIO_R','SERVICIO_18','SERVICIO_14','SERVICIO_12',
                          'SERVICIO_34','SERVICIO_F','KM_INICIAL','KM_FINAL','SERVICIO_LUGAR',
                          'SERVICIO_HRSALIDA','SERVICIO_HRREGRESO',                          
                          'SERVICIO_OBS','SBITACO_STATUS1','SBITACO_STATUS2',
                          'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                          ->where(  'BITACO_FOLIO',$id)
                          ->orderBy('PERIODO_ID'  ,'ASC')
                          ->orderBy('BITACO_FOLIO','ASC') 
                          ->get();            
        }
        //dd($unidades);
        return view('sicinar.bitacorarendi.nuevoServicio',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regbitarendi','regbitaservi'));
    }

    public function actionAltaNuevoServicio(Request $request){
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

        // *************** Validar duplicidad ***********************************/
        //$duplicado = regBitaServicioModel::where(['PERIODO_ID' => $request->periodo_id, 
        //                                          'TKPAG_FOLAPROB' => $request->tkpag_folaprob])
        //             ->get();
        //if($duplicado->count() >= 1)
        //    return back()->withInput()->withErrors(['TKPAG_FOLAPROB' => ' Periodo fiscal-Folio de aprobación '.$request->tkpag_folaprob.' Ya existe existe. Por favor verificar captura.']);
        //else{  
            /************ ALTA  *****************************/ 
            //*********** Se obtiene la placa y el resguardatario   *****/
            $placa_placa = regPlacaModel::ObtPlaca($request->placa_id);
            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);
            //************** Obtener partida del servicio en bitacora *******************//
            $servicio = regBitaservicioModel::where(['PERIODO_ID'   => $request->periodo_id,
                                                     'BITACO_FOLIO' => $request->bitaco_folio])
                        ->max('SERVICIO');
            $servicio = $servicio + 1;
            $nuevoservicio= new regBitaservicioModel();
            $nuevoservicio->BITACO_FOLIO     = $request->bitaco_folio;
            $nuevoservicio->PLACA_ID         = $request->placa_id;
            $nuevoservicio->PLACA_PLACA      = strtoupper($placa_placa[0]->placa_placa);
            $nuevoservicio->PERIODO_ID       = $request->periodo_id;
            $nuevoservicio->MES_ID           = $request->mes_id;
            $nuevoservicio->QUINCENA_ID      = $request->quincena_id;
            $nuevoservicio->SERVICIO         = $servicio;

            $nuevoservicio->SERVICIO_FECHA   = date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) ));
            $nuevoservicio->SERVICIO_FECHA2  = trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);
            $nuevoservicio->PERIODO_ID1      = $request->periodo_id1;
            $nuevoservicio->MES_ID1          = $request->mes_id1;
            $nuevoservicio->DIA_ID1          = $request->dia_id1;
            $nuevoservicio->SP_ID            = $request->sp_id;
            $nuevoservicio->SP_NOMB          = substr(trim(strtoupper($request->sp_nomb)),0,79);
            $nuevoservicio->SERVICIO_DOTACION= $request->servicio_dotacion;
            $nuevoservicio->SERVICIO_R       = $request->servicio_r;
            $nuevoservicio->SERVICIO_18      = $request->servicio_18;
            $nuevoservicio->SERVICIO_14      = $request->servicio_14;
            $nuevoservicio->SERVICIO_12      = $request->servicio_12;
            $nuevoservicio->SERVICIO_34      = $request->servicio_34;
            $nuevoservicio->SERVICIO_F       = $request->servicio_f;

            $nuevoservicio->KM_INICIAL        = $request->km_inicial;
            $nuevoservicio->KM_FINAL          = $request->km_final;
            $nuevoservicio->SERVICIO_LUGAR    = substr(trim(strtoupper($request->servicio_lugar))    ,0,249);
            $nuevoservicio->SERVICIO_HRSALIDA = substr(trim(strtoupper($request->servicio_hrsalida)) ,0,  6);    
            $nuevoservicio->SERVICIO_HRREGRESO= substr(trim(strtoupper($request->servicio_hrregreso)),0,  6);                                     
            $nuevoservicio->SERVICIO_OBS      = substr(trim(strtoupper($request->servicio_obs))      ,0,499);
        
            $nuevoservicio->IP               = $ip;
            $nuevoservicio->LOGIN            = $nombre;         // Usuario ;
            $nuevoservicio->save();
            if($nuevoservicio->save() == true){
                toastr()->success('Servicio de bitacora de rendimiento registrado.','OK!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3002;
                $xtrx_id      =       165;    //Alta 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                           'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 
                           'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                    'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                    'TRX_ID' => $xtrx_id, 'FOLIO' => $request->bitaco_folio])
                           ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $request->bitaco_folio;   // Folio    
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
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $request->bitaco_folio])
                             ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora=regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                             ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                      'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,
                                      'TRX_ID' => $xtrx_id,'FOLIO' => $request->bitaco_folio])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }
                /************ Bitacora termina *************************************/ 

            }else{
                toastr()->error('Error al dar de alta el servicio en la bitacora de rendimiento de combustible. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                //return back();
                //return redirect()->route('nuevoProceso');
            }   //********** Termina de dar de alta ********************************//
        //}   //*********** Termina de validar duplicidad ******************//
        return redirect()->route('verServicios',array($request->periodo_id, $request->bitaco_folio));
    }

    public function actionEditarServicio($id, $id1, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
        //$regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
        //                ->get();   
        //$regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
        //                ->get();                                                 
        //$regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
        //                ->get(); 
        $regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE','PLACA_ANTERIOR',
                        'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
                        'DEPENDENCIA_ID','PLACA_MODELO','PLACA_MODELO2','PLACA_GASOLINA','PLACA_INVENTARIO',                        
                        'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
                        'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','asc')
                        ->get();
        if($role->rol_name == 'user'){                                                
            $regbitarendi=regBitarendiModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA','PERIODO_ID','MES_ID',
                        'QUINCENA_ID','BITACO_FECHA','BITACO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'SP_ID1','SP_NOMB1','SP_ID2','SP_NOMB2',
                        'BITACO_FOTO1','BITACO_FOTO2','BITACO_FOTO3','BITACO_FOTO4','BITACO_FOTO5',
                        'BITACO_OBS1','BITACO_OBS2','BITACO_STATUS1','BITACO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id,'BITACO_FOLIO' => $id1,'LOGIN' => $nombre])
                        ->get();                         
        }else{
            $regbitarendi=regBitarendiModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA','PERIODO_ID','MES_ID',
                        'QUINCENA_ID','BITACO_FECHA','BITACO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'SP_ID1','SP_NOMB1','SP_ID2','SP_NOMB2',
                        'BITACO_FOTO1','BITACO_FOTO2','BITACO_FOTO3','BITACO_FOTO4','BITACO_FOTO5',
                        'BITACO_OBS1','BITACO_OBS2','BITACO_STATUS1','BITACO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id,'BITACO_FOLIO' => $id1])
                        ->get();               
        }                                         
        $regbitaservi = regBitaservicioModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA',
                          'PERIODO_ID','MES_ID','QUINCENA_ID','SERVICIO',
                          'SERVICIO_FECHA','SERVICIO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','SP_ID','SP_NOMB',
                          'SERVICIO_DOTACION','SERVICIO_R','SERVICIO_18','SERVICIO_14','SERVICIO_12',
                          'SERVICIO_34','SERVICIO_F','KM_INICIAL','KM_FINAL','SERVICIO_LUGAR',
                          'SERVICIO_HRSALIDA','SERVICIO_HRREGRESO',                          
                          'SERVICIO_OBS','SBITACO_STATUS1','SBITACO_STATUS2',
                          'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id,'BITACO_FOLIO' => $id1, 'SERVICIO' => $id2])
                        ->first();
        if($regbitaservi->count() <= 0){
            toastr()->error('No existe registro de servicio en bitacora de rendimiento.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.bitacorarendi.editarServicio',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regbitarendi','regbitaservi'));
    }

    public function actionActualizarServicio(BitaservicioRequest $request, $id){
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
        $regbitaservi = regBitaservicioModel::where(['PERIODO_ID'   => $request->periodo_id,
                                                     'BITACO_FOLIO' => $request->bitaco_folio, 
                                                     'SERVICIO'     => $request->partida_id]);
        //dd('Periodo:',$request->periodo_id,'-folio:',$request->bitaco_folio, '-servicio:',$id,'-servicio2:',$request->partida_id);
        if($regbitaservi->count() <= 0)
            toastr()->error('No existe servicio en bitacora de rendimiento.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            // *********** actualizar ************************************/
            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);
            $regbitaservi= regBitaservicioModel::where(['PERIODO_ID'   => $request->periodo_id,
                                                        'BITACO_FOLIO' => $request->bitaco_folio, 
                                                        'SERVICIO'     => $request->partida_id]) 
                           ->update(
                            [                
                'SERVICIO_FECHA'    => date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) )),
                'SERVICIO_FECHA2'   => trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1),
                'PERIODO_ID1'       => $request->periodo_id1,                
                'MES_ID1'           => $request->mes_id1,
                'DIA_ID1'           => $request->dia_id1,                                          
                'SP_NOMB'           => substr(trim(strtoupper($request->sp_nomb)),0,79),  
                'SERVICIO_DOTACION' => $request->servicio_dotacion, 
                'SERVICIO_R'        => $request->servicio_r,                   
                'SERVICIO_18'       => $request->servicio_18, 
                'SERVICIO_14'       => $request->servicio_14, 
                'SERVICIO_12'       => $request->servicio_12, 
                'SERVICIO_34'       => $request->servicio_34, 
                'SERVICIO_F'        => $request->servicio_f,  
                'KM_INICIAL'        => $request->km_inicial, 
                'KM_FINAL'          => $request->km_final, 

                'SERVICIO_LUGAR'    => substr(trim(strtoupper($request->servicio_lugar)),    0,249),
                'SERVICIO_HRSALIDA' => substr(trim(strtoupper($request->servicio_hrsalida)), 0,  6),
                'SERVICIO_HRREGRESO'=> substr(trim(strtoupper($request->servicio_hrregreso)),0,  6),
                'SERVICIO_OBS'      => substr(trim(strtoupper($request->servicio_obs)),      0,499),
                
                'IP_M'              => $ip,
                'LOGIN_M'           => $nombre,
                'FECHA_M'           => date('Y/m/d')    //date('d/m/Y')                                
            ]);
            toastr()->success('Servicio en Bitacora de rendimiento actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       166;    //Actualizar        
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                           'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 
                           'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $request->bitaco_folio])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $request->bitaco_folio; // Folio    
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
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                                      'FOLIO' => $request->bitaco_folio])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,
                                        'FOLIO' => $request->bitaco_folio])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/

        return redirect()->route('verServicios',array($request->periodo_id,$request->bitaco_folio));
    }

    public function actionBorrarServicio($id, $id1, $id2){
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

        /************ Eliminar registro **************************************/
        $regbitaservi = regBitaservicioModel::where(['PERIODO_ID' => $id,'BITACO_FOLIO' => $id1,'SERVICIO' => $id2]);
        if($regbitaservi->count() <= 0)
            toastr()->error('No existe servicio en bitacora de rendimiento.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regbitaservi->delete();
            toastr()->success('Servicio eliminado de la bitacora de rendimiento.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       167;     // Baja 
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id1])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $id1;             // Folio    
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
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id1])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         

                $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                              ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                       'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                       'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id1])
                              ->update([
                                        'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                        'IP_M'    => $regbitacora->IP       = $ip,
                                        'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                        'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                      ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/     
        }       /************* Termina de eliminar  **********************************/
        return redirect()->route('verBitarendi');
    }    

    // exportar a formato PDF
    public function actionExportBitarendiPdf($id,$id2,$id3){
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
        $xfuncion_id  =      3003;
        $xtrx_id      =       164;       //Exportar a formato PDF
        $id           =         0;
        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                       'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                       ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                'FOLIO' => $id3])
                       ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $id3;             // Folio    
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
                         'TRX_ID' => $xtrx_id, 'FOLIO' => $id3])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id, 
                                    'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                    'TRX_ID' => $xtrx_id,'FOLIO' => $id3])
                           ->update([
                                     'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M'    => $regbitacora->IP       = $ip,
                                     'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                     'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/ 

        //********* Validar rol de usuario **********************/
        $regmes      = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                       ->get();   
        $regdia      = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                       ->get();   
        $regquincena = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                       ->get(); 
        //$regmarca    = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
        //               ->get();   
        //$regtipogasto= regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
        //               ->get();                                                 
        //$regtipooper = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
        //               ->get(); 
        //$regfpagos   = regFpagosModel::select('FP_ID','FP_DESC')->orderBy('FP_ID','asc')
        //               ->get();                            
        $regplaca    = regPlacaModel::join('COMB_CAT_TIPOGASTO','COMB_CAT_TIPOGASTO.TIPOG_ID','=',
                                                                'COMB_PLACAS.TIPOG_ID')
                       ->select('COMB_CAT_TIPOGASTO.TIPOG_DESC',
                                'COMB_PLACAS.PLACA_ID'  ,'COMB_PLACAS.PLACA_PLACA',
                                'COMB_PLACAS.PLACA_DESC','COMB_PLACAS.PLACA_SERIE',
                                'COMB_PLACAS.PLACA_CILINDROS',
                                'COMB_PLACAS.MARCA_ID',
                                'COMB_PLACAS.DEPENDENCIA_ID',
                                'COMB_PLACAS.PLACA_MODELO',
                                'COMB_PLACAS.PLACA_MODELO2',
                                'COMB_PLACAS.PLACA_GASOLINA',
                                'COMB_PLACAS.PLACA_INVENTARIO',
                                'COMB_PLACAS.TIPOO_ID',
                                'COMB_PLACAS.TIPOG_ID','COMB_PLACAS.PLACA_OBS1','COMB_PLACAS.PLACA_OBS2')
                       ->orderBy('COMB_PLACAS.PLACA_ID','asc')
                       ->get();   
        if($role->rol_name == 'user'){                
            $regbitarendi=regBitarendiModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA','PERIODO_ID','MES_ID',
                        'QUINCENA_ID','BITACO_FECHA','BITACO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'SP_ID1','SP_NOMB1','SP_ID2','SP_NOMB2',
                        'BITACO_FOTO1','BITACO_FOTO2','BITACO_FOTO3','BITACO_FOTO4','BITACO_FOTO5',
                        'BITACO_OBS1','BITACO_OBS2','BITACO_STATUS1','BITACO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id2,'BITACO_FOLIO' => $id3,'LOGIN' => $nombre])
                        ->get();
        }else{
            $regbitarendi=regBitarendiModel::select('BITACO_FOLIO','PLACA_ID','PLACA_PLACA','PERIODO_ID','MES_ID',
                        'QUINCENA_ID','BITACO_FECHA','BITACO_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'SP_ID1','SP_NOMB1','SP_ID2','SP_NOMB2',
                        'BITACO_FOTO1','BITACO_FOTO2','BITACO_FOTO3','BITACO_FOTO4','BITACO_FOTO5',
                        'BITACO_OBS1','BITACO_OBS2','BITACO_STATUS1','BITACO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id2,'BITACO_FOLIO' => $id3])
                        ->get();       
        }         
        $regbitaservi = regBitaservicioModel::join('COMB_BITACORA_RENDCOMB','COMB_BITACORA_RENDCOMB.BITACO_FOLIO','=',
                                                                            'COMB_BITACORA_SERVICIOS.BITACO_FOLIO')
                        ->select('COMB_BITACORA_RENDCOMB.BITACO_FOLIO',
                          'COMB_BITACORA_RENDCOMB.PLACA_ID'   ,'COMB_BITACORA_RENDCOMB.PLACA_PLACA',
                          'COMB_BITACORA_RENDCOMB.PERIODO_ID' ,'COMB_BITACORA_RENDCOMB.MES_ID',
                          'COMB_BITACORA_RENDCOMB.QUINCENA_ID','COMB_BITACORA_RENDCOMB.BITACO_FECHA2',
                          'COMB_BITACORA_RENDCOMB.SP_NOMB1'   ,'COMB_BITACORA_RENDCOMB.SP_NOMB2',
                          'COMB_BITACORA_RENDCOMB.BITACO_OBS1','COMB_BITACORA_RENDCOMB.BITACO_STATUS1',
                          'COMB_BITACORA_SERVICIOS.SERVICIO'  ,
                          'COMB_BITACORA_SERVICIOS.SERVICIO_FECHA'   ,'COMB_BITACORA_SERVICIOS.SERVICIO_FECHA2',
                          'COMB_BITACORA_SERVICIOS.PERIODO_ID1'      ,'COMB_BITACORA_SERVICIOS.MES_ID1','COMB_BITACORA_SERVICIOS.DIA_ID1',
                          'COMB_BITACORA_SERVICIOS.SP_ID'            ,'COMB_BITACORA_SERVICIOS.SP_NOMB',
                          'COMB_BITACORA_SERVICIOS.SERVICIO_DOTACION',
                          'COMB_BITACORA_SERVICIOS.SERVICIO_R'       ,'COMB_BITACORA_SERVICIOS.SERVICIO_18',
                          'COMB_BITACORA_SERVICIOS.SERVICIO_14'      ,'COMB_BITACORA_SERVICIOS.SERVICIO_12',
                          'COMB_BITACORA_SERVICIOS.SERVICIO_34'      ,'COMB_BITACORA_SERVICIOS.SERVICIO_F',
                          'COMB_BITACORA_SERVICIOS.KM_INICIAL'       ,'COMB_BITACORA_SERVICIOS.KM_FINAL',
                          'COMB_BITACORA_SERVICIOS.SERVICIO_LUGAR'   ,
                          'COMB_BITACORA_SERVICIOS.SERVICIO_HRSALIDA','COMB_BITACORA_SERVICIOS.SERVICIO_HRREGRESO',                          
                          'COMB_BITACORA_SERVICIOS.SERVICIO_OBS'     ,'COMB_BITACORA_SERVICIOS.SBITACO_STATUS1')
                       ->where(['COMB_BITACORA_RENDCOMB.PERIODO_ID' => $id2,'COMB_BITACORA_RENDCOMB.BITACO_FOLIO' => $id3])
                       ->orderBy('COMB_BITACORA_RENDCOMB.PERIODO_ID'  ,'ASC')
                       ->orderBy('COMB_BITACORA_RENDCOMB.BITACO_FOLIO','ASC')
                       ->orderBy('COMB_BITACORA_SERVICIOS.SERVICIO'   ,'ASC')
                       ->get(); 
        //dd('REGISTRO:',$id,' llave2:',$id2,' llave2:',$id3);       
        //dd('REGISTRO:',$regbitaservi);       
        if($regbitaservi->count() <= 0){
            toastr()->error('No existen registros de servicios en bitacora de rendimiento de combustible.','Uppss!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('verTrx');
        }
        //$pdf = PDF::loadView('sicinar.pdf.cattrxPDF', compact('nombre','usuario','regplaca'));
        $pdf = PDF::loadView('sicinar.pdf.BitacorarendimientoPdf', compact('nombre','usuario','regmes','regquincena','regplaca','regbitarendi','regbitaservi'));
        //******** Horizontal ***************
        $pdf->setPaper('A4', 'landscape');      
        //$pdf->set('defaultFont', 'Courier');          
        //$pdf->setPaper('A4','portrait');
        // Output the generated PDF to Browser
        //******** vertical *************** 
        //El tamaño de hoja se especifica en page_size puede ser letter, legal, A4, etc.         
        //$pdf->setPaper('letter','portrait');      
        return $pdf->stream('BitacoraRendimiento');
    }



}
