<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\facturaRequest;
use App\Http\Requests\cobranzafacturasRequest;
use App\Http\Requests\facturaproductoRequest;

use App\regProductoModel;
use App\regClientesModel;
use App\regSaldosModel;

use App\regEmpleadosModel;
use App\regSaldosempModel;
use App\regTipocreditoModel;
use App\regMesesModel;
use App\regPeriodosModel;

use App\regDiarioModel;

use App\regDfacturaModel;
use App\regEfacturaModel;
use App\regMunicipioModel;
use App\regEntidadesModel; 
use App\regBitacoraModel;

// Exportar a excel 
//use App\Exports\ExcelExportPLacas;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExcelExportCobranzaFacturas;
// Exportar a pdf
use PDF;
//use Options;

class facturasController extends Controller
{

    public function actionBuscarFactura(Request $request){
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

        $regperiodo   = regPeriodosModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();
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
        $regfacturaprod=regDfacturaModel::select('FACTURA_FOLIO','DFACTURA_NPARTIDA','DESCRIPCION','CODIGO_BARRAS','PRECIO','CANTIDAD',
                        'CLIENTE_ID','EMP_ID','DFACTURA_CANTIDAD','DFACTURA_PRECIO','DFACTURA_IMPORTE','DFACTURA_IVA','DFACTURA_OTRO',
                        'DFACTURA_TOTALNETO' ,'PERIODO_ID','MES_ID','DIA_ID','CREATE_AT','UPDATE_AT',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('PERIODO_ID'       ,'desc')
                        ->orderBy('FACTURA_FOLIO'    ,'desc')
                        ->orderBy('DFACTURA_NPARTIDA','desc')
                        ->get();  
        $totprods     = regDfacturaModel::join('CEN_VENTAS','CEN_VENTAS.FACTURA_FOLIO','=','CEN_PRODUCTOS_VENDIDOS.FACTURA_FOLIO')
                        ->select(   'CEN_VENTAS.PERIODO_ID','CEN_VENTAS.FACTURA_FOLIO')
                        ->selectRaw('COUNT(*) AS PARTIDAS')
                        ->groupBy(  'CEN_VENTAS.PERIODO_ID','CEN_VENTAS.FACTURA_FOLIO')
                        ->get();  
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $perr   = $request->get('perr');   
        $mess   = $request->get('mess');  
        $diaa   = $request->get('diaa');    
        $empp   = $request->get('empp');       
        $cliee  = $request->get('cliee');  
        $folioo = $request->get('folioo');   
        $statuss= $request->get('statuss');   
        $regfactura = regEfacturaModel::orderBy('PERIODO_ID' ,'desc')
                      ->orderBy('FACTURA_FOLIO','desc')
                      ->perr($perr)         //Metodos personalizados es equvalente a ->where('IAP_DESC', 'LIKE', "%$name%");
                      ->mess($mess)         //Metodos personalizados
                      ->diaa($diaa)         //Metodos personalizados
                      ->empp($empp)         //Metodos personalizados
                      ->cliee($cliee)       //Metodos personalizados
                      ->folioo($folioo)     //Metodos personalizados es equvalente a ->where('IAP_DESC', 'LIKE', "%$name%"); 
                      ->statuss($statuss)   //Metodos personalizados
                      ->paginate(30);
        if($regfactura->count() <= 0){
            toastr()->error('No existen facturas.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }            
        return view('sicinar.facturas.verFacturas',compact('nombre','usuario','regperiodo','regtipocredito','regmes','regempleado','regedoctaemp','regcliente','regedoctacli','regfactura','regfacturaprod','totprods'));

    }

    public function actionVerFacturas(){
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

        $regperiodo   = regPeriodosModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();
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
        $regfacturaprod=regDfacturaModel::select('FACTURA_FOLIO','DFACTURA_NPARTIDA','DESCRIPCION','CODIGO_BARRAS','PRECIO','CANTIDAD',
                        'CLIENTE_ID','EMP_ID','DFACTURA_CANTIDAD','DFACTURA_PRECIO','DFACTURA_IMPORTE','DFACTURA_IVA','DFACTURA_OTRO',
                        'DFACTURA_TOTALNETO' ,'PERIODO_ID','MES_ID','DIA_ID','CREATE_AT','UPDATE_AT',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        //->where('VENTA_ID' ,$id)
                        ->orderBy('PERIODO_ID'       ,'desc')
                        ->orderBy('FACTURA_FOLIO'    ,'desc')
                        ->orderBy('DFACTURA_NPARTIDA','desc')
                        ->get();  
        if($role->rol_name == 'user'){                                                
            $totprods = regDfacturaModel::join('CEN_VENTAS','CEN_VENTAS.FACTURA_FOLIO','=','CEN_PRODUCTOS_VENDIDOS.FACTURA_FOLIO')
                        ->select(   'CEN_VENTAS.PERIODO_ID','CEN_VENTAS.FACTURA_FOLIO')
                        ->selectRaw('COUNT(*) AS PARTIDAS')
                        ->where(    'CEN_VENTAS.LOGIN', $nombre)
                        ->groupBy(  'CEN_VENTAS.PERIODO_ID','CEN_VENTAS.FACTURA_FOLIO')
                        ->get();            
            $regfactura=regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
                        'SUCURSAL_ID','MUNICIPIO_ID','ENTIDADFED_ID','CLIENTE_COL','LOCALIDAD',
                        'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_MONTOSUBSIDIO','EFACTURA_MONTOAPORTACIONES',
                        'EFACTURA_NUMAPORTACIONES','EFACTURA_MONTOPAGOS','EFACTURA_FECAPORTACION1','EFACTURA_FECAPORTACION2',
                        'EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO','EFACTURA_SALDO',
                        'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('LOGIN',$nombre)
                        ->orderBy('PERIODO_ID'   ,'desc')
                        ->orderBy('FACTURA_FOLIO','desc')
                        ->paginate(30);
        }else{
            $totprods = regDfacturaModel::join('CEN_VENTAS','CEN_VENTAS.FACTURA_FOLIO','=','CEN_PRODUCTOS_VENDIDOS.FACTURA_FOLIO')
                        ->select(   'CEN_VENTAS.PERIODO_ID','CEN_VENTAS.FACTURA_FOLIO')
                        ->selectRaw('COUNT(*) AS PARTIDAS')
                        ->groupBy(  'CEN_VENTAS.PERIODO_ID','CEN_VENTAS.FACTURA_FOLIO')
                        ->get();           
            $regfactura=regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
                        'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_MONTOSUBSIDIO','EFACTURA_MONTOAPORTACIONES',
                        'SUCURSAL_ID','MUNICIPIO_ID','ENTIDADFED_ID','CLIENTE_COL','LOCALIDAD',
                        'EFACTURA_NUMAPORTACIONES','EFACTURA_MONTOPAGOS','EFACTURA_FECAPORTACION1','EFACTURA_FECAPORTACION2',
                        'EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO','EFACTURA_SALDO',
                        'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('PERIODO_ID'   ,'desc')
                        ->orderBy('FACTURA_FOLIO','desc')
                        ->paginate(30);           
        }                  
        if($regfactura->count() <= 0){
            toastr()->error('No existen facturas de venta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.facturas.verFacturas',compact('nombre','usuario','regperiodo','regtipocredito','regmes','regempleado','regedoctaemp','regcliente','regedoctacli','regfactura','regfacturaprod','totprods'));
    }


    public function actionNuevaFactura(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');

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
        $regfactura=regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
                        'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_MONTOSUBSIDIO','EFACTURA_MONTOAPORTACIONES',
                        'SUCURSAL_ID','MUNICIPIO_ID','ENTIDADFED_ID','CLIENTE_COL','LOCALIDAD',                        
                        'EFACTURA_NUMAPORTACIONES','EFACTURA_MONTOPAGOS','EFACTURA_FECAPORTACION1','EFACTURA_FECAPORTACION2',
                        'EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO','EFACTURA_SALDO',
                        'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->get();  
        //dd($unidades);
        return view('sicinar.facturas.nuevaFactura',compact('nombre','usuario','regtipocredito','regempleado','regedoctaemp','regcliente','regedoctacli','regfactura'));
    }

    public function actionAltaNuevaFactura(Request $request){
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

        /************ ALTA  *****************************/ 

        //*********** Se obtiene datos del cliente   *****/
        $municipio_id = regClientesModel::obtcliMpio($request->cliente_id);
        $entidadfed_id= regClientesModel::obtcliEntfed($request->cliente_id);
        $cliente_col  = regClientesModel::obtcliCol($request->cliente_id);
        $localidad    = regClientesModel::obtcliLoc($request->cliente_id);

        $dias         = regTipocreditoModel::Obtcreditodias($request->tipocredito_id);

        $xperiodo_id  = (int)date('Y');
        $xmes_id      = (int)date('m');
        $xdia_id      = (int)date('d');
        // Obtener folio de factura de sistema
        $venta_id     = regEfacturaModel::max('FACTURA_FOLIO');
        $venta_id     = $venta_id+1;

        $venta                            = new regEfacturaModel();
        $venta->FACTURA_FOLIO             = $venta_id;
        $venta->PERIODO_ID                = $xperiodo_id;
        $venta->MES_ID                    = $xmes_id;
        $venta->DIA_ID                    = $xdia_id;
        $venta->CLIENTE_ID                = $request->input('cliente_id');
        $venta->EMP_ID                    = $request->input('emp_id');
        $venta->TIPOCREDITO_ID            = $request->input('tipocredito_id');
        $venta->TIPOCREDITO_DIAS          = $dias[0]->tipocredito_dias;

        $venta->EFACTURA_MONTOSUBSIDIO    = $request->input('efactura_montosubsidio');
        $venta->EFACTURA_TOTALNETO        = $request->input('efactura_montosubsidio');
        $venta->EFACTURA_MONTOAPORTACIONES= $request->input('efactura_montoaportaciones');
        $venta->EFACTURA_NUMAPORTACIONES  =($request->input('efactura_montosubsidio')/$request->input('efactura_montoaportaciones'));
        $venta->EFACTURA_SALDO            = $request->input('efactura_montosubsidio');

        $venta->EFACTURA_FECAPORTACION1   = $request->input('efactura_fecaportacion1');
        $venta->EFACTURA_FECAPORTACION2   = $request->input('efactura_fecaportacion1');
        $venta->EFACTURA_STATUS2          = '0'; //Por pagar    $request->input('efactura_status2');

        $venta->MUNICIPIO_ID              = $municipio_id[0]->municipio_id;
        $venta->ENTIDADFED_ID             = $entidadfed_id[0]->entidadfed_id;
        $venta->CLIENTE_COL               = $cliente_col[0]->cliente_col;
        $venta->LOCALIDAD                 = $localidad[0]->localidad;

        $venta->IP                        = $ip;
        $venta->LOGIN                     = $nombre;         // Usuar
        
        $venta->save();

        if($venta->save() == true){
            toastr()->success('Factura registrada.','OK!',['positionClass' => 'toast-bottom-right']);


            /************ Diario de movimientos *************************************/               
            $nuevodiario = new regDiarioModel();

            $nuevodiario->PERIODO_ID     = $xperiodo_id;            
            $nuevodiario->DIARIO_ID      = $venta_id;
            $nuevodiario->DIARIO_FOLIO   = $venta_id;
            $nuevodiario->FACTURA_FOLIO  = $venta_id;            
            $nuevodiario->CLIENTE_ID     = $request->cliente_id;        
            $nuevodiario->EMP_ID         = $request->emp_id;                
            $nuevodiario->DIARIO_FECHA   = date('Y/m/d');
            $nuevodiario->DIARIO_FECHA2  = date('Y/m/d');            
            $nuevodiario->MES_ID         = $xmes_id;
            $nuevodiario->DIA_ID         = $xdia_id;        
            $nuevodiario->DIARIO_TIPO    = 'C';        
            $nuevodiario->DIARIO_CONCEPTO= 'FACTURA DE VENTA';
            $nuevodiario->DIARIO_IMPORTE = $request->efactura_montosubsidio;

            $nuevodiario->IP             = $ip;
            $nuevodiario->LOGIN          = $nombre;         // Usuario ;
            $nuevodiario->save();

            if($nuevodiario->save() == true)
                toastr()->success('Trx de diario de movimientos registrada.',' dada de alta!',['positionClass' => 'toast-bottom-right']);
            else
                toastr()->error('Error al dar de alta en el diario de movtos.','Ups!',['positionClass' => 'toast-bottom-right']);            

            /*********************** Estado de cuenta del cliente ***********************/               
            $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                            'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                            'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                            'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                            'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                            ->where('CLIENTE_ID', $request->cliente_id)
                            ->get();
            if($regedoctacli->count() <= 0){              // Alta
                //$nuevoedocta = new regEdoctacliModel();              

                //$nuevoedocta->PERIODO_ID     = $xxperiodo_id;            
                //$nuevoedocta->DIARIO_ID      = $apor_folio;
                //$nuevoedocta->DIARIO_FOLIO   = $apor_folio;
                //$nuevodocta->IP             = $ip;
                //$nuevodocta->LOGIN          = $nombre;         // Usuario ;
                //$nuevodocta->save();

                //if($nuevodocta->save() == true){
                    //toastr()->success('Estado de cuenta del cleinte registrado.',' dada de alta!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** obtenemos datos del estado de cta. *****************************
                //*********** actualiza el abono *****************************
                switch ($xmes_id) {
                case 1:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['CARGO_M01' => $regedoctacli->CARGO =($regedoctacli[0]->CARGO_M01+$request->efactura_montosubsidio),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 2:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['CARGO_M02' => $regedoctacli->CARGO_M02 =($regedoctacli[0]->CARGO_M02+$request->efactura_montosubsidio),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 3:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['CARGO_M03' => $regedoctacli->CARGO_M03 =($regedoctacli[0]->CARGO_M03+$request->efactura_montosubsidio),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 4:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['CARGO_M04' => $regedoctacli->CARGO_M04 =($regedoctacli[0]->CARGO_M04+$request->efactura_montosubsidio),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break; 
                case 5:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['CARGO_M05' => $regedoctacli->CARGO_M05 = ($regedoctacli[0]->CARGO_M05+$request->efactura_montosubsidio),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                                        
                case 6:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['CARGO_M06' => $regedoctacli->CARGO_M06 = ($regedoctacli[0]->CARGO_M06+$request->efactura_montosubsidio),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                     
                case 7:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['CARGO_M07' => $regedoctacli->CARGO_M07 =($regedoctacli[0]->CARGO_M07+$request->efactura_montosubsidio),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 8:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['CARGO_M08' => $regedoctacli->CARGO_M08 =($regedoctacli[0]->CARGO_M08+$request->efactura_montosubsidio),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 9:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['CARGO_M09' => $regedoctacli->CARGO_M09 =($regedoctacli[0]->CARGO_M09+$request->efactura_montosubsidio),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 10:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['CARGO_M10' => $regedoctacli->CARGO_M10 =($regedoctacli[0]->CARGO_M10+$request->efactura_montosubsidio),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break; 
                case 11:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['CARGO_M11' => $regedoctacli->CARGO_M11 =($regedoctacli[0]->CARGO_M11+$request->efactura_montosubsidio),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                                        
                case 12:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['CARGO_M12' => $regedoctacli->CARGO_M12 =($regedoctacli[0]->CARGO_M12+$request->efactura_montosubsidio),

                                          'IP_M'      => $regedoctacli->IP       = $ip,
                                          'USU_M'     => $regedoctacli->USU_M    = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                 
                }
                toastr()->success('Estado de cuenta del cliente actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Estado de cuenta del cliente termina *************************************/                        

            /************ Estado de cuenta del vendedor *************************************/               
            $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                            'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                            'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                            'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                            'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                            ->where('EMP_ID', $request->emp_id)
                            ->get();
            if($regedoctaemp->count() <= 0){              // Alta
                //$nuevoedocta = new regEdoctacliModel();              

                //$nuevodocta->IP             = $ip;
                //$nuevodocta->LOGIN          = $nombre;         // Usuario ;
                //$nuevodocta->save();

                //if($nuevodocta->save() == true){
                    //toastr()->success('Estado de cuenta del cleinte registrado.',' dada de alta!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** obtenemos datos del estado de cta. *****************************
                //*********** actualiza el abono *****************************
                switch ($xmes_id) {
                case 1:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['ABONO_M01' => $regedoctaemp->ABONO_M01 =($regedoctaemp[0]->ABONO_M01+$request->efactura_montosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 2:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['ABONO_M02' => $regedoctaemp->ABONO_M02 =($regedoctaemp[0]->ABONO_M02+$request->efactura_montosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 3:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['ABONO_M03' => $regedoctaemp->ABONO_M03 =($regedoctaemp[0]->ABONO_M03+$request->efactura_montosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 4:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['ABONO_M04' => $regedoctaemp->ABONO_M04 =($regedoctaemp[0]->ABONO_M04+$request->efactura_montosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break; 
                case 5:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['ABONO_M05' => $regedoctaemp->ABONO_M05 = ($regedoctaemp[0]->ABONO_M05+$request->efactura_montosubsidio),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                                        
                case 6:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['ABONO_M06' => $regedoctaemp->ABONO_M06 = ($regedoctaemp[0]->ABONO_M06+$request->efactura_montosubsidio),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                     
                case 7:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['ABONO_M07' => $regedoctaemp->ABONO_M07 =($regedoctaemp[0]->ABONO_M07+$request->efactura_montosubsidio),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 8:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['ABONO_M08' => $regedoctaemp->ABONO_M08 =($regedoctaemp[0]->ABONO_M08+$request->efactura_montosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 9:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['ABONO_M09' => $regedoctaemp->ABONO_M09 =($regedoctaemp[0]->ABONO_M09+$request->efactura_montosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 10:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['ABONO_M10' => $regedoctaemp->ABONO_M10 =($regedoctaemp[0]->ABONO_M10+$request->efactura_montosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break; 
                case 11:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['ABONO_M11' => $regedoctaemp->ABONO_M11 =($regedoctaemp[0]->ABONO_M11+$request->efactura_montosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;                                        
                case 12:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['ABONO_M12' => $regedoctaemp->ABONO_M12 =($regedoctaemp[0]->ABONO_M12+$request->efactura_montosubsidio),

                                              'IP_M'      => $regedoctaemp->IP       = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M    = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;                 
                }
                toastr()->success('Estado de cuenta del vendedor actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Estado de cuenta del cliente termina *************************************/                                 

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         4;
            $xfuncion_id  =      4003;
            $xtrx_id      =        38;    //Alta 
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID','FUNCION_ID','TRX_ID','FOLIO',
                          'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN','FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                    'TRX_ID' => $xtrx_id, 'FOLIO' => $venta_id])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $venta_id;       // Folio    
                $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                $nuevoregBitacora->IP         = $ip;             // IP
                $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

                $nuevoregBitacora->save();
                if($nuevoregBitacora->save() == true)
                    toastr()->success('Trx de factura dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error al dar de alta trx de factura en la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces   = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                               'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $venta_id])
                               ->max('NO_VECES');
                $xno_veces   = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         

                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $venta_id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de factura actualizada en bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }
            /************ Bitacora termina *************************************/ 

        }else{
            toastr()->error('Error al dar de alta factura de venta. Por favor volver a intentarlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //return back();
            //return redirect()->route('nuevoProceso');
        }

        return redirect()->route('verFacturas');
    }


    public function actionEditarFactura($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        

        $regmes       = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();
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
        $regfactura   = regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
                        'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_MONTOSUBSIDIO','EFACTURA_MONTOAPORTACIONES',
                        'SUCURSAL_ID','MUNICIPIO_ID','ENTIDADFED_ID','CLIENTE_COL','LOCALIDAD',
                        'EFACTURA_NUMAPORTACIONES','EFACTURA_MONTOPAGOS','EFACTURA_FECAPORTACION1','EFACTURA_FECAPORTACION2',
                        'EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO','EFACTURA_SALDO',
                        'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(  'FACTURA_FOLIO',$id)
                        ->first();
        if($regfactura->count() <= 0){
            toastr()->error('No existe factura de venta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.facturas.editarFactura',compact('nombre','usuario','regtipocredito','regempleado','regedoctaemp','regcliente','regedoctacli','regfactura'));
    }

    public function actionActualizarFactura(facturaRequest $request, $id){
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
        $regfactura    = regEfacturaModel::where('FACTURA_FOLIO',$id);
        if($regfactura->count() <= 0)
            toastr()->error('No existe factura.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //$name1 =null;
            ////   if(!empty($_PUT['recibo_rfoto1'])){
            //if(isset($request->recibo_rfoto1)){
            //    if(!empty($request->recibo_rfoto1)){
            //        //Comprobar  si el campo foto1 tiene un archivo asignado:
            //        if($request->hasFile('recibo_rfoto1')){
            //          $name1 = $id.'_'.$request->file('recibo_rfoto1')->getClientOriginalName(); 
            //          //sube el archivo a la carpeta del servidor public/images/
            //          $request->file('recibo_rfoto1')->move(public_path().'/images/', $name1);
            //        }
            //    }
            //}

            //*********** Se obtiene la placa y el resguardatario   *****/
            $dias= regTipocreditoModel::Obtcreditodias($request->tipocredito_id);
            //dd($request->tipocredito_id);
            //dd($dias);
            $regfactura = regEfacturaModel::where('FACTURA_FOLIO',$id)        
                          ->update([                
                            'CLIENTE_ID'                => $request->cliente_id,
                            'EMP_ID'                    => $request->emp_id,                
                            'TIPOCREDITO_ID'            => $request->tipocredito_id,
                            'TIPOCREDITO_DIAS'          => $dias[0]->tipocredito_dias,                
                                    
                            'EFACTURA_MONTOSUBSIDIO'    => $request->input('efactura_montosubsidio'),                
                            'EFACTURA_MONTOAPORTACIONES'=> $request->input('efactura_montoaportaciones'),
                            'EFACTURA_NUMAPORTACIONES'  => ($request->input('efactura_montosubsidio')/$request->input('efactura_montoaportaciones')),
                            'EFACTURA_SALDO'            => $request->input('efactura_montosubsidio'),                
                            //'EFACTURA_STATUS2'          => $request->efactura_status2, 

                            'IP_M'                      => $ip,
                            'LOGIN_M'                   => $nombre,
                            'FECHA_M'                   => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
            toastr()->success('Factura de venta actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         4;
            $xfuncion_id  =      4003;
            $xtrx_id      =        39;    //Actualizar        

            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 
                                                    'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
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
                    toastr()->success('Trx de factura dada de alta en bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error en trx de factura al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces   = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->max('NO_VECES');
                $xno_veces   = $xno_veces+1;                        
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
                toastr()->success('Trx de factura actualizada en bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Factura termina *************************************/         
        }

        return redirect()->route('verFacturas');
    }


    public function actionBorrarFactura($id, $id2){
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

        /****************** Cancelar factura de venta *********************************************/
        $regfactura = regEfacturaModel::where(['PERIODO_ID' => $id, 'FACTURA_FOLIO' => $id2])
                      ->get();
        if($regfactura->count() <= 0)
            toastr()->error('No existe factura.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //$regfactura->delete();
            //toastr()->success('Factura eliminada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            $xcliente_id    = $regfactura[0]->cliente_id;
            $xemp_id        = $regfactura[0]->emp_id;
            $xmes_id        = $regfactura[0]->mes_id;
            $xmontosubsidio = $regfactura[0]->efactura_montosubsidio;
            //dd($xcliente_id,$xmes_id);
            // Actualizar factura de venta
            $regfactura = regEfacturaModel::where(['PERIODO_ID' => $id, 'FACTURA_FOLIO' => $id2])        
                          ->update([                
                                    'EFACTURA_STATUS2' => $regfactura->EFACTURA_STATUS2 = '1',   // 0-Pendiente de pagar, 1-Cancelada, 2-Pagada

                                    'IP_M'             => $regfactura->IP_M             = $ip,
                                    'LOGIN_M'          => $regfactura->LOGIN_M          = $nombre,
                                    'FECHA_M'          => $regfactura->FECHA_M          = date('Y/m/d')                                
                                   ]);
            toastr()->success('Factura de venta cancelada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //************ Eliminar factura productos  **************************************/
            //$regfacturaprod=regDfacturaModel::where('FACTURA_FOLIO', $id);
            //if($regfacturaprod->count() <= 0)
            //    toastr()->error('No existen productos en factura de venta.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
            //else{        
            //    $regfacturaprod->delete();
            //    toastr()->success('Factura-Productos eliminada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            //} 

            // Cancelar factura de venta en diario de movimientos
            $regdiario  = regDiarioModel::where(['PERIODO_ID' => $id,'DIARIO_FOLIO'=> $id2,'CLIENTE_ID' => $xcliente_id])
                          ->update([                
                                     'DIARIO_STATUS2'=> '1',   // 0-Pendiente de pagar, 1-Cancelada, 2-Pagada

                                     'IP_M'          => $regdiario->IP_M    = $ip,
                                     'LOGIN_M'       => $regdiario->LOGIN_M = $nombre,
                                     'FECHA_M'       => $regdiario->FECHA_M = date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
            toastr()->success('Factura de venta cancelada en diario de movimientos.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /*********************** Estado de cuenta del cliente ***********************/               
            $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                            'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                            'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                            'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                            'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                            ->where('CLIENTE_ID',$xcliente_id)
                            ->get();
            if($regedoctacli->count() <= 0){              // Alta
                //$nuevodocta->IP             = $ip;
                //$nuevodocta->LOGIN          = $nombre;         // Usuario ;
                //$nuevodocta->save();

                //if($nuevodocta->save() == true){
                    //toastr()->success('Estado de cuenta del cleinte registrado.',' dada de alta!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** obtenemos datos del estado de cta. *****************************
                //*********** actualiza el abono *****************************
                switch ($xmes_id) {
                case 1:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $xcliente_id)
                                    ->update([
                                               'CARGO_M01' => $regedoctacli->CARGO =($regedoctacli[0]->CARGO_M01-$xmontosubsidio),

                                               'IP_M'      => $regedoctacli->IP      = $ip,
                                               'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                               'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 2:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $xcliente_id)
                                    ->update([
                                               'CARGO_M02' => $regedoctacli->CARGO_M02 =($regedoctacli[0]->CARGO_M02-$xmontosubsidio),

                                               'IP_M'      => $regedoctacli->IP      = $ip,
                                               'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                               'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 3:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $xcliente_id)
                                    ->update([
                                              'CARGO_M03' => $regedoctacli->CARGO_M03 =($regedoctacli[0]->CARGO_M03-$xmontosubsidio),

                                              'IP_M'      => $regedoctacli->IP      = $ip,
                                              'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 4:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $xcliente_id)
                                    ->update([
                                              'CARGO_M04' => $regedoctacli->CARGO_M04 =($regedoctacli[0]->CARGO_M04-$xmontosubsidio),

                                              'IP_M'      => $regedoctacli->IP      = $ip,
                                              'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break; 
                case 5:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $xcliente_id)
                                    ->update([
                                              'CARGO_M05' => $regedoctacli->CARGO_M05 = ($regedoctacli[0]->CARGO_M05-$xmontosubsidio),

                                              'IP_M'      => $regedoctacli->IP      = $ip,
                                              'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;                                        
                case 6:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $xcliente_id)
                                    ->update([
                                              'CARGO_M06' => $regedoctacli->CARGO_M06 = ($regedoctacli[0]->CARGO_M06-$xmontosubsidio),

                                              'IP_M'      => $regedoctacli->IP      = $ip,
                                              'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;                     
                case 7:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $xcliente_id)
                                    ->update([
                                              'CARGO_M07' => $regedoctacli->CARGO_M07 =($regedoctacli[0]->CARGO_M07-$xmontosubsidio),

                                              'IP_M'      => $regedoctacli->IP      = $ip,
                                              'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 8:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $xcliente_id)
                                    ->update([
                                              'CARGO_M08' => $regedoctacli->CARGO_M08 =($regedoctacli[0]->CARGO_M08-$xmontosubsidio),

                                               'IP_M'     => $regedoctacli->IP      = $ip,
                                               'USU_M'    => $regedoctacli->USU_M   = $nombre,
                                               'FECHA_M'  => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 9:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $xcliente_id)
                                    ->update([
                                              'CARGO_M09' => $regedoctacli->CARGO_M09 =($regedoctacli[0]->CARGO_M09-$xmontosubsidio),

                                              'IP_M'      => $regedoctacli->IP      = $ip,
                                              'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 10:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $xcliente_id)
                                    ->update([
                                    'CARGO_M10' => $regedoctacli->CARGO_M10 =($regedoctacli[0]->CARGO_M10-$xmontosubsidio),

                                          'IP_M'      => $regestadocta->IP      = $ip,
                                          'USU_M'     => $regestadocta->USU_M   = $nombre,
                                          'FECHA_M'   => $regestadocta->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break; 
                case 11:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $xcliente_id)
                                    ->update([
                                              'CARGO_M11' => $regedoctacli->CARGO_M11 =($regedoctacli[0]->CARGO_M11-$xmontosubsidio),

                                              'IP_M'      => $regedoctacli->IP      = $ip,
                                              'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;                                        
                case 12:
                    $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $xcliente_id)
                                    ->update([
                                              'CARGO_M12' => $regedoctacli->CARGO_M12 =($regedoctacli[0]->CARGO_M12-$xmontosubsidio),

                                              'IP_M'      => $regedoctacli->IP       = $ip,
                                              'USU_M'     => $regedoctacli->USU_M    = $nombre,
                                              'FECHA_M'   => $regedoctacli->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;                 
                }
                toastr()->success('Estado de cuenta del cliente actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Estado de cuenta del cliente termina *************************************/                        

            /************ Estado de cuenta del vendedor *************************************/               
            $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                            'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                            'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                            'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                            'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                            ->where('EMP_ID', $xemp_id)
                            ->get();
            if($regedoctaemp->count() <= 0){              // Alta
                //$nuevoedocta = new regEdoctacliModel();              

                //$nuevoedocta->DIARIO_IMPORTE =$request->efactura_montosubsidio;        

                //$nuevodocta->IP             = $ip;
                //$nuevodocta->LOGIN          = $nombre;         // Usuario ;
                //$nuevodocta->save();

                //if($nuevodocta->save() == true){
                    //toastr()->success('Estado de cuenta del cleinte registrado.',' dada de alta!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** obtenemos datos del estado de cta. *****************************
                //*********** actualiza el abono *****************************
                switch ($xmes_id) {
                case 1:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $xemp_id)
                                    ->update([
                                              'ABONO_M01' => $regedoctaemp->ABONO_M01 =($regedoctaemp[0]->ABONO_M01-$xmontosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 2:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $xemp_id)
                                    ->update([
                                              'ABONO_M02' => $regedoctaemp->ABONO_M02 =($regedoctaemp[0]->ABONO_M02-$xmontosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 3:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $xemp_id)
                                    ->update([
                                              'ABONO_M03' => $regedoctaemp->ABONO_M03 =($regedoctaemp[0]->ABONO_M03-$xmontosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 4:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $xemp_id)
                                    ->update([
                                              'ABONO_M04' => $regedoctaemp->ABONO_M04 =($regedoctaemp[0]->ABONO_M04-$xmontosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break; 
                case 5:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $xemp_id)
                                    ->update([
                                              'ABONO_M05' => $regedoctaemp->ABONO_M05 = ($regedoctaemp[0]->ABONO_M05-$xmontosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;                                        
                case 6:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $xemp_id)
                                    ->update([
                                              'ABONO_M06' => $regedoctaemp->ABONO_M06 = ($regedoctaemp[0]->ABONO_M06-$xmontosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;                     
                case 7:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $xemp_id)
                                    ->update([
                                              'ABONO_M07' => $regedoctaemp->ABONO_M07 =($regedoctaemp[0]->ABONO_M07-$xmontosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 8:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $xemp_id)
                                    ->update([
                                              'ABONO_M08' => $regedoctaemp->ABONO_M08 =($regedoctaemp[0]->ABONO_M08-$xefactura_montosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 9:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $xemp_id)
                                    ->update([
                                              'ABONO_M09' => $regedoctaemp->ABONO_M09 =($regedoctaemp[0]->ABONO_M09-$xmontosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 10:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $xemp_id)
                                    ->update([
                                              'ABONO_M10' => $regedoctaemp->ABONO_M10 =($regedoctaemp[0]->ABONO_M10-$xmontosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break; 
                case 11:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $xemp_id)
                                    ->update([
                                              'ABONO_M11' => $regedoctaemp->ABONO_M11 =($regedoctaemp[0]->ABONO_M11-$xmontosubsidio),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;                                        
                case 12:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $xemp_id)
                                    ->update([
                                              'ABONO_M12' => $regedoctaemp->ABONO_M12 =($regedoctaemp[0]->ABONO_M12-$xmontosubsidio),

                                              'IP_M'      => $regedoctaemp->IP       = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M    = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;                 
                }
                toastr()->success('Estado de cuenta del vendedor actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Estado de cuenta del cliente termina *************************************/                                 

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         4;
            $xfuncion_id  =      4003;
            $xtrx_id      =        40;     // Cancelar

            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID','TRX_ID', 
                                                    'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id2])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $id2;             // Folio    
                $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                $nuevoregBitacora->IP         = $ip;             // IP
                $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

                $nuevoregBitacora->save();
                if($nuevoregBitacora->save() == true)
                   toastr()->success('trx de baja de factura dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                   toastr()->error('Error en trx de factura al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces   = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id2])
                               ->max('NO_VECES');
                $xno_veces   = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         

                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id2])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                toastr()->success('trx de factura actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }
            /************ Bitacora termina *************************************/     
        }
        /************* Termina de eliminar  **********************************/
        return redirect()->route('verFacturas');
    }    

    //************************************************************************//
    //**************** AGREGAR PRODUCTOS EN FACTURA DE VENTA *****************//
    //************************************************************************//
    public function actionVerfactProductos($id, $id2){
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

        $regmes       = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();
        $regtipocredito=regTipocreditoModel::select('TIPOCREDITO_ID','TIPOCREDITO_DESC','TIPOCREDITO_DIAS', 'TIPOCREDITO_STATUS')
                        ->orderBy('TIPOCREDITO_ID','asc')
                        ->get();        
        $producto     = regProductoModel::select('id','codigo_barras', 'descripcion', 'precio_compra', 'precio_venta', 'existencia',
                                                 'prod_foto1','prod_status','prod_fecreg')
                        ->orderBy('codigo_barras','asc')
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
        $regfactura   = regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
                        'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_MONTOSUBSIDIO','EFACTURA_MONTOAPORTACIONES',
                        'SUCURSAL_ID','MUNICIPIO_ID','ENTIDADFED_ID','CLIENTE_COL','LOCALIDAD',                        
                        'EFACTURA_NUMAPORTACIONES','EFACTURA_MONTOPAGOS','EFACTURA_FECAPORTACION1','EFACTURA_FECAPORTACION2',
                        'EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO','EFACTURA_SALDO',
                        'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'FACTURA_FOLIO' => $id2])                        
                        ->get();
        if($role->rol_name == 'user'){                                                
            $regfacturaprod=regDfacturaModel::select('FACTURA_FOLIO','DFACTURA_NPARTIDA','DESCRIPCION','CODIGO_BARRAS','PRECIO','CANTIDAD',
                        'CLIENTE_ID','EMP_ID','DFACTURA_CANTIDAD','DFACTURA_PRECIO','DFACTURA_IMPORTE','DFACTURA_IVA','DFACTURA_OTRO',
                        'DFACTURA_TOTALNETO' ,'PERIODO_ID','MES_ID','DIA_ID','CREATE_AT','UPDATE_AT',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'FACTURA_FOLIO' => $id2, 'LOGIN' => $nombre])
                        ->orderBy('PERIODO_ID'       ,'asc')
                        ->orderBy('FACTURA_FOLIO'    ,'asc')
                        ->orderBy('DFACTURA_NPARTIDA','asc')
                        ->paginate(30);           
        }else{
            $regfacturaprod=regDfacturaModel::select('FACTURA_FOLIO','DFACTURA_NPARTIDA','DESCRIPCION','CODIGO_BARRAS','PRECIO','CANTIDAD',
                        'CLIENTE_ID','EMP_ID','DFACTURA_CANTIDAD','DFACTURA_PRECIO','DFACTURA_IMPORTE','DFACTURA_IVA','DFACTURA_OTRO',
                        'DFACTURA_TOTALNETO' ,'PERIODO_ID','MES_ID','DIA_ID','CREATE_AT','UPDATE_AT',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'FACTURA_FOLIO' => $id2])                        
                        ->orderBy('PERIODO_ID'       ,'asc')
                        ->orderBy('FACTURA_FOLIO'    ,'asc')
                        ->orderBy('DFACTURA_NPARTIDA','asc')
                        ->paginate(30);           
        }                  
        if($regfacturaprod->count() <= 0){
            toastr()->error('No existe factura-productos de venta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.facturas.verfactProductos',compact('nombre','usuario','regtipocredito','regmes','regempleado','regedoctaemp','regcliente','regedoctacli','regfactura','regfacturaprod','producto'));
    }


    public function actionNuevafactProducto($id, $id2){
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

        $regmes       = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();
        $regtipocredito=regTipocreditoModel::select('TIPOCREDITO_ID','TIPOCREDITO_DESC','TIPOCREDITO_DIAS', 'TIPOCREDITO_STATUS')
                        ->orderBy('TIPOCREDITO_ID','asc')
                        ->get();        
        $producto     = regProductoModel::select('id','codigo_barras', 'descripcion', 'precio_compra', 'precio_venta', 'existencia',
                                                 'prod_foto1','prod_status','prod_fecreg')
                        ->orderBy('codigo_barras','asc')
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
        $regfactura   = regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
                        'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_MONTOSUBSIDIO','EFACTURA_MONTOAPORTACIONES',
                        'SUCURSAL_ID','MUNICIPIO_ID','ENTIDADFED_ID','CLIENTE_COL','LOCALIDAD',                        
                        'EFACTURA_NUMAPORTACIONES','EFACTURA_MONTOPAGOS','EFACTURA_FECAPORTACION1','EFACTURA_FECAPORTACION2',
                        'EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO','EFACTURA_SALDO',
                        'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'FACTURA_FOLIO' => $id2])                        
                        ->get();
        if($role->rol_name == 'user'){                        
            $regfacturaprod=regDfacturaModel::select('FACTURA_FOLIO','DFACTURA_NPARTIDA','DESCRIPCION','CODIGO_BARRAS','PRECIO','CANTIDAD',
                        'CLIENTE_ID','EMP_ID','DFACTURA_CANTIDAD','DFACTURA_PRECIO','DFACTURA_IMPORTE','DFACTURA_IVA','DFACTURA_OTRO',
                        'DFACTURA_TOTALNETO' ,'PERIODO_ID','MES_ID','DIA_ID','CREATE_AT','UPDATE_AT',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'FACTURA_FOLIO' => $id2, 'LOGIN' => $nombre])
                        ->orderBy('PERIODO_ID'       ,'asc')
                        ->orderBy('FACTURA_FOLIO'    ,'asc')
                        ->orderBy('DFACTURA_NPARTIDA','asc')
                        ->get(); 
        }else{
            $regfacturaprod=regDfacturaModel::select('FACTURA_FOLIO','DFACTURA_NPARTIDA','DESCRIPCION','CODIGO_BARRAS','PRECIO','CANTIDAD',
                        'CLIENTE_ID','EMP_ID','DFACTURA_CANTIDAD','DFACTURA_PRECIO','DFACTURA_IMPORTE','DFACTURA_IVA','DFACTURA_OTRO',
                        'DFACTURA_TOTALNETO' ,'PERIODO_ID','MES_ID','DIA_ID','CREATE_AT','UPDATE_AT',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'FACTURA_FOLIO' => $id2])                        
                        ->orderBy('PERIODO_ID'       ,'asc')
                        ->orderBy('FACTURA_FOLIO'    ,'asc')
                        ->orderBy('DFACTURA_NPARTIDA','asc')
                        ->get();             
        }                       
        //dd($unidades);
        return view('sicinar.facturas.nuevafactProducto',compact('nombre','usuario','regtipocredito','regmes','regempleado','regedoctaemp','regcliente','regedoctacli','regfactura','regfacturaprod','producto'));
    }

    public function actionAltaNuevafactProducto(Request $request){
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

        // *************** Validar duplicidad ***********************************/
        $duplicado = regDfacturaModel::where(['PERIODO_ID'    => $request->periodo_id, 
                                              'FACTURA_FOLIO' => $request->factura_folio,
                                              'CODIGO_BARRAS' => $request->codigo_barras
                                             ])
                     ->get();
        if($duplicado->count() >= 1)
            return back()->withInput()->withErrors(['CODIGO_BARRAS' => ' Producto en factura de venta '.$request->codigo_barras.' ya existe existe. Por favor verificar...']);
        else{  
            /************ Alta de registro ******************************/ 
            setlocale(LC_TIME, "spanish");        
            //*********** Se obtienen datos del producto   *****/
            $producto     = regProductoModel::Obtproducto($request->codigo_barras);
            $precio_venta = regProductoModel::Obtprecioventa($request->codigo_barras);

            //$mes1   = regMesesModel::ObtMes($request->mes_id1);
            //$dia1   = regDiasModel::ObtDia($request->dia_id1);

            $npartida = regDfacturaModel::where(['PERIODO_ID'    => $request->periodo_id,
                                                 'FACTURA_FOLIO' => $request->factura_folio])
                        ->max('DFACTURA_NPARTIDA');
            $npartida = $npartida + 1;

            //$name01 =null;
            ////Comprobar  si el campo foto1 tiene un archivo asignado:
            //if($request->hasFile('carga_foto1')){
            //    $name01 = $request->recibo_folio.'_'.$carga.'_'.$request->file('carga_foto1')->getClientOriginalName(); 
                ////$file->move(public_path().'/images/', $name1);
                ////sube el archivo a la carpeta del servidor public/images/
                //$request->file('carga_foto1')->move(public_path().'/images/', $name01);
            //}

            $nuevacarga                    = new regDfacturaModel();

            $nuevacarga->FACTURA_FOLIO     = $request->factura_folio;
            $nuevacarga->CODIGO_BARRAS     = $request->codigo_barras;
            $nuevacarga->DESCRIPCION       = strtoupper($producto[0]->descripcion);
            $nuevacarga->PERIODO_ID        = $request->periodo_id;
            $nuevacarga->MES_ID            = $request->mes_id;
            $nuevacarga->DIA_ID            = $request->dia_id;
            $nuevacarga->DFACTURA_NPARTIDA = $npartida;

            $nuevacarga->PRECIO            = $precio_venta[0]->precio_venta;
            $nuevacarga->CANTIDAD          = $request->cantidad;
            $nuevacarga->DFACTURA_CANTIDAD = $request->cantidad;
            $nuevacarga->DFACTURA_PRECIO   = $precio_venta[0]->precio_venta;
            $nuevacarga->DFACTURA_IMPORTE  = ($precio_venta[0]->precio_venta*$request->cantidad);
            $nuevacarga->DFACTURA_TOTALNETO= ($precio_venta[0]->precio_venta*$request->cantidad);
            $nuevacarga->EMP_ID            = $request->emp_id;
            $nuevacarga->CLIENTE_ID        = $request->cliente_id;

            $nuevacarga->IP                = $ip;
            $nuevacarga->LOGIN             = $nombre;         // Usuario ;
            
            $nuevacarga->save();
            if($nuevacarga->save() == true){
                toastr()->success('Producto registrado en factura de venta.  ','OK!',['positionClass' => 'toast-bottom-right']);

                // ********************** calcular importe **************************/
                $importe = 0;
                $factproductos = regDfacturaModel::where(['PERIODO_ID' => $request->periodo_id,'FACTURA_FOLIO' => $request->factura_folio])
                                 ->get();
                if($factproductos->count() <= 0)
                   toastr()->error('No existen productos en factura de venta.',' ¡upps!',['positionClass' => 'toast-bottom-right']);
                else{  
                    $partida   = 0;
                    // Recorrer carrito de compras
                    foreach ($factproductos as $producto) {
                        $partida = $partida+1;
                        // Calcular importe del carrito a facturar
                        $importe += $producto->cantidad * $producto->precio;
                    }   // foreach

                }

                if($importe > 0){
                    /****************** Actualizar factura de venta *********************************************/
                    $regfactura = regEfacturaModel::where(['PERIODO_ID' => $request->periodo_id, 'FACTURA_FOLIO' => $request->factura_folio])
                                  ->get();
                    if($regfactura->count() <= 0)
                        toastr()->error('No existe factura de venta.',' ¡upsss!',['positionClass' => 'toast-bottom-right']);
                    else{        
                        //$montoaportaciones = $regfactura[0]->EFACTURA_MONTOAPORTACIONES;
                        //$numaportaciones   = ($importe / $montoaportaciones);
                        //dd($importe,'-Monto de aportaciones:'.$regfactura[0]->EFACTURA_MONTOAPORTACIONES);
                        //***************** Actualizar importe **********************
                        //dd($xcliente_id,$xmes_id);
                        $regfactura = regEfacturaModel::where(['PERIODO_ID' => $request->periodo_id, 'FACTURA_FOLIO' => $request->factura_folio])
                                      ->update([                
                                                //'EFACTURA_NUMAPORTACIONES'=> $regfactura->EFACTURA_NUMAPORTACIONES= $numaportaciones,
                                                'EFACTURA_IMPORTE'        => $regfactura->EFACTURA_IMPORTE        = $importe,
                                                'EFACTURA_TOTALNETO'      => $regfactura->EFACTURA_TOTALNETO      = $importe,                          
                                                'EFACTURA_MONTOSUBSIDIO'  => $regfactura->EFACTURA_MONTOSUBSIDIO  = $importe,
                                                'EFACTURA_SALDO'          => $regfactura->EFACTURA_SALDO          = $importe
                                               ]);
                        toastr()->success('Importe de factura de venta actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    }
                }

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         4;
                $xfuncion_id  =      4003;
                $xtrx_id      =        41;    //Alta 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                               'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 
                               'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO' => $request->factura_folio])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $request->factura_folio;   // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('trx de factura-producto dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de trx de factura-producto en alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces  = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                           'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO' => $request->factura_folio])
                                  ->max('NO_VECES');
                    $xno_veces  = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                           'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $request->factura_folio])
                                  ->update([
                                             'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'     => $regbitacora->IP       = $ip,
                                             'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                           ]);
                    toastr()->success('trx de factura-producto actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 

            }else{
                toastr()->error('Error de trx de factura-producto en alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                //return back();
                //return redirect()->route('nuevoRecibo');
            }   // Termina de dar alta ***********************************//
        }       //*********** Termina de validar duplicidad ******************//
        return redirect()->route('verfactProductos',array($request->periodo_id,$request->factura_folio) );
    }


    public function actionBorrarfactProducto($id, $id1, $id2){
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

        /************ Eliminar  **************************************/
        $regfacturaprod= regDfacturaModel::where(['PERIODO_ID' => $id,'FACTURA_FOLIO' => $id1,'DFACTURA_NPARTIDA' => $id2]);
        if($regfacturaprod->count() <= 0)
            toastr()->error('No existe producto en factura.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regfacturaprod->delete();
            toastr()->success('Producto eliminado de factura de venta.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                // ********************** REcalcular importe **************************/
                $importe = 0;
                $factproductos = regDfacturaModel::where(['PERIODO_ID' => $id,'FACTURA_FOLIO' => $id1])
                                 ->get();
                if($factproductos->count() <= 0)
                   toastr()->error('No existen productos en factura de venta.',' ¡upps!',['positionClass' => 'toast-bottom-right']);
                else{  
                    $partida   = 0;
                    // Recorrer carrito de compras
                    foreach ($factproductos as $producto) {
                        $partida = $partida+1;
                        // Calcular importe del carrito a facturar
                        $importe += $producto->cantidad * $producto->precio;
                    }   // foreach

                }

                /****************** Actualizar factura de venta *********************************************/
                if($importe > 0){
                    $regfactura = regEfacturaModel::where(['PERIODO_ID' => $id, 'FACTURA_FOLIO' => $id1])
                                  ->get();
                    if($regfactura->count() <= 0)
                        toastr()->error('No existe factura de venta.',' ¡upsss!',['positionClass' => 'toast-bottom-right']);
                    else{        
                        //***************** Actualizar importe **********************
                        //$montoaportaciones = $regfactura[0]->EFACTURA_MONTOAPORTACIONES;
                        //$numaportaciones   = ($importe / $montoaportaciones);
                        $regfactura = regEfacturaModel::where(['PERIODO_ID' => $request->periodo_id, 'FACTURA_FOLIO' => $request->factura_folio])
                                      ->update([
                                                //'EFACTURA_NUMAPORTACIONES'=> $regfactura->EFACTURA_NUMAPORTACIONES= $numaportaciones,                
                                                'EFACTURA_IMPORTE'        => $regfactura->EFACTURA_IMPORTE        = $importe,
                                                'EFACTURA_TOTALNETO'      => $regfactura->EFACTURA_TOTALNETO      = $importe,
                                                'EFACTURA_MONTOSUBSIDIO'  => $regfactura->EFACTURA_MONTOSUBSIDIO  = $importe,
                                                'EFACTURA_SALDO'          => $regfactura->EFACTURA_SALDO          = $importe
                                               ]);
                        toastr()->success('Importe de factura de venta actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    }
                }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         4;
            $xfuncion_id  =      4003;
            $xtrx_id      =        43;     // Baja 

            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID','FUNCION_ID','TRX_ID', 
                                                    'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id1])
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
                   toastr()->success('trx de eliminar producto de factura dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                   toastr()->error('Error trx de eliminar producto de factura al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces  = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                                       'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO' => $id1])
                              ->max('NO_VECES');
                $xno_veces  = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         

                $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                              ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                       'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id1])
                              ->update([
                                        'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                        'IP_M'    => $regbitacora->IP       = $ip,
                                        'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                        'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                      ]);
                toastr()->success('trx de eliminar producto de factura actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/     
        }       /************* Termina de eliminar  **********************************/
        return redirect()->route('verfactProductos',array($id, $id1));
    }    

    public function actionEditarfactProducto($id, $id1, $id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');

        $regmes       = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();
        $regtipocredito=regTipocreditoModel::select('TIPOCREDITO_ID','TIPOCREDITO_DESC','TIPOCREDITO_DIAS', 'TIPOCREDITO_STATUS')
                        ->orderBy('TIPOCREDITO_ID','asc')
                        ->get();        
        $producto     = regProductoModel::select('id','codigo_barras', 'descripcion', 'precio_compra', 'precio_venta', 'existencia',
                                                 'prod_foto1','prod_status','prod_fecreg')
                        ->orderBy('codigo_barras','asc')
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
        $regfactura   = regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
                        'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_MONTOSUBSIDIO','EFACTURA_MONTOAPORTACIONES',
                        'SUCURSAL_ID','MUNICIPIO_ID','ENTIDADFED_ID','CLIENTE_COL','LOCALIDAD',                        
                        'EFACTURA_NUMAPORTACIONES','EFACTURA_MONTOPAGOS','EFACTURA_FECAPORTACION1','EFACTURA_FECAPORTACION2',
                        'EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO','EFACTURA_SALDO',
                        'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'FACTURA_FOLIO' => $id1])                        
                        ->get();
        if($role->rol_name == 'user'){                        
            $regfacturaprod=regDfacturaModel::select('FACTURA_FOLIO','DFACTURA_NPARTIDA','DESCRIPCION','CODIGO_BARRAS','PRECIO','CANTIDAD',
                        'CLIENTE_ID','EMP_ID','DFACTURA_CANTIDAD','DFACTURA_PRECIO','DFACTURA_IMPORTE','DFACTURA_IVA','DFACTURA_OTRO',
                        'DFACTURA_TOTALNETO' ,'PERIODO_ID','MES_ID','DIA_ID','CREATE_AT','UPDATE_AT',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'FACTURA_FOLIO' => $id1,'DFACTURA_NPARTIDA' => $id2,'LOGIN' => $nombre])
                        ->orderBy('PERIODO_ID'       ,'asc')
                        ->orderBy('FACTURA_FOLIO'    ,'asc')
                        ->orderBy('DFACTURA_NPARTIDA','asc')
                        ->first(); 
        }else{
            $regfacturaprod=regDfacturaModel::select('FACTURA_FOLIO','DFACTURA_NPARTIDA','DESCRIPCION','CODIGO_BARRAS','PRECIO','CANTIDAD',
                        'CLIENTE_ID','EMP_ID','DFACTURA_CANTIDAD','DFACTURA_PRECIO','DFACTURA_IMPORTE','DFACTURA_IVA','DFACTURA_OTRO',
                        'DFACTURA_TOTALNETO' ,'PERIODO_ID','MES_ID','DIA_ID','CREATE_AT','UPDATE_AT',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'FACTURA_FOLIO' => $id1,'DFACTURA_NPARTIDA' => $id2])                        
                        ->orderBy('PERIODO_ID'       ,'asc')
                        ->orderBy('FACTURA_FOLIO'    ,'asc')
                        ->orderBy('DFACTURA_NPARTIDA','asc')
                        ->first();             
        }                       
        if($regfacturaprod->count() <= 0){
            toastr()->error('No existe producto en factura de venta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.facturas.editarfactProducto',compact('nombre','usuario','regtipocredito','regmes','regempleado','regedoctaemp','regcliente','regedoctacli','regfactura','regfacturaprod','producto'));
    }

    public function actionActualizarfactProducto(facturaproductoRequest $request, $id, $id1, $id2){
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
        $regfacturaprod= regDfacturaModel::where(['PERIODO_ID'       => $request->periodo_id,
                                                  'FACTURA_FOLIO'    => $request->factura_folio, 
                                                  'DFACTURA_NPARTIDA'=> $id2]);
        //dd('periodo:',$request->periodo_id,' folio:',$request->recibo_folio,' carga:',$id2);
        if($regfacturaprod->count() <= 0)
            toastr()->error('No existe producto en factura de venta.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //*********** Se obtienen datos del producto   *****/
            $producto      = regProductoModel::Obtproducto($request->codigo_barras);
            $precio_venta  = regProductoModel::Obtprecioventa($request->codigo_barras);

            $regfacturaprod= regDfacturaModel::where(['PERIODO_ID'       => $request->periodo_id,
                                                      'FACTURA_FOLIO'    => $request->factura_folio, 
                                                      'DFACTURA_NPARTIDA'=> $id2])        
                             ->update([                
                                       'CODIGO_BARRAS'     => $request->codigo_barras,
                                       'DESCRIPCION'       => strtoupper($producto[0]->descripcion),  
                                       'PRECIO'            => $precio_venta[0]->precio_venta,
                                       'CANTIDAD'          => $request->cantidad,
                                       'DFACTURA_CANTIDAD' => $request->cantidad,
                                       'DFACTURA_PRECIO'   => $precio_venta[0]->precio_venta,                
                                       'DFACTURA_IMPORTE'  => ($precio_venta[0]->precio_venta*$request->cantidad),
                                       'DFACTURA_TOTALNETO'=> ($precio_venta[0]->precio_venta*$request->cantidad), 

                                       'IP_M'              => $ip,
                                       'LOGIN_M'           => $nombre,
                                       'FECHA_M'           => date('Y/m/d')    //date('d/m/Y')                                
                                      ]);
            toastr()->success('Producto actulizado en factura de venta.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         4;
            $xfuncion_id  =      4003;
            $xtrx_id      =        42;    //Actualizar        
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID','TRX_ID','FOLIO', 
                                                    'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $request->factura_folio])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $request->factura_folio;             // Folio    
                $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                $nuevoregBitacora->IP         = $ip;             // IP
                $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

                $nuevoregBitacora->save();
                if($nuevoregBitacora->save() == true)
                    toastr()->success('trx de factura-producto dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error en trx de factura-producto al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces   = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO' => $request->factura_folio])
                               ->max('NO_VECES');
                $xno_veces   = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO' => $request->factura_folio])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                toastr()->success('Trx de factura-producto actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/

        return redirect()->route('verfactProductos',array($id, $id1)); 
    }

    // exportar a formato PDF
    public function actionExportFacturaPdf($id,$id2,$id3){
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
        $xproceso_id  =         4;
        $xfuncion_id  =      4003;
        $xtrx_id      =        44;       //Generar factura en formato PDF
        $id           =         0;
        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                       'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                       ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,
                                'TRX_ID' => $xtrx_id,'FOLIO' => $id3])
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
               toastr()->success('Trx pdf de factura de venta dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error de trx Pdf de factura de venta al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces   = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id,
                                                    'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO' => $id3])
                           ->max('NO_VECES');
            $xno_veces   = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,
                                    'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $id3])
                           ->update([
                                     'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M'    => $regbitacora->IP       = $ip,
                                     'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                     'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Trx pdf de factura de venta actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/ 

        //********* Validar rol de usuario **********************/
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();
        $regtipocredito=regTipocreditoModel::select('TIPOCREDITO_ID','TIPOCREDITO_DESC','TIPOCREDITO_DIAS', 'TIPOCREDITO_STATUS')
                        ->orderBy('TIPOCREDITO_ID','asc')
                        ->get();        
        $producto     = regProductoModel::select('id','codigo_barras', 'descripcion', 'precio_compra', 'precio_venta', 'existencia',
                                                 'prod_foto1','prod_status','prod_fecreg')
                        ->orderBy('codigo_barras','asc')
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
        $regfacturaprod=regDfacturaModel::join('CEN_VENTAS','CEN_VENTAS.FACTURA_FOLIO',   '=',
                                                            'CEN_PRODUCTOS_VENDIDOS.FACTURA_FOLIO')
                        ->select(
                        'CEN_VENTAS.FACTURA_FOLIO',
                        'CEN_VENTAS.CLIENTE_ID',
                        'CEN_VENTAS.EMP_ID',
                        'CEN_VENTAS.TIPOCREDITO_ID',
                        'CEN_VENTAS.TIPOCREDITO_DIAS',
                        'CEN_VENTAS.PERIODO_ID',
                        'CEN_VENTAS.MES_ID',
                        'CEN_VENTAS.DIA_ID',
                        'CEN_VENTAS.EFACTURA_MONTOSUBSIDIO',
                        'CEN_VENTAS.EFACTURA_MONTOAPORTACIONES',
                        'CEN_VENTAS.EFACTURA_NUMAPORTACIONES',
                        'CEN_VENTAS.EFACTURA_MONTOPAGOS',
                        'CEN_VENTAS.EFACTURA_FECAPORTACION1',
                        'CEN_VENTAS.EFACTURA_FECAPORTACION2',
                        //'CEN_VENTAS.EFACTURA_IMPORTE',
                        //'CEN_VENTAS.EFACTURA_IVA',
                        //'CEN_VENTAS.EFACTURA_OTRO',
                        //'CEN_VENTAS.EFACTURA_TOTALNETO',
                        'CEN_VENTAS.EFACTURA_STATUS1',
                        'CEN_VENTAS.EFACTURA_STATUS2',
                        'CEN_VENTAS.CREATE_AT',
                        'CEN_VENTAS.UPDATE_AT',
                        'CEN_VENTAS.FECREG',
                        'CEN_VENTAS.IP',
                        'CEN_VENTAS.LOGIN',
                        'CEN_VENTAS.FECHA_M',
                        'CEN_VENTAS.IP_M',
                        'CEN_VENTAS.LOGIN_M',
                        'CEN_PRODUCTOS_VENDIDOS.FACTURA_FOLIO',
                        'CEN_PRODUCTOS_VENDIDOS.DFACTURA_NPARTIDA',
                        'CEN_PRODUCTOS_VENDIDOS.DESCRIPCION',
                        'CEN_PRODUCTOS_VENDIDOS.CODIGO_BARRAS',
                        'CEN_PRODUCTOS_VENDIDOS.PRECIO',
                        'CEN_PRODUCTOS_VENDIDOS.CANTIDAD',
                        'CEN_PRODUCTOS_VENDIDOS.CLIENTE_ID',
                        'CEN_PRODUCTOS_VENDIDOS.EMP_ID',
                        'CEN_PRODUCTOS_VENDIDOS.DFACTURA_CANTIDAD',
                        'CEN_PRODUCTOS_VENDIDOS.DFACTURA_PRECIO',
                        'CEN_PRODUCTOS_VENDIDOS.DFACTURA_IMPORTE',
                        'CEN_PRODUCTOS_VENDIDOS.DFACTURA_IVA',
                        'CEN_PRODUCTOS_VENDIDOS.DFACTURA_OTRO',
                        'CEN_PRODUCTOS_VENDIDOS.DFACTURA_TOTALNETO'
                         )
                        ->where( ['CEN_PRODUCTOS_VENDIDOS.PERIODO_ID' => $id2, 'CEN_PRODUCTOS_VENDIDOS.FACTURA_FOLIO' => $id3])                        
                        ->orderBy('CEN_PRODUCTOS_VENDIDOS.PERIODO_ID'       ,'asc')
                        ->orderBy('CEN_PRODUCTOS_VENDIDOS.FACTURA_FOLIO'    ,'asc')
                        ->orderBy('CEN_PRODUCTOS_VENDIDOS.DFACTURA_NPARTIDA','asc')                        
                        ->get();         
        //dd('REGISTRO:',$id,' llave2:',$id2,' llave2:',$id3);       
        //dd('REGISTRO:',$regcargas);       
        if($regfacturaprod->count() <= 0){
            toastr()->error('No existen productos en factura de venta.','Uppss!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('verTrx');
        }
        //$pdf = PDF::loadView('sicinar.pdf.cattrxPDF', compact('nombre','usuario','regplaca'));
        $pdf = PDF::loadView('sicinar.pdf.FacturaPdf', compact('nombre','usuario','regtipocredito','regmes','regempleado','regedoctaemp','regcliente','regedoctacli','regfacturaprod','producto'));
        //******** Horizontal ***************
        //$pdf->setPaper('A4', 'landscape');      
        //$pdf->set('defaultFont', 'Courier');          
        //$pdf->setPaper('A4','portrait');
        // Output the generated PDF to Browser
        //******** vertical *************** 
        //El tamaño de hoja se especifica en page_size puede ser letter, legal, A4, etc.         
        $pdf->setPaper('letter','portrait');      
        return $pdf->stream('Factura');
    }


    public function actionVentasxmes(){
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

        $regperiodo   = regPeriodosModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();
        $regtipocredito=regTipocreditoModel::select('TIPOCREDITO_ID','TIPOCREDITO_DESC','TIPOCREDITO_DIAS', 'TIPOCREDITO_STATUS')
                        ->orderBy('TIPOCREDITO_ID','asc')
                        ->get();        
        $producto     = regProductoModel::select('id','codigo_barras', 'descripcion', 'precio_compra', 'precio_venta', 'existencia',
                                                 'prod_foto1','prod_status','prod_fecreg')
                        ->orderBy('codigo_barras','asc')
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
        $regfactura   = regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
                        'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_MONTOSUBSIDIO','EFACTURA_MONTOAPORTACIONES',
                        'SUCURSAL_ID','MUNICIPIO_ID','ENTIDADFED_ID','CLIENTE_COL','LOCALIDAD',                        
                        'EFACTURA_NUMAPORTACIONES','EFACTURA_MONTOPAGOS','EFACTURA_FECAPORTACION1','EFACTURA_FECAPORTACION2',
                        'EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO','EFACTURA_SALDO',
                        'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->get();
        if($regfactura->count() <= 0){
            toastr()->error('No existen facturas de venta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.numeralia.ventasxmes',compact('nombre','usuario','regtipocredito','regmes','regempleado','regperiodo','regedoctaemp','regcliente','regedoctacli','regfactura'));
        //return view('sicinar.agenda.verProgdilGraficaxmes',compact('nombre','usuario','regmeses', 'reghoras','regdias','regperiodos','regiap','regprogdil'));

    }

    //**************** Estadistica de ventas x mes *************************
    public function actionGraficaventasxmes(Request $request){
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

        $regperiodo   = regPeriodosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->where('PERIODO_ID',$request->periodo_id)
                        ->get();   
        //$regmes       = regMesesModel::select('MES_ID','MES_DESC')
        //                ->get();
        $totventasxmes=regEfacturaModel::join('CEN_CAT_MESES','CEN_CAT_MESES.MES_ID','=','CEN_VENTAS.MES_ID')
                       ->selectRaw('SUM(EFACTURA_TOTALNETO) AS TOTALVENTASXMES, COUNT(*) AS TOTALFACTXMES')
                       ->get();                        
        $regfactura   = regEfacturaModel::join('CEN_CAT_MESES','CEN_CAT_MESES.MES_ID','=','CEN_VENTAS.MES_ID')
                       ->selectRaw('CEN_VENTAS.MES_ID,  CEN_CAT_MESES.MES_DESC AS MES, SUM(EFACTURA_TOTALNETO) AS TOTVENTAS, COUNT(*) AS TOTFACTURAS')
                       ->where(  'PERIODO_ID',$request->periodo_id)
                       ->groupBy('CEN_VENTAS.MES_ID','CEN_CAT_MESES.MES_DESC')
                       ->orderBy('CEN_VENTAS.MES_ID','asc')
                       ->get();                       
        //dd('Valor:'.$request->periodo_id,$regprogdil);
        return view('sicinar.numeralia.graficaventasxmes',compact('nombre','usuario','role','regperiodo','regfactura','totventasxmes'));
    }

    public function actionVerProgdilGraficaxtipo(){
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

        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();   
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();        
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->get();       
        $regprogdil   = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','IAP_ID',
                                             'MUNICIPIO_ID','ENTIDAD_ID',
                                             'VISITA_CONTACTO','VISITA_TEL','VISITA_EMAIL','VISITA_DOM','VISITA_OBJ',
                                             'VISITA_SPUB','VISITA_SPUB2','VISITA_AUDITOR2','VISITA_AUDITOR4',
                                             'VISITA_TIPO1','VISITA_FECREGP','VISITA_EDO')
                        ->orderBy('VISITA_FOLIO','ASC')                    
                        ->paginate(30);
        if($regprogdil->count() <= 0){
            toastr()->error('No existen registros de programación de visistas de diligencias.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.agenda.verProgdilGraficaxtipo',compact('nombre','usuario','regmeses', 'regperiodos','regiap','regprogdil'));

    }

    // Gráfica programa de diligencias x tipo
    public function actionProgdilGraficaxtipo(Request $request){
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

        $regmeses    = regMesesModel::select('MES_ID','MES_DESC')->get();   
        $regperiodos = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')
                       ->where('PERIODO_ID',$request->periodo_id)
                       ->get();        
        $agendatotxtipo=regAgendaModel::selectRaw('COUNT(*) AS TOTALXTIPO')
                       ->get();                        
        $regprogdil  = regAgendaModel::selectRaw('VISITA_TIPO1, COUNT(*) AS TOTAL')
                       ->where(  'PERIODO_ID',$request->periodo_id)
                       ->groupBy('VISITA_TIPO1')
                       ->orderBy('VISITA_TIPO1','asc')
                       ->get();                       
        //dd('Valor:'.$request->periodo_id,$regprogdil);
        return view('sicinar.numeralia.progdilgraficaxtipo',compact('nombre','usuario','rango','regperiodos','regprogdil','agendatotxtipo'));
    }


    public function actionVerCobranzaFacturas(cobranzafacturasRequest $request){
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

        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $perr   = $request->get('perr');   
        $mess   = $request->get('mess');  
        $diaa   = $request->get('diaa');    
        $empp   = $request->get('empp');       
        $cliee  = $request->get('cliee');  
        $folioo = $request->get('folioo');   
        $statuss= $request->get('statuss');   

        $regperiodo   = regPeriodosModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();
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
                        ->where('EMP_ID',$request->empp)
                        ->get();
        $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                        'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06','CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10',
                        'CARGO_M11','CARGO_M12','SALDO','STATUS_1','STATUS_2','FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                        ->get();                        
        $regcliente   = regClientesModel::select('CLIENTE_ID','CLIENTE_NOMBRECOMPLETO','CLIENTE_STATUS1')
                        ->orderBy('CLIENTE_ID' ,'asc')
                        ->get();
        $regfacturaprod=regDfacturaModel::select('FACTURA_FOLIO','DFACTURA_NPARTIDA','DESCRIPCION','CODIGO_BARRAS','PRECIO','CANTIDAD',
                        'CLIENTE_ID','EMP_ID','DFACTURA_CANTIDAD','DFACTURA_PRECIO','DFACTURA_IMPORTE','DFACTURA_IVA','DFACTURA_OTRO',
                        'DFACTURA_TOTALNETO' ,'PERIODO_ID','MES_ID','DIA_ID','CREATE_AT','UPDATE_AT',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('PERIODO_ID'       ,'desc')
                        ->orderBy('FACTURA_FOLIO'    ,'desc')
                        ->orderBy('DFACTURA_NPARTIDA','desc')
                        ->get();  
        $totprods     = regDfacturaModel::join('CEN_VENTAS','CEN_VENTAS.FACTURA_FOLIO','=','CEN_PRODUCTOS_VENDIDOS.FACTURA_FOLIO')
                        ->select(   'CEN_VENTAS.PERIODO_ID','CEN_VENTAS.FACTURA_FOLIO')
                        ->selectRaw('COUNT(*) AS PARTIDAS')
                        ->groupBy(  'CEN_VENTAS.PERIODO_ID','CEN_VENTAS.FACTURA_FOLIO')
                        ->get();  
        //dd('periodo:'.$request->perr,'Mes:'.$request->mess,'Dia:'.$request->diaa,'Cliente:'.$request->cliee,'Folio:'.$request->folioo,'Edo:'.$request->statuss);
        //if($request->visita_tipo2 == 'E'){
            //dd('hola ya entre......');  
            //return redirect()->route('programavisitasExcel',[$request->periodo_id, $request->mes_id, $request->visita_tipo1]);
            //return view('programavisitasExcel',[$request->periodo_id, $request->mes_id, $request->visita_tipo1]);
        //}else{
         switch ($request->tipo) {
            case 'P':    //Por pantalla
               if((!is_null($request->diaa))&&(is_null($request->cliee)) ){
                    $regfactura = regEfacturaModel::where('CEN_VENTAS.EFACTURA_STATUS2','=','0')
                                                  ->Where('CEN_VENTAS.PERIODO_ID'      ,'=',$request->perr) 
                                                  ->Where('CEN_VENTAS.MES_ID'          ,'=',$request->mess)                                
                                                  ->where('CEN_VENTAS.DIA_ID'          ,'=',$request->diaa)                      
                                 ->orderBy('PERIODO_ID'   ,'desc')
                                 ->orderBy('FACTURA_FOLIO','desc')
                                 ->paginate(30);
                }elseif((is_null($request->diaa))&&(!is_null($request->cliee)) ){
                    $regfactura = regEfacturaModel::where('CEN_VENTAS.EFACTURA_STATUS2','=','0')
                                                  ->Where('CEN_VENTAS.PERIODO_ID'      ,'=',$request->perr) 
                                                  ->Where('CEN_VENTAS.MES_ID'          ,'=',$request->mess)                                
                                                  ->where('CEN_VENTAS.CLIENTE_ID'      ,'=',$request->cliee)
                                 ->orderBy('PERIODO_ID'   ,'desc')
                                 ->orderBy('FACTURA_FOLIO','desc')
                                 ->paginate(30);  
                }elseif((!is_null($request->diaa))&&(!is_null($request->cliee)) ){
                    $regfactura = regEfacturaModel::where('CEN_VENTAS.EFACTURA_STATUS2','=','0')
                                                  ->Where('CEN_VENTAS.PERIODO_ID'      ,'=',$request->perr) 
                                                  ->Where('CEN_VENTAS.MES_ID'          ,'=',$request->mess)                                
                                                  ->where('CEN_VENTAS.DIA_ID'          ,'=',$request->diaa)                                                  
                                                  ->where('CEN_VENTAS.CLIENTE_ID'      ,'=',$request->cliee)
                                 ->orderBy('PERIODO_ID'   ,'desc')
                                 ->orderBy('FACTURA_FOLIO','desc')
                                 ->paginate(30);   
                }elseif((is_null($request->diaa))&&(is_null($request->cliee)) ){                                                                                              
                    $regfactura = regEfacturaModel::where('CEN_VENTAS.EFACTURA_STATUS2','=','0')
                                                  ->Where('CEN_VENTAS.PERIODO_ID'      ,'=',$request->perr) 
                                                  ->Where('CEN_VENTAS.MES_ID'          ,'=',$request->mess)                                
                                 ->orderBy('PERIODO_ID'   ,'desc')
                                 ->orderBy('FACTURA_FOLIO','desc')
                                 ->paginate(30);                       
                }
                if($regfactura->count() <= 0){
                    toastr()->error('No existen facturas.','Lo siento!',['positionClass' => 'toast-bottom-right']);  
                }else{ 

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         5;
                $xfuncion_id  =      5002;
                $xtrx_id      =        50;       //Generar factura a pantalla
                $id           =         0;
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                             ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,
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
                        toastr()->success('Ruta de cobranza a pantalla dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de generación de ruta de cobranza a pantalla al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //************ Obtine el no. de veces *****************************
                    $xno_veces   = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id,
                                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                   ->max('NO_VECES');
                    $xno_veces   = $xno_veces+1;                        
                    //************ Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                            ]);
                    toastr()->success('Ruta de cobranza a pantalla actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 

                    //************** Genera ruta de cobranza por pantalla *********************
                    return view('sicinar.facturas.verCobranzaFacturas',compact('nombre','usuario','regperiodo','regtipocredito','regmes','regempleado','regedoctaemp','regcliente','regedoctacli','regfactura','regfacturaprod','totprods'));
                }
                break;
            case 'E':    //Formato excel
                if((!is_null($request->diaa))&&(is_null($request->cliee)) ){
                    $regfactura=regEfacturaModel::join('CEN_CAT_MESES','CEN_CAT_MESES.MES_ID'   ,'=','CEN_VENTAS.MES_ID')
                                ->join('CEN_CLIENTES' ,'CEN_CLIENTES.CLIENTE_ID','=','CEN_VENTAS.CLIENTE_ID')
                              ->select('CEN_VENTAS.PERIODO_ID',
                                       'CEN_VENTAS.MES_ID',
                                       'CEN_CAT_MESES.MES_DESC',                                     
                                       'CEN_VENTAS.DIA_ID',
                                       'CEN_VENTAS.FACTURA_FOLIO',
                                       'CEN_VENTAS.EFACTURA_MONTOSUBSIDIO AS SUBSIDIO',
                                       'CEN_VENTAS.EFACTURA_MONTOPAGOS AS ACUMPAGOS', 
                                       '(CEN_VENTAS.EFACTURA_MONTOSUBSIDIO - SUBSIDIO-CEN_VENTAS.EFACTURA_MONTOPAGOS) AS SALDO',
                                       'CEN_VENTAS.FECREG',
                                       'CEN_VENTAS.EFACTURA_FECAPORTACION1 AS FECPROXCOBRO',                                       
                                       'CEN_VENTAS.CLIENTE_ID', 
                                       'CEN_CLIENTES.CLIENTE_NOMBRECOMPLETO AS NOMBRE',
                                       'CEN_CLIENTES.CLIENTE_DOM',
                                       'CEN_CLIENTES.CLIENTE_COL',
                                       'CEN_CLIENTES.CLIENTE_CP',
                                       //'CEN_CLIENTES.CLIENTE_OTRAREF',
                                       //'CEN_CLIENTES.MUNICIPIO_ID',
                                       'CEN_CLIENTES.CLIENTE_TEL',
                                       'CEN_CLIENTES.CLIENTE_CEL',
                                       //'CEN_CLIENTES.CLIENTE_EMAIL',
                                       'CEN_VENTAS.EFACTURA_STATUS2'
                                       ) 
                               ->where('CEN_VENTAS.EFACTURA_STATUS2','=','0')
                               ->Where('CEN_VENTAS.PERIODO_ID'      ,'=',$request->perr) 
                               ->Where('CEN_VENTAS.MES_ID'          ,'=',$request->mess)                                
                               ->where('CEN_VENTAS.DIA_ID'          ,'=',$request->diaa)                          
                             ->orderBy('CEN_VENTAS.PERIODO_ID'      ,'ASC')
                             ->orderBy('CEN_VENTAS.MES_ID'          ,'ASC')
                             ->orderBy('CEN_CLIENTES.CLIENTE_COL'   ,'ASC')
                             ->orderBy('CEN_VENTAS.FACTURA_FOLIO'   ,'ASC') 
                             ->get();   
                }elseif((is_null($request->diaa))&&(!is_null($request->cliee)) ){
                    $regfactura=regEfacturaModel::join('CEN_CAT_MUNICIPIOS',
                                                     [['CEN_CAT_MUNICIPIOS.MUNICIPIOID','=','CEN_VENTAS.MUNICIPIO_ID'],
                                                      ['CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID','=','CEN_VENTAS.ENTIDADFED_ID']])
                                ->join('CEN_CAT_MESES','CEN_CAT_MESES.MES_ID'   ,'=','CEN_VENTAS.MES_ID')
                                ->join('CEN_CLIENTES' ,'CEN_CLIENTES.CLIENTE_ID','=','CEN_VENTAS.CLIENTE_ID')
                              ->select('CEN_VENTAS.PERIODO_ID',
                                       'CEN_VENTAS.MES_ID',
                                       'CEN_CAT_MESES.MES_DESC',                                     
                                       'CEN_VENTAS.DIA_ID',
                                       'CEN_VENTAS.FACTURA_FOLIO',
                                       'CEN_VENTAS.EFACTURA_MONTOSUBSIDIO AS SUBSIDIO',
                                       'CEN_VENTAS.EFACTURA_MONTOPAGOS AS ACUMPAGOS', 
                                       '(CEN_VENTAS.EFACTURA_MONTOSUBSIDIO - SUBSIDIO-CEN_VENTAS.EFACTURA_MONTOPAGOS) AS SALDO',
                                       'CEN_VENTAS.FECREG',
                                       'CEN_VENTAS.EFACTURA_FECAPORTACION1 AS FECPROXCOBRO', 
                                       'CEN_VENTAS.CLIENTE_ID', 
                                       'CEN_CLIENTES.CLIENTE_NOMBRECOMPLETO AS NOMBRE',
                                       'CEN_CLIENTES.CLIENTE_DOM',
                                       'CEN_CLIENTES.CLIENTE_COL',
                                       'CEN_CLIENTES.CLIENTE_CP',
                                       //'CEN_CLIENTES.CLIENTE_OTRAREF',
                                       //'CEN_CLIENTES.MUNICIPIO_ID',
                                       'CEN_CLIENTES.CLIENTE_TEL',
                                       'CEN_CLIENTES.CLIENTE_CEL',
                                       //'CEN_CLIENTES.CLIENTE_EMAIL',
                                       'CEN_VENTAS.EFACTURA_STATUS2'
                                       ) 
                               ->where('CEN_VENTAS.EFACTURA_STATUS2','=','0')
                               ->Where('CEN_VENTAS.PERIODO_ID'      ,'=',$request->perr) 
                               ->Where('CEN_VENTAS.MES_ID'          ,'=',$request->mess)                                
                               ->where('CEN_VENTAS.CLIENTE_ID'      ,'=',$request->cliee)
                             ->orderBy('CEN_VENTAS.PERIODO_ID'      ,'ASC')
                             ->orderBy('CEN_VENTAS.MES_ID'          ,'ASC')
                             ->orderBy('CEN_CLIENTES.CLIENTE_COL'   ,'ASC')
                             ->orderBy('CEN_VENTAS.FACTURA_FOLIO'   ,'ASC') 
                             ->get();
                }elseif((!is_null($request->diaa))&&(!is_null($request->cliee)) ){
                    $regfactura=regEfacturaModel::join('CEN_CAT_MESES','CEN_CAT_MESES.MES_ID'   ,'=','CEN_VENTAS.MES_ID')
                                ->join('CEN_CLIENTES' ,'CEN_CLIENTES.CLIENTE_ID','=','CEN_VENTAS.CLIENTE_ID')
                              ->select('CEN_VENTAS.PERIODO_ID',
                                       'CEN_VENTAS.MES_ID',
                                       'CEN_CAT_MESES.MES_DESC',                                     
                                       'CEN_VENTAS.DIA_ID',
                                       'CEN_VENTAS.FACTURA_FOLIO',
                                       'CEN_VENTAS.EFACTURA_MONTOSUBSIDIO AS SUBSIDIO',
                                       'CEN_VENTAS.EFACTURA_MONTOPAGOS AS ACUMPAGOS', 
                                       '(CEN_VENTAS.EFACTURA_MONTOSUBSIDIO - SUBSIDIO-CEN_VENTAS.EFACTURA_MONTOPAGOS) AS SALDO',
                                       'CEN_VENTAS.FECREG',
                                       'CEN_VENTAS.EFACTURA_FECAPORTACION1 AS FECPROXCOBRO',                                       
                                       'CEN_VENTAS.CLIENTE_ID', 
                                       'CEN_CLIENTES.CLIENTE_NOMBRECOMPLETO AS NOMBRE',
                                       'CEN_CLIENTES.CLIENTE_DOM',
                                       'CEN_CLIENTES.CLIENTE_COL',
                                       'CEN_CLIENTES.CLIENTE_CP',
                                       //'CEN_CLIENTES.CLIENTE_OTRAREF',
                                       //'CEN_CLIENTES.MUNICIPIO_ID',
                                       'CEN_CLIENTES.CLIENTE_TEL',
                                       'CEN_CLIENTES.CLIENTE_CEL',
                                       //'CEN_CLIENTES.CLIENTE_EMAIL',
                                       'CEN_VENTAS.EFACTURA_STATUS2'
                                       ) 
                               ->where('CEN_VENTAS.EFACTURA_STATUS2','=','0')
                               ->Where('CEN_VENTAS.PERIODO_ID'      ,'=',$request->perr) 
                               ->Where('CEN_VENTAS.MES_ID'          ,'=',$request->mess)                                
                               ->where('CEN_VENTAS.DIA_ID'          ,'=',$request->diaa)
                               ->where('CEN_VENTAS.CLIENTE_ID'      ,'=',$request->cliee)
                             ->orderBy('CEN_VENTAS.PERIODO_ID'   ,'ASC')
                             ->orderBy('CEN_VENTAS.MES_ID'       ,'ASC')
                             ->orderBy('CEN_CLIENTES.CLIENTE_COL','ASC')
                             ->orderBy('CEN_VENTAS.FACTURA_FOLIO','ASC') 
                             ->get();                                
                }elseif((is_null($request->diaa))&&(is_null($request->cliee)) ){
                    $regfactura=regEfacturaModel::join('CEN_CAT_MESES','CEN_CAT_MESES.MES_ID'   ,'=','CEN_VENTAS.MES_ID')
                                ->join('CEN_CLIENTES' ,'CEN_CLIENTES.CLIENTE_ID','=','CEN_VENTAS.CLIENTE_ID')
                                ->select('CEN_VENTAS.PERIODO_ID',
                                       'CEN_VENTAS.MES_ID',
                                       'CEN_CAT_MESES.MES_DESC',                                     
                                       'CEN_VENTAS.DIA_ID',
                                       'CEN_VENTAS.FACTURA_FOLIO',
                                       'CEN_VENTAS.EFACTURA_MONTOSUBSIDIO AS SUBSIDIO',
                                       'CEN_VENTAS.EFACTURA_MONTOPAGOS AS ACUMPAGOS', 
                                       'CEN_VENTAS.EFACTURA_SALDO AS SALDO',
                                       'CEN_VENTAS.FECREG',
                                       'CEN_VENTAS.EFACTURA_FECAPORTACION1 AS FECPROXCOBRO',                                       
                                       'CEN_VENTAS.CLIENTE_ID', 
                                       'CEN_CLIENTES.CLIENTE_NOMBRECOMPLETO AS NOMBRE',
                                       'CEN_CLIENTES.CLIENTE_DOM',
                                       'CEN_CLIENTES.CLIENTE_COL',
                                       'CEN_CLIENTES.CLIENTE_CP',
                                       //'CEN_CLIENTES.CLIENTE_OTRAREF',
                                       //'CEN_CLIENTES.MUNICIPIO_ID',
                                       'CEN_CLIENTES.CLIENTE_TEL',
                                       'CEN_CLIENTES.CLIENTE_CEL',
                                       //'CEN_CLIENTES.CLIENTE_EMAIL',
                                       'CEN_VENTAS.EFACTURA_STATUS2'
                                       ) 
                                ->where(  'CEN_VENTAS.EFACTURA_STATUS2','=','0')
                                ->Where('CEN_VENTAS.PERIODO_ID'        ,'=',$request->perr) 
                                ->Where('CEN_VENTAS.MES_ID'            ,'=',$request->mess)                                
                                ->orderBy('CEN_VENTAS.PERIODO_ID'   ,'ASC')
                                ->orderBy('CEN_VENTAS.MES_ID'       ,'ASC')
                                ->orderBy('CEN_CLIENTES.CLIENTE_COL','ASC')
                                ->orderBy('CEN_VENTAS.FACTURA_FOLIO','ASC') 
                                ->get();                                                                
                }
                if($regfactura->count() <= 0){
                    toastr()->error('No existen facturas.','Lo siento!',['positionClass' => 'toast-bottom-right']);
                    //return redirect()->route('nuevaIap');
                }else{                    

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         5;
                $xfuncion_id  =      5002;
                $xtrx_id      =        51;       //Generar factura en formato excel
                $id           =         0;
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                             ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,
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
                        toastr()->success('Ruta de cobranza a formato excel dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de generación de ruta de cobranza a formato excel al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //************ Obtine el no. de veces *****************************
                    $xno_veces   = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id,
                                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                   ->max('NO_VECES');
                    $xno_veces   = $xno_veces+1;                        
                    //************ Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                            ]);
                    toastr()->success('Ruta de cobranza a formato excel actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 

                   //return Excel::download(new ExcelExportCobranzaFacturas($request->perr, $request->mess, $request->diaa),'ReporteDeCobranza_'.date('d-m-Y').'.xlsx');
                   return Excel::download(new ExcelExportCobranzaFacturas($regfactura,$request->perr, $request->mess, $request->diaa, 
                                          $request->empp, $request->diaa,$request->cliee),'ReporteDeCobranza_'.date('d-m-Y').'.xlsx');
                }
                break;
            case 'D':    //Formato PDF
                if((!is_null($request->diaa))&&(is_null($request->cliee)) ){
                    $regfactura=regEfacturaModel::join('CEN_CAT_MESES','CEN_CAT_MESES.MES_ID'   ,'=','CEN_VENTAS.MES_ID')
                                ->join('CEN_CLIENTES' ,'CEN_CLIENTES.CLIENTE_ID','=','CEN_VENTAS.CLIENTE_ID')
                              ->select('CEN_VENTAS.PERIODO_ID',
                                       'CEN_VENTAS.MES_ID',
                                       'CEN_CAT_MESES.MES_DESC',                                     
                                       'CEN_VENTAS.DIA_ID',
                                       'CEN_VENTAS.FACTURA_FOLIO',
                                       'CEN_VENTAS.EFACTURA_MONTOSUBSIDIO AS SUBSIDIO',
                                       'CEN_VENTAS.EFACTURA_MONTOPAGOS AS ACUMPAGOS', 
                                       'CEN_VENTAS.EFACTURA_SALDO AS SALDO',
                                       'CEN_VENTAS.CLIENTE_ID', 
                                       'CEN_CLIENTES.CLIENTE_NOMBRECOMPLETO AS NOMBRE',
                                       'CEN_CLIENTES.CLIENTE_DOM',
                                       'CEN_CLIENTES.CLIENTE_COL',
                                       'CEN_CLIENTES.CLIENTE_CP',
                                       //'CEN_CLIENTES.CLIENTE_OTRAREF',
                                       //'CEN_CLIENTES.MUNICIPIO_ID',
                                       'CEN_CLIENTES.CLIENTE_TEL',
                                       'CEN_CLIENTES.CLIENTE_CEL',
                                       //'CEN_CLIENTES.CLIENTE_EMAIL',
                                       'CEN_VENTAS.EFACTURA_FECAPORTACION1 AS FECPROXCOBRO',
                                       'CEN_VENTAS.EFACTURA_FECAPORTACION2 AS FECHACOBRO2',
                                       'CEN_VENTAS.EFACTURA_STATUS2',
                                       'CEN_VENTAS.FECREG') 
                               ->where('CEN_VENTAS.EFACTURA_STATUS2','=','0')
                               ->Where('CEN_VENTAS.PERIODO_ID'      ,'=',$request->perr) 
                               ->Where('CEN_VENTAS.MES_ID'          ,'=',$request->mess)                                
                               ->where('CEN_VENTAS.DIA_ID'          ,'=',$request->diaa)                          
                             ->orderBy('CEN_VENTAS.PERIODO_ID'      ,'ASC')
                             ->orderBy('CEN_VENTAS.MES_ID'          ,'ASC')
                             ->orderBy('CEN_CLIENTES.CLIENTE_COL'   ,'ASC')
                             ->orderBy('CEN_VENTAS.FACTURA_FOLIO'   ,'ASC') 
                             ->get();   
                }elseif((is_null($request->diaa))&&(!is_null($request->cliee)) ){
                    $regfactura=regEfacturaModel::join('CEN_CAT_MESES','CEN_CAT_MESES.MES_ID'   ,'=','CEN_VENTAS.MES_ID')
                                ->join('CEN_CLIENTES' ,'CEN_CLIENTES.CLIENTE_ID','=','CEN_VENTAS.CLIENTE_ID')
                              ->select('CEN_VENTAS.PERIODO_ID',
                                       'CEN_VENTAS.MES_ID',
                                       'CEN_CAT_MESES.MES_DESC',                                     
                                       'CEN_VENTAS.DIA_ID',
                                       'CEN_VENTAS.FACTURA_FOLIO',
                                       'CEN_VENTAS.EFACTURA_MONTOSUBSIDIO AS SUBSIDIO',
                                       'CEN_VENTAS.EFACTURA_MONTOPAGOS AS ACUMPAGOS', 
                                       'CEN_VENTAS.EFACTURA_SALDO AS SALDO',
                                       'CEN_VENTAS.CLIENTE_ID', 
                                       'CEN_CLIENTES.CLIENTE_NOMBRECOMPLETO AS NOMBRE',
                                       'CEN_CLIENTES.CLIENTE_DOM',
                                       'CEN_CLIENTES.CLIENTE_COL',
                                       'CEN_CLIENTES.CLIENTE_CP',
                                       //'CEN_CLIENTES.CLIENTE_OTRAREF',
                                       //'CEN_CLIENTES.MUNICIPIO_ID',
                                       'CEN_CLIENTES.CLIENTE_TEL',
                                       'CEN_CLIENTES.CLIENTE_CEL',
                                       //'CEN_CLIENTES.CLIENTE_EMAIL',
                                       'CEN_VENTAS.EFACTURA_FECAPORTACION1 AS FECPROXCOBRO',
                                       'CEN_VENTAS.EFACTURA_FECAPORTACION2 AS FECHACOBRO2',
                                       'CEN_VENTAS.EFACTURA_STATUS2',
                                       'CEN_VENTAS.FECREG') 
                               ->where('CEN_VENTAS.EFACTURA_STATUS2','=','0')
                               ->Where('CEN_VENTAS.PERIODO_ID'      ,'=',$request->perr) 
                               ->Where('CEN_VENTAS.MES_ID'          ,'=',$request->mess)                                
                               ->where('CEN_VENTAS.CLIENTE_ID'      ,'=',$request->cliee)
                             ->orderBy('CEN_VENTAS.PERIODO_ID'      ,'ASC')
                             ->orderBy('CEN_VENTAS.MES_ID'          ,'ASC')
                             ->orderBy('CEN_CLIENTES.CLIENTE_COL'   ,'ASC')
                             ->orderBy('CEN_VENTAS.FACTURA_FOLIO'   ,'ASC') 
                             ->get();
                }elseif((!is_null($request->diaa))&&(!is_null($request->cliee)) ){
                    $regfactura=regEfacturaModel::join('CEN_CAT_MESES','CEN_CAT_MESES.MES_ID'   ,'=','CEN_VENTAS.MES_ID')
                                ->join('CEN_CLIENTES' ,'CEN_CLIENTES.CLIENTE_ID','=','CEN_VENTAS.CLIENTE_ID')
                              ->select('CEN_VENTAS.PERIODO_ID',
                                       'CEN_VENTAS.MES_ID',
                                       'CEN_CAT_MESES.MES_DESC',                                     
                                       'CEN_VENTAS.DIA_ID',
                                       'CEN_VENTAS.FACTURA_FOLIO',
                                       'CEN_VENTAS.EFACTURA_MONTOSUBSIDIO AS SUBSIDIO',
                                       'CEN_VENTAS.EFACTURA_MONTOPAGOS AS ACUMPAGOS', 
                                       'CEN_VENTAS.EFACTURA_SALDO AS SALDO',
                                       'CEN_VENTAS.CLIENTE_ID', 
                                       'CEN_CLIENTES.CLIENTE_NOMBRECOMPLETO AS NOMBRE',
                                       'CEN_CLIENTES.CLIENTE_DOM',
                                       'CEN_CLIENTES.CLIENTE_COL',
                                       'CEN_CLIENTES.CLIENTE_CP',
                                       //'CEN_CLIENTES.CLIENTE_OTRAREF',
                                       //'CEN_CLIENTES.MUNICIPIO_ID',
                                       'CEN_CLIENTES.CLIENTE_TEL',
                                       'CEN_CLIENTES.CLIENTE_CEL',
                                       //'CEN_CLIENTES.CLIENTE_EMAIL',
                                       'CEN_VENTAS.EFACTURA_FECAPORTACION1 AS FECPROXCOBRO',
                                       'CEN_VENTAS.EFACTURA_FECAPORTACION2 AS FECHACOBRO2',
                                       'CEN_VENTAS.EFACTURA_STATUS2',
                                       'CEN_VENTAS.FECREG') 
                               ->where('CEN_VENTAS.EFACTURA_STATUS2','=','0')
                               ->Where('CEN_VENTAS.PERIODO_ID'      ,'=',$request->perr) 
                               ->Where('CEN_VENTAS.MES_ID'          ,'=',$request->mess)                                
                               ->where('CEN_VENTAS.DIA_ID'          ,'=',$request->diaa)
                               ->where('CEN_VENTAS.CLIENTE_ID'      ,'=',$request->cliee)
                             ->orderBy('CEN_VENTAS.PERIODO_ID'   ,'ASC')
                             ->orderBy('CEN_VENTAS.MES_ID'       ,'ASC')
                             ->orderBy('CEN_CLIENTES.CLIENTE_COL','ASC')
                             ->orderBy('CEN_VENTAS.FACTURA_FOLIO','ASC') 
                             ->get();                                
                }elseif((is_null($request->diaa))&&(is_null($request->cliee)) ){
                    $regfactura=regEfacturaModel::join('CEN_CAT_MESES','CEN_CAT_MESES.MES_ID'   ,'=','CEN_VENTAS.MES_ID')
                                ->join('CEN_CLIENTES' ,'CEN_CLIENTES.CLIENTE_ID','=','CEN_VENTAS.CLIENTE_ID')
                                ->select('CEN_VENTAS.PERIODO_ID',
                                       'CEN_VENTAS.MES_ID',
                                       'CEN_CAT_MESES.MES_DESC',                                     
                                       'CEN_VENTAS.DIA_ID',
                                       'CEN_VENTAS.FACTURA_FOLIO',
                                       'CEN_VENTAS.EFACTURA_MONTOSUBSIDIO AS SUBSIDIO',
                                       'CEN_VENTAS.EFACTURA_MONTOPAGOS AS ACUMPAGOS', 
                                       'CEN_VENTAS.EFACTURA_SALDO AS SALDO',
                                       'CEN_VENTAS.CLIENTE_ID', 
                                       'CEN_CLIENTES.CLIENTE_NOMBRECOMPLETO AS NOMBRE',
                                       'CEN_CLIENTES.CLIENTE_DOM',
                                       'CEN_CLIENTES.CLIENTE_COL',
                                       'CEN_CLIENTES.CLIENTE_CP',
                                       //'CEN_CLIENTES.CLIENTE_OTRAREF',
                                       //'CEN_CLIENTES.MUNICIPIO_ID',
                                       'CEN_CLIENTES.CLIENTE_TEL',
                                       'CEN_CLIENTES.CLIENTE_CEL',
                                       //'CEN_CLIENTES.CLIENTE_EMAIL',
                                       'CEN_VENTAS.EFACTURA_FECAPORTACION1 AS FECPROXCOBRO',
                                       'CEN_VENTAS.EFACTURA_FECAPORTACION2 AS FECHACOBRO2',
                                       'CEN_VENTAS.EFACTURA_STATUS2',
                                       'CEN_VENTAS.FECREG') 
                                ->where(  'CEN_VENTAS.EFACTURA_STATUS2','=','0')
                                ->Where('CEN_VENTAS.PERIODO_ID'        ,'=',$request->perr) 
                                ->Where('CEN_VENTAS.MES_ID'            ,'=',$request->mess)                                
                                ->orderBy('CEN_VENTAS.PERIODO_ID'   ,'ASC')
                                ->orderBy('CEN_VENTAS.MES_ID'       ,'ASC')
                                ->orderBy('CEN_CLIENTES.CLIENTE_COL','ASC')
                                ->orderBy('CEN_VENTAS.FACTURA_FOLIO','ASC') 
                                ->get();                                                                
                }
                             //->orwhere('CEN_VENTAS.FACTURA_FOLIO'   ,'=',$request->folioo)
                                                                          
                      //->perr2($perr)         //Metodos personalizados es equvalente a ->where('IAP_DESC', 'LIKE', "%$name%");
                      //->mess2($mess)         //Metodos personalizados
                      //->diaa2($diaa)         //Metodos personalizados
                      //->empp2($empp)         //Metodos personalizados
                      //->cliee2($cliee)       //Metodos personalizados
                      //->folioo2($folioo)     //Metodos personalizados es equvalente a ->where('IAP_DESC', 'LIKE', "%$name%"); 
                      //->statuss2($statuss)   //Metodos personalizados
                      //->get();
                      //dd($regfactura);
                if($regfactura->count() <= 0){
                    toastr()->error('No existen facturas.','Lo siento!',['positionClass' => 'toast-bottom-right']);
                    //return redirect()->route('nuevaIap');
                }else{             
                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         5;
                $xfuncion_id  =      5002;
                $xtrx_id      =        52;       //Generar factura en formato PDF
                $id           =         0;
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                             ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,
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
                        toastr()->success('Ruta de cobranza a PDF dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de generación de ruta de cobranza a PDF al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //************ Obtine el no. de veces *****************************
                    $xno_veces   = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id,
                                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                   ->max('NO_VECES');
                    $xno_veces   = $xno_veces+1;                        
                    //************ Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                            ]);
                    toastr()->success('Ruta de cobranza a PDF actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 


                    /**************** Genera reporte pdf **********************/            
                    $pdf = PDF::loadView('sicinar.pdf.cobranzafacturasPDF', compact('nombre','usuario','regperiodo','regtipocredito','regmes','regempleado','regedoctaemp','regcliente','regedoctacli','regfactura'));
                    //$options = new Options();
                    //$options->set('defaultFont', 'Courier');
                    //$pdf->set_option('defaultFont', 'Courier');
                    //******** Horizontal ***************
                    $pdf->setPaper('A4', 'landscape');      
                    //$pdf->set('defaultFont', 'Courier');
                    //$pdf->set_options('isPhpEnabled', true);
                    //$pdf->setOptions(['isPhpEnabled' => true]);
                    //******** vertical ***************          
                    //$pdf->setPaper('A4','portrait');

                    // Output the generated PDF to Browser
                    return $pdf->stream('ReporteDeCobranza');            
                    //return view('sicinar.facturas.verCobranzaFacturas',compact('nombre','usuario','regperiodo','regtipocredito','regmes','regempleado','regedoctaemp','regcliente','regedoctacli','regfactura','regfacturaprod','totprods'));
                }
                break;                              
         }       

    }

    public function actionCobranzaFacturas(){
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

        $regperiodo   = regPeriodosModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();
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
        $regfacturaprod=regDfacturaModel::select('FACTURA_FOLIO','DFACTURA_NPARTIDA','DESCRIPCION','CODIGO_BARRAS','PRECIO','CANTIDAD',
                        'CLIENTE_ID','EMP_ID','DFACTURA_CANTIDAD','DFACTURA_PRECIO','DFACTURA_IMPORTE','DFACTURA_IVA','DFACTURA_OTRO',
                        'DFACTURA_TOTALNETO' ,'PERIODO_ID','MES_ID','DIA_ID','CREATE_AT','UPDATE_AT',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        //->where('VENTA_ID' ,$id)
                        ->orderBy('PERIODO_ID'       ,'desc')
                        ->orderBy('FACTURA_FOLIO'    ,'desc')
                        ->orderBy('DFACTURA_NPARTIDA','desc')
                        ->get();  
        if($role->rol_name == 'user'){                                                
            $totprods = regDfacturaModel::join('CEN_VENTAS','CEN_VENTAS.FACTURA_FOLIO','=','CEN_PRODUCTOS_VENDIDOS.FACTURA_FOLIO')
                        ->select(   'CEN_VENTAS.PERIODO_ID','CEN_VENTAS.FACTURA_FOLIO')
                        ->selectRaw('COUNT(*) AS PARTIDAS')
                        ->where(    'CEN_VENTAS.LOGIN', $nombre)
                        ->groupBy(  'CEN_VENTAS.PERIODO_ID','CEN_VENTAS.FACTURA_FOLIO')
                        ->get();            
            $regfactura=regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
                        'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_MONTOSUBSIDIO','EFACTURA_MONTOAPORTACIONES',
                        'SUCURSAL_ID','MUNICIPIO_ID','ENTIDADFED_ID','CLIENTE_COL','LOCALIDAD',                        
                        'EFACTURA_NUMAPORTACIONES','EFACTURA_MONTOPAGOS','EFACTURA_FECAPORTACION1','EFACTURA_FECAPORTACION2',
                        'EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO','EFACTURA_SALDO',
                        'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('LOGIN',$nombre)
                        ->orderBy('PERIODO_ID'   ,'desc')
                        ->orderBy('FACTURA_FOLIO','desc')
                        ->paginate(30);
        }else{
            $totprods = regDfacturaModel::join('CEN_VENTAS','CEN_VENTAS.FACTURA_FOLIO','=','CEN_PRODUCTOS_VENDIDOS.FACTURA_FOLIO')
                        ->select(   'CEN_VENTAS.PERIODO_ID','CEN_VENTAS.FACTURA_FOLIO')
                        ->selectRaw('COUNT(*) AS PARTIDAS')
                        ->groupBy(  'CEN_VENTAS.PERIODO_ID','CEN_VENTAS.FACTURA_FOLIO')
                        ->get();           
            $regfactura=regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
                        'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_MONTOSUBSIDIO','EFACTURA_MONTOAPORTACIONES',
                        'SUCURSAL_ID','MUNICIPIO_ID','ENTIDADFED_ID','CLIENTE_COL','LOCALIDAD',                        
                        'EFACTURA_NUMAPORTACIONES','EFACTURA_MONTOPAGOS','EFACTURA_FECAPORTACION1','EFACTURA_FECAPORTACION2',
                        'EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO','EFACTURA_SALDO',
                        'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('PERIODO_ID'   ,'desc')
                        ->orderBy('FACTURA_FOLIO','desc')
                        ->paginate(30);           
        }                  
        if($regfactura->count() <= 0){
            toastr()->error('No existen facturas de venta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.facturas.cobranzaFacturas',compact('nombre','usuario','regperiodo','regtipocredito','regmes','regempleado','regedoctaemp','regcliente','regedoctacli','regfactura','regfacturaprod','totprods'));
    }


}
