<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regClientesModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportClientesExcel implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID_SISTEMA',
            'FOLIO_SOLICITUD',
            'FECHA_INGRESO',
            'NOMBRE',
            'CURP',
            'SEXO',
            'DOMICILIO',       
            'COLONIA',
            'LOCALIDAD',
            'CP',                 
            'OTRA_REFERENCIA',   
            'TELEFONO',
            'CELULAR',            
            'EMAIL',            
            'ENTIDAD_NACIMIENTO',
            'MUNICIPIO',
            'ESTADO',
            'FEC_REG'
        ];
    }

    public function collection()
    {
        //$arbol_id     = session()->get('arbol_id');        
        //********* Validar rol de usuario **********************/
        //if(session()->get('rango') !== '0'){                          
            //return regPadronModel::join('JP_CAT_MUNICIPIOS_SEDESEM','JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=',
            //                                                        'CEN_CLIENTES.MUNICIPIO_ID') 
            //                ->wherein('JP_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9,15,22])
            return regClientesModel::join('CEN_CAT_ENTIDADES_FED','CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
                                                                  'CEN_CLIENTES.ENTIDADNAC_ID')
                            ->join(       'CEN_CAT_MUNICIPIOS',   'CEN_CAT_MUNICIPIOS.MUNICIPIOID','=',
                                                                  'CEN_CLIENTES.MUNICIPIO_ID') 
                            ->wherein('CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID',[15])
                            ->select( 'CEN_CLIENTES.CLIENTE_ID',
                                      'CEN_CLIENTES.CLIENTE_FOLIO',  
                                      'CEN_CLIENTES.CLIENTE_FECING', 
                                      'CEN_CLIENTES.CLIENTE_NOMBRES',
                                      //'CEN_CLIENTES.FECHA_NACIMIENTO2',     
                                      'CEN_CLIENTES.CLIENTE_CURP',     
                                      'CEN_CLIENTES.CLIENTE_SEXO',     
                                      'CEN_CLIENTES.CLIENTE_DOM',
                                      'CEN_CLIENTES.CLIENTE_COL',
                                      'CEN_CLIENTES.LOCALIDAD',                                      
                                      'CEN_CLIENTES.CLIENTE_CP', 
                                      'CEN_CLIENTES.CLIENTE_OTRAREF',   
                                      'CEN_CLIENTES.CLIENTE_TEL',
                                      'CEN_CLIENTES.CLIENTE_CEL',                                                                        
                                      'CEN_CLIENTES.CLIENTE_EMAIL',
                                      'CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
                                      'CEN_CLIENTES.LOCALIDAD',         
                                      'CEN_CAT_MUNICIPIOS.MUNICIPIONOMBRE', 
                                      'CEN_CLIENTES.CLIENTE_STATUS1',  
                                      'CEN_CLIENTES.FECREG'
                                     )
                            ->orderBy('CEN_CLIENTES.CLIENTE_NOMBRECOMPLETO','ASC')
                            ->get();                               
        //}else{
        //    return regPadronModel::join('JP_CAT_ENTIDADES_FED','JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
        //                                                       'CEN_CLIENTES.ENTIDAD_FED_ID')
        //                    ->join('JP_CAT_SERVICIOS'  ,'JP_CAT_SERVICIOS.SERVICIO_ID','=','CEN_CLIENTES.SERVICIO_ID')
        //                    ->join('JP_IAPS'           ,'JP_IAPS.IAP_ID'              ,'=','CEN_CLIENTES.IAP_ID')
        //                    ->select('CEN_CLIENTES.FOLIO',
        //                             'JP_IAPS.IAP_DESC'        ,  
        //                             'CEN_CLIENTES.FECHA_INGRESO2', 
        //                             'CEN_CLIENTES.PRIMER_APELLIDO',
        //                             'CEN_CLIENTES.SEGUNDO_APELLIDO',
        //                             'CEN_CLIENTES.NOMBRES',
        //                             'CEN_CLIENTES.FECHA_NACIMIENTO2',     
        //                             'CEN_CLIENTES.CURP',     
        //                             'CEN_CLIENTES.SEXO',     
        //                             'CEN_CLIENTES.DOMICILIO',     
        //                             'CEN_CLIENTES.CP', 
        //                             'CEN_CLIENTES.COLONIA',
        //                             'JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
        //                             'CEN_CLIENTES.LOCALIDAD',          
        //                             'CEN_CLIENTES.MOTIVO_ING',
        //                             'CEN_CLIENTES.INTEG_FAM', 
        //                             'JP_CAT_SERVICIOS.SERVICIO_DESC', 
        //                             'CEN_CLIENTES.CUOTA_RECUP',
        //                             'CEN_CLIENTES.QUIEN_CANALIZO', 
        //                             'CEN_CLIENTES.STATUS_1',
        //                             'CEN_CLIENTES.FECHA_REG'
        //                            )
        //                    ->where('CEN_CLIENTES.IAP_ID',$arbol_id)
        //                    ->orderBy('CEN_CLIENTES.NOMBRE_COMPLETO','ASC')
        //                    ->get();               
        //}                            
    }
}
