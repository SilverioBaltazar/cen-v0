<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regIapModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportCatIaps implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'IAP_ID',
            'NOMBRE',
            'DOMICILIO_LEGAL',
            'DOMICILIO_FISCAL',
            'DOMICILIO_ASISTENCIAL',
            'COLONIA',
            'ENTIDADFEDERATIVA_ID',
            'ENTIDAD_FEDERATIVA',
            'MUNICIPIO_ID',
            'MUNICIPIO',
            'RUBRO_ID',
            'RUBRO',
            'REGISTRO_CONSTITUCION',
            'RFC',
            'CP',
            'FECHA_CONSTITUCION',
            'TELEFONO',
            'EMAIL',
            'SITIO_WEB',
            'PRESIDENTE',
            'REPRESENTANTE_LEGAL',
            'SRIO',
            'TESORERO',
            'OBJETO_SOCIAL',
            'STATUS',
            'FECHA_CERTIFICACION',
            'FECHA_REGISTRO'
        ];
    }

    public function collection()
    {
       // return regIapModel::join('CAT_MUNICIPIOS_SEDESEM',[['CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
       //                                                    ['CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','JP_IAPS.MUNICIPIO_ID']])
        return regIapModel::join('CAT_MUNICIPIOS_SEDESEM','CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','JP_IAPS.MUNICIPIO_ID') 
                            ->wherein('CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9,15,22])
                            ->join('CAT_ENTIDADES_FEDERATIVAS','CAT_ENTIDADES_FEDERATIVAS.ENTIDADFEDERATIVA_ID', '=', 
                                                                        'CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                          ->join('JP_CAT_RUBROS'         ,'JP_CAT_RUBROS.RUBRO_ID','=','JP_IAPS.RUBRO_ID')
                          ->select('JP_IAPS.IAP_ID', 'JP_IAPS.IAP_DESC', 'JP_IAPS.IAP_DOM1','JP_IAPS.IAP_DOM2',
                              'JP_IAPS.IAP_DOM3',    'JP_IAPS.IAP_COLONIA', 
                              'JP_IAPS.ENTIDADFEDERATIVA_ID','CAT_ENTIDADES_FEDERATIVAS.ENTIDADFEDERATIVA_DESC',
                              'JP_IAPS.MUNICIPIO_ID','CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE',         
                              'JP_IAPS.RUBRO_ID',    'JP_CAT_RUBROS.RUBRO_DESC', 'JP_IAPS.IAP_REGCONS', 'JP_IAPS.IAP_RFC', 
                              'JP_IAPS.IAP_CP',      'JP_IAPS.IAP_FECCONS',      'JP_IAPS.IAP_TELEFONO','JP_IAPS.IAP_EMAIL',
                              'JP_IAPS.IAP_SWEB',    'JP_IAPS.IAP_PRES',         'JP_IAPS.IAP_REPLEGAL','JP_IAPS.IAP_SRIO', 
                              'JP_IAPS.IAP_TESORERO','JP_IAPS.IAP_OBJSOC',       'JP_IAPS.IAP_STATUS',  
                              'JP_IAPS.IAP_FECCERTIFIC','JP_IAPS.IAP_FECREG')
                            ->orderBy('JP_IAPS.IAP_ID','DESC')
                            ->get();                               
    }
}
