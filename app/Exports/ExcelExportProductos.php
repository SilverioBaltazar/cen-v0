<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regProductoModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportProductos implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID',
            'CODIGO',
            'PRODUCTO',
            'PRECIO_COMPRA',
            'PRECIO_VENTA',
            'EXISTENCIA',
            'ESTADO',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
        return $producto = regProductoModel::select('id','codigo_barras', 'descripcion', 'precio_compra', 'precio_venta', 'existencia',
                                                    'prod_status','prod_fecreg')
                           ->orderBy('ID','asc')->get();                                
    }
}
