<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regBancoModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportCatBancos implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID',
            'BANCO',
            'ESTADO',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
        return $regbancos = regBancoModel::select('BANCO_ID','BANCO_DESC', 'BANCO_STATUS','BANCO_FECREG')
            ->orderBy('BANCO_ID','asc')->get();                                
    }
}
