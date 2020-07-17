<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regInmuebleedoModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportCatInmueblesedo implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID',
            'INMUEBLE_EDO',
            'ESTADO',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
        return $reginmuebleedo = regInmuebleedoModel::select('INM_ID','INM_DESC', 'INM_STATUS','INM_FECREG')
            ->orderBy('INM_ID','desc')->get();                                
    }
}
