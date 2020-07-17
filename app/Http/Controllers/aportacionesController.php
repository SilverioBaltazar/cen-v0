<?php
//**************************************************************/
//* File:       aportacionesController.php
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: diciembre 2020
//* @Derechos reservados. Ing. Silverio Baltazar Barrientos Zarate
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\aportacionesRequest;
use App\regClientesModel;
use App\regSaldosModel;

use App\regMesesModel;
use App\regPeriodosModel;
use App\regFpagoModel;
use App\regBancoModel;

use App\regEmpleadosModel;
use App\regSaldosempModel;

use App\regEfacturaModel;
use App\regAportacionesModel;
use App\regDiarioModel;
use App\regBitacoraModel;

// Exportar a excel 
//use App\Exports\ExcelExportCatIAPS;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class aportacionesController extends Controller
{

    public function actionBuscarApor(Request $request)
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

        $regfpago     = regFpagoModel::select('FPAGO_ID','FPAGO_DESC')->get();
        $regbancos    = regBancoModel::select('BANCO_ID','BANCO_DESC')->get();
        $regempleados = regEmpleadosModel::select('EMP_ID','EMP_NOMBRECOMPLETO','EMP_STATUS1')->get();
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();  
        $regclientes  = regClientesModel::select('CLIENTE_ID','CLIENTE_NOMBRECOMPLETO','CLIENTE_STATUS1')->orderBy('CLIENTE_NOMBRECOMPLETO','asc')
                        ->get();

        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//
        //**************************************************************//
        $perr    = $request->get('perr');   
        $cliee   = $request->get('cliee');  
        $empp    = $request->get('empp');  
        $mess    = $request->get('mess');    
        $regapor = regAportacionesModel::orderBy('APOR_FOLIO', 'ASC')
                   ->perr($perr)           //Metodos personalizados es equvalente a ->where('IAP_DESC', 'LIKE', "%$name%");
                   ->cliee($cliee)         //Metodos personalizados
                   ->empp($empp)           //Metodos personalizados
                   ->mess($mess)           //Metodos personalizados
                   ->paginate(30);
        if($regapor->count() <= 0){
            toastr()->error('No existen registros de aportaciones monetarias.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }       
        return view('sicinar.aportaciones.verApor',compact('nombre','usuario','role','regempleados','regapor', 'regclientes', 'regmeses','regperiodos'));
    }


    //*********** Mostrar las aportaciones ***************//
    public function actionVerApor(){
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

        $regfpago    = regFpagoModel::select('FPAGO_ID','FPAGO_DESC')->get();
        $regbancos   = regBancoModel::select('BANCO_ID','BANCO_DESC')->get();
        $regmeses    = regMesesModel::select('MES_ID', 'MES_DESC')->get();
        $regperiodos = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                       ->get();                  
        $regempleados= regEmpleadosModel::select('EMP_ID', 'EMP_NOMBRECOMPLETO','EMP_STATUS1')->get();
        $regedoctaemp= regSaldosempModel::select('PERIODO_ID','EMP_ID',
                       'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                       'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                       'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                       'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                       ->get();                        
        $regclientes = regClientesModel::select('CLIENTE_ID', 'CLIENTE_NOMBRECOMPLETO','CLIENTE_STATUS1')->orderBy('CLIENTE_NOMBRECOMPLETO','asc')
                       ->get();
        $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                       'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                       'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                       'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                       'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                       ->get();                                    
        $regfactclie = regEfacturaModel::join('CEN_CLIENTES','CEN_CLIENTES.CLIENTE_ID','=','CEN_VENTAS.CLIENTE_ID')
                       ->select( 'CEN_CLIENTES.CLIENTE_NOMBRECOMPLETO',
                                 'CEN_VENTAS.FACTURA_FOLIO','CEN_VENTAS.CLIENTE_ID','CEN_VENTAS.EMP_ID',
                                 'CEN_VENTAS.EFACTURA_MONTOSUBSIDIO','CEN_VENTAS.EFACTURA_MONTOAPORTACIONES','EFACTURA_MONTOPAGOS')
                       ->where(  'CEN_VENTAS.EFACTURA_STATUS2', "0")
                       ->orderBy('CEN_VENTAS.PERIODO_ID'   ,'desc')
                       ->orderBy('CEN_VENTAS.FACTURA_FOLIO','desc')                       
                       ->get();   
        $regfactura  = regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
                       'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_MONTOSUBSIDIO','EFACTURA_MONTOAPORTACIONES',
                       'EFACTURA_NUMAPORTACIONES','EFACTURA_MONTOPAGOS','EFACTURA_FECAPORTACION1','EFACTURA_FECAPORTACION2',
                       'EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO',
                       'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->orderBy('CEN_VENTAS.PERIODO_ID'   ,'desc')
                       ->orderBy('CEN_VENTAS.FACTURA_FOLIO','desc')                       
                       ->get();                                 
        if($role->rol_name == 'user'){                        
            $regapor= regAportacionesModel::select('PERIODO_ID','APOR_FOLIO','APOR_RECIBO','FACTURA_FOLIO','CLIENTE_ID','EMP_ID',
                                                   'FPAGO_ID','BANCO_ID','APOR_FECHA','APOR_FECHA2','APOR_FECHA3','MES_ID','DIA_ID',
                                                   'APOR_FECPROXPAGO','APOR_FECPROXPAGO2','APOR_FECPROXPAGO3','APOR_NOCHEQUE',
                                                   'APOR_CONCEPTO','APOR_IMPORTE','APOR_IVA','APOR_OTRO','APOR_TOTALNETO','FACTURA_SALDO',
                                                   'APOR_OBS1','APOR_OBS2','APOR_FOTO1','APOR_FOTO2','APOR_STATUS1','APOR_STATUS2',
                                                   'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                      ->where('LOGIN',$nombre)
                      ->orderBy('APOR_FOLIO','ASC')
                      ->paginate(30);
        }else{
            $regapor= regAportacionesModel::select('PERIODO_ID','APOR_FOLIO','APOR_RECIBO','FACTURA_FOLIO','CLIENTE_ID','EMP_ID',
                                                   'FPAGO_ID','BANCO_ID','APOR_FECHA','APOR_FECHA2','APOR_FECHA3','MES_ID','DIA_ID',
                                                   'APOR_FECPROXPAGO','APOR_FECPROXPAGO2','APOR_FECPROXPAGO3','APOR_NOCHEQUE',
                                                   'APOR_CONCEPTO','APOR_IMPORTE','APOR_IVA','APOR_OTRO','APOR_TOTALNETO','FACTURA_SALDO',
                                                   'APOR_OBS1','APOR_OBS2','APOR_FOTO1','APOR_FOTO2','APOR_STATUS1','APOR_STATUS2',
                                                   'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                      ->orderBy('APOR_FOLIO','ASC')
                      ->paginate(30);
        }          
        if($regapor->count() <= 0){
            toastr()->error('No existen registros de aportaciones monetarias.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaApor');
        }
        return view('sicinar.aportaciones.verApor',compact('nombre','usuario','role','regapor','regclientes','regmeses','regempleados','regperiodos'));
    }

    public function actionNuevaApor(){
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

        $regfpago    = regFpagoModel::select('FPAGO_ID','FPAGO_DESC')->get();
        $regbancos   = regBancoModel::select('BANCO_ID','BANCO_DESC')->orderBy('BANCO_ID','asc')->get();
        $regmeses    = regMesesModel::select('MES_ID', 'MES_DESC')->orderBy('MES_ID','asc')
                       ->get();
        $regperiodos = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                       ->get();
        $regdiario   = regDiarioModel::select('PERIODO_ID','DIARIO_ID','DIARIO_FOLIO','FACTURA_FOLIO','CLIENTE_ID','EMP_ID','DIARIO_FECHA',
                       'DIARIO_FECHA2','MES_ID','DIA_ID','DIARIO_TIPO','DIARIO_CONCEPTO','DIARIO_IMPORTE','DIARIO_IVA','DIARIO_OTRO',
                       'DIARIO_TOTALNETO','DIARIO_OBS1','DIARIO_OBS2','DIARIO_STATUS1','DIARIO_STATUS2',
                       'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->get();
        $regempleados= regEmpleadosModel::select('EMP_ID', 'EMP_NOMBRECOMPLETO','EMP_STATUS1')
                       ->orderBy('EMP_NOMBRECOMPLETO','asc')
                       ->get();
        $regedoctaemp= regSaldosempModel::select('PERIODO_ID','EMP_ID',
                       'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                       'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                       'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                       'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                       ->get();                                 
        $regclientes = regClientesModel::select('CLIENTE_ID', 'CLIENTE_NOMBRECOMPLETO','CLIENTE_STATUS1')
                       ->get();                                                        
        $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID','CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02',
                       'CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                       'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                       'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                       'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                       ->get(); 
        $regfactura  = regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
                       'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_MONTOSUBSIDIO','EFACTURA_MONTOAPORTACIONES',
                       'EFACTURA_NUMAPORTACIONES','EFACTURA_MONTOPAGOS','EFACTURA_FECAPORTACION1','EFACTURA_FECAPORTACION2',
                       'EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO','EFACTURA_SALDO',
                       'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->get();                        
        $regfactclie = regEfacturaModel::join('CEN_CLIENTES','CEN_CLIENTES.CLIENTE_ID','=','CEN_VENTAS.CLIENTE_ID')
                       ->select( 'CEN_CLIENTES.CLIENTE_NOMBRECOMPLETO',
                                 'CEN_VENTAS.FACTURA_FOLIO','CEN_VENTAS.CLIENTE_ID','CEN_VENTAS.EMP_ID',
                                 'CEN_VENTAS.EFACTURA_MONTOSUBSIDIO','CEN_VENTAS.EFACTURA_MONTOAPORTACIONES','EFACTURA_MONTOPAGOS')
                       ->where(  'CEN_VENTAS.EFACTURA_STATUS2',"0")
                       ->orderBy('CEN_VENTAS.PERIODO_ID'   ,'desc')
                       ->orderBy('CEN_VENTAS.FACTURA_FOLIO','desc')                       
                       ->get();                                              
        //if($role->rol_name == 'user'){                        

        //}else{
        //    $regclientes = regClientesModel::select('CLIENTE_ID', 'CLIENTE_NOMBRECOMPLETO','CLIENTE_STATUS1')
        //              ->where('CLIENTE_ID',$arbol_id)
        //              ->get();            
        //}                    
        $regapor    = regAportacionesModel::select('PERIODO_ID','APOR_FOLIO','APOR_RECIBO','FACTURA_FOLIO','CLIENTE_ID','EMP_ID',
                                                   'FPAGO_ID','BANCO_ID','APOR_FECHA','APOR_FECHA2','APOR_FECHA3','MES_ID','DIA_ID',
                                                   'APOR_FECPROXPAGO','APOR_FECPROXPAGO2','APOR_FECPROXPAGO3','APOR_NOCHEQUE',
                                                   'APOR_CONCEPTO','APOR_IMPORTE','APOR_IVA','APOR_OTRO','APOR_TOTALNETO','FACTURA_SALDO',
                                                   'APOR_OBS1','APOR_OBS2','APOR_FOTO1','APOR_FOTO2','APOR_STATUS1','APOR_STATUS2',
                                                   'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                      ->orderBy('APOR_FOLIO','ASC')
                      ->get();        
        //dd($unidades);
        return view('sicinar.aportaciones.nuevaApor',compact('nombre','usuario','regfpago','regbancos','regempleados','regmeses','regclientes','regapor','regperiodos','regdiario','regedoctacli','regedoctaemp','regfactclie','regfactura'));
    }

    public function actionAltaNuevaApor(Request $request){
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

        /************ Saldo de factura *************************************/               
        $bandera      = '0';
        $xsaldo       = regEfacturaModel::Obtfactsaldo($request->factura_folio);
        $regfactura   = regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID',
                        'PERIODO_ID','EFACTURA_MONTOSUBSIDIO','EFACTURA_MONTOAPORTACIONES',
                        'EFACTURA_NUMAPORTACIONES','EFACTURA_MONTOPAGOS','EFACTURA_FECAPORTACION1','EFACTURA_FECAPORTACION2',
                        'EFACTURA_IMPORTE','EFACTURA_TOTALNETO','EFACTURA_SALDO',
                        'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('FACTURA_FOLIO', $request->factura_folio)
                        ->get();
                        //dd($regfactura);
        if($regfactura->count() <= 0)              // Alta
            toastr()->success('Factura de venta.','  no existe revisar. No se registro la aportación!',['positionClass' => 'toast-bottom-right']);
        else{ 
                //***************** Obtenemos datos *************************//
                $montosubsidio =  $regfactura[0]->efactura_montosubsidio;
                $montopagos    = ($regfactura[0]->efactura_montopagos+$request->apor_importe);
                $saldo         = ($montosubsidio-$montopagos);
                //dd('subsidio:'.$montosubsidio);
                if($montosubsidio > $montopagos)
                    $bandera = '0';            // 0-por pagar
                else
                    if($montosubsidio <= $montopagos)
                        $bandera = '2';        // 2-Pagada                
                //***************** actualizar *****************************/      
                $regfactura = regEfacturaModel::where('FACTURA_FOLIO', $request->factura_folio)        
                              ->update([                
                              'EFACTURA_MONTOPAGOS'    => $regfactura->EFACTURA_MONTOPAGOS     = $montopagos,
                              'EFACTURA_SALDO'         => $regfactura->EFACTURA_SALDO          = $saldo,
                              'EFACTURA_STATUS2'       => $regfactura->EFACTURA_STATUS2        = $bandera,
                              'EFACTURA_FECAPORTACION1'=> $regfactura->EFACTURA_FECAPORTACION1 = $request->input('apor_fecproxpago'),
                              'EFACTURA_FECAPORTACION2'=> $regfactura->EFACTURA_FECAPORTACION2 = $request->input('apor_fecproxpago')
                                      ]);
                toastr()->success('Saldo de factura de venta actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);                

        /************ Registro de la aportación monetaria *****************************/ 
        //$xcliente_id   = regEfacturaModel::Obtfactcliente($request->factura_folio);
        
        $xxperiodo_id  = (int)date('Y');
        $xxmes_id      = (int)date('m');
        $xxdia_id      = (int)date('d');

        $apor_folio = regAportacionesModel::max('APOR_FOLIO');
        $apor_folio = $apor_folio+1;

        $nuevaapor = new regAportacionesModel();
        $name1 =null;
        //Comprobar  si el campo foto1 tiene un archivo asignado:
        if($request->hasFile('apor_foto1')){
           $name1 = $apor_folio.'_'.$request->file('apor_foto1')->getClientOriginalName(); 
           //$file->move(public_path().'/images/', $name1);
           //sube el archivo a la carpeta del servidor public/images/
           $request->file('apor_foto1')->move(public_path().'/images/', $name1);
        }
        $nuevaapor->APOR_FOLIO      = $apor_folio;
        $nuevaapor->PERIODO_ID      = $xxperiodo_id;
        //$nuevaapor->CLIENTE_ID      = $xcliente[0]->cliente_id;        
        $nuevaapor->CLIENTE_ID      = $request->cliente_id;        
        $nuevaapor->FACTURA_FOLIO   = $request->factura_folio;
        $nuevaapor->EMP_ID          = $request->emp_id;                
        $nuevaapor->MES_ID          = $xxmes_id;
        $nuevaapor->DIA_ID          = $xxdia_id;        
        $nuevaapor->BANCO_ID        = $request->banco_id;         
        $nuevaapor->FPAGO_ID        = $request->fpago_id;  
        $nuevaapor->APOR_CONCEPTO   = substr(trim(strtoupper($request->apor_concepto)),0,99);
        
        $nuevaapor->APOR_RECIBO     = $request->apor_recibo;        
        $nuevaapor->APOR_IMPORTE    = $request->apor_importe;        
        $nuevaapor->APOR_FECHA      = $request->input('apor_fecha');
        $nuevaapor->APOR_FECHA2     = $request->input('apor_fecha');
        $nuevaapor->APOR_FECHA3     = $request->input('apor_fecha');
        $nuevaapor->APOR_FECPROXPAGO= $request->input('apor_fecproxpago');
        $nuevaapor->APOR_FECPROXPAGO= $request->input('apor_fecproxpago');
        $nuevaapor->APOR_FECPROXPAGO= $request->input('apor_fecproxpago');        
        $nuevaapor->FACTURA_SALDO   = $saldo;
        $nuevaapor->APOR_FOTO1      = $name1;

        $nuevaapor->IP              = $ip;
        $nuevaapor->LOGIN           = $nombre;         // Usuario ;
        $nuevaapor->save();

        if($nuevaapor->save() == true){
            toastr()->success('Aportación monetaria.',' dada de alta!',['positionClass' => 'toast-bottom-right']);

            /************ Diario de movimientos *************************************/               
            $nuevodiario = new regDiarioModel();

            $nuevodiario->PERIODO_ID     = $xxperiodo_id;            
            $nuevodiario->DIARIO_ID      = $apor_folio;
            $nuevodiario->DIARIO_FOLIO   = $apor_folio;
            $nuevodiario->FACTURA_FOLIO  = $request->factura_folio;   
            $nuevodiario->CLIENTE_ID     = $request->cliente_id;        
            $nuevodiario->EMP_ID         = $request->emp_id;                
            $nuevodiario->DIARIO_FECHA   = $request->input('apor_fecha');
            $nuevodiario->DIARIO_FECHA2  = $request->input('apor_fecha');            
            $nuevodiario->MES_ID         = $xxmes_id;
            $nuevodiario->DIA_ID         = $xxdia_id;        
            $nuevodiario->DIARIO_TIPO    = 'A';        
            $nuevodiario->DIARIO_CONCEPTO= substr(trim(strtoupper($request->apor_concepto)),0,99);
            $nuevodiario->DIARIO_IMPORTE = $request->apor_importe;        

            $nuevodiario->IP             = $ip;
            $nuevodiario->LOGIN          = $nombre;         // Usuario ;
            $nuevodiario->save();

            if($nuevodiario->save() == true)
                toastr()->success('Trx de aportación monetaria registrada en el diario de movimientos.',' dada de alta!',['positionClass' => 'toast-bottom-right']);
            else
                toastr()->error('Error trxt al dar de alta en el diario de movtos.','Ups!',['positionClass' => 'toast-bottom-right']);            

            /************ Estado de cuenta del cliente *************************************/               
            $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                            'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                            'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                            'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                            'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                            ->where('CLIENTE_ID', $request->cliente_id)
                            ->get();
            if($regedoctacli->count() <= 0){              // Alta
                //$nuevoedocta = new regEdoctacliModel();              
                //$nuevodocta->LOGIN          = $nombre;         // Usuario ;
                //$nuevodocta->save();
                //if($nuevodocta->save() == true){
                    //toastr()->success('Estado de cuenta del cleinte registrado.',' dada de alta!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** obtenemos datos del estado de cta. *****************************
                //*********** actualiza el abono *****************************
                switch ($xxmes_id) {
                case 1:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update([
                                              'ABONO_M01' => $regedoctacli->ABONO_M01 =($regedoctacli[0]->ABONO_M01 + $request->apor_importe),

                                              'IP_M'      => $regedoctacli->IP      = $ip,
                                              'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 2:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['ABONO_M02' => $regedoctacli->ABONO_M02 =($regedoctacli[0]->ABONO_M02 + $request->apor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 3:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['ABONO_M03' => $regedoctacli->ABONO_M03 =($regedoctacli[0]->ABONO_M03 + $request->apor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 4:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['ABONO_M04' => $regedoctacli->ABONO_M04 =($regedoctacli[0]->ABONO_M04 + $request->apor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break; 
                case 5:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['ABONO_M05' => $regedoctacli->ABONO_M05 = ($regedoctacli[0]->ABONO_M05 + $request->apor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                                        
                case 6:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['ABONO_M06' => $regedoctacli->ABONO_M06 = ($regedoctacli[0]->ABONO_M06 + $request->apor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                     
                case 7:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['ABONO_M07' => $regedoctacli->ABONO_M07 =($regedoctacli[0]->ABONO_M07 + $request->apor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 8:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['ABONO_M08' => $regedoctacli->ABONO_M08 =($regedoctacli[0]->ABONO_M08 + $request->apor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 9:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['ABONO_M09' => $regedoctacli->ABONO_M09 =($regedoctacli[0]->ABONO_M09 + $request->apor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 10:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['ABONO_M10' => $regedoctacli->ABONO_M10 =($regedoctacli[0]->ABONO_M10 + $request->apor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break; 
                case 11:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['ABONO_M11' => $regedoctacli->ABONO_M11 =($regedoctacli[0]->ABONO_M11 + $request->apor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                                        
                case 12:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $request->cliente_id)
                                    ->update(['ABONO_M12' => $regedoctacli->ABONO_M12 =($regedoctacli[0]->ABONO_M12 + $request->apor_importe),

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
                switch ($xxmes_id) {
                case 1:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['CARGO_M01' => $regedoctaemp->CARGO =($regedoctaemp[0]->CARGO_M01+$request->apor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 2:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['CARGO_M02' => $regedoctaemp->CARGO_M02 =($regedoctaemp[0]->CARGO_M02+$request->apor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 3:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['CARGO_M03' => $regedoctaemp->CARGO_M03 =($regedoctaemp[0]->CARGO_M03+$request->apor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 4:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['CARGO_M04' => $regedoctaemp->CARGO_M04 =($regedoctaemp[0]->CARGO_M04+$request->apor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break; 
                case 5:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['CARGO_M05' => $regedoctaemp->CARGO_M05 = ($regedoctaemp[0]->CARGO_M05+$request->apor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                                        
                case 6:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['CARGO_M06' => $regedoctaemp->CARGO_M06 = ($regedoctaemp[0]->CARGO_M06+$request->apor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                     
                case 7:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['CARGO_M07' => $regedoctaemp->CARGO_M07 =($regedoctaemp[0]->CARGO_M07+$request->apor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 8:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update(['CARGO_M08' => $regedoctaemp->CARGO_M08 =($regedoctaemp[0]->CARGO_M08+$request->apor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 9:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update([
                                              'CARGO_M09' => $regedoctaemp->CARGO_M09 =($regedoctaemp[0]->CARGO_M09+$request->apor_importe),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 10:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update([
                                              'CARGO_M10' => $regedoctaemp->CARGO_M10 =($regedoctaemp[0]->CARGO_M10+$request->apor_importe),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break; 
                case 11:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update([
                                              'CARGO_M11' => $regedoctaemp->CARGO_M11 =($regedoctaemp[0]->CARGO_M11+$request->apor_importe),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                              ]);
                    break;                                        
                case 12:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $request->emp_id)
                                    ->update([
                                              'CARGO_M12' => $regedoctaemp->CARGO_M12 =($regedoctaemp[0]->CARGO_M12+$request->apor_importe),

                                              'IP_M'      => $regedoctaemp->IP       = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M    = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;                 
                }
                toastr()->success('Estado de cuenta del empleado actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Estado de cuenta del empleado termina *************************************/                                    /*

            //*********** Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         5;
            $xfuncion_id  =      5001;
            $xtrx_id      =        53;    //Registro de aportaciones monetarias
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID','FUNCION_ID', 'TRX_ID', 
                                                    'FOLIO','NO_VECES','FECHA_REG','IP', 'LOGIN', 'FECHA_M','IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $apor_folio])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $apor_folio;     // Folio    
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
                                                      'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO' => $apor_folio])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,
                                        'TRX_ID' => $xtrx_id,'FOLIO' => $apor_folio])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/             
            //return redirect()->route('nuevaIap');
            //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','rango','preguntas','apartados'));
        }else{
            toastr()->error('Error al registrar la aportación monetaria. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }

        }   //********************** Terminar validar factura de venta ************************************//        

        return redirect()->route('verApor');
    }

    
    /****************** Editar registro de aportación monetaria **********/
    public function actionEditarApor($id){
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

        $regfpago    = regFpagoModel::select('FPAGO_ID','FPAGO_DESC')->get();
        $regbancos   = regBancoModel::select('BANCO_ID','BANCO_DESC')->get();
        $regmeses    = regMesesModel::select('MES_ID', 'MES_DESC')->orderBy('MES_ID','asc')
                       ->get();
        $regperiodos = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                       ->get();        
        $regdiario   = regDiarioModel::select('PERIODO_ID','DIARIO_ID','DIARIO_FOLIO','FACTURA_FOLIO','CLIENTE_ID','EMP_ID','DIARIO_FECHA',
                       'DIARIO_FECHA2','MES_ID','DIA_ID','DIARIO_TIPO','DIARIO_CONCEPTO','DIARIO_IMPORTE','DIARIO_IVA','DIARIO_OTRO',
                       'DIARIO_TOTALNETO','DIARIO_OBS1','DIARIO_OBS2','DIARIO_STATUS1','DIARIO_STATUS2',
                       'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->get();
        $regempleados= regEmpleadosModel::select('EMP_ID', 'EMP_NOMBRECOMPLETO','EMP_STATUS1')
                       ->orderBy('EMP_NOMBRECOMPLETO','asc')
                       ->get();
        $regedoctaemp= regSaldosempModel::select('PERIODO_ID','EMP_ID',
                       'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                       'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                       'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                       'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                       ->get();                                 
        $regclientes = regClientesModel::select('CLIENTE_ID', 'CLIENTE_NOMBRECOMPLETO','CLIENTE_STATUS1')
                       ->get();                                                        
        $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID','CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02',
                       'CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                       'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                       'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                       'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                       ->get(); 
        $regfactclie = regEfacturaModel::join('CEN_CLIENTES','CEN_CLIENTES.CLIENTE_ID','=','CEN_VENTAS.CLIENTE_ID')
                       ->select( 'CEN_CLIENTES.CLIENTE_NOMBRECOMPLETO',
                                 'CEN_VENTAS.FACTURA_FOLIO','CEN_VENTAS.CLIENTE_ID','CEN_VENTAS.EMP_ID',
                                 'CEN_VENTAS.EFACTURA_MONTOSUBSIDIO','CEN_VENTAS.EFACTURA_MONTOAPORTACIONES','EFACTURA_MONTOPAGOS')
                       ->where(  'CEN_VENTAS.EFACTURA_STATUS2',"0")
                       ->orderBy('CEN_VENTAS.PERIODO_ID'   ,'desc')
                       ->orderBy('CEN_VENTAS.FACTURA_FOLIO','desc')                       
                       ->get();                                              
        //if($role->rol_name == 'user'){                                

        //}else{
        //    $regclientes = regClientesModel::select('CLIENTE_ID', 'CLIENTE_NOMBRECOMPLETO','CLIENTE_STATUS1')
        //              ->where('CLIENTE_ID',$arbol_id)
        //              ->get();            
        //}                    
        $regapor    = regAportacionesModel::select('PERIODO_ID','APOR_FOLIO','APOR_RECIBO','FACTURA_FOLIO','CLIENTE_ID','EMP_ID',
                                                   'FPAGO_ID','BANCO_ID','APOR_FECHA','APOR_FECHA2','APOR_FECHA3','MES_ID','DIA_ID',
                                                   'APOR_FECPROXPAGO','APOR_FECPROXPAGO2','APOR_FECPROXPAGO3','APOR_NOCHEQUE',
                                                   'APOR_CONCEPTO','APOR_IMPORTE','APOR_IVA','APOR_OTRO','APOR_TOTALNETO','FACTURA_SALDO',
                                                   'APOR_OBS1','APOR_OBS2','APOR_FOTO1','APOR_FOTO2','APOR_STATUS1','APOR_STATUS2',
                                                   'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                      ->where('APOR_FOLIO',$id)
                      ->first();
        if($regapor->count() <= 0){
            toastr()->error('No existe registro de aportación monetaria.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaApor');
        }
        return view('sicinar.aportaciones.editarApor',compact('nombre','usuario','regfpago','regbancos','regclientes','regempleados','regmeses','regperiodos', 'regapor','regdiario','regedoctaemp','regedoctacli','regfactclie'));
    }

    public function actionActualizarApor(aportacionesRequest $request, $id){
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
            $regapor = regAportacionesModel::where('APOR_FOLIO',$id)        
                       ->update([                
                          'PERIODO_ID'    => $request->periodo_id,                
                          'CLIENTE_ID'    => $request->cliente_id,                  
                          'FACTURA_FOLIO' => $request->factura_folio,
                          'MES_ID'        => $request->mes_id,
                          'DIA_ID'        => $request->dia_id,
                          'EMP_ID'        => $request->emp_id,                
                          'APOR_CONCEPTO' => substr(trim(strtoupper($request->apor_concepto)),0,99),
                          'APOR_IMPORTE'  => $request->apor_importe,
                          'APOR_NOCHEQUE' => substr(trim(strtoupper($request->apor_nocheque)),0,79),
                          //'APOR_ENTREGA'  => strtoupper($request->apor_entrega),
                          //'APOR_RECIBE'   => strtoupper($request->apor_recibe),                

                          'IP_M'          => $ip,
                          'LOGIN_M'       => $nombre,
                          'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                ]);
            toastr()->success('Aportación monetaria actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         5;
            $xfuncion_id  =      5001;
            $xtrx_id      =        54;    //Actualizar aportacion monetaria        
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID','PROCESO_ID','FUNCION_ID','TRX_ID','FOLIO', 
                           'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M','IP_M', 'LOGIN_M')
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
                $xno_veces   = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->max('NO_VECES');
                $xno_veces   = $xno_veces+1;                        
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

        return redirect()->route('verApor');
        //return view('sicinar.catalogos.verProceso',compact('nombre','usuario','regproceso'));
    }


    public function actionBorrarApor($id){
        //dd($request->all());
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

        /************ Cancela movimiento de aportación monetaria **************************************/
        //$regdiario   = regDiarioModel::select('PERIODO_ID','DIARIO_ID','DIARIO_FOLIO','FACTURA_FOLIO','CLIENTE_ID','EMP_ID','DIARIO_FECHA',
        //               'DIARIO_FECHA2','MES_ID','DIA_ID','DIARIO_TIPO','DIARIO_CONCEPTO','DIARIO_IMPORTE','DIARIO_IVA','DIARIO_OTRO',
        //               'DIARIO_TOTALNETO','DIARIO_OBS1','DIARIO_OBS2','DIARIO_STATUS1','DIARIO_STATUS2',
        //               'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
        //               ->get();
        //$regempleados= regEmpleadosModel::select('EMP_ID', 'EMP_NOMBRECOMPLETO','EMP_STATUS1')
        //               ->orderBy('EMP_NOMBRECOMPLETO','asc')
        //               ->get();
        //$regedoctaemp= regSaldosempModel::select('PERIODO_ID','EMP_ID',
        //               'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
        //               'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
        //               'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
        //               'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
        //               ->get();                                 
        //$regclientes = regClientesModel::select('CLIENTE_ID', 'CLIENTE_NOMBRECOMPLETO','CLIENTE_STATUS1')
        //               ->get();                                                        
        //$regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID','CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02',
        //               'CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
        //               'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
        //               'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
        //               'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
        //               ->get(); 
        //$regfactura  = regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
        //               'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_MONTOSUBSIDIO','EFACTURA_MONTOAPORTACIONES',
        //               'EFACTURA_NUMAPORTACIONES','EFACTURA_MONTOPAGOS','EFACTURA_FECAPORTACION1','EFACTURA_FECAPORTACION2',
        //               'EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO','EFACTURA_SALDO',
        //               'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
        //               ->get();                                               
        $regapor     = regAportacionesModel::select('PERIODO_ID','APOR_FOLIO','FACTURA_FOLIO','MES_ID','CLIENTE_ID','EMP_ID','APOR_IMPORTE')
                       ->where('APOR_FOLIO',$id)
                       ->get();
              //dd($regapor );
        if($regapor->count() <= 0)
            toastr()->error('No existe folio de aportacion monetaria.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //**************** Elimina aportación *******************/
            //$regapor->delete();
            //toastr()->success('Aportación monetaria eliminada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            //$yperiodo_id  = regAportacionesModel::select('PERIODO_ID')->where('APOR_FOLIO',$id)->get();
            $yperiodo_id   = $regapor[0]->periodo_id;
            $ymes_id       = $regapor[0]->mes_id;
            $ycliente_id   = $regapor[0]->cliente_id;
            $yemp_id       = $regapor[0]->emp_id;
            $yapor_importe = $regapor[0]->apor_importe;
            $yfactura_folio= $regapor[0]->factura_folio;
            //$ymes_id      = regAportacionesModel::select('MES_ID')->where('APOR_FOLIO',$id)->get();                        
            //$ycliente_id  = regAportacionesModel::select('CLIENTE_ID')->where('APOR_FOLIO',$id)->get();                                 
            //$yapor_importe= regAportacionesModel::select('APOR_IMPORTE')->where('APOR_FOLIO',$id)->get();
            //***************** actualizar *****************************/      
            $regapor = regAportacionesModel::where('APOR_FOLIO',$id)        
                       ->update([                
                                 'APOR_STATUS1' => $regapor->APOR_TATUS1  = 'N',  //N Aportación cancelada
                                 'APOR_STATUS2' => $regapor->APOR_STATUS2 = '1',  //0-pendiente, 1-cancelada, 2 pagada
                                 'APOR_IMPORTE' => $regapor->APOR_IMPORTE = 0,    //N Aportación cancelada

                                 'IP_M'         => $regapor->IP_M         = $ip,
                                 'LOGIN_M'      => $regapor->LOGIN_M      = $nombre,
                                 'FECHA_M'      => $regapor->FECHA_M      = date('Y/m/d')    //date('d/m/Y')                                
                                ]);
            toastr()->success('Aportación monetaria cancelada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Saldo de factura *************************************/               
            $regfactura=regEfacturaModel::select('FACTURA_FOLIO','CLIENTE_ID','EMP_ID','TIPOCREDITO_ID','TIPOCREDITO_DIAS',
                        'PERIODO_ID','MES_ID','DIA_ID','EFACTURA_MONTOSUBSIDIO','EFACTURA_MONTOAPORTACIONES',
                        'EFACTURA_NUMAPORTACIONES','EFACTURA_MONTOPAGOS','EFACTURA_FECAPORTACION1','EFACTURA_FECAPORTACION2',
                        'EFACTURA_IMPORTE','EFACTURA_IVA','EFACTURA_OTRO','EFACTURA_TOTALNETO','EFACTURA_SALDO',
                        'EFACTURA_STATUS1','EFACTURA_STATUS2','CREATE_AT','UPDATE_AT','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('FACTURA_FOLIO', $yfactura_folio)
                        ->get();
            if($regfactura->count() <= 0)              // Alta
                toastr()->success('Factura de venta.',' No existe!',['positionClass' => 'toast-bottom-right']);
            else{ 
                //***************** Obtenemos datos *************************//
                $montosubsidio =  $regfactura[0]->efactura_montosubsidio;
                $montopagos    = ($regfactura[0]->efactura_montopagos+$request->apor_importe);
                $saldo         = ($montosubsidio-$montopagos);
                $bandera       = '0';
                if($montosubsidio > $montopagos)
                    $bandera = '0';            // 0-por pagar
                else
                    if($montosubsidio <= $montopagos)
                        $bandera = '2';        // 2-Pagada                
                //***************** actualizar *****************************//    
                $regfactura = regEfacturaModel::where('FACTURA_FOLIO', $yfactura_folio)        
                              ->update([                
                              'EFACTURA_MONTOPAGOS'=> $regfactura->EFACTURA_MONTOPAGOS = $montopagos,
                              'EFACTURA_SALDO'     => $regfactura->EFACTURA_SALDO      = $saldo,
                              'EFACTURA_STATUS2'   => $regfactura->EFACTURA_STATUS2    = $bandera  
                                       ]);
                toastr()->success('Saldo de factura de venta actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);                
            }   //********************** Terminar actualizar facturas ************************************//

            /************ Diario de movimientos *************************************/               
            $regdiario  = regDiarioModel::select('PERIODO_ID','DIARIO_ID','DIARIO_FOLIO',
                          'MES_ID','DIA_ID','DIARIO_TIPO','DIARIO_IMPORTE','DIARIO_IVA','DIARIO_OTRO','DIARIO_TOTALNETO',
                          'DIARIO_STATUS1','DIARIO_STATUS2','FECHA_M','IP_M','LOGIN_M')
                          //->where(['PERIODO_ID' => $yperiodo_id,'DIARIO_ID' => $id])
                          ->where(['PERIODO_ID' => $yperiodo_id,'DIARIO_ID' => $id])
                          ->get();
                          //dd($regdiario, $yperiodo_id, $ymes_id,$yapor_importe, $id );
            if($regdiario->count() <= 0){             
                toastr()->success('Aportación monetaria no existe en diario de movimientos.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{                                 
                //***************** actualizar *****************************/      
               // $regdiario = regDiarioModel::where(['PERIODO_ID' => $yperiodo_id,'DIARIO_ID' => $id])        
               $regdiario = regDiarioModel::where(['PERIODO_ID' => $yperiodo_id,'DIARIO_ID' => $id])        
                            ->update([                
                                       'DIARIO_IMPORTE' => $regdiario->DIARIO_IMPORTE = 0,  
                                       'DIARIO_STATUS1' => $regdiario->DIARIO_STATUS1 = 'N',    //N Aportación cancelada
                                       'DIARIO_STATUS2' => $regdiario->DIARIO_STATUS2 = '1',    //0-POR PAGAR, 1-Cancelada, 2-Pagado

                                       'IP_M'           => $regdiario->IP_M           = $ip,
                                       'LOGIN_M'        => $regdiario->LOGIN_M        = $nombre,
                                       'FECHA_M'        => $regdiario->FECHA_M        = date('Y/m/d')  //date('d/m/Y')                                
                                      ]);
                toastr()->success('Aportación monetaria cancelada en el diario de movimientos.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   // ******** Termina de actualizar diario **************//

            /************ Estado de cuenta del cliente *************************************/               
            $regedoctacli = regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                            'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                            'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                            'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                            'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                            ->where('CLIENTE_ID', $ycliente_id)
                            ->get();
            if($regedoctacli->count() <= 0){              // Alta
                //$nuevoedocta = new regEdoctacliModel();              
                //$nuevoedocta->PERIODO_ID     = $xxperiodo_id;            
                //$nuevodocta->IP             = $ip;
                //$nuevodocta->LOGIN          = $nombre;         // Usuario ;
                //$nuevodocta->save();

                //if($nuevodocta->save() == true){
                    //toastr()->success('Estado de cuenta del cleinte registrado.',' dada de alta!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** obtenemos datos del estado de cta. *****************************
                //*********** actualiza el abono *****************************
                switch ($ymes_id) {
                case 1:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $ycliente_id)
                                    ->update(['ABONO_M01' => $regedoctacli->ABONO_M01 = ($regedoctacli[0]->ABONO_M01 - $yapor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 2:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $ycliente_id)
                                    ->update(['ABONO_M02' => $regedoctacli->ABONO_M02 = ($regedoctacli[0]->ABONO_M02 - $yapor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 3:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $ycliente_id)
                                    ->update(['ABONO_M03' => $regedoctacli->ABONO_M03 = ($regedoctacli[0]->ABONO_M03 - $yapor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 4:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $ycliente_id)
                                    ->update(['ABONO_M04' => $regedoctacli->ABONO_M04 = ($regedoctacli[0]->ABONO_M04 - $yapor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break; 
                case 5:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $ycliente_id)
                                    ->update(['ABONO_M05' => $regedoctacli->ABONO_M05 = ($regedoctacli[0]->ABONO_M05 - $yapor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                                        
                case 6:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $ycliente_id)
                                    ->update(['ABONO_M06' => $regedoctacli->ABONO_M06 = ($regedoctacli[0]->ABONO_M06 - $yapor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                     
                case 7:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $ycliente_id)
                                    ->update(['ABONO_M07' => $regedoctacli->ABONO_M07 = ($regedoctacli[0]->ABONO_M07 - $yapor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 8:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $ycliente_id)
                                    ->update(['ABONO_M08' => $regedoctacli->ABONO_M08 = ($regedoctacli[0]->ABONO_M08 - $yapor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 9:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $ycliente_id)
                                    ->update(['ABONO_M09' => $regedoctacli->ABONO_M09 = ($regedoctacli[0]->ABONO_M09 - $yapor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 10:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $ycliente_id)
                                    ->update(['ABONO_M10' => $regedoctacli->ABONO_M10 = ($regedoctacli[0]->ABONO_M10 - $yapor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break; 
                case 11:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $ycliente_id)
                                    ->update(['ABONO_M11' => $regedoctacli->ABONO_M11 = ($regedoctacli[0]->ABONO_M11 - $yapor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                                        
                case 12:
                    $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID',
                                    'ABONO_M01','ABONO_M02','ABONO_M03','ABONO_M04','ABONO_M05','ABONO_M06',
                                    'ABONO_M07','ABONO_M08','ABONO_M09','ABONO_M10','ABONO_M11','ABONO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('CLIENTE_ID', $ycliente_id)
                                    ->update(['ABONO_M12' => $regedoctacli->ABONO_M12 = ($regedoctacli[0]->ABONO_M12 - $yapor_importe),

                                          'IP_M'      => $regedoctacli->IP      = $ip,
                                          'USU_M'     => $regedoctacli->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctacli->FECHA_M = date('Y/m/d')  //date('d/m/Y')
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
                            ->where('EMP_ID', $yemp_id)
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
                switch ($ymes_id) {
                case 1:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $yemp_id)
                                    ->update(['CARGO_M01' => $regedoctaemp->CARGO =($regedoctaemp[0]->CARGO_M01-$yapor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 2:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $yemp_id)
                                    ->update(['CARGO_M02' => $regedoctaemp->CARGO_M02 =($regedoctaemp[0]->CARGO_M02-$yapor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 3:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $yemp_id)
                                    ->update(['CARGO_M03' => $regedoctaemp->CARGO_M03 =($regedoctaemp[0]->CARGO_M03-$yapor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 4:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $yemp_id)
                                    ->update(['CARGO_M04' => $regedoctaemp->CARGO_M04 =($regedoctaemp[0]->CARGO_M04-$yapor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break; 
                case 5:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $yemp_id)
                                    ->update(['CARGO_M05' => $regedoctaemp->CARGO_M05 = ($regedoctaemp[0]->CARGO_M05-$yapor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                                        
                case 6:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $yemp_id)
                                    ->update(['CARGO_M06' => $regedoctaemp->CARGO_M06 = ($regedoctaemp[0]->CARGO_M06-$yapor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;                     
                case 7:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $yemp_id)
                                    ->update(['CARGO_M07' => $regedoctaemp->CARGO_M07 =($regedoctaemp[0]->CARGO_M07-$yapor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 8:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $yemp_id)
                                    ->update(['CARGO_M08' => $regedoctaemp->CARGO_M08 =($regedoctaemp[0]->CARGO_M08-$yapor_importe),

                                          'IP_M'      => $regedoctaemp->IP      = $ip,
                                          'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                          'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    break;
                case 9:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $yemp_id)
                                    ->update([
                                              'CARGO_M09' => $regedoctaemp->CARGO_M09 =($regedoctaemp[0]->CARGO_M09-$yapor_importe),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;
                case 10:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $yemp_id)
                                    ->update([
                                              'CARGO_M10' => $regedoctaemp->CARGO_M10 =($regedoctaemp[0]->CARGO_M10-$yapor_importe),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break; 
                case 11:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $yemp_id)
                                    ->update([
                                              'CARGO_M11' => $regedoctaemp->CARGO_M11 =($regedoctaemp[0]->CARGO_M11-$yapor_importe),

                                              'IP_M'      => $regedoctaemp->IP      = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M   = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M = date('Y/m/d')  //date('d/m/Y')
                                              ]);
                    break;                                        
                case 12:
                    $regedoctaemp = regSaldosempModel::select('PERIODO_ID','EMP_ID',
                                    'CARGO_M01','CARGO_M02','CARGO_M03','CARGO_M04','CARGO_M05','CARGO_M06',
                                    'CARGO_M07','CARGO_M08','CARGO_M09','CARGO_M10','CARGO_M11','CARGO_M12',
                                    'FECHA_M','USU_M','IP_M')
                                    ->where('EMP_ID', $yemp_id)
                                    ->update([
                                              'CARGO_M12' => $regedoctaemp->CARGO_M12 =($regedoctaemp[0]->CARGO_M12-$yapor_importe),

                                              'IP_M'      => $regedoctaemp->IP       = $ip,
                                              'USU_M'     => $regedoctaemp->USU_M    = $nombre,
                                              'FECHA_M'   => $regedoctaemp->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    break;                 
                }
                toastr()->success('Estado de cuenta del empleado actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Estado de cuenta del empleado termina *************************************/                                    /*

            //echo 'Ya entre aboorar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         5;
            $xfuncion_id  =      5001;
            $xtrx_id      =        55;     // Cancelación de la aportacion monetaria
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                           'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 
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
                    toastr()->success('Bitacora dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error inesperado al dar de alta en la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
        }       /************ Termina de eliminar aportación monetaria *************/        
        return redirect()->route('verApor');
    }    

public function actionCobranzaxmes(){
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

        $regfpago    = regFpagoModel::select('FPAGO_ID','FPAGO_DESC')->get();
        $regbancos   = regBancoModel::select('BANCO_ID','BANCO_DESC')->get();
        $regmeses    = regMesesModel::select('MES_ID', 'MES_DESC')->orderBy('MES_ID','asc')
                       ->get();
        $regperiodo  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                       ->get();        
        $regdiario   = regDiarioModel::select('PERIODO_ID','DIARIO_ID','DIARIO_FOLIO','FACTURA_FOLIO','CLIENTE_ID','EMP_ID','DIARIO_FECHA',
                       'DIARIO_FECHA2','MES_ID','DIA_ID','DIARIO_TIPO','DIARIO_CONCEPTO','DIARIO_IMPORTE','DIARIO_IVA','DIARIO_OTRO',
                       'DIARIO_TOTALNETO','DIARIO_OBS1','DIARIO_OBS2','DIARIO_STATUS1','DIARIO_STATUS2',
                       'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->get();
        $regempleados= regEmpleadosModel::select('EMP_ID', 'EMP_NOMBRECOMPLETO','EMP_STATUS1')
                       ->orderBy('EMP_NOMBRECOMPLETO','asc')
                       ->get();
        $regedoctaemp= regSaldosempModel::select('PERIODO_ID','EMP_ID',
                       'CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02','CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                       'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                       'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                       'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                       ->get();                                 
        $regclientes = regClientesModel::select('CLIENTE_ID', 'CLIENTE_NOMBRECOMPLETO','CLIENTE_STATUS1')
                       ->get();                                                        
        $regedoctacli= regSaldosModel::select('PERIODO_ID','CLIENTE_ID','CARGO_M01','ABONO_M01','CARGO_M02','ABONO_M02',
                       'CARGO_M03','ABONO_M03','CARGO_M04','ABONO_M04','CARGO_M05','ABONO_M05',
                       'CARGO_M06','ABONO_M06','CARGO_M07','ABONO_M07','CARGO_M08','ABONO_M08','CARGO_M09','ABONO_M09','CARGO_M10','ABONO_M10',
                       'CARGO_M11','ABONO_M11','CARGO_M12','ABONO_M12','SALDO','STATUS_1','STATUS_2',
                       'FECREG','USU','IP','FECHA_M','USU_M','IP_M')
                       ->get(); 
        $regapor    = regAportacionesModel::select('PERIODO_ID','APOR_FOLIO','APOR_RECIBO','FACTURA_FOLIO','CLIENTE_ID','EMP_ID',
                                                   'FPAGO_ID','BANCO_ID','APOR_FECHA','APOR_FECHA2','APOR_FECHA3','MES_ID','DIA_ID',
                                                   'APOR_FECPROXPAGO','APOR_FECPROXPAGO2','APOR_FECPROXPAGO3','APOR_NOCHEQUE',
                                                   'APOR_CONCEPTO','APOR_IMPORTE','APOR_IVA','APOR_OTRO','APOR_TOTALNETO','FACTURA_SALDO',
                                                   'APOR_OBS1','APOR_OBS2','APOR_FOTO1','APOR_FOTO2','APOR_STATUS1','APOR_STATUS2',
                                                   'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                      ->get();
        if($regapor->count() <= 0){
            toastr()->error('No existen aportaciones monetarias.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.numeralia.cobranzaxmes',compact('nombre','usuario','regfpago','regmeses','regempleado','regperiodo','regedoctaemp','regcliente','regedoctacli','regapor'));

    }

    //**************** Estadistica de cobranza x mes *************************
    public function actionGraficacobranzaxmes(Request $request){
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
        $totcobrosxmes=regAportacionesModel::join('CEN_CAT_MESES','CEN_CAT_MESES.MES_ID','=','CEN_APORTACIONES.MES_ID')
                       ->selectRaw('SUM(APOR_IMPORTE) AS TOTALCOBRANZAXMES, COUNT(*) AS TOTALCOBROSXMES')
                       ->get();                        
        $regapor      = regAportacionesModel::join('CEN_CAT_MESES','CEN_CAT_MESES.MES_ID','=','CEN_APORTACIONES.MES_ID')
                       ->selectRaw('CEN_APORTACIONES.MES_ID,  CEN_CAT_MESES.MES_DESC AS MES, SUM(APOR_IMPORTE) AS TOTCOBRANZA, COUNT(*) AS TOTCOBROS')
                       ->where(  'PERIODO_ID',$request->periodo_id)
                       ->groupBy('CEN_APORTACIONES.MES_ID','CEN_CAT_MESES.MES_DESC')
                       ->orderBy('CEN_APORTACIONES.MES_ID','asc')
                       ->get();                       
        //dd('Valor:'.$request->periodo_id,$regprogdil);
        return view('sicinar.numeralia.graficacobranzaxmes',compact('nombre','usuario','role','regperiodo','regapor','totcobrosxmes'));
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



}
