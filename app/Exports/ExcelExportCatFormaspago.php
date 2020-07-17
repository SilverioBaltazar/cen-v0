<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regFpagoModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportCatFormaspago implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID',
            'FORMA_PAGO',
            'ESTADO',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
        return $regformaspago = regFpagoModel::select('FPAGO_ID','FPAGO_DESC', 'FPAGO_STATUS','FPAGO_FECREG')
            ->orderBy('FPAGO_ID','asc')->get();                                
    }
}
