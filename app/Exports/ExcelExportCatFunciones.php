<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regFuncionModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportCatFunciones implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID_PROCESO',
            'PROCESO',            
            'ID_FUNCION',
            'FUNCION',
            'ESTADO',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
         return regfuncionModel::join('CEN_CAT_PROCESOS','CEN_CAT_PROCESOS.PROCESO_ID','=',
                                                         'CEN_CAT_FUNCIONES.PROCESO_ID')
                            ->select( 'CEN_CAT_FUNCIONES.PROCESO_ID','CEN_CAT_PROCESOS.PROCESO_DESC',
                                      'CEN_CAT_FUNCIONES.FUNCION_ID','CEN_CAT_FUNCIONES.FUNCION_DESC',
                                      'CEN_CAT_FUNCIONES.FUNCION_STATUS','CEN_CAT_FUNCIONES.FUNCION_FECREG')
                            ->orderBy('CEN_CAT_FUNCIONES.PROCESO_ID','ASC')
                            ->orderBy('CEN_CAT_FUNCIONES.FUNCION_ID','ASC')
                            ->get();                               
    }
}
