<?php
/*

RED DE PRODUCTOS Y SERVICIOS

    Blog:     
    Ayuda:    
    Contacto: 

    Copyright (c) 2020 Ing. Silverio Baltazar Barrientos Zarate
    Licenciado bajo la licencia MIT

    El texto de arriba debe ser incluido en cualquier redistribucion
*/ ?>
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\ventaclienteRequest;
use App\regProductoModel;
use App\regClientesModel;
use App\regSaldosModel;

use App\regEmpleadosModel;
use App\regSaldosempModel;
use App\regTipocreditoModel;

use App\regDiarioModel;

use App\regDfacturaModel;
use App\regEfacturaModel;
use App\regBitacoraModel;

class VenderController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function venderProducto()
    {
        $nombre    = session()->get('userlog');
        $pass      = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario   = session()->get('usuario');
        $role      = session()->get('role');
        $rango     = session()->get('rango');
        $dep       = session()->get('dep');        
        $ip        = session()->get('ip'); 

        $clientes  = regClientesModel::all();
        //             ->get();
        $total     = 0;
        foreach ($this->obtenerProductos() as $producto) {
            $total += $producto->cantidad * $producto->precio_venta;
        }
        return view('sicinar.vender.vender', compact('nombre','usuario','clientes','total'));
        //return view('sicinar.vender.vender',
        //            [
        //              "total"    => $total,
        //              "clientes" => regClientesModel::all(),
        //            ], compact('nombre','usuario'));
    }

    private function obtenerProductos()
    {
        $productos = session("productos");
        if (!$productos) {
            $productos = [];
        }
        return $productos;
    }


    public function terminarOCancelarVenta(Request $request)
    {
        //dd($request->input('cliente_id'));
        if ($request->input("accion") == "terminar") {
            return $this->terminarVenta($request);
        } else {
            return $this->cancelarVenta();
        }
    }

    public function terminarVenta(Request $request)
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

        // Crear una venta
        //$venta = new Venta();
        $xperiodo_id  = (int)date('Y');
        $xmes_id      = (int)date('m');
        $xdia_id      = (int)date('d');

        // Obtener folio de factura de sistema
        $venta_id = regEfacturaModel::max('factura_folio');
        $venta_id = $venta_id+1;

        $importe   = 0;
        $partida   = 0;
        //$idVenta   = $venta->id;
        $productos = $this->obtenerProductos();
        //dd($productos);
        // Recorrer carrito de compras
        foreach ($productos as $producto) {
            $partida = $partida+1;
            // El producto que se vende...
            //$productoVendido = new ProductoVendido();
            $productoVendido    = new regDfacturaModel();
            
            $productoVendido->factura_folio    = $venta_id;
            $productoVendido->dfactura_npartida= $partida;
            $productoVendido->descripcion      = $producto->descripcion;
            $productoVendido->codigo_barras    = $producto->codigo_barras;
            $productoVendido->precio           = $producto->precio_venta;
            $productoVendido->cantidad         = $producto->cantidad;
            $productoVendido->dfactura_importe = $producto->precio_venta;
            $productoVendido->dfactura_cantidad= $producto->cantidad;

            $productoVendido->periodo_id       = $xperiodo_id;
            $productoVendido->mes_id           = $xmes_id;
            $productoVendido->dia_id           = $xdia_id;
            $productoVendido->cliente_id       = $request->input('cliente_id');

            $productoVendido->IP               = $ip;
            $productoVendido->LOGIN            = $nombre;         // Usuario ;
            // Lo guardamos
            $productoVendido->saveOrFail();

            // Calcular importe del carrito a facturar
            $importe += $producto->cantidad * $producto->precio_venta;

            // Y restamos la existencia del original
            $productoActualizado = regProductoModel::find($producto->id);
            $productoActualizado->existencia -= $productoVendido->cantidad;
            $productoActualizado->saveOrFail();
        }
        //Dar de alta encabezado de factura ***********7
        $venta              = new regEfacturaModel();
        $venta->factura_folio     = $venta_id;
        $venta->periodo_id        = $xperiodo_id;
        $venta->mes_id            = $xmes_id;
        $venta->dia_id            = $xdia_id;
        $venta->dia_id            = $xdia_id;
        $venta->efactura_importe  = $importe;
        $venta->efactura_totalneto= $importe;
        $venta->cliente_id        = $request->input('cliente_id');

        $venta->IP                = $ip;
        $venta->LOGIN             = $nombre;         // Usuario ;
        $venta->saveOrFail();
        // ************ Limpiar el carrito ****************/        
        $this->vaciarProductos();
        //return redirect()
        //       ->route('venderProducto')
        //       ->with("mensaje", "Venta terminada. A quien se le van a facturar...");
        //$regventacli  = regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
        //                'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO',
        //                'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
        //                ->get();
        return view('sicinar.vender.editarventaCliente',array($venta_id),compact('nombre','usuario'));                        
        //return redirect()
        //       ->route('editarventaCliente',array($venta_id))
        //       ->with("mensaje", "Venta terminada. A quien se le van a facturar...");

    }

    private function vaciarProductos()
    {
        $this->guardarProductos(null);
    }

    private function guardarProductos($productos)
    {
        session(["productos" => $productos,]);
    }

    public function cancelarVenta()
    {
        $this->vaciarProductos();
        return redirect()
               ->route('venderProducto')
               ->with("mensaje", "Venta cancelada");
    }

    public function quitarProductoDeVenta(Request $request)
    {
        $indice    = $request->post("indice");
        $productos = $this->obtenerProductos();
        array_splice($productos, $indice, 1);
        $this->guardarProductos($productos);
        return redirect()
               ->route('venderProducto');
    }

    public function agregarProductoVenta(Request $request)
    {
        $codigo   = $request->post("codigo");
        $producto = regProductoModel::where("codigo_barras", "=", $codigo)->first();
        if (!$producto) {
            return redirect()
                   ->route('venderProducto')
                   ->with("mensaje", "Producto no encontrado");
        }
        $this->agregarProductoACarrito($producto);
        return redirect()
               ->route('venderProducto');
    }

    private function agregarProductoACarrito($producto)
    {
        if ($producto->existencia <= 0) {
            return redirect()->route('venderProducto')
                   ->with([
                           "mensaje" => "No hay existencias del producto",
                           "tipo"    => "danger"
                          ]);
        }
        $productos     = $this->obtenerProductos();
        $posibleIndice = $this->buscarIndiceDeProducto($producto->codigo_barras, $productos);
        // Es decir, producto no fue encontrado
        if ($posibleIndice === -1) {
            $producto->cantidad = 1;
            array_push($productos, $producto);
        } else {
            if ($productos[$posibleIndice]->cantidad + 1 > $producto->existencia) {
                return redirect()->route('venderProducto')
                       ->with([
                               "mensaje" => "No se pueden agregar más productos de este tipo, se quedarían sin existencia",
                               "tipo"    => "danger"
                              ]);
            }
            $productos[$posibleIndice]->cantidad++;
        }
        $this->guardarProductos($productos);
    }

    private function buscarIndiceDeProducto(string $codigo, array &$productos)
    {
        foreach ($productos as $indice => $producto) {
            if ($producto->codigo_barras === $codigo) {
                return $indice;
            }
        }
        return -1;
    }

    //************************************* facturar venta al cliente *******************************//
    //***********************************************************************************************//
    public function actionEditarventaCliente($id){
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

        //dd($id,'-importe:'.$importe);
        //$ximporte     = $importe;
        $regtipocredito=regTipocreditoModel::select('TIPOCREDITO_ID','TIPOCREDITO_DESC','TIPOCREDITO_DIAS', 'TIPOCREDITO_STATUS')
                        ->orderBy('TIPOCREDITO_ID','asc')
                        ->get();        
        $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                        'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                        'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                        'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                        'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                        ->get();                        
        $regempleado  = regEmpleadosModel::select('PERIODO_ID','EMP_ID','EMP_NOMBRECOMPLETO','EMP_CURP','EMP_STATUS1','EMP_STATUS2')
                        ->orderBy('EMP_ID','asc')
                        ->get();
        $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                        'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06','CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10',
                        'CARGO_M11','CARGO_M12','SALDO','STATUS_1','STATUS_2','FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                        ->get();                        
        $regcliente   = regClientesModel::select('CLIENTE_ID','CLIENTE_NOMBRECOMPLETO','CLIENTE_STATUS1')
                        ->orderBy('CLIENTE_ID' ,'asc')
                        ->get();

        $regventacli  = regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
                        'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO',
                        'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('FACTURA_FOLIO' ,$id)
                        ->get();
        //dd($id,'-importe:'.$importe);
        //dd($ventacliente);
        if($regventacli->count() <= 0){
            toastr()->error('No existe venta para el cliente.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoPadron');
        }
        return view('sicinar.vender.editarventaCliente',compact('nombre','usuario','regempleado','regedoctaemp','regcliente','regedoctacli',           'regventacli','regtipocredito'));

    }

    public function actionActualizarventaCliente(ventaclienteRequest $request, $id){
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
            $xfecha_ing   = str_replace("/", "", trim($request->cliente_fecing));
            $xdia         = substr($xfecha_ing, 0, 2);
            $xmes         = substr($xfecha_ing, 2, 2);
            $xanio        = substr($xfecha_ing, 4, 4);
            //$fecha_okr    = $anio."/".$mes."/".$dia;
            $xfecha_ingok = $xdia."/".$xmes."/".$xanio;

            $yfecha_nac   = str_replace("/", "", trim($request->cliente_fecnac));
            $ydia         = substr($yfecha_nac, 0, 2);
            $ymes         = substr($yfecha_nac, 2, 2);
            $yanio        = substr($yfecha_nac, 4, 4);
            $yfecha_nacok = $ydia."/".$ymes."/".$yanio;            
            //dd('fecha nac:'.$request->cliente_fecnac,'-año:'.$yanio,'-mes:'.$ymes,'-dia:'.$ydia,'-Fecha_ok:'.$yfecha_nacok);            
            $regcliente = regClientesModel::where('CLIENTE_ID',$id)        
                           ->update([                
                'CLIENTE_FOLIO'          => $request->cliente_folio,
                //'CLIENTE_FECING'         => $xfecha_ingok,
                //'CLIENTE_FECING2'        => $xfecha_ingok,   //$request->input('cliente_fecing'),
                'CLIENTE_AP'             => substr(strtoupper(trim($request->cliente_ap))     ,0,79),
                'CLIENTE_AM'             => substr(strtoupper(trim($request->cliente_am))     ,0,79),
                'CLIENTE_NOMBRES'        => substr(strtoupper(trim($request->cliente_nombres)),0,79),
                'CLIENTE_NOMBRECOMPLETO' => substr(strtoupper(trim($request->cliente_ap)).' '.strtoupper(trim($request->cliente_am)).' '.strtoupper(trim($request->cliente_nombres)),0,99),
                'CLIENTE_CURP'           => substr(strtoupper(trim($request->cliente_curp)),0,17),
                'CLIENTE_SEXO'           => $request->cliente_sexo,
                //'CLIENTE_FECNAC'         => $yfecha_nacok,
                //'CLIENTE_FECNAC2'        => $yfecha_nacok,

                'ENTIDADNAC_ID'          => $request->entidadnac_id,                
                //'ENTIDADFED_ID'          => $request->entidad_fed_id,                
                'MUNICIPIO_ID'           => $request->municipio_id,
                //'SERVICIO_ID'          => $request->servicio_id,

                'CLIENTE_DOM'            => substr(strtoupper(trim($request->cliente_dom))    ,0,149),
                'CLIENTE_COL'            => substr(strtoupper(trim($request->cliente_col))    ,0, 79),
                'LOCALIDAD'              => substr(strtoupper(trim($request->localidad))      ,0,149),
                'CLIENTE_OTRAREF'        => substr(strtoupper(trim($request->cliente_otraref)),0, 99),                
                'CLIENTE_CP'             => $request->cliente_cp,
                
                'CLIENTE_TEL'            => substr(strtoupper(trim($request->cliente_tel))  ,0,29),
                'CLIENTE_CEL'            => substr(strtoupper(trim($request->cliente_cel))  ,0,29),
                'CLIENTE_EMAIL'          => substr(strtolower(trim($request->cliente_email)),0,59),
                //'CLIENTE_GEOREFLONGITUD' => $request->cliente_georeflongitud,               
                //'CLIENTE_GEOREFLATITUD'  => $request->cliente_georeflatitud,               
                'CLIENTE_STATUS1'        => $request->cliente_status1,                

                'IP_M'                   => $ip,
                'USU_M'                  => $nombre,
                'FECHA_M'                => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
            toastr()->success('Cliente actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

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
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/

        return redirect()->route('verderProducto');

    }


}
