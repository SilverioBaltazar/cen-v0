<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\procesosRequest;
use App\regProcesoModel;
// Exportar a excel 
use App\Exports\ExcelExportCatProcesos;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;

class procesosController extends Controller
{
    public function actionNuevoProceso(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');

        $regproceso = regProcesoModel::select('PROCESO_ID','PROCESO_DESC', 'PROCESO_STATUS','PROCESO_FECREG')
            ->orderBy('PROCESO_ID','asc')->get();
        //dd($unidades);
        return view('sicinar.procesos.nuevoProceso',compact('regproceso','nombre','usuario'));
    }

    public function actionAltaNuevoProceso(Request $request){
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
        $proceso_id = regProcesoModel::max('PROCESO_ID');
        $proceso_id = $proceso_id+1;
        /* ALTA DEl proceso ****************************/
        $nuevoProceso = new regProcesoModel();
        $nuevoProceso->PROCESO_ID   = $proceso_id;
        $nuevoProceso->PROCESO_DESC = $request->proceso_desc;
        $nuevoProceso->save();

        if($nuevoProceso->save() == true){
            toastr()->success('El Proceso ha sido dado de alta correctamente.','Proceso dado de alta!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoProceso');
            //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','estructura','id_estructura','rango','preguntas','apartados'));
        }else{
            toastr()->error('Error inesperado al dar de alta el Proceso. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //return back();
            //return redirect()->route('nuevoProceso');
        }
        return redirect()->route('verProceso');
    }

    
    public function actionVerProceso(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $regproceso = regProcesoModel::select('PROCESO_ID','PROCESO_DESC', 'PROCESO_STATUS','PROCESO_FECREG')
            ->orderBy('PROCESO_ID','ASC')
            ->paginate(10);
        if($regproceso->count() <= 0){
            toastr()->error('No existen registro de Procesos dados de alta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevoProceso');
        }
        return view('sicinar.procesos.verProceso',compact('nombre','usuario','regproceso'));

    }

    public function actionEditarProceso($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $regproceso = regProcesoModel::select('PROCESO_ID','PROCESO_DESC','PROCESO_STATUS','PROCESO_FECREG')
            ->where('PROCESO_ID',$id)
            ->orderBy('PROCESO_ID','ASC')
            ->first();
        if($regproceso->count() <= 0){
            toastr()->error('No existe registros de procesos dados de alta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevoProceso');
        }
        return view('sicinar.procesos.editarProceso',compact('nombre','usuario','estructura','id_estructura','regproceso'));

    }

    public function actionActualizarProceso(procesosRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');

        $regproceso = regProcesoModel::where('PROCESO_ID',$id);
        if($regproceso->count() <= 0)
            toastr()->error('No existe procesos.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regproceso = regProcesoModel::where('PROCESO_ID',$id)        
            ->update([
                'PROCESO_DESC' => strtoupper($request->proceso_desc),
                'PROCESO_STATUS' => $request->proceso_status
            ]);
            toastr()->success('Proceso ha sido actualizado correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verProceso');
        //return view('sicinar.catalogos.verProceso',compact('nombre','usuario','estructura','id_estructura','regproceso'));

    }

public function actionBorrarProceso($id){
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
        $regproceso = regProcesoModel::select('PROCESO_ID','PROCESO_DESC','PROCESO_STATUS','PROCESO_FECREG')
            ->where('PROCESO_ID',$id);
        if($regproceso->count() <= 0)
            toastr()->error('No existe proceso.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regproceso->delete();
            toastr()->success('Proceso ha sido eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verProceso');
    }    

    // exportar a formato catalogo de procesos a formato excel
    public function exportCatProcesosExcel(){
        return Excel::download(new ExcelExportCatProcesos, 'Cat_Procesos_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato catalogo de procesos a formato PDF
    public function exportCatProcesosPdf(){
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
            ->orderBy('PROCESO_ID','ASC')->get();
        if($regproceso->count() <= 0){
            toastr()->error('No existen registros en el catalogo de procesos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verProceso');
        }
        $pdf = PDF::loadView('sicinar.pdf.catprocesosPDF', compact('nombre','usuario','estructura','id_estructura','regproceso'));
        //******** Horizontal ***************
        //$pdf->setPaper('A4', 'landscape');      
        //$pdf->set('defaultFont', 'Courier');          
        //$pdf->setPaper('A4','portrait');
        // Output the generated PDF to Browser
        //******** vertical *************** 
        //El tamaño de hoja se especifica en page_size puede ser letter, legal, A4, etc.         
        $pdf->setPaper('letter','portrait');       
        return $pdf->stream('CatalogoDeProcesos');
    }

}
