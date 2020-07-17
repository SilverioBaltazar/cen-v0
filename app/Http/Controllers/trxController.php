<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\trxRequest;
use App\regTrxModel;
use App\regBitacoraModel;

// Exportar a excel 
use App\Exports\ExcelExportCatTrx;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;

class trxController extends Controller
{
    public function actionNuevaTrx(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');

        $regtrx = regTrxModel::select('TRX_ID','TRX_DESC', 'TRX_STATUS','TRX_FECREG')
            ->orderBy('TRX_ID','asc')->get();
        //dd($unidades);
        return view('sicinar.trx.nuevaTrx',compact('regtrx','nombre','usuario','estructura','id_estructura'));
    }

    public function actionAltaNuevaTrx(Request $request){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        //$plan = progtrabModel::select('STATUS_1')
        //    ->where('N_PERIODO',date('Y'))
        //    ->where('ESTRUCGOB_ID','like',$request->estructura.'%')
        //    ->where('CVE_DEPENDENCIA','like',$request->unidad.'%')
        //    ->get();
        //if($plan->count() > 0){
        //    toastr()->error('El Plan de Trabajo para esta Unidad Administrativa ya ha sido creado.','Plan de Trabajo Duplicado!',['positionClass' => 'toast-bottom-right']);
        //    return back();
        //}
        $trx_id = regTrxModel::max('TRX_ID');
        $trx_id = $trx_id+1;
        /* ALTA DEl proceso ****************************/
        $nuevaActividad = new regTrxModel();
        $nuevaActividad->TRX_ID   = $trx_id;
        $nuevaActividad->TRX_DESC = strtoupper($request->trx_desc);
        $nuevaActividad->save();

        if($nuevaActividad->save() == true){
            toastr()->success('La trx dada de alta.','Actividad dada de alta!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoProceso');
            //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','estructura','id_estructura','rango','preguntas','apartados'));
        }else{
            toastr()->error('Error inesperado al dar de alta la trx. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //return back();
            //return redirect()->route('nuevoProceso');
        }
        return redirect()->route('verTrx');
    }

    
    public function actionVerTrx(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');

        $regtrx = regTrxModel::select('TRX_ID','TRX_DESC', 'TRX_STATUS','TRX_FECREG')
            ->orderBy('TRX_ID','ASC')
            ->paginate(15);
        if($regtrx->count() <= 0){
            toastr()->error('No existen registro de trx.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaTrx');
        }
        return view('sicinar.trx.verTrx',compact('nombre','usuario','regtrx'));

    }

    public function actionEditarTrx($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');

        $regtrx = regTrxModel::select('TRX_ID','TRX_DESC','TRX_STATUS','TRX_FECREG')
            ->where('TRX_ID',$id)
            ->orderBy('TRX_ID','ASC')
            ->first();
        if($regtrx->count() <= 0){
            toastr()->error('No existe registros de trx.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevoProceso');
        }
        return view('sicinar.trx.editarTrx',compact('nombre','usuario','regtrx'));

    }

    public function actionActualizarTrx(trxRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');

        $regtrx = regTrxModel::where('TRX_ID',$id);
        if($regtrx->count() <= 0)
            toastr()->error('No existe trx.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regtrx = regTrxModel::where('TRX_ID',$id)        
            ->update([
                'TRX_DESC' => strtoupper($request->trx_desc),
                'TRX_STATUS' => $request->trx_status
            ]);
            toastr()->success('Trx actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verTrx');
        //return view('sicinar.trx.verProceso',compact('nombre','usuario','estructura','id_estructura','regproceso'));

    }

public function actionBorrarTrx($id){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        //echo 'Ya entre aboorar registro..........';

        $regtrx = regTrxModel::select('TRX_ID','TRX_DESC', 'TRX_STATUS','TRX_FECREG')->where('TRX_ID',$id);
        //                     ->find('TRX_ID',$id);
        if($regtrx->count() <= 0)
            toastr()->error('No existe trx.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regtrx->delete();
            toastr()->success('trx ha sido eliminada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verTrx');
    }    

    // exportar a formato catalogo de trx a formato excel
    public function exportCatTrxExcel(){
        return Excel::download(new ExcelExportCatTrx, 'Cat_trx_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato catalogo de actividades a formato PDF
    public function exportCatTrxPdf(){
        set_time_limit(0);
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 0);

        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');

        $regtrx = regTrxModel::select('TRX_ID','TRX_DESC','TRX_STATUS','TRX_FECREG')
            ->orderBy('TRX_ID','ASC')->get();
        if($regtrx->count() <= 0){
            toastr()->error('No existen registros en el catalogo de actividades.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verTrx');
        }
        $pdf = PDF::loadView('sicinar.pdf.cattrxPDF', compact('nombre','usuario','regtrx'));
        //******** Horizontal ***************
        //$pdf->setPaper('A4', 'landscape');      
        //$pdf->set('defaultFont', 'Courier');          
        //$pdf->setPaper('A4','portrait');
        // Output the generated PDF to Browser
        //******** vertical *************** 
        //El tamaño de hoja se especifica en page_size puede ser letter, legal, A4, etc.         
        $pdf->setPaper('letter','portrait');      
        return $pdf->stream('CatalogoDeTrx');
    }


    // Gráfica demanda de transacciones (Bitacora)
    public function Bitacora(){
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

        // http://www.chartjs.org/docs/#bar-chart
        $regbitatxmes=regBitacoraModel::join('CEN_CAT_PROCESOS','CEN_CAT_PROCESOS.PROCESO_ID'  ,'=','CEN_BITACORA.PROCESO_ID')
                                      ->join('CEN_CAT_FUNCIONES','CEN_CAT_FUNCIONES.FUNCION_ID','=','CEN_BITACORA.FUNCION_ID')
                                      ->join('CEN_CAT_TRX'      ,'CEN_CAT_TRX.TRX_ID'          ,'=','CEN_BITACORA.TRX_ID')
                                      ->join('CEN_CAT_MESES'    ,'CEN_CAT_MESES.MES_ID'        ,'=','CEN_BITACORA.MES_ID')
                         ->select(   'CEN_BITACORA.MES_ID','CEN_CAT_MESES.MES_DESC')
                         ->selectRaw('COUNT(*) AS TOTALGENERAL')
                         ->groupBy(  'CEN_BITACORA.MES_ID','CEN_CAT_MESES.MES_DESC')
                         ->orderBy(  'CEN_BITACORA.MES_ID','asc')
                         ->get();        
        $regbitatot=regBitacoraModel::join('CEN_CAT_PROCESOS' ,'CEN_CAT_PROCESOS.PROCESO_ID' ,'=','CEN_BITACORA.PROCESO_ID')
                                    ->join('CEN_CAT_FUNCIONES','CEN_CAT_FUNCIONES.FUNCION_ID','=','CEN_BITACORA.FUNCION_ID')
                                    ->join('CEN_CAT_TRX'      ,'CEN_CAT_TRX.TRX_ID'          ,'=','CEN_BITACORA.TRX_ID')
                         ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 1 THEN 1 END) AS M01')  
                         ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 2 THEN 1 END) AS M02')
                         ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 3 THEN 1 END) AS M03')
                         ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 4 THEN 1 END) AS M04')
                         ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 5 THEN 1 END) AS M05')
                         ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 6 THEN 1 END) AS M06')
                         ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 7 THEN 1 END) AS M07')
                         ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 8 THEN 1 END) AS M08')
                         ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 9 THEN 1 END) AS M09')
                         ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID =10 THEN 1 END) AS M10')
                         ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID =11 THEN 1 END) AS M11')
                         ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID =12 THEN 1 END) AS M12')
                         ->selectRaw('COUNT(*) AS TOTALGENERAL')
                         ->get();

        $regbitacora=regBitacoraModel::join('CEN_CAT_PROCESOS' ,'CEN_CAT_PROCESOS.PROCESO_ID' ,'=','CEN_BITACORA.PROCESO_ID')
                                     ->join('CEN_CAT_FUNCIONES','CEN_CAT_FUNCIONES.FUNCION_ID','=','CEN_BITACORA.FUNCION_ID')
                                     ->join('CEN_CAT_TRX'      ,'CEN_CAT_TRX.TRX_ID'          ,'=','CEN_BITACORA.TRX_ID')
                    ->select(   'CEN_BITACORA.PERIODO_ID', 'CEN_BITACORA.PROCESO_ID', 
                                'CEN_CAT_PROCESOS.PROCESO_DESC', 'CEN_BITACORA.FUNCION_ID', 'CEN_CAT_FUNCIONES.FUNCION_DESC', 
                                'CEN_BITACORA.TRX_ID', 'CEN_CAT_TRX.TRX_DESC')
                    ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 1 THEN 1 END) AS ENE')  
                    ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 2 THEN 1 END) AS FEB')
                    ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 3 THEN 1 END) AS MAR')
                    ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 4 THEN 1 END) AS ABR')
                    ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 5 THEN 1 END) AS MAY')
                    ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 6 THEN 1 END) AS JUN')
                    ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 7 THEN 1 END) AS JUL')
                    ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 8 THEN 1 END) AS AGO')
                    ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID = 9 THEN 1 END) AS SEP')
                    ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID =10 THEN 1 END) AS OCT')
                    ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID =11 THEN 1 END) AS NOV')
                    ->selectRaw('SUM(CASE WHEN CEN_BITACORA.MES_ID =12 THEN 1 END) AS DIC')                   
                    ->selectRaw('COUNT(*) AS SUMATOTAL')
                    ->groupBy('CEN_BITACORA.PERIODO_ID','CEN_BITACORA.PROCESO_ID', 
                              'CEN_CAT_PROCESOS.PROCESO_DESC','CEN_BITACORA.FUNCION_ID','CEN_CAT_FUNCIONES.FUNCION_DESC', 
                              'CEN_BITACORA.TRX_ID', 'CEN_CAT_TRX.TRX_DESC')
                    ->orderBy('CEN_BITACORA.PERIODO_ID','ASC')
                    ->orderBy('CEN_BITACORA.PROCESO_ID','ASC') 
                    ->orderBy('CEN_BITACORA.FUNCION_ID','ASC')  
                    ->orderBy('CEN_BITACORA.TRX_ID',    'ASC')
                    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.bitacora',compact('nombre','usuario','role','regbitatxmes','regbitacora','regbitatot'));
    }


}
