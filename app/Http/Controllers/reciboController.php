<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\reciboRequest;
use App\Http\Requests\recibo01Request;
use App\Http\Requests\recibocompRequest;
use App\Http\Requests\cargaRequest;
use App\Http\Requests\carga1Request;

use App\regDiasModel;
use App\regMesesModel;
use App\regPfiscalesModel;
use App\regQuincenaModel;
use App\regPlacaModel;
use App\regBitacoraModel;
use App\regMarcaModel;
use App\regTipogastoModel;
use App\regTipooperacionModel;
use App\regReciboModel;

use App\regFpagosModel;
use App\regBancosModel;
use App\regCargasModel;

// Exportar a excel 
//use App\Exports\ExcelExportPLacas;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class reciboController extends Controller
{

    public function actionBuscarRecibo(Request $request){
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

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
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
                        'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','ASC')
                        ->get();
        $totpartidas  = regCargasModel::join('COMB_RECIBO_BIPADECO','COMB_RECIBO_CARGAS.RECIBO_FOLIO', '=', 
                                                                    'COMB_RECIBO_BIPADECO.RECIBO_FOLIO')
                        ->select('COMB_RECIBO_BIPADECO.PERIODO_ID','COMB_RECIBO_BIPADECO.RECIBO_FOLIO')
                        ->selectRaw('COUNT(*) AS PARTIDAS')
                        ->where('COMB_RECIBO_CARGAS.STATUS_1', 'S')
                        ->groupBy('COMB_RECIBO_BIPADECO.PERIODO_ID','COMB_RECIBO_BIPADECO.RECIBO_FOLIO')
                        ->get();            

        $regcargas = regCargasModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA',
                        'PERIODO_ID', 'MES_ID','CARGA','TKPAG_FOLAPROB','TKPAG_TARJETA',
                        'TKPAG_FECHA','TKPAG_FECHA2',  'PERIODO_ID1','MES_ID1','DIA_ID1',
                        'TKPAG_HORA', 'TKPAG_IMPORTE', 'BANCO_ID',
                        'TKBOMBA_TICKET','TKBOMBA_CODIGO','TKBOMBA_RFC',
                        'TKBOMBA_FECHA','TKBOMBA_FECHA2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TKBOMBA_HORA', 'TKBOMBA_IMPORTE','FP_ID','OBS_1','STATUS_1',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('PERIODO_ID'  ,'asc')
                        ->orderBy('RECIBO_FOLIO','asc')
                        ->orderBy('CARGA'       ,'asc')
                        ->get();
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $folio     = $request->get('folio');   
        $codigo    = $request->get('codigo');  
        $placa     = $request->get('placa');    
        $regrecibos=regReciboModel::orderBy('RECIBO_FOLIO', 'ASC')
                    ->folio($folio)         //Metodos personalizados es equvalente a ->where('IAP_DESC', 'LIKE', "%$name%");
                    ->codigo($codigo)       //Metodos personalizados
                    ->placa($placa)         //Metodos personalizados
                    ->paginate(30);
        if($regrecibos->count() <= 0){
            toastr()->error('No existen registros de recibos de bitacora.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }            
        return view('sicinar.recibo.verRecibos',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regmarca','regtipogasto','regtipooper','totpartidas','regrecibos','regcargas'));

    }

    public function actionVerRecibos(){
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

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
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
                               'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','ASC')
                        ->get();
        $regcargas = regCargasModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA',
                        'PERIODO_ID', 'MES_ID','CARGA','TKPAG_FOLAPROB','TKPAG_TARJETA',
                        'TKPAG_FECHA','TKPAG_FECHA2',  'PERIODO_ID1','MES_ID1','DIA_ID1',
                        'TKPAG_HORA', 'TKPAG_IMPORTE', 'BANCO_ID',
                        'TKBOMBA_TICKET','TKBOMBA_CODIGO','TKBOMBA_RFC',
                        'TKBOMBA_FECHA','TKBOMBA_FECHA2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TKBOMBA_HORA', 'TKBOMBA_IMPORTE','FP_ID','OBS_1','STATUS_1',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('PERIODO_ID'  ,'asc')
                        ->orderBy('RECIBO_FOLIO','asc')
                        ->orderBy('CARGA'       ,'asc')
                        ->get();                        
        //dd($unidades);
        if($role->rol_name == 'user'){                        
            //dd($role->rol_name,$nombre,'ya entre al rol de user');
            $totpartidas=regCargasModel::join('COMB_RECIBO_BIPADECO','COMB_RECIBO_BIPADECO.RECIBO_FOLIO', '=', 
                                                                     'COMB_RECIBO_CARGAS.RECIBO_FOLIO')
                        ->select('COMB_RECIBO_BIPADECO.PERIODO_ID','COMB_RECIBO_BIPADECO.RECIBO_FOLIO')
                        ->selectRaw('COUNT(*) AS PARTIDAS')
                        ->where(['COMB_RECIBO_BIPADECO.LOGIN' => $nombre,'COMB_RECIBO_CARGAS.STATUS_1' => 'S'])
                        ->groupBy('COMB_RECIBO_BIPADECO.PERIODO_ID','COMB_RECIBO_BIPADECO.RECIBO_FOLIO')
                        ->get();            
            $regrecibos = regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                          'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                          'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                          'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                          'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                          'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                          'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                          'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                          'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                          'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                          ->where('LOGIN',$nombre)
                          ->orderBy('RECIBO_FOLIO','ASC')                        
                          ->paginate(30);
        }else{
            // dd($role->rol_name,'Rol distinto de user');
            $totpartidas=regCargasModel::join('COMB_RECIBO_BIPADECO','COMB_RECIBO_CARGAS.RECIBO_FOLIO', '=', 
                                                                     'COMB_RECIBO_BIPADECO.RECIBO_FOLIO')
                        ->select('COMB_RECIBO_BIPADECO.PERIODO_ID','COMB_RECIBO_BIPADECO.RECIBO_FOLIO')
                        ->selectRaw('COUNT(*) AS PARTIDAS')
                        ->where('COMB_RECIBO_CARGAS.STATUS_1', 'S')
                        ->groupBy('COMB_RECIBO_BIPADECO.PERIODO_ID','COMB_RECIBO_BIPADECO.RECIBO_FOLIO')
                        ->get();            
            $regrecibos = regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                          'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                          'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                          'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                          'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                          'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                          'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                          'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                          'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                          'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                          ->orderBy('RECIBO_FOLIO','ASC')                        
                          ->paginate(30);            
        }
        if($regrecibos->count() <= 0){
            toastr()->error('No existen registros de recibos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.recibo.verRecibos',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regmarca','regtipogasto','regtipooper','regrecibos','regcargas','totpartidas','regcargas'));
    }    

    public function actionNuevoRecibo(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
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
                               'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','asc')
                        ->get();
        $regrecibos   = regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                        'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                        'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                        'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                        'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                        'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                        'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('RECIBO_FOLIO','asc')
                        ->get();                        
        //dd($unidades);
        return view('sicinar.recibo.nuevoRecibo',compact('regperiodo','regmes','regdia','regquincena','regplaca','regmarca','regtipogasto','regtipooper','regrecibos','nombre','usuario'));
    }

    public function actionAltaNuevoRecibo(Request $request){
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

        /************ Obtenemos la IP ***************************/                
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

        /************ ALTA  *****************************/ 
        //https://ajgallego.gitbooks.io/laravel-5/content/capitulo_4_datos_de_entrada.html
        // video https://www.youtube.com/watch?v=1Z7oson-G8M
        // Mover el fichero a la ruta conservando el nombre original:         
        //$request->file('photo')->move($destinationPath);
        // Mover el fichero a la ruta con un nuevo nombre:
        //$request->file('photo')->move($destinationPath, $fileName);

        //*********** Se obtiene la placa y el resguardatario   *****/
        $placa_placa = regPlacaModel::ObtPlaca($request->placa_id);
        $recibo_obs2 = regPlacaModel::ObtResguardatario($request->placa_id);
        $tipoo_id    = regPlacaModel::ObtTipoOperacion($request->placa_id);

        $mes1 = regMesesModel::ObtMes($request->mes_id1);
        $dia1 = regDiasModel::ObtDia($request->dia_id1);
        //$mes2 = regMesesModel::ObtMes($request->mes_id2);
        $dia2 = regDiasModel::ObtDia($request->dia_id2);

        $recibo_folio = regReciboModel::max('RECIBO_FOLIO');
        $recibo_folio = $recibo_folio+1;

        $nuevorecibo  = new regReciboModel();
        $name11 =null;
        //Comprobar  si el campo foto1 tiene un archivo asignado:
        if($request->hasFile('recibo_rfoto1')){
           $name11 = $recibo_folio.'_'.$request->file('recibo_rfoto1')->getClientOriginalName(); 
           //$file->move(public_path().'/images/', $name1);
           //sube el archivo a la carpeta del servidor public/images/
           $request->file('recibo_rfoto1')->move(public_path().'/images/', $name11);
        }
        $name21 =null;
        //Comprobar  si el campo foto2 tiene un archivo asignado:        
        if($request->hasFile('recibo_bfoto1')){
           $name21 = $recibo_folio.'_'.$request->file('recibo_bfoto1')->getClientOriginalName(); 
           //sube el archivo a la carpeta del servidor public/images/
           $request->file('recibo_bfoto1')->move(public_path().'/images/', $name21);
        }

        $nuevorecibo->RECIBO_FOLIO   = $recibo_folio;
        $nuevorecibo->PLACA_ID       = $request->placa_id;
        $nuevorecibo->PLACA_PLACA    = strtoupper($placa_placa[0]->placa_placa);

        $nuevorecibo->RECIBO_KI       = $request->recibo_ki;
        $nuevorecibo->RECIBO_KF       = $request->recibo_kf;
        $nuevorecibo->QUINCENA_ID     = $request->quincena_id;
        $nuevorecibo->RECIBO_IR       = $request->recibo_ir;
        $nuevorecibo->RECIBO_I18      = $request->recibo_i18;
        $nuevorecibo->RECIBO_I14      = $request->recibo_i14;
        $nuevorecibo->RECIBO_I12      = $request->recibo_i13;
        $nuevorecibo->RECIBO_I34      = $request->recibo_i34;
        $nuevorecibo->RECIBO_IF       = $request->recibo_if;        

        $nuevorecibo->RECIBO_FR       = $request->recibo_fr;
        $nuevorecibo->RECIBO_F18      = $request->recibo_f18;
        $nuevorecibo->RECIBO_F14      = $request->recibo_f14;
        $nuevorecibo->RECIBO_F12      = $request->recibo_f13;
        $nuevorecibo->RECIBO_F34      = $request->recibo_f34;
        $nuevorecibo->RECIBO_FF       = $request->recibo_ff;        
        
        $nuevorecibo->RECIBO_FECINI   = date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) ));
        $nuevorecibo->RECIBO_FECINI2  = trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);
        $nuevorecibo->PERIODO_ID1     = $request->periodo_id1;
        $nuevorecibo->MES_ID1         = $request->mes_id1;
        $nuevorecibo->DIA_ID1         = $request->dia_id1;

        //$nuevorecibo->RECIBO_FECFIN = date('Y/m/d', strtotime(trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2) ));
        //$nuevorecibo->RECIBO_FECFIN2= trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2);
        $nuevorecibo->RECIBO_FECFIN   = date('Y/m/d', strtotime(trim($dia2[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) ));
        $nuevorecibo->RECIBO_FECFIN2  = trim($dia2[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);
        //$nuevorecibo->PERIODO_ID2   = $request->periodo_id2;
        //$nuevorecibo->MES_ID2       = $request->mes_id2;
        $nuevorecibo->PERIODO_ID2     = $request->periodo_id1;
        $nuevorecibo->MES_ID2         = $request->mes_id1;  
        $nuevorecibo->DIA_ID2         = $request->dia_id2;

        $nuevorecibo->TIPOO_ID        = $tipoo_id[0]->tipoo_id;

        $nuevorecibo->TARJETA_NO      = substr(trim(strtoupper($request->tarjeta_no)),0,29);
        //$nuevorecibo->PERIODO_ID      = $request->periodo_id;        
        //$nuevorecibo->MES_ID          = $request->mes_id;
        $nuevorecibo->PERIODO_ID      = $request->periodo_id1;        
        $nuevorecibo->MES_ID          = $request->mes_id1;        
        $nuevorecibo->SP_NOMB         = substr(trim(strtoupper($recibo_obs2[0]->placa_obs2)),0,79);
        
        $nuevorecibo->RECIBO_OBS1     = substr(trim(strtoupper($request->recibo_obs1)),0,99);
        $nuevorecibo->RECIBO_OBS2     = substr(trim(strtoupper($recibo_obs2[0]->placa_obs2)),0,99);
        $nuevorecibo->RECIBO_RFOTO1   = $name11;
        $nuevorecibo->RECIBO_BFOTO1   = $name21;

        $nuevorecibo->IP              = $ip;
        $nuevorecibo->LOGIN           = $nombre;         // Usuario ;
        $nuevorecibo->save();

        if($nuevorecibo->save() == true){
            toastr()->success('Recibo registrado.','OK!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3001;
            $xtrx_id      =       150;    //Alta 
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                           'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 
                           'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                    'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                    'TRX_ID' => $xtrx_id, 'FOLIO' => $recibo_folio])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $recibo_folio;   // Folio    
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
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $recibo_folio])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         

                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $recibo_folio])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de recibo en bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }
            /************ Bitacora termina *************************************/ 

        }else{
            toastr()->error('Error al dar de alta el recibo. Por favor volver a intentarlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //return back();
            //return redirect()->route('nuevoProceso');
        }

        return redirect()->route('verRecibos');
    }


    public function actionEditarRecibo($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
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
                               'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','asc')
                        ->get();
        $regrecibos   = regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                        'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                        'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                        'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                        'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                        'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                        'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('RECIBO_FOLIO',$id)
                        ->orderBy('RECIBO_FOLIO','ASC')
                        ->first();
        if($regrecibos->count() <= 0){
            toastr()->error('No existe registro de recibo.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.recibo.editarRecibo',compact('regperiodo','regmes','regdia','regquincena','regplaca','regmarca','regtipogasto','regtipooper','regrecibos','nombre','usuario'));
    }

    public function actionActualizarRecibo(reciboRequest $request, $id){
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
        $regrecibos = regReciboModel::where('RECIBO_FOLIO',$id);
        if($regrecibos->count() <= 0)
            toastr()->error('No existe recibo.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $name1 =null;
            //   if(!empty($_PUT['recibo_rfoto1'])){
            if(isset($request->recibo_rfoto1)){
                if(!empty($request->recibo_rfoto1)){
                    //Comprobar  si el campo foto1 tiene un archivo asignado:
                    if($request->hasFile('recibo_rfoto1')){
                      $name1 = $id.'_'.$request->file('recibo_rfoto1')->getClientOriginalName(); 
                      //sube el archivo a la carpeta del servidor public/images/
                      $request->file('recibo_rfoto1')->move(public_path().'/images/', $name1);
                    }
                }
            }

            //*********** Se obtiene la placa y el resguardatario   *****/
            $placa_placa = regPlacaModel::ObtPlaca($request->placa_id);
            $recibo_obs2 = regPlacaModel::ObtResguardatario($request->placa_id);
            $tipoo_id    = regPlacaModel::ObtTipoOperacion($request->placa_id);

            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);
            //$mes2 = regMesesModel::ObtMes($request->mes_id2);
            $dia2 = regDiasModel::ObtDia($request->dia_id2);

            $regrecibos = regReciboModel::where('RECIBO_FOLIO',$id)        
            ->update([                
                //'PLACA_ID'      => $request->placa_id,
                //'PLACA_PLACA'   => $request->placa_placa,
                'RECIBO_KI'       => $request->recibo_ki,
                'RECIBO_KF'       => $request->recibo_kf,                
                'RECIBO_IR'       => $request->recibo_ir,
                'RECIBO_I18'      => $request->recibo_i18,                
                'RECIBO_I14'      => $request->recibo_i14,
                'RECIBO_I12'      => $request->recibo_i12,                
                'RECIBO_I34'      => $request->recibo_i34,
                'RECIBO_IF'       => $request->recibo_if, 

                'RECIBO_FR'       => $request->recibo_fr,
                'RECIBO_F18'      => $request->recibo_f18,                
                'RECIBO_F14'      => $request->recibo_f14,
                'RECIBO_F12'      => $request->recibo_f12,                
                'RECIBO_F34'      => $request->recibo_f34,
                'RECIBO_FF'       => $request->recibo_ff,                                
                'RECIBO_FECINI'   => date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) )),
                'RECIBO_FECINI2'  => trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1),
                'PERIODO_ID1'     => $request->periodo_id1,                
                'MES_ID1'         => $request->mes_id1,
                'DIA_ID1'         => $request->dia_id1,
                //'RECIBO_FECFIN' =>date('Y/m/d', strtotime(trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2) )),
                //'RECIBO_FECFIN2'=> trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2),  
                'RECIBO_FECFIN'   =>date('Y/m/d', strtotime(trim($dia2[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) )),
                'RECIBO_FECFIN2'  => trim($dia2[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1),  
                //'PERIODO_ID2'   => $request->periodo_id2,                
                //'MES_ID2'       => $request->mes_id2,
                'PERIODO_ID2'     => $request->periodo_id1,                
                'MES_ID2'         => $request->mes_id1,                
                'DIA_ID2'         => $request->dia_id2,

                //'TIPOO_ID'      => $tipoo_id[0]->tipoo_id,
                'TARJETA_NO'      => substr(trim(strtoupper($request->tarjeta_no)),0,29),  
                //'PERIODO_ID'    => $request->periodo_id,  
                //'MES_ID'        => $request->mes_id,           
                'PERIODO_ID'      => $request->periodo_id1,  
                'MES_ID'          => $request->mes_id1,                  
                //'SP_NOMB'       => strtoupper($recibo_obs2[0]->placa_obs2),

                'RECIBO_OBS1'     => substr(trim(strtoupper($request->recibo_obs1)),0,99),
                //'RECIBO_OBS2'   => strtoupper($recibo_obs2[0]->placa_obs2),
                'QUINCENA_ID'     => $request->quincena_id,                
                //'RECIBO_STATUS1'=> $request->recibo_status1, 
                //'PLACA_FOTO1'   => $name1, 

                'IP_M'            => $ip,
                'LOGIN_M'         => $nombre,
                'FECHA_M'         => date('Y/m/d')    //date('d/m/Y')                                
            ]);
            toastr()->success('Recibo actualizado correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3001;
            $xtrx_id      =       151;    //Actualizar        

            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
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
                    toastr()->success('Transacción de recibo en bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error en trx de recibo al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de recibo en bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }

        return redirect()->route('verRecibos');
    }


    public function actionBorrarRecibo($id){
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

        /****************** Eliminar *********************************************/
        $regrecibo = regReciboModel::where('RECIBO_FOLIO',$id);
        //                    ->find('RUBRO_ID',$id);
        if($regrecibo->count() <= 0)
            toastr()->error('No existe recibo.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regrecibo->delete();
            toastr()->success('Recibo eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);


            /************ Eliminar cargas  **************************************/
            $regcarga = regCargasModel::where('RECIBO_FOLIO', $id);
            if($regcarga->count() <= 0)
                toastr()->error('No existen cargas de servicio en el recibo de bitacora.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
            else{        
                $regcarga->delete();
                toastr()->success('Cargas eliminadas.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            } 

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3001;
            $xtrx_id      =       152;     // Baja 

            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
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
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                            ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         

                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M' => $regbitacora->IP           = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }
            /************ Bitacora termina *************************************/     
        }
        /************* Termina de eliminar  **********************************/
        return redirect()->route('verRecibos');
    }    

    //************************************************************************//
    //**************** CARGAS DE COMBUSTIBLE DEL RECIBO DE BITACORA **********//
    //************************************************************************//
    public function actionVerCargas($id, $id2){
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

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
        $regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
                        ->get();   
        $regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
                        ->get();                                                 
        $regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
                        ->get(); 
        $regbancos    = regBancosModel::select('BANCO_ID','BANCO_DESC')->orderBy('BANCO_ID','asc')
                        ->get(); 
        $regfpagos    = regFpagosModel::select('FP_ID','FP_DESC')->orderBy('FP_ID','asc')
                        ->get();                               
        $regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE','PLACA_ANTERIOR',
                        'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
                        'DEPENDENCIA_ID','PLACA_MODELO','PLACA_MODELO2','PLACA_GASOLINA','PLACA_INVENTARIO',                        
                        'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
                        'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','ASC')
                        ->get();
        $regrecibos   = regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                        'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                        'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                        'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                        'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                        'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                        'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'RECIBO_FOLIO' => $id2])                        
                        ->get();
        //dd($unidades);
        if($role->rol_name == 'user'){                        
            //dd($role->rol_name,$nombre,'ya entre al rol de user');
            $regcargas  = regCargasModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA',
                          'PERIODO_ID', 'MES_ID','CARGA','TKPAG_FOLAPROB','TKPAG_TARJETA',
                          'TKPAG_FECHA','TKPAG_FECHA2',  'PERIODO_ID1','MES_ID1','DIA_ID1',
                          'TKPAG_HORA', 'TKPAG_IMPORTE', 'BANCO_ID',
                          'TKBOMBA_TICKET','TKBOMBA_CODIGO','TKBOMBA_RFC',
                          'TKBOMBA_FECHA','TKBOMBA_FECHA2','PERIODO_ID2','MES_ID2','DIA_ID2',
                          'TKBOMBA_HORA', 'TKBOMBA_IMPORTE','FP_ID','OBS_1','CARGA_FOTO1','STATUS_1',
                          'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $id,'RECIBO_FOLIO' => $id2, 'LOGIN' => $nombre])
                           ->orderBy('PERIODO_ID'  ,'ASC')
                           ->orderBy('RECIBO_FOLIO','ASC')                        
                           ->orderBy('CARGA','ASC') 
                           ->paginate(30);
        }else{
            // dd($role->rol_name,'Rol distinto de user');
            $regcargas  = regCargasModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA',
                          'PERIODO_ID', 'MES_ID','CARGA','TKPAG_FOLAPROB','TKPAG_TARJETA',
                          'TKPAG_FECHA','TKPAG_FECHA2',  'PERIODO_ID1','MES_ID1','DIA_ID1',
                          'TKPAG_HORA', 'TKPAG_IMPORTE', 'BANCO_ID',
                          'TKBOMBA_TICKET','TKBOMBA_CODIGO','TKBOMBA_RFC',
                          'TKBOMBA_FECHA','TKBOMBA_FECHA2','PERIODO_ID2','MES_ID2','DIA_ID2',
                          'TKBOMBA_HORA', 'TKBOMBA_IMPORTE','FP_ID','OBS_1','CARGA_FOTO1','STATUS_1',
                          'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $id, 'RECIBO_FOLIO' => $id2])
                           ->orderBy('PERIODO_ID'  ,'ASC')
                           ->orderBy('RECIBO_FOLIO','ASC') 
                           ->orderBy('CARGA','ASC') 
                           ->paginate(30);            
        }
        if($regcargas->count() <= 0){
            toastr()->error('No existen registros de cargas en el recibo seleccionado.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.recibo.verCargas',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regmarca','regtipogasto','regtipooper','regbancos','regfpagos','regrecibos','regcargas'));
    }


    public function actionNuevaCarga($id, $id2){
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

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
        $regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
                        ->get();   
        $regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
                        ->get();                                                 
        $regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
                        ->get(); 
        $regbancos    = regBancosModel::select('BANCO_ID','BANCO_DESC')->orderBy('BANCO_ID','asc')
                        ->get(); 
        $regfpagos    = regFpagosModel::select('FP_ID','FP_DESC')->orderBy('FP_ID','asc')
                        ->get();                            
        $regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE','PLACA_ANTERIOR',
                               'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
                               'DEPENDENCIA_ID','PLACA_MODELO','PLACA_MODELO2','PLACA_GASOLINA','PLACA_INVENTARIO',                               
                               'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
                               'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','asc')
                        ->get();
        $regrecibos   = regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                        'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                        'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                        'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                        'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                        'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                        'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'RECIBO_FOLIO' => $id2])
                        ->orderBy('PERIODO_ID'  ,'asc')
                        ->orderBy('RECIBO_FOLIO','asc')
                        ->get();                         
        if($role->rol_name == 'user'){                        
            //dd($role->rol_name,$nombre,'ya entre al rol de user');
            $regcargas= regCargasModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA',
                        'PERIODO_ID', 'MES_ID','CARGA','TKPAG_FOLAPROB','TKPAG_TARJETA',
                        'TKPAG_FECHA','TKPAG_FECHA2',  'PERIODO_ID1','MES_ID1','DIA_ID1',
                        'TKPAG_HORA', 'TKPAG_IMPORTE', 'BANCO_ID',
                        'TKBOMBA_TICKET','TKBOMBA_CODIGO','TKBOMBA_RFC',
                        'TKBOMBA_FECHA','TKBOMBA_FECHA2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TKBOMBA_HORA', 'TKBOMBA_IMPORTE','FP_ID','OBS_1','CARGA_FOTO1','STATUS_1',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'RECIBO_FOLIO' => $id2,'LOGIN' => $nombre])
                        ->orderBy('PERIODO_ID'  ,'asc')
                        ->orderBy('RECIBO_FOLIO','asc')
                        ->get(); 
        }else{
            $regcargas= regCargasModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA',
                        'PERIODO_ID', 'MES_ID','CARGA','TKPAG_FOLAPROB','TKPAG_TARJETA',
                        'TKPAG_FECHA','TKPAG_FECHA2',  'PERIODO_ID1','MES_ID1','DIA_ID1',
                        'TKPAG_HORA', 'TKPAG_IMPORTE', 'BANCO_ID',
                        'TKBOMBA_TICKET','TKBOMBA_CODIGO','TKBOMBA_RFC',
                        'TKBOMBA_FECHA','TKBOMBA_FECHA2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TKBOMBA_HORA', 'TKBOMBA_IMPORTE','FP_ID','OBS_1','CARGA_FOTO1','STATUS_1',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'RECIBO_FOLIO' => $id2])
                        ->orderBy('PERIODO_ID'  ,'ASC')
                        ->orderBy('RECIBO_FOLIO','ASC')   
                        ->get();             
        }                       
        //dd($unidades);
        return view('sicinar.recibo.nuevaCarga',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regmarca','regtipogasto','regtipooper','regbancos','regfpagos','regrecibos','regcargas'));
    }

    public function actionAltaNuevaCarga(Request $request){
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

        /************ Obtenemos la IP ***************************/                
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

        // *************** Validar duplicidad ***********************************/
        $duplicado = regCargasModel::where(['PERIODO_ID' => $request->periodo_id, 
                                            'TKPAG_FOLAPROB' => $request->tkpag_folaprob])
                     ->get();
        if($duplicado->count() >= 1)
            return back()->withInput()->withErrors(['TKPAG_FOLAPROB' => ' Periodo fiscal-Folio de aprobación '.$request->tkpag_folaprob.' Ya existe existe. Por favor verificar captura.']);
        else{  
            /************ Alta de registro ******************************/ 
            //*********** Se obtiene la placa y el resguardatario   *****/
            setlocale(LC_TIME, "spanish");        
            $placa_placa = regPlacaModel::ObtPlaca($request->placa_id);
            //$recibo_obs2 = regPlacaModel::ObtResguardatario($request->placa_id);
            //$tipoo_id    = regPlacaModel::ObtTipoOperacion($request->placa_id);

            $mes1   = regMesesModel::ObtMes($request->mes_id1);
            $dia1   = regDiasModel::ObtDia($request->dia_id1);
            $mes2   = regMesesModel::ObtMes($request->mes_id2);
            $dia2   = regDiasModel::ObtDia($request->dia_id2);

            $carga  = regCargasModel::where(['PERIODO_ID' => $request->periodo_id,
                                            'RECIBO_FOLIO' => $request->recibo_folio])
                      ->max('CARGA');
            $carga  = $carga + 1;

            $name01 =null;
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('carga_foto1')){
                $name01 = $request->recibo_folio.'_'.$carga.'_'.$request->file('carga_foto1')->getClientOriginalName(); 
                //$file->move(public_path().'/images/', $name1);
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('carga_foto1')->move(public_path().'/images/', $name01);
            }

            $nuevacarga  = new regCargasModel();
            $nuevacarga->RECIBO_FOLIO    = $request->recibo_folio;
            $nuevacarga->PLACA_ID        = $request->placa_id;
            $nuevacarga->PLACA_PLACA     = strtoupper($placa_placa[0]->placa_placa);
            $nuevacarga->PERIODO_ID      = $request->periodo_id;
            $nuevacarga->MES_ID          = $request->mes_id;
            $nuevacarga->CARGA           = $carga;

            $nuevacarga->TKPAG_FOLAPROB  = $request->tkpag_folaprob;
            $nuevacarga->TKPAG_TARJETA   = $request->tkpag_tarjeta;
            $nuevacarga->TKPAG_FECHA     = date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) ));
            $nuevacarga->TKPAG_FECHA2    = trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);
            $nuevacarga->PERIODO_ID1     = $request->periodo_id1;
            $nuevacarga->MES_ID1         = $request->mes_id1;
            $nuevacarga->DIA_ID1         = $request->dia_id1;
            $nuevacarga->TKPAG_HORA      = $request->tkpag_hora;
            $nuevacarga->TKPAG_IMPORTE   = $request->tkpag_importe;
            $nuevacarga->BANCO_ID        = $request->banco_id;

            $nuevacarga->TKBOMBA_TICKET  = strtoupper($request->tkbomba_ticket);
            $nuevacarga->TKBOMBA_CODIGO  = strtoupper($request->tkbomba_codigo);
            $nuevacarga->TKBOMBA_RFC     = strtoupper($request->tkbomba_rfc);
            $nuevacarga->TKBOMBA_FECHA   = date('Y/m/d', strtotime(trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2) ));
            $nuevacarga->TKBOMBA_FECHA2  = trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2);
            $nuevacarga->PERIODO_ID2     = $request->periodo_id2;
            $nuevacarga->MES_ID2         = $request->mes_id2;
            $nuevacarga->DIA_ID2         = $request->dia_id2;
            $nuevacarga->TKBOMBA_HORA    = $request->tkbomba_hora;
            $nuevacarga->TKBOMBA_IMPORTE = $request->tkbomba_importe;
            $nuevacarga->FP_ID           = $request->fp_id;
            $nuevacarga->OBS_1           = substr(trim(strtoupper($request->obs_1)),0,3999);
            $nuevacarga->CARGA_FOTO1     = $name01;

            $nuevacarga->IP              = $ip;
            $nuevacarga->LOGIN           = $nombre;         // Usuario ;
            $nuevacarga->save();
            if($nuevacarga->save() == true){
                toastr()->success('Carga registrada.  ','OK!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3001;
                $xtrx_id      =       155;    //Alta 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                               'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 
                               'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                        'TRX_ID' => $xtrx_id, 'FOLIO' => $request->recibo_folio])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $request->recibo_folio;   // Folio    
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
                                 'TRX_ID' => $xtrx_id, 'FOLIO' => $request->recibo_folio])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $request->recibo_folio])
                                   ->update([
                                             'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'     => $regbitacora->IP           = $ip,
                                             'LOGIN_M'  => $regbitacora->LOGIN_M   = $nombre,
                                             'FECHA_M'  => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                            ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 

            }else{
                toastr()->error('Error al dar de alta la carga del recibo de bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                //return back();
                //return redirect()->route('nuevoRecibo');
            }   // Termina de dar alta ***********************************//

        }   //*********** Termina de validar duplicidad ******************//

        return redirect()->route('verCargas',array($request->periodo_id,$request->recibo_folio));
    }


    public function actionBorrarCarga($id, $id1, $id2){
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
        $regcarga = regCargasModel::where(['PERIODO_ID' => $id,'RECIBO_FOLIO' => $id1,'CARGA' => $id2]);
        if($regcarga->count() <= 0)
            toastr()->error('No existe carga en el recibo de bitacora.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regcarga->delete();
            toastr()->success('Carga eliminada del recibo de bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3001;
            $xtrx_id      =       157;     // Baja 

            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id1])
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
                   toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                   toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                             'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id1])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         

                $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                              ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                       'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                       'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id1])
                              ->update([
                                        'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                        'IP_M'    => $regbitacora->IP       = $ip,
                                        'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                        'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                      ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }
            /************ Bitacora termina *************************************/     
        }
        /************* Termina de eliminar  **********************************/
        return redirect()->route('verCargas',array($id, $id1));
    }    


    public function actionEditarCarga($id, $id1, $id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
        $regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
                        ->get();   
        $regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
                        ->get();                                                 
        $regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
                        ->get(); 
        $regbancos    = regBancosModel::select('BANCO_ID','BANCO_DESC')->orderBy('BANCO_ID','asc')
                        ->get(); 
        $regfpagos    = regFpagosModel::select('FP_ID','FP_DESC')->orderBy('FP_ID','asc')
                        ->get();                            
        $regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE','PLACA_ANTERIOR',
                               'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
                               'DEPENDENCIA_ID','PLACA_MODELO','PLACA_MODELO2','PLACA_GASOLINA','PLACA_INVENTARIO',                               
                               'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
                               'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','asc')
                        ->get();
        if($role->rol_name == 'user'){                                                
            $regrecibos=regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                        'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                        'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                        'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                        'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                        'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                        'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id,'RECIBO_FOLIO' => $id1,'LOGIN' => $nombre])
                        ->orderBy('RECIBO_FOLIO','asc')
                        ->get();                         
        }else{
            $regrecibos=regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                        'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                        'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                        'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                        'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                        'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                        'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id,'RECIBO_FOLIO' => $id1])
                        ->orderBy('RECIBO_FOLIO','asc')
                        ->get();               
        }                        
        $regcargas = regCargasModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA',
                        'PERIODO_ID', 'MES_ID','CARGA','TKPAG_FOLAPROB','TKPAG_TARJETA',
                        'TKPAG_FECHA','TKPAG_FECHA2',  'PERIODO_ID1','MES_ID1','DIA_ID1',
                        'TKPAG_HORA', 'TKPAG_IMPORTE', 'BANCO_ID',
                        'TKBOMBA_TICKET','TKBOMBA_CODIGO','TKBOMBA_RFC',
                        'TKBOMBA_FECHA','TKBOMBA_FECHA2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TKBOMBA_HORA', 'TKBOMBA_IMPORTE','FP_ID','OBS_1','STATUS_1',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id,'RECIBO_FOLIO' => $id1, 'CARGA' => $id2])
                        //->orderBy('PERIODO_ID'  ,'asc')
                        //->orderBy('RECIBO_FOLIO','asc')
                        //->orderBy('CARGA'       ,'asc')
                        ->first();
        if($regcargas->count() <= 0){
            toastr()->error('No existe registro de carga en recibo de bitacora.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.recibo.editarCarga',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regmarca','regtipogasto','regtipooper','regfpagos','regrecibos','regcargas'));
    }

    public function actionActualizarCarga(cargaRequest $request, $id, $id1, $id2){
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
        $regcargas = regCargasModel::where(['PERIODO_ID'   => $request->periodo_id,
                                            'RECIBO_FOLIO' => $request->recibo_folio, 
                                            'CARGA'        => $id2]);
        //dd('periodo:',$request->periodo_id,' folio:',$request->recibo_folio,' carga:',$id2);
        if($regcargas->count() <= 0)
            toastr()->error('No existe carga en recibo de bitacora.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //*********** Se obtiene la placa y el resguardatario   *****/
            //$placa_placa = regPlacaModel::ObtPlaca($request->placa_id);
            //$recibo_obs2 = regPlacaModel::ObtResguardatario($request->placa_id);
            //$tipoo_id    = regPlacaModel::ObtTipoOperacion($request->placa_id);

            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);
            $mes2 = regMesesModel::ObtMes($request->mes_id2);
            $dia2 = regDiasModel::ObtDia($request->dia_id2);

            $regcargas = regCargasModel::where(['PERIODO_ID'   => $request->periodo_id,
                                                'RECIBO_FOLIO' => $request->recibo_folio, 
                                                'CARGA'        => $id2])        
                         ->update([                
                'TKPAG_FOLAPROB' => $request->tkpag_folaprob,
                'TKPAG_TARJETA'  => $request->tkpag_tarjeta,  
                'TKPAG_FECHA'    => date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) )),
                'TKPAG_FECHA2'   => trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1),
                'PERIODO_ID1'    => $request->periodo_id1,                
                'MES_ID1'        => $request->mes_id1,
                'DIA_ID1'        => $request->dia_id1,                
                'TKPAG_HORA'     => $request->tkpag_hora,
                'TKPAG_IMPORTE'  => $request->tkpag_importe,
                'BANCO_ID'       => $request->banco_id,

                'TKBOMBA_TICKET' => strtoupper($request->tkbomba_ticket),                
                'TKBOMBA_CODIGO' => strtoupper($request->tkbomba_codigo),
                'TKBOMBA_RFC'    => strtoupper($request->tkbomba_rfc), 
                'TKBOMBA_FECHA'  => date('Y/m/d', strtotime(trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2) )),
                'TKBOMBA_FECHA2' => trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2),
                'PERIODO_ID2'    => $request->periodo_id2,                
                'MES_ID2'        => $request->mes_id2,
                'DIA_ID2'        => $request->dia_id2,

                'TKBOMBA_HORA'   => $request->tkbomba_hora,
                'TKBOMBA_IMPORTE'=> $request->tkbomba_importe,
                'FP_ID'          => $request->fp_id,
                'OBS_1'          => substr(trim(strtoupper($request->obs_1)),0,3999),

                'IP_M'           => $ip,
                'LOGIN_M'        => $nombre,
                'FECHA_M'        => date('Y/m/d')    //date('d/m/Y')                                
            ]);
            toastr()->success('Carga actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3001;
            $xtrx_id      =       156;    //Actualizar        
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $request->recibo_folio])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $request->recibo_folio;             // Folio    
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
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                                      'FOLIO' => $request->recibo_folio])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,
                                        'FOLIO' => $request->recibo_folio])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/

        return redirect()->route('verCargas',array($id, $id1)); 
    }


    public function actionEditarCarga1($id, $id1, $id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
        $regmarca     = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
                        ->get();   
        $regtipogasto = regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
                        ->get();                                                 
        $regtipooper  = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
                        ->get(); 
        $regfpagos    = regFpagosModel::select('FP_ID','FP_DESC')->orderBy('FP_ID','asc')
                        ->get();                           
        $regplaca     = regPlacaModel::select('PLACA_ID','PLACA_PLACA','PLACA_DESC','PLACA_SERIE','PLACA_ANTERIOR',
                        'PLACA_CILINDROS','MARCA_ID','TIPOO_ID','TIPOG_ID','SP_ID',
                        'DEPENDENCIA_ID','PLACA_MODELO','PLACA_MODELO2','PLACA_GASOLINA','PLACA_INVENTARIO',                               
                        'PLACA_OBS1','PLACA_OBS2','PLACA_FOTO1','PLACA_FOTO2',
                        'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','asc')
                        ->get();
        if($role->rol_name == 'user'){                                                
            $regrecibos=regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                        'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                        'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                        'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                        'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                        'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                        'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id,'RECIBO_FOLIO' => $id1,'LOGIN' => $nombre])
                        ->orderBy('RECIBO_FOLIO','asc')
                        ->get();                         
        }else{
            $regrecibos=regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                        'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                        'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                        'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                        'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                        'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                        'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id,'RECIBO_FOLIO' => $id1])
                        ->orderBy('RECIBO_FOLIO','asc')
                        ->get();               
        }                        
        $regcargas = regCargasModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA',
                        'PERIODO_ID', 'MES_ID','CARGA','TKPAG_FOLAPROB','TKPAG_TARJETA',
                        'TKPAG_FECHA','TKPAG_FECHA2',  'PERIODO_ID1','MES_ID1','DIA_ID1',
                        'TKPAG_HORA', 'TKPAG_IMPORTE', 'BANCO_ID',
                        'TKBOMBA_TICKET','TKBOMBA_CODIGO','TKBOMBA_RFC',
                        'TKBOMBA_FECHA','TKBOMBA_FECHA2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TKBOMBA_HORA', 'TKBOMBA_IMPORTE','FP_ID','OBS_1','CARGA_FOTO1','STATUS_1',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id,'RECIBO_FOLIO' => $id1, 'CARGA' => $id2])
                        //->orderBy('PERIODO_ID'  ,'asc')
                        //->orderBy('RECIBO_FOLIO','asc')
                        //->orderBy('CARGA'       ,'asc')
                        ->first();
        if($regcargas->count() <= 0){
            toastr()->error('No existe registro de carga en recibo de bitacora.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.recibo.editarCarga1',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regmarca','regtipogasto','regtipooper','regfpagos','regrecibos','regcargas'));
    }

    public function actionActualizarCarga1(carga1Request $request, $id, $id1, $id2){
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
        $regcargas = regCargasModel::where(['PERIODO_ID'   => $request->periodo_id,
                                            'RECIBO_FOLIO' => $request->recibo_folio, 
                                            'CARGA'        => $id2]);
        //dd('periodo:',$request->periodo_id,' folio:',$request->recibo_folio,' carga:',$id2);
        if($regcargas->count() <= 0)
            toastr()->error('No existe carga en recibo de bitacora.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //*********** Actualizar ****************************/
            $name1 =null;
            //   if(!empty($_PUT['recibo_rfoto1'])){
            if(isset($request->carga_foto1)){
                if(!empty($request->carga_foto1)){
                    //Comprobar  si el campo foto1 tiene un archivo asignado:
                    if($request->hasFile('carga_foto1')){
                        $name1 = $request->recibo_folio.'_'.$id2.'_'.$request->file('carga_foto1')->getClientOriginalName(); 
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('carga_foto1')->move(public_path().'/images/', $name1);

                        $regcargas = regCargasModel::where(['PERIODO_ID'   => $request->periodo_id,
                                                            'RECIBO_FOLIO' => $request->recibo_folio, 
                                                            'CARGA'        => $id2])        
                                     ->update([                
                                                'CARGA_FOTO1'    => $name1,

                                                'IP_M'           => $ip,
                                                'LOGIN_M'        => $nombre,
                                                'FECHA_M'        => date('Y/m/d')    //date('d/m/Y')                                
                                              ]);
                        toastr()->success('Archivo digital de la carga actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                        /************ Bitacora inicia *************************************/ 
                        setlocale(LC_TIME, "spanish");        
                        $xip          = session()->get('ip');
                        $xperiodo_id  = (int)date('Y');
                        $xprograma_id = 1;
                        $xmes_id      = (int)date('m');
                        $xproceso_id  =         3;
                        $xfuncion_id  =      3001;
                        $xtrx_id      =       156;    //Actualizar        
                        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO',           'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $request->recibo_folio])
                                        ->get();
                        if($regbitacora->count() <= 0){              // Alta
                            $nuevoregBitacora = new regBitacoraModel();              
                            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                            $nuevoregBitacora->FOLIO      = $request->recibo_folio;             // Folio    
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
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                                      'FOLIO' => $request->recibo_folio])
                                         ->max('NO_VECES');
                            $xno_veces = $xno_veces+1;                        
                            //*********** Termina de obtener el no de veces *****************************         
                            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                            'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,
                                            'FOLIO' => $request->recibo_folio])
                                           ->update([
                                                      'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                                      'IP_M'     => $regbitacora->IP       = $ip,
                                                      'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                                      'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                                   ]);
                            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                        }   /************ Bitacora termina *************************************/         
                 
                    }   // ************** hasfile(....)  **********//
                }       //*************** Empty() *****************//
            }           //*************** isset() *****************//

        }       /************ Termina de actualizar ********************************/

        return redirect()->route('verCargas',array($id, $id1)); 
    }


    // exportar a formato PDF
    public function actionExportReciboPdf($id,$id2,$id3){
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
        $xproceso_id  =         3;
        $xfuncion_id  =      3003;
        $xtrx_id      =       154;       //Exportar a formato PDF
        $id           =         0;
        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                       'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                       ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                'FOLIO' => $id3])
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
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                         'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                         'TRX_ID' => $xtrx_id, 'FOLIO' => $id3])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id, 
                                    'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                    'TRX_ID' => $xtrx_id,'FOLIO' => $id3])
                           ->update([
                                     'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M'    => $regbitacora->IP       = $ip,
                                     'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                     'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/ 

        //********* Validar rol de usuario **********************/
        $regmes      = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                       ->get();   
        $regdia      = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                       ->get();   
        $regquincena = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                       ->get(); 
        $regmarca    = regMarcaModel::select('MARCA_ID','MARCA_DESC')->orderBy('MARCA_ID','asc')
                       ->get();   
        $regtipogasto= regTipogastoModel::select('TIPOG_ID','TIPOG_DESC')->orderBy('TIPOG_ID','asc')
                       ->get();                                                 
        $regtipooper = regTipooperacionModel::select('TIPOO_ID','TIPOO_DESC')->orderBy('TIPOO_ID','asc')
                       ->get(); 
        $regfpagos   = regFpagosModel::select('FP_ID','FP_DESC')->orderBy('FP_ID','asc')
                       ->get();                            
        $regplaca    = regPlacaModel::join('COMB_CAT_TIPOGASTO','COMB_CAT_TIPOGASTO.TIPOG_ID','=',
                                                                'COMB_PLACAS.TIPOG_ID')
                       ->select('COMB_CAT_TIPOGASTO.TIPOG_DESC',
                                'COMB_PLACAS.PLACA_ID'  ,'COMB_PLACAS.PLACA_PLACA',
                                'COMB_PLACAS.PLACA_DESC','COMB_PLACAS.PLACA_SERIE',
                                'COMB_PLACAS.PLACA_CILINDROS',
                                'COMB_PLACAS.MARCA_ID',
                                'COMB_PLACAS.DEPENDENCIA_ID',
                                'COMB_PLACAS.PLACA_MODELO',
                                'COMB_PLACAS.PLACA_MODELO2',
                                'COMB_PLACAS.PLACA_GASOLINA',
                                'COMB_PLACAS.PLACA_INVENTARIO',
                                'COMB_PLACAS.TIPOO_ID',
                                'COMB_PLACAS.TIPOG_ID','COMB_PLACAS.PLACA_OBS1','COMB_PLACAS.PLACA_OBS2')
                       ->orderBy('COMB_PLACAS.PLACA_ID','asc')
                       ->get();   
        if($role->rol_name == 'user'){                
            $regrecibos=regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                        'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                        'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                        'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                        'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                        'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                        'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id2,'RECIBO_FOLIO' => $id3,'LOGIN' => $nombre])
                        ->get();
        }else{
            $regrecibos=regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                        'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                        'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                        'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                        'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                        'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                        'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id2,'RECIBO_FOLIO' => $id3])
                        ->get();       
        }                                              
        $regcargas   = regCargasModel::join('COMB_RECIBO_BIPADECO','COMB_RECIBO_BIPADECO.RECIBO_FOLIO','=',
                                                                   'COMB_RECIBO_CARGAS.RECIBO_FOLIO')
                       ->select('COMB_RECIBO_BIPADECO.PERIODO_ID','COMB_RECIBO_BIPADECO.RECIBO_FOLIO',
                             'COMB_RECIBO_BIPADECO.PLACA_ID'     ,'COMB_RECIBO_BIPADECO.PLACA_PLACA',
                             'COMB_RECIBO_BIPADECO.RECIBO_KI'    ,'COMB_RECIBO_BIPADECO.RECIBO_KF',
                             'COMB_RECIBO_BIPADECO.MES_ID'       ,'COMB_RECIBO_BIPADECO.QUINCENA_ID',
                             'COMB_RECIBO_BIPADECO.TIPOO_ID'     ,'COMB_RECIBO_BIPADECO.TARJETA_NO',
                             'COMB_RECIBO_BIPADECO.SP_NOMB'      ,'COMB_RECIBO_BIPADECO.RECIBO_IR',
                             'COMB_RECIBO_BIPADECO.RECIBO_I18'   ,'COMB_RECIBO_BIPADECO.RECIBO_I14',
                             'COMB_RECIBO_BIPADECO.RECIBO_I12'   ,'COMB_RECIBO_BIPADECO.RECIBO_I34',
                             'COMB_RECIBO_BIPADECO.RECIBO_IF'    ,'COMB_RECIBO_BIPADECO.RECIBO_FR',
                             'COMB_RECIBO_BIPADECO.RECIBO_F18'   ,'COMB_RECIBO_BIPADECO.RECIBO_F14',
                             'COMB_RECIBO_BIPADECO.RECIBO_F12'   ,'COMB_RECIBO_BIPADECO.RECIBO_F34',
                             'COMB_RECIBO_BIPADECO.RECIBO_FF'    ,'COMB_RECIBO_BIPADECO.RECIBO_FECINI2',
                             'COMB_RECIBO_BIPADECO.DIA_ID1'      ,'COMB_RECIBO_BIPADECO.RECIBO_FECFIN2',
                             'COMB_RECIBO_CARGAS.CARGA'          ,'COMB_RECIBO_CARGAS.TKPAG_FOLAPROB',
                             'COMB_RECIBO_CARGAS.TKPAG_TARJETA'  ,'COMB_RECIBO_CARGAS.TKPAG_FECHA',
                             'COMB_RECIBO_CARGAS.TKPAG_FECHA2'   ,'COMB_RECIBO_CARGAS.PERIODO_ID1',
                             'COMB_RECIBO_CARGAS.MES_ID1'        ,'COMB_RECIBO_CARGAS.DIA_ID1',
                             'COMB_RECIBO_CARGAS.TKPAG_HORA'     ,'COMB_RECIBO_CARGAS.TKPAG_IMPORTE', 
                             'COMB_RECIBO_CARGAS.BANCO_ID'       ,'COMB_RECIBO_CARGAS.TKBOMBA_TICKET',
                             'COMB_RECIBO_CARGAS.TKBOMBA_CODIGO' ,'COMB_RECIBO_CARGAS.TKBOMBA_RFC',
                             'COMB_RECIBO_CARGAS.TKBOMBA_FECHA'  ,'COMB_RECIBO_CARGAS.TKBOMBA_FECHA2',
                             'COMB_RECIBO_CARGAS.PERIODO_ID2'    ,'COMB_RECIBO_CARGAS.MES_ID2',
                             'COMB_RECIBO_CARGAS.DIA_ID2'        ,'COMB_RECIBO_CARGAS.TKBOMBA_HORA', 
                             'COMB_RECIBO_CARGAS.TKBOMBA_IMPORTE','COMB_RECIBO_CARGAS.FP_ID',
                             'COMB_RECIBO_CARGAS.OBS_1'          ,'COMB_RECIBO_CARGAS.STATUS_1')
                       ->where(['COMB_RECIBO_BIPADECO.PERIODO_ID' => $id2,'COMB_RECIBO_BIPADECO.RECIBO_FOLIO' => $id3])
                       ->orderBy('COMB_RECIBO_BIPADECO.PERIODO_ID'  ,'ASC')
                       ->orderBy('COMB_RECIBO_BIPADECO.RECIBO_FOLIO','ASC')
                       ->orderBy('COMB_RECIBO_CARGAS.CARGA'         ,'ASC')
                       ->get(); 
        //dd('REGISTRO:',$id,' llave2:',$id2,' llave2:',$id3);       
        //dd('REGISTRO:',$regcargas);       
        if($regcargas->count() <= 0){
            toastr()->error('No existen registros de carga en el recibo de bitacora.','Uppss!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('verTrx');
        }
        //$pdf = PDF::loadView('sicinar.pdf.cattrxPDF', compact('nombre','usuario','regplaca'));
        $pdf = PDF::loadView('sicinar.pdf.RecibobitacoraPdf', compact('nombre','usuario','regfpagos','regmes','regquincena','regplaca','regrecibos','regcargas'));
        //******** Horizontal ***************
        //$pdf->setPaper('A4', 'landscape');      
        //$pdf->set('defaultFont', 'Courier');          
        //$pdf->setPaper('A4','portrait');
        // Output the generated PDF to Browser
        //******** vertical *************** 
        //El tamaño de hoja se especifica en page_size puede ser letter, legal, A4, etc.         
        $pdf->setPaper('letter','portrait');      
        return $pdf->stream('Recibo');
    }



    public function actionActualizarRecibocom(reciboRequest $request, $id){
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
        $regrecibos = regReciboModel::where('RECIBO_FOLIO',$id);
        if($regrecibos->count() <= 0)
            toastr()->error('No existe recibo.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $name1 =null;
            //   if(!empty($_PUT['recibo_rfoto1'])){
            if(isset($request->recibo_rfoto1)){
                if(!empty($request->recibo_rfoto1)){
                    //Comprobar  si el campo foto1 tiene un archivo asignado:
                    if($request->hasFile('recibo_rfoto1')){
                      $name1 = $id.'_'.$request->file('recibo_rfoto1')->getClientOriginalName(); 
                      //sube el archivo a la carpeta del servidor public/images/
                      $request->file('recibo_rfoto1')->move(public_path().'/images/', $name1);
                    }
                }
            }

            //*********** Se obtiene la placa y el resguardatario   *****/
            $placa_placa = regPlacaModel::ObtPlaca($request->placa_id);
            $recibo_obs2 = regPlacaModel::ObtResguardatario($request->placa_id);
            $tipoo_id    = regPlacaModel::ObtTipoOperacion($request->placa_id);

            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);
            $mes2 = regMesesModel::ObtMes($request->mes_id2);
            $dia2 = regDiasModel::ObtDia($request->dia_id2);

            $regrecibos = regReciboModel::where('RECIBO_FOLIO',$id)        
            ->update([                
                //'PLACA_ID'       => $request->placa_id,
                //'PLACA_PLACA'    => $request->placa_placa,
                'RECIBO_KI'      => $request->recibo_ki,
                'RECIBO_KF'      => $request->recibo_kf,                
                'RECIBO_IR'      => $request->recibo_ir,
                'RECIBO_I18'     => $request->recibo_i18,                
                'RECIBO_I14'     => $request->recibo_i14,
                'RECIBO_I12'     => $request->recibo_i12,                
                'RECIBO_I34'     => $request->recibo_i34,
                'RECIBO_IF'      => $request->recibo_if, 

                'RECIBO_FR'      => $request->recibo_fr,
                'RECIBO_F18'     => $request->recibo_f18,                
                'RECIBO_F14'     => $request->recibo_f14,
                'RECIBO_F12'     => $request->recibo_f12,                
                'RECIBO_F34'     => $request->recibo_f34,
                'RECIBO_FF'      => $request->recibo_ff,                                
                'RECIBO_FECINI'  => date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) )),
                'RECIBO_FECINI2'  => trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1),
                'PERIODO_ID1'    => $request->periodo_id1,                
                'MES_ID1'        => $request->mes_id1,
                'DIA_ID1'        => $request->dia_id1,
                'RECIBO_FECINI'  =>date('Y/m/d', strtotime(trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2) )),
                'RECIBO_FECFIN2'  => trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2),  
                'PERIODO_ID2'    => $request->periodo_id2,                
                'MES_ID2'        => $request->mes_id2,
                'DIA_ID2'        => $request->dia_id2,

                //'TIPOO_ID'       => $tipoo_id[0]->tipoo_id,
                'TARJETA_NO'     => substr(trim(strtoupper($request->tarjeta_no)),0,29),  
                'PERIODO_ID'     => $request->periodo_id,  
                'MES_ID'         => $request->mes_id,           
                //'SP_NOMB'        => strtoupper($recibo_obs2[0]->placa_obs2),

                'RECIBO_OBS1'    => substr(trim(strtoupper($request->recibo_obs1)),0,99),
                //'RECIBO_OBS2'    => strtoupper($recibo_obs2[0]->placa_obs2),
                'QUINCENA_ID'    => $request->quincena_id,                
                'RECIBO_STATUS1'  => $request->recibo_status1, 
                //'PLACA_FOTO1'    => $name1, 
                'IP_M'           => $ip,
                'LOGIN_M'        => $nombre,
                'FECHA_M'        => date('Y/m/d')    //date('d/m/Y')                                
            ]);
            toastr()->success('Recibo actualizado correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3001;
            $xtrx_id      =       151;    //Actualizar        

            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
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
            }
            /************ Bitacora termina *************************************/         
        }

        return redirect()->route('verRecibos');
    }


public function actionSolicitarRecibocomp(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
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
                               'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','asc')
                        ->get();
        if($role->rol_name == 'user'){                                                
            $regrecibos=regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                        'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                        'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                        'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                        'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                        'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                        'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('LOGIN', $nombre)
                        //->where(['PERIODO_ID' => $id,'RECIBO_FOLIO' => $id1,'LOGIN' => $nombre])
                        ->orderBy('RECIBO_FOLIO','asc')
                        ->get();                         
        }else{
            $regrecibos=regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                        'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                        'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                        'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                        'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                        'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                        'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        //->where(['PERIODO_ID' => $id,'RECIBO_FOLIO' => $id1])
                        ->orderBy('RECIBO_FOLIO','asc')
                        ->get();               
        }                        
        $regcargas = regCargasModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA',
                        'PERIODO_ID', 'MES_ID','CARGA','TKPAG_FOLAPROB','TKPAG_TARJETA',
                        'TKPAG_FECHA','TKPAG_FECHA2',  'PERIODO_ID1','MES_ID1','DIA_ID1',
                        'TKPAG_HORA', 'TKPAG_IMPORTE', 'BANCO_ID',
                        'TKBOMBA_TICKET','TKBOMBA_CODIGO','TKBOMBA_RFC',
                        'TKBOMBA_FECHA','TKBOMBA_FECHA2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TKBOMBA_HORA', 'TKBOMBA_IMPORTE','FP_ID','OBS_1','STATUS_1',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        //->where(['PERIODO_ID' => $id,'RECIBO_FOLIO' => $id1, 'CARGA' => $id2])
                        ->orderBy('PERIODO_ID'  ,'asc')
                        ->orderBy('RECIBO_FOLIO','asc')
                        ->orderBy('CARGA'       ,'asc')
                        ->get();
        if($regcargas->count() <= 0){
            toastr()->error('No existen registros de carga en recibo de bitacora.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.comprobacion.solicitarRecibocomp',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regmarca','regtipogasto','regtipooper','regfpagos','regrecibos','regcargas'));
    }

public function actionEditarRecibocomp(recibo01Request $request){
        //dd('ya entre....');
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');

        $regperiodo   = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();   
        $regmes       = regMesesModel::select('MES_ID','MES_DESC')->orderBy('MES_ID','asc')
                        ->get();   
        $regdia       = regDiasModel::select('DIA_ID','DIA_DESC')->orderBy('DIA_ID','asc')
                        ->get();   
        $regquincena  = regQuincenaModel::select('QUINCENA_ID','QUINCENA_DESC')->orderBy('QUINCENA_ID','asc')
                        ->get(); 
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
                               'PLACA_STATUS1','PLACA_STATUS2')
                        ->orderBy('PLACA_ID','asc')
                        ->get();
        if($role->rol_name == 'user'){                                                
            $regrecibos=regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                        'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                        'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                        'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                        'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                        'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                        'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        //->where('LOGIN', $nombre])
                        ->where(['RECIBO_FOLIO' => $request->recibo_folio,'LOGIN' => $nombre])
                        ->orderBy('RECIBO_FOLIO','asc')
                        ->get();                         
        }else{
            $regrecibos=regReciboModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA','RECIBO_KI','RECIBO_KF',
                        'QUINCENA_ID','RECIBO_IR','RECIBO_I18','RECIBO_I14','RECIBO_I12','RECIBO_I34','RECIBO_IF',
                        'RECIBO_FR','RECIBO_F18','RECIBO_F14','RECIBO_F12','RECIBO_F34','RECIBO_FF',
                        'RECIBO_FECINI','RECIBO_FECINI2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'RECIBO_FECFIN','RECIBO_FECFIN2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TIPOO_ID','TARJETA_NO','PERIODO_ID','MES_ID','SP_ID','SP_NOMB', 
                        'RECIBO_RFOTO1','RECIBO_RFOTO2','RECIBO_RFOTO3','RECIBO_RFOTO4','RECIBO_RFOTO5',
                        'RECIBO_BFOTO1','RECIBO_BFOTO2','RECIBO_BFOTO3','RECIBO_BFOTO4','RECIBO_BFOTO5',
                        'RECIBO_OBS1','RECIBO_OBS2','RECIBO_STATUS1','RECIBO_STATUS2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('RECIBO_FOLIO',$request->recibo_folio)
                        ->orderBy('RECIBO_FOLIO','asc')
                        ->get();               
        }                        
        $regcargas = regCargasModel::select('RECIBO_FOLIO','PLACA_ID','PLACA_PLACA',
                        'PERIODO_ID', 'MES_ID','CARGA','TKPAG_FOLAPROB','TKPAG_TARJETA',
                        'TKPAG_FECHA','TKPAG_FECHA2',  'PERIODO_ID1','MES_ID1','DIA_ID1',
                        'TKPAG_HORA', 'TKPAG_IMPORTE', 'BANCO_ID',
                        'TKBOMBA_TICKET','TKBOMBA_CODIGO','TKBOMBA_RFC',
                        'TKBOMBA_FECHA','TKBOMBA_FECHA2','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'TKBOMBA_HORA', 'TKBOMBA_IMPORTE','FP_ID','OBS_1','STATUS_1',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('RECIBO_FOLIO', $request->recibo_folio)
                        ->orderBy('PERIODO_ID'  ,'asc')
                        ->orderBy('RECIBO_FOLIO','asc')
                        ->orderBy('CARGA'       ,'asc')
                        ->get();
                        //dd(''.$request->recibo_folio,'-'.$regcargas);
        if($regcargas->count() <= 0){
            toastr()->error('No existen registros de carga en recibo de bitacora.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.comprobacion.editarRecibocomp',compact('nombre','usuario','regperiodo','regmes','regdia','regquincena','regplaca','regmarca','regtipogasto','regtipooper','regrecibos','regcargas'));
    }

    public function actionActualizarRecibocomp(recibocompRequest $request ){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $role         = session()->get('role');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip            = session()->get('ip');

        // **************** actualizar ******************************
        $regrecibos = regReciboModel::where(['PERIODO_ID'   => $request->periodo_id,
                                             'RECIBO_FOLIO' => $request->recibo_folio]);
        //dd('periodo:',$request->periodo_id,' folio:',$request->recibo_folio,' carga:',$id2);
        if($regrecibos->count() <= 0)
            toastr()->error('No existe recibo de bitacora.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //*********** Se obtiene la placa y el resguardatario   *****/

            $regrecibos =regReciboModel::where(['PERIODO_ID'   => $request->periodo_id,
                                                 'RECIBO_FOLIO' => $request->recibo_folio])
                         ->update([                
                                   'RECIBO_STATUS2' => $request->recibo_status2,
                                   'RECIBO_OBS2'    => substr(trim(strtoupper($request->recibo_obs2)),0,3999),

                                   'IP_M'           => $ip,
                                   'LOGIN_M'        => $nombre,
                                   'FECHA_M'        => date('Y/m/d')    //date('d/m/Y')                                
                                  ]);
            toastr()->success('Recibo de bitacora comprobado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3005;
            $xtrx_id      =         2;    //Actualizar        
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $request->recibo_folio])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $request->recibo_folio;             // Folio    
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
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                                      'FOLIO' => $request->recibo_folio])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,
                                        'FOLIO' => $request->recibo_folio])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/

        return redirect()->route('solicitarRecibocomp'); 
    }


}
