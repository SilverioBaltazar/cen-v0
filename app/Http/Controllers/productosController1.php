<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\producto1Request;
use App\regProductoModel;
use App\regBitacoraModel;

//use Options;

class productosController1 extends Controller
{


    public function actionEditarProducto1($id){
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

        $producto   = regProductoModel::select('id','codigo_barras', 'descripcion', 'precio_compra', 'precio_venta', 'existencia',
                                               'prod_foto1','prod_status','prod_fecreg')
                      ->where(  'id',$id)
                      ->orderBy('id','ASC')
                      ->first();
        if($producto->count() <= 0){
            toastr()->error('No existe producto.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoProceso');
        }
        return view('sicinar.productos.editarProducto1',compact('nombre','usuario','producto'));

    }

    public function actionActualizarProducto1(producto1Request $request, $id){
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
        $producto     = regProductoModel::where('ID',$id);
        if($producto->count() <= 0)
            toastr()->error('No existe producto.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //***************** actualizar *****************************/      
            $name02 =null;
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('prod_foto1')){
                echo "Escribió en el campo de texto 1: " .'-'. $request->prod_foto1 .'-'. "<br><br>"; 
                $name02 = $id.'_'.$request->file('prod_foto1')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('prod_foto1')->move(public_path().'/images/', $name02);


                //*************** Actualizar ********************************/
                $producto   = regProductoModel::where('ID',$id)        
                              ->update([                
                                        'PROD_FOTO1' => $name02

                                        //'IP_M'          => $ip,
                                        //'USU_M'         => $nombre,
                                        //'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                         ]);
                toastr()->success('Foto del producto actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

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
                        toastr()->error('Error de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces   = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                   ->max('NO_VECES');
                    $xno_veces   = $xno_veces+1;                        
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
        return redirect()->route('verProductos');
    }

}
