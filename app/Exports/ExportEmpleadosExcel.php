<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regEmpleadosModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportEmpleadosExcel implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID_SISTEMA',
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
            //                                                        'CEN_EMPLEADOS.MUNICIPIO_ID') 
            //                ->wherein('JP_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9,15,22])
            return regEmpleadosModel::join('CEN_CAT_ENTIDADES_FED','CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
                                                                   'CEN_EMPLEADOS.ENTIDADNAC_ID')
                            ->join(        'CEN_CAT_MUNICIPIOS',   'CEN_CAT_MUNICIPIOS.MUNICIPIOID','=',
                                                                   'CEN_EMPLEADOS.MUNICIPIO_ID') 
                            ->wherein('CEN_CAT_MUNICIPIOS.ENTIDADFEDERATIVAID',[15])
                            ->select( 'CEN_EMPLEADOS.EMP_ID',
                                      'CEN_EMPLEADOS.EMP_FECING', 
                                      'CEN_EMPLEADOS.EMP_NOMBRES',
                                      //'CEN_EMPLEADOS.FECHA_NACIMIENTO2',     
                                      'CEN_EMPLEADOS.EMP_CURP',     
                                      'CEN_EMPLEADOS.EMP_SEXO',     
                                      'CEN_EMPLEADOS.EMP_DOM',
                                      'CEN_EMPLEADOS.EMP_COL',
                                      'CEN_EMPLEADOS.LOCALIDAD',                                      
                                      'CEN_EMPLEADOS.EMP_CP', 
                                      'CEN_EMPLEADOS.EMP_OTRAREF',   
                                      'CEN_EMPLEADOS.EMP_TEL',
                                      'CEN_EMPLEADOS.EMP_CEL',                                                                        
                                      'CEN_EMPLEADOS.EMP_EMAIL',
                                      'CEN_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
                                      'CEN_EMPLEADOS.LOCALIDAD',         
                                      'CEN_CAT_MUNICIPIOS.MUNICIPIONOMBRE', 
                                      'CEN_EMPLEADOS.EMP_STATUS1',  
                                      'CEN_EMPLEADOS.FECREG'
                                     )
                            ->orderBy('CEN_EMPLEADOS.EMP_NOMBRECOMPLETO','ASC')
                            ->get();                               
        //}else{
        //    return regPadronModel::join('JP_CAT_ENTIDADES_FED','JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
        //                                                       'CEN_EMPLEADOS.ENTIDAD_FED_ID')
        //                    ->join('JP_CAT_SERVICIOS'  ,'JP_CAT_SERVICIOS.SERVICIO_ID','=','CEN_EMPLEADOS.SERVICIO_ID')
        //                    ->join('JP_IAPS'           ,'JP_IAPS.IAP_ID'              ,'=','CEN_EMPLEADOS.IAP_ID')
        //                    ->select('CEN_EMPLEADOS.FOLIO',
        //                             'JP_IAPS.IAP_DESC'        ,  
        //                             'CEN_EMPLEADOS.FECHA_INGRESO2', 
        //                             'CEN_EMPLEADOS.PRIMER_APELLIDO',
        //                             'CEN_EMPLEADOS.SEGUNDO_APELLIDO',
        //                             'CEN_EMPLEADOS.NOMBRES',
        //                             'CEN_EMPLEADOS.FECHA_NACIMIENTO2',     
        //                             'CEN_EMPLEADOS.CURP',     
        //                             'CEN_EMPLEADOS.SEXO',     
        //                             'CEN_EMPLEADOS.DOMICILIO',     
        //                             'CEN_EMPLEADOS.CP', 
        //                             'CEN_EMPLEADOS.COLONIA',
        //                             'JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
        //                             'CEN_EMPLEADOS.LOCALIDAD',          
        //                             'CEN_EMPLEADOS.MOTIVO_ING',
        //                             'CEN_EMPLEADOS.INTEG_FAM', 
        //                             'JP_CAT_SERVICIOS.SERVICIO_DESC', 
        //                             'CEN_EMPLEADOS.CUOTA_RECUP',
        //                             'CEN_EMPLEADOS.QUIEN_CANALIZO', 
        //                             'CEN_EMPLEADOS.STATUS_1',
        //                             'CEN_EMPLEADOS.FECHA_REG'
        //                            )
        //                    ->where('CEN_EMPLEADOS.IAP_ID',$arbol_id)
        //                    ->orderBy('CEN_EMPLEADOS.NOMBRE_COMPLETO','ASC')
        //                    ->get();               
        //}                            
    }
}
