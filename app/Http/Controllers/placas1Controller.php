<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\placa1Request;
use App\regPlacaModel;
use App\regBitacoraModel;
use App\regMarcaModel;
use App\regTipogastoModel;
use App\regTipooperacionModel;
use App\dependenciasModel;
// Exportar a excel 
//use App\Exports\ExcelExportPLacas;
//use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
//use PDF;
//use Options;

class placas1Controller extends Controller
{

    public function actionEditarPlaca1($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango'); 

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
        return view('sicinar.placas.editarPlaca1',compact('nombre','usuario','regmarca','regtipogasto','regtipooper','regplaca'));
    }

    public function actionActualizarPlaca1(placa1Request $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
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

            $regplaca = regPlacaModel::where('PLACA_ID',$id)        
            ->update([                
                //'PLACA_PLACA'    => strtoupper($request->placa_placa),
                'PLACA_FOTO1'    => $name1, 
                'IP_M'           => $ip,
                'LOGIN_M'        => $nombre,
                'FECHA_M'        => date('Y/m/d')    //date('d/m/Y')                                
            ]);
            toastr()->success('Archivo digital de las placas actualizado correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);

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
                                                    'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 
                                                    'IP','LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                    'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
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
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                       'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                       'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
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

        return redirect()->route('verPlacas');
    }


}
