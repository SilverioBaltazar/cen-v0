<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regTipocreditoModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportCatTipocredito implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID',
            'TIPO_CREDITO',
            'DIAS_CREDITO',
            'ESTADO',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
        return $regtipocredito = regTipocreditoModel::select('TIPOCREDITO_ID','TIPOCREDITO_DESC', 'TIPOCREDITO_DIAS','TIPOCREDITO_STATUS','TIPOCREDITO_FECREG')
            ->orderBy('TIPOCREDITO_ID','asc')->get();                                
    }
}
