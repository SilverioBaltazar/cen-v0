<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regPlacaModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class excelPlacas implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
        'CODIGO',
        'PLACAS',
        'VEHICULO',
        'SERIE_MOTOR',
        'PLACA_ANTERIOR',
        'NO_CILINDROS',
        'MODELO',
        'TIPO_GASOLINA',
        'INVENTARIO',        
        'MARCA',
        'TIPO_GASTO',
        'TIPO_OP_ADMON',
        'UNIDAD_ADMON_ID',         
        'UNIDAD_ADMON',
        'RESGUARDATARIO',
        'PLACA_ACTIVA',
        'FECHA_REG'
        ];
    }

    public function collection()
    {
      return regPlacaModel::join('COMB_CAT_MARCAS'       ,'COMB_CAT_MARCAS.MARCA_ID'       ,'=','COMB_PLACAS.MARCA_ID')
                          ->join('COMB_CAT_TIPOGASTO'    ,'COMB_CAT_TIPOGASTO.TIPOG_ID'    ,'=','COMB_PLACAS.TIPOG_ID')
                          ->join('COMB_CAT_TIPOOPERACION','COMB_CAT_TIPOOPERACION.TIPOO_ID','=','COMB_PLACAS.TIPOO_ID')
                        ->select('COMB_PLACAS.PLACA_ID'  ,'COMB_PLACAS.PLACA_PLACA'   ,'COMB_PLACAS.PLACA_DESC',
                                 'COMB_PLACAS.PLACA_SERIE','COMB_PLACAS.PLACA_ANTERIOR','COMB_PLACAS.PLACA_CILINDROS',
                                 'COMB_PLACAS.PLACA_MODELO2',
                                 'COMB_PLACAS.PLACA_GASOLINA',
                                 'COMB_PLACAS.PLACA_INVENTARIO',
                                 'COMB_CAT_MARCAS.MARCA_DESC',
                                 'COMB_CAT_TIPOGASTO.TIPOG_DESC',
                                 'COMB_CAT_TIPOOPERACION.TIPOO_DESC',
                                 'COMB_PLACAS.DEPENDENCIA_ID',
                                 'COMB_PLACAS.PLACA_OBS1',           'COMB_PLACAS.PLACA_OBS2',
                                 'COMB_PLACAS.PLACA_STATUS1',        'COMB_PLACAS.FECREG')
                       ->orderBy('COMB_PLACAS.PLACA_ID','ASC')
                       ->get();                               
    }
}
