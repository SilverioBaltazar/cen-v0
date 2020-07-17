<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\cliente1Request;
use App\regclientesModel;
use App\regBitacoraModel;
use App\regMunicipioModel;
use App\regEntidadesModel; 
use App\regSaldosModel;
//use App\regRubrosModel;
//use App\regPeriodosaniosModel;
//use App\regMesesModel;
//use App\regDiasModel;

// Exportar a excel 
use App\Exports\ExportClientesExcel;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class clientesController1 extends Controller
{


    public function actionEditarCliente1($id){
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
        $regestadocta = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                        'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                        'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                        'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                        'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                        ->where('CLIENTE_ID', $id)
                        ->get();                        
        $regcliente   = regClientesModel::select('PERIODO_ID','CLIENTE_ID','CLIENTE_FOLIO','CLIENTE_AP','CLIENTE_AM','CLIENTE_NOMBRES',
                        'CLIENTE_NOMBRECOMPLETO','CLIENTE_CURP','CLIENTE_FECING','CLIENTE_FECING2','CLIENTE_FECNAC','CLIENTE_FECNAC2',
                        'CLIENTE_SEXO','CLIENTE_RFC','CLIENTE_IDOFICIAL','CLIENTE_DOM','CLIENTE_COL','CLIENTE_CP',
                        'CLIENTE_ENTRECALLE','CLIENTE_YCALLE','CLIENTE_OTRAREF','CLIENTE_TEL','CLIENTE_CEL','CLIENTE_EMAIL',
                        'ENTIDADNAC_ID','ENTIDADFED_ID','MUNICIPIO_ID','LOCALIDAD_ID','LOCALIDAD','EDOCIVIL_ID','GRADOESTUDIOS_ID',
                        'CLIENTE_PUESTO','TIPOCLIENTE_ID','CLASECLIENTE_ID','CLIENTE_OBS1','CLIENTE_OBS2','CLIENTE_FOTO1','CLIENTE_FOTO2',
                        'CLIENTE_STATUS1','CLIENTE_STATUS2','CLIENTE_GEOREFLATITUD','CLIENTE_GEOREFLONGITUD',
                        'FECREG','IP','USU','FECHA_M','IP_M','USU_M')
                         ->where(  'CLIENTE_ID' ,$id)
                         ->orderBy('CLIENTE_ID' ,'asc')
                         //->orderBy('IAP_ID','asc')
                         ->first();
        if($regcliente->count() <= 0){
            toastr()->error('No existe cliente.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoPadron');
        }
        return view('sicinar.clientes.editarCliente1',compact('nombre','usuario','regentidades','regmunicipio','regcliente','regestadocta'));

    }

    public function actionActualizarCliente1(cliente1Request $request, $id){
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
        $regcliente   = regClientesModel::where('CLIENTE_ID',$id);
        if($regcliente->count() <= 0)
            toastr()->error('No existe cliente.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //***************** actualizar *****************************/      
            $name02 =null;
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('cliente_foto1')){
                echo "Escribió en el campo de texto 1: " .'-'. $request->cliente_foto1 .'-'. "<br><br>"; 
                $name02 = $id.'_'.$request->file('cliente_foto1')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('cliente_foto1')->move(public_path().'/images/', $name02);


                //*************** Actualizar ********************************/
                $regcliente = regClientesModel::where('CLIENTE_ID',$id)        
                              ->update([                
                                        'CLIENTE_FOTO1' => $name02,                

                                        'IP_M'          => $ip,
                                        'USU_M'         => $nombre,
                                        'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                         ]);
                toastr()->success('Archivo digital de solicitud del cliente actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Estado de cuenta del cliente *************************************/ 
                $xperiodo_id  = (int)date('Y');              
                $regestadocta = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                            'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                            'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                            'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                            'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                            ->where('CLIENTE_ID', $id)
                            ->get();
                if($regestadocta->count() <= 0){              // Alta
                    $nuevoedocta = new regSaldosModel();              

                    $nuevoedocta->PERIODO_ID    = $xperiodo_id;            
                    $nuevoedocta->CLIENTE_ID    = $id;        

                    $nuevoedocta->IP            = $ip;
                    $nuevoedocta->USU           = $nombre;         // Usuario ;
                    $nuevoedocta->save();

                    if($nuevoedocta->save() == true)
                        toastr()->success('Estado de cuenta del cliente creado.',' dada de alta!',['positionClass' => 'toast-bottom-right']);
                }   /************ Estado de cuenta del cliente termina *************************************/                                 


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
                }   /************ Bitacora termina *********************************************/                     
            }       /************ Termina de validar archivo digital ***************************/
        }           /************ Termina Actualizar *******************************************/

        return redirect()->route('verClientes');

    }


}
