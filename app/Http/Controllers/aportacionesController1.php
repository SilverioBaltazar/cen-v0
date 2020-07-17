<?php
//*****************************************************************/
//* File:       aportacionesController.php
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: diciembre 2020
//* @Derechos reservados. Ing. Silverio Baltazar Barrientos Zarate
//*****************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\aportaciones1Request;
use App\regClientesModel;
use App\regBitacoraModel;
use App\regEmpleadosModel;
use App\regMesesModel;
use App\regPeriodosModel;
use App\regDiarioModel;
use App\regSaldosModel;
use App\regAportacionesModel;

class aportacionesController1 extends Controller
{

    /****************** Editar registro de aportación monetaria **********/
    public function actionEditarApor1($id){
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

        $regempleados= regEmpleadosModel::select('EMP_ID', 'EMP_NOMBRECOMPLETO','EMP_STATUS1')->orderBy('EMP_NOMBRECOMPLETO','asc')
                       ->get();
        $regdiario   = regDiarioModel::select('PERIODO_ID','DIARIO_ID','DIARIO_FOLIO','FACTURA_FOLIO','CLIENTE_ID','EMP_ID','DIARIO_FECHA','DIARIO_FECHA2',
                       'MES_ID','DIA_ID','DIARIO_TIPO','DIARIO_CONCEPTO','DIARIO_IMPORTE','DIARIO_IVA','DIARIO_OTRO','DIARIO_TOTALNETO',
                       'DIARIO_OBS1','DIARIO_OBS2','DIARIO_STATUS1','DIARIO_STATUS2','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->get();
        $regestadocta= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                       'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                       'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                       'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                       'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                       ->get();
        //if($role->rol_name == 'user'){                                
            $regclientes = regClientesModel::select('CLIENTE_ID', 'CLIENTE_NOMBRECOMPLETO','CLIENTE_STATUS1')
                       ->get();                                                        
        //}else{
        //    $regclientes = regClientesModel::select('CLIENTE_ID', 'CLIENTE_NOMBRECOMPLETO','CLIENTE_STATUS1')
        //              ->where('CLIENTE_ID',$arbol_id)
        //              ->get();            
        //}                    
        $regapor    = regAportacionesModel::select('PERIODO_ID','APOR_FOLIO','APOR_RECIBO','FACTURA_FOLIO','CLIENTE_ID','EMP_ID',
                                                   'APOR_FECHA','APOR_FECHA2','MES_ID','DIA_ID','BANCO_ID',
                                                   'APOR_NOCHEQUE','APOR_CONCEPTO','APOR_IMPORTE','APOR_IVA','APOR_OTRO','APOR_TOTALNETO',
                                                   'APOR_OBS1','APOR_OBS2','APOR_FOTO1','APOR_FOTO2','APOR_STATUS1','APOR_STATUS2',
                                                   'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                      ->where('APOR_FOLIO',$id)
                      ->first();
        if($regapor->count() <= 0){
            toastr()->error('No existe registro de aportación monetaria.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaApor');
        }
        return view('sicinar.aportaciones.editarApor1',compact('nombre','usuario','regclientes','regempleados', 'regapor','regdiario','regestadocta'));
    }

    public function actionActualizarApor1(aportaciones1Request $request, $id){
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
        $regapor = regAportacionesModel::where('APOR_FOLIO',$id);
        if($regapor->count() <= 0)
            toastr()->error('No existe folio de la aportacion monetaria.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{  
            //***************** actualizar *****************************/      
            $name02 =null;
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('apor_foto1')){
                echo "Escribió en el campo de texto 1: " .'-'. $request->apor_foto1 .'-'. "<br><br>"; 
                $name02 = $id.'_'.$request->file('apor_foto1')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('apor_foto1')->move(public_path().'/images/', $name02);

                $regapor = regAportacionesModel::where('APOR_FOLIO',$id)        
                           ->update([                
                                      'APOR_FOTO1'  => $name02,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Archivo digital de aportación monetaria actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         5;
                $xfuncion_id  =      5001;
                $xtrx_id      =        54;    //Actualizar aportacion monetaria        
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID','FUNCION_ID', 'TRX_ID', 
                               'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M','IP_M', 'LOGIN_M')
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
                        toastr()->success('Bitacora dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error inesperado en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                          'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces ***************************** 
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                   ->update([
                                            'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M'     => $regbitacora->IP       = $ip,
                                            'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                            'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                            ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/                     
            }       /************ Termina de actualizar ********************************/
        }           //*********** Valida archivo digital *******************************/    
        return redirect()->route('verApor');
        //return view('sicinar.catalogos.verProceso',compact('nombre','usuario','regproceso'));
    }

}
