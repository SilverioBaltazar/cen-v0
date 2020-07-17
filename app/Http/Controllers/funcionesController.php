<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\funcionesRequest;
use App\regProcesoModel;
use App\regFuncionModel;

// Exportar a excel 
use App\Exports\ExcelExportCatFunciones;
use Maatwebsite\Excel\Facades\Excel;

// Exportar a pdf
use PDF;

class funcionesController extends Controller
{
    public function actionNuevaFuncion(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        
        $regproceso = regProcesoModel::select('PROCESO_ID','PROCESO_DESC','PROCESO_STATUS','PROCESO_FECREG')
                      ->orderBy('PROCESO_ID','asc')
                      ->get();
        if($regproceso->count() <= 0){
            toastr()->error('No existen registros en el catalogo de funciones.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('verProceso');
        }        

        $regfuncion = regFuncionModel::select('PROCESO_ID','FUNCION_ID','FUNCION_DESC', 'FUNCION_STATUS','FUNCION_FECREG')
                      ->orderBy('PROCESO_ID','asc')
                      ->orderBy('FUNCION_ID','asc')
                      ->get();
        //dd($unidades);
        return view('sicinar.funciones.nuevaFuncion',compact('regfuncion','regproceso','nombre','usuario'));
    }

    public function actionAltaNuevaFuncion(Request $request){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        //$regfuncion = regFuncionModel::select('PROCESO_ID','FUNCION_ID','FUNCION_DESC', 'FUNCION_STATUS','FUNCION_FECREG')
        //              ->where(['PROCESO_ID' => $request->proceso_id,'FUNCION_ID',$request->funcion_id])
        ////->first();
        //                               ->get();
        //dd($regfuncion,$regfuncion->count(),$request->proceso_id,$request->funcion_id);
        //if($regfuncion->count() > 0)
        //    toastr()->error('Ya existe funcion del modelado de proceso.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        //else{  
            /* ALTA DEl proceso ****************************/
            $nuevaFuncion = new regFuncionModel();
            $nuevaFuncion->PROCESO_ID    = $request->proceso_id;
            $nuevaFuncion->FUNCION_ID    = $request->funcion_id;
            $nuevaFuncion->FUNCION_DESC  = strtoupper($request->funcion_desc);
            //$nuevaFuncion->FUNCION_STATUS= $request->funcion_status;        
            $nuevaFuncion->save();

            if($nuevaFuncion->save() == true){
                toastr()->success('La función del Proceso ha sido dada de alta correctamente.','Función dada de alta!',['positionClass' => 'toast-bottom-right']);
            }else{
                toastr()->error('Error inesperado al dar de alta la función del Proceso. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }
        //}
        return redirect()->route('verFuncion');
    }

    
    public function actionVerFuncion(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $regfuncion = regFuncionModel::join('CEN_CAT_PROCESOS','CEN_CAT_PROCESOS.PROCESO_ID','=',
                                                               'CEN_CAT_FUNCIONES.PROCESO_ID')
                      ->select( 'CEN_CAT_FUNCIONES.PROCESO_ID','CEN_CAT_PROCESOS.PROCESO_DESC',
                                'CEN_CAT_FUNCIONES.FUNCION_ID','CEN_CAT_FUNCIONES.FUNCION_DESC',
                                'CEN_CAT_FUNCIONES.FUNCION_STATUS','CEN_CAT_FUNCIONES.FUNCION_FECREG')
                      ->orderBy('CEN_CAT_FUNCIONES.PROCESO_ID','ASC')
                      ->orderBy('CEN_CAT_FUNCIONES.FUNCION_ID','ASC')
                      ->paginate(30);
        if($regfuncion->count() <= 0){
            toastr()->error('No existen registros de funciones de Procesos dados de alta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaFuncion');
        }
        return view('sicinar.funciones.verFuncion',compact('nombre','usuario','estructura','id_estructura','regfuncion'));

    }

    public function actionEditarFuncion($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');

        $regproceso = regProcesoModel::select('PROCESO_ID','PROCESO_DESC')
                      ->get();        
        $regfuncion = regFuncionModel::join('CEN_CAT_PROCESOS','CEN_CAT_PROCESOS.PROCESO_ID','=','CEN_CAT_FUNCIONES.PROCESO_ID')
                                   ->select('CEN_CAT_FUNCIONES.PROCESO_ID','CEN_CAT_PROCESOS.PROCESO_DESC',
                                            'CEN_CAT_FUNCIONES.FUNCION_ID','CEN_CAT_FUNCIONES.FUNCION_DESC',
                                            'CEN_CAT_FUNCIONES.FUNCION_STATUS','CEN_CAT_FUNCIONES.FUNCION_FECREG')
                                    ->where('CEN_CAT_FUNCIONES.FUNCION_ID',$id)
                      ->first();
        if($regfuncion->count() <= 0){
            toastr()->error('No existe registro de función de proceso.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verFuncion');
        }

        return view('sicinar.funciones.editarFuncion',compact('nombre','usuario','regproceso','regfuncion'));
        //return view('sicinar.funciones.editarFuncion',compact('nombre','usuario','estructura','id_estructura','regfuncion'));

    }

    public function actionActualizarFuncion(funcionesRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
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

        $regfuncion = regFuncionModel::where('FUNCION_ID',$id);
        if($regfuncion->count() <= 0)
            toastr()->error('No existe funcion del modelado de proceso.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regfuncion = regFuncionModel::where('FUNCION_ID',$id)        
                ->update([
                'FUNCION_DESC' => strtoupper($request->funcion_desc),
                'FUNCION_STATUS' => $request->funcion_status
                ]);
            toastr()->success('Funcion del proceso ha sido actualizado correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verFuncion');
        //return view('sicinar.funciones.verFuncion',compact('nombre','usuario','estructura','id_estructura','regfuncion'));

    }

public function actionBorrarFuncion($id){
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

        $regfuncion = regFuncionModel::select('PROCESO_ID','FUNCION_ID','FUNCION_DESC','FUNCION_STATUS','FUNCION_FECREG')
                                     ->where('FUNCION_ID',$id);
        if($regfuncion->count() <= 0)
            toastr()->error('No existe funcion del proceso.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regfuncion->delete();
            toastr()->success('La funcion del proceso ha sido eliminada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verFuncion');
    }    

    // exportar a formato catalogo de funciones de procesos a formato excel
    public function exportCatFuncionesExcel(){
        return Excel::download(new ExcelExportCatFunciones, 'Cat_Funciones_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato catalogo de funciones de procesos a formato PDF
    public function exportCatFuncionesPdf(){
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

        $regproceso = regProcesoModel::select('PROCESO_ID','PROCESO_DESC','PROCESO_STATUS','PROCESO_FECREG')
            ->orderBy('PROCESO_ID','DESC')->get();
        if($regproceso->count() <= 0){
            toastr()->error('No existen registros en el catalogo de procesos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('verProceso');
        }

        $regfuncion = regFuncionModel::join('CEN_CAT_PROCESOS','CEN_CAT_PROCESOS.PROCESO_ID','=',
                                                               'CEN_CAT_FUNCIONES.PROCESO_ID')
                      ->select( 'CEN_CAT_FUNCIONES.PROCESO_ID','CEN_CAT_PROCESOS.PROCESO_DESC',
                                'CEN_CAT_FUNCIONES.FUNCION_ID','CEN_CAT_FUNCIONES.FUNCION_DESC',
                                'CEN_CAT_FUNCIONES.FUNCION_STATUS','CEN_CAT_FUNCIONES.FUNCION_FECREG')
                      ->orderBy('CEN_CAT_FUNCIONES.PROCESO_ID','ASC')
                      ->orderBy('CEN_CAT_FUNCIONES.FUNCION_ID','ASC')
                      ->get();
        if($regfuncion->count() <= 0){
            toastr()->error('No existen registros en el catalogo de funciones de procesos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verFuncion');
        }
        $pdf = PDF::loadView('sicinar.pdf.catfuncionesPDF', compact('nombre','usuario','estructura','id_estructura','regfuncion','regproceso'));
        //******** Horizontal ***************
        //$pdf->setPaper('A4', 'landscape');      
        //$pdf->set('defaultFont', 'Courier');          
        //$pdf->setPaper('A4','portrait');
        // Output the generated PDF to Browser
        //******** vertical *************** 
        //El tamaño de hoja se especifica en page_size puede ser letter, legal, A4, etc.         
        $pdf->setPaper('letter','portrait');       
        return $pdf->stream('FuncionesDeProcesos');
    }

}
