<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\eventoRequest;

use App\regBitacoraModel;
use App\regAgendaeventoModel;

// Exportar a pdf
use PDF;

class calendarioController extends Controller
{

    // =================== CALENDARIO =====================//
    // ****************** Consultar mas información en     //
    // https://www.tutofox.com/laravel/tutorial-laravel-calendario-crear-una-enevto-en-el-calendario/

    //public function index(){
    public function actionVerCalendario(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $dep          = session()->get('dep');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        $month = date("Y-m");
        //
        $data  = $this->calendar_month($month);
        $mes   = $data['month'];
        // obtener mes en espanol
        $mespanish = $this->spanish_month($mes);
        $mes = $data['month'];

        return view("sicinar.eventos.calendario",[
                                                  'data'      => $data,
                                                  'mes'       => $mes,
                                                  'mespanish' => $mespanish
                                                 ],compact('nombre','usuario'));

   }

   //public function index_month($month){
   public function actionVerCalendariomes($month){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $dep          = session()->get('dep');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        $data = $this->calendar_month($month);
        $mes = $data['month'];
        // obtener mes en espanol
        $mespanish = $this->spanish_month($mes);
        $mes = $data['month'];

        return view("sicinar.eventos.calendario",[
                                                  'data'      => $data,
                                                  'mes'       => $mes,
                                                  'mespanish' => $mespanish
                                                  ],compact('nombre','usuario'));

    }

    public static function calendar_month($month){
      //$mes = date("Y-m");
      $mes = $month;
      //sacar el ultimo de dia del mes
      //$daylast =  date("Y-m-d", strtotime("last day of ".$mes));
      $daylast =  date("Y/m/d", strtotime("last day of ".$mes));
      //sacar el dia de dia del mes
      //$fecha      =  date("Y-m-d", strtotime("first day of ".$mes));
      $fecha      =  date("Y/m/d", strtotime("first day of ".$mes));
      $daysmonth  =  date("d", strtotime($fecha));
      $montmonth  =  date("m", strtotime($fecha));
      $yearmonth  =  date("Y", strtotime($fecha));
      // sacar el lunes de la primera semana
      $nuevaFecha = mktime(0,0,0,$montmonth,$daysmonth,$yearmonth);
      $diaDeLaSemana = date("w", $nuevaFecha);
      $nuevaFecha = $nuevaFecha - ($diaDeLaSemana*24*3600); //Restar los segundos totales de los dias transcurridos de la semana
      //$dateini = date ("Y-m-d",$nuevaFecha);
      $dateini = date ("Y/m/d",$nuevaFecha);
      //$dateini = date("Y-m-d",strtotime($dateini."+ 1 day"));
      // numero de primer semana del mes
      $semana1 = date("W",strtotime($fecha));
      // numero de ultima semana del mes
      $semana2 = date("W",strtotime($daylast));
      // semana todal del mes
      // en caso si es diciembre
      if (date("m", strtotime($mes))==12) {
          $semana = 5;
      }
      else {
        $semana = ($semana2-$semana1)+1;
      }
      // semana todal del mes
      $datafecha = $dateini;
      $calendario = array();
      $iweek = 0;
      while ($iweek < $semana):
          $iweek++;
          //echo "Semana $iweek <br>";
          //
          $weekdata = [];
          for ($iday=0; $iday < 7 ; $iday++){
            // code...
            $datafecha = date("Y-m-d",strtotime($datafecha."+ 1 day"));
            $datanew['mes'] = date("M", strtotime($datafecha));
            $datanew['dia'] = date("d", strtotime($datafecha));
            $datanew['fecha'] = $datafecha;
            //AGREGAR CONSULTAS EVENTO
            //$datanew['evento'] = Event::where("fecha",$datafecha)->get();
            $datanew['evento'] = regAgendaeventoModel::where("EVENTO_FECHA",$datafecha)
                                 ->orderBy('EVENTO_HORA','asc')
                                 ->get();
            array_push($weekdata,$datanew);
          }
          $dataweek['semana'] = $iweek;
          $dataweek['datos'] = $weekdata;
          //$datafecha['horario'] = $datahorario;
          array_push($calendario,$dataweek);
      endwhile;
      $nextmonth = date("Y-M",strtotime($mes."+ 1 month"));
      $lastmonth = date("Y-M",strtotime($mes."- 1 month"));
      $month     = date("M",strtotime($mes));
      $yearmonth = date("Y",strtotime($mes));
      //$month = date("M",strtotime("2019-03"));
      $data = array(
        'next' => $nextmonth,
        'month'=> $month,
        'year' => $yearmonth,
        'last' => $lastmonth,
        'calendar' => $calendario,
      );
      return $data;
    }

    public static function spanish_month($month)
    {

        $mes = $month;
        if ($month=="Jan") {
          $mes = "Enero";
        }
        elseif ($month=="Feb")  {
          $mes = "Febrero";
        }
        elseif ($month=="Mar")  {
          $mes = "Marzo";
        }
        elseif ($month=="Apr") {
          $mes = "Abril";
        }
        elseif ($month=="May") {
          $mes = "Mayo";
        }
        elseif ($month=="Jun") {
          $mes = "Junio";
        }
        elseif ($month=="Jul") {
          $mes = "Julio";
        }
        elseif ($month=="Aug") {
          $mes = "Agosto";
        }
        elseif ($month=="Sep") {
          $mes = "Septiembre";
        }
        elseif ($month=="Oct") {
          $mes = "Octubre";
        }
        elseif ($month=="Oct") {
          $mes = "December";
        }
        elseif ($month=="Dec") {
          $mes = "Diciembre";
        }
        else {
          $mes = $month;
        }
        return $mes;
    }


    //public function form(){
    public function actionNuevaCita(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $dep          = session()->get('dep');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        return view("sicinar.eventos.nuevaCita",compact('nombre','usuario')); 
    }

    //public function create(Request $request){
    public function actionAltaCita(Request $request){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $dep          = session()->get('dep');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        //$validaduplicada = regPlacaModel::where('PLACA_PLACA',$request->placa_placa);
        //if($placaduplicada->count() > 0)
        //    toastr()->error('Ya existen placas.','¡Por favor revisar!',['positionClass' => 'toast-bottom-right']);
        //else{
            /************ Dar de alta *****************************/ 
            setlocale(LC_TIME, "spanish");        
            //$xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xmes_id      = (int)date('m');
            $xdia_id      = (int)date('d');

            $xevento_desc = substr(trim(strtoupper( $request->input('evento_desc') )),0,99);
            $xevento_hora = substr(trim(strtoupper( $request->input('evento_hora') )),0, 9);
            $xevento_nomb = substr(trim(strtoupper( $request->input('evento_nomb') )),0,79);

            $this->validate($request, [
                                  'evento_desc'  => 'required',
                                  'evento_fecha' => 'required',
                                  'evento_hora'  => 'required',
                                  'evento_nomb'  => 'required'
                                  ]);

            //Event::insert([
            //'titulo'       => $request->input("titulo"),
            //'descripcion'  => $request->input("descripcion"),
            //'fecha'        => $request->input("fecha")
            //            ]);

            $evento_id   = regAgendaeventoModel::max('EVENTO_ID');
            $evento_id   = $evento_id + 1;

            $nuevoevento = new regAgendaeventoModel();
            $nuevoevento->PERIODO_ID      = $xperiodo_id;
            $nuevoevento->EVENTO_ID       = $evento_id;
            $nuevoevento->EVENTO_DESC     = $xevento_desc;
            $nuevoevento->EVENTO_HORA     = $xevento_hora;
            $nuevoevento->EVENTO_NOMB     = $xevento_nomb;
            $nuevoevento->EVENTO_FECHA    = $request->input('evento_fecha');
            $nuevoevento->EVENTO_FECHA2   = $request->input('evento_fecha');
            $nuevoevento->PERIODO_ID1     = $xperiodo_id;
            $nuevoevento->MES_ID1         = $xmes_id;
            $nuevoevento->DIA_ID1         = $xdia_id;
            
            $nuevoevento->IP              = $ip;
            $nuevoevento->LOGIN           = $nombre;         // Usuario ;
            $nuevoevento->save();

            if($nuevoevento->save() == true){
                toastr()->success('Cita de comprobación registrada.','ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3004;
                $xtrx_id      =       138;    //Alta 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID','PROCESO_ID','FUNCION_ID','TRX_ID', 
                                                        'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,
                                'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $evento_id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $evento_id;      // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Bitacora dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);

                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $evento_id])
                                ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                   'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $evento_id])
                                   ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES  = $xno_veces,
                                         'IP_M'     => $regbitacora->IP        = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M   = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 

            }else
                toastr()->error('Error al dar de alta cita de comprobación de combustible. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //****************************** Dar de alta ************************/
        //}   //************************** validar duplicado de placas ************/

        //return back()->with('success', 'Enviado exitosamente!');
        return redirect()->route('vercalendario');

    }

    public function actionEditarCita($id){
        $nombre    = session()->get('userlog');
        $pass      = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario   = session()->get('usuario');
        $dep       = session()->get('dep');
        $rango     = session()->get('rango');
        $ip        = session()->get('ip');

        //$regevento = regAgendaeventoModel::find($id);
        //dd('id:',$id,'-',$regevento);
        //return view('sicinar.eventos.editarCita',compact('nombre','usuario','regevento'));
        $regcitas  = regAgendaeventoModel::select('PERIODO_ID'  ,'EVENTO_ID'     ,'EVENTO_FOLIO',
                     'EVENTO_DESC' ,'EVENTO_HORA'   ,'EVENTO_NOMB',
                     'EVENTO_FECHA','EVENTO_FECHA2' ,'PERIODO_ID1','MES_ID1','DIA_ID1',
                     'EVENTO_OBS'  ,'EVENTO_STATUS1','EVENTO_STATUS2',
                     'FECREG'      ,'IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                     ->where('EVENTO_ID' ,$id)
                     ->first();
        if($regcitas->count() <= 0){
            toastr()->error('No existe cita de comprobación de combustible.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        //dd('id:',$id,'-',$regcitas);
        return view('sicinar.eventos.editarCita',compact('nombre','usuario','regcitas'));

    }

    public function actionActualizarCita(eventoRequest $request, $id){
        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario    = session()->get('usuario');
        $rango      = session()->get('rango');
        $dep        = session()->get('dep');        
        $ip         = session()->get('ip');

        // **************** actualizar ******************************
        $regcitas   = regAgendaeventoModel::where('EVENTO_ID',$id);
        if($regcitas->count() <= 0)
            toastr()->error('No existe cita de comprobación.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //*************************** Actualizar ******************************/
            $fecha_esp  = str_replace("/", "", $request->evento_fecha);
            $dia        = substr($fecha_esp, 0, 2);
            $mes        = substr($fecha_esp, 2, 2);
            $anio       = substr($fecha_esp, 4, 4);
            $fecha_okr  = $anio."/".$mes."/".$dia;
            $fecha_ok   = $dia."/".$mes."/".$anio;
            //dd('fecha capturada:'.$request->evento_fecha,'-año:'.$anio,'-mes:'.$mes,'-dia:'.$dia,'-Fecha_ok:'.$fecha_ok);
            $regcitas = regAgendaeventoModel::where('EVENTO_ID',$id)        
                        ->update([                
                                  'EVENTO_FECHA' => $fecha_okr,
                                  'EVENTO_FECHA2'=> $fecha_ok,
                                  'PERIODO_ID1'  => $anio,  
                                  'MES_ID1'      => (int)$mes,  
                                  'DIA_ID1'      => (int)$dia,  

                                  'EVENTO_NOMB'  => substr(trim(strtoupper($request->evento_nomb)),0,79),  
                                  'EVENTO_DESC'  => substr(trim(strtoupper($request->evento_desc)),0,99),
                                  'EVENTO_HORA'  => substr(trim(strtoupper($request->evento_hora)),0, 9),  
                
                                  'IP_M'         => $ip,
                                  'LOGIN_M'      => $nombre,
                                  'FECHA_M'      => date('Y/m/d')    //date('d/m/Y')                                
                                  ]);
            toastr()->success('Cita de comprobación actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =       139;    //Actualizar        

            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID','TRX_ID',
                                                    'FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id  ,'TRX_ID' => $xtrx_id,'FOLIO' => $id])
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
                                        'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP           = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M   = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /********** Termina de actualizar *****************************/

        return redirect()->route('vercalendario');
    }    


 // exportar a formato catalogo de procesos a formato PDF
    public function actionCalendarioPdf(){
        set_time_limit(0);
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 0);

        $nombre     = session()->get('userlog');
        $pass       = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario    = session()->get('usuario');
        $rango      = session()->get('rango');
        $dep        = session()->get('dep');        
        $ip         = session()->get('ip');

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         3;
        $xfuncion_id  =      3004;
        $xtrx_id      =       142;       //Exportar a formato PDF
        $id           =         0;
        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                       'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                       ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                'FOLIO' => $id])
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
                         'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                         'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id, 
                                    'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                    'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                           ->update([
                                     'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M'    => $regbitacora->IP       = $ip,
                                     'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                     'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/ 

        $month = date("Y-m");
        //
        $data  = $this->calendar_month($month);
        $mes   = $data['month'];
        // obtener mes en espanol
        $mespanish = $this->spanish_month($mes);
        $mes = $data['month'];

        //$pdf = PDF::loadView('sicinar.pdf.calendarioPDF', compact('nombre','usuario','estructura','id_estructura','regproceso'));
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadView('sicinar.pdf.calendarioPDF',[
                                                          'data'      => $data,
                                                          'mes'       => $mes,
                                                          'mespanish' => $mespanish
                                                          ],compact('nombre','usuario'));

        //******** Horizontal ***************
        $pdf->setPaper('A4', 'landscape');      
        //$pdf->set('defaultFont', 'Courier');          
        //$pdf->setPaper('A4','portrait');
        // Output the generated PDF to Browser
        //******** vertical *************** 
        //El tamaño de hoja se especifica en page_size puede ser letter, legal, A4, etc.  

        //$pdf->setPaper('letter','portrait');  
        return $pdf->stream('CalendarioCitasComprobacion');
    }

}