<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
//use Maatwebsite\Excel\Concerns\FromArray;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
//use Maatwebsite\Excel\Concerns\WithTitle;

//use App\regEfacturaModel;
//use App\regClientesModel;
//use App\regSaldosModel;

//use App\regEmpleadosModel;
//use App\regSaldosempModel;
//use App\regTipocreditoModel;
//use App\regMesesModel;
//use App\regPeriodosModel;

// class ExcelExportProgramavisitas implements FromQuery,  WithHeadings   ojo jala con el query************
//class ExcelExportCobranzaFacturas implements FromCollection, /*FromQuery,*/ WithHeadings, WithTitle
//class ExcelExportCobranzaFacturas implements FromQuery, WithHeadings /*, WithTitle */
//class ExcelExportCobranzaFacturas implements FromArray 
class ExcelExportCobranzaFacturas implements FromCollection, WithHeadings
{

    use Exportable;

    //********** Parámetros de filtro del query *******************//
    //protected $regfactura;
    protected $perr;
    protected $mess;
    protected $diaa;
    protected $cliee;
    //protected $folioo;
    //protected $statuss;
 
    public function __construct($regfactura, $perr, $mess, $diaa, $cliee)
    {
        $this->regfactura = $regfactura;
        $this->perr      = $perr;
        $this->mess      = $mess;
        $this->diaa      = $diaa;
        //$this->empp    = $empp;
        $this->cliee     = $cliee;
        //$this->folioo    = $folioo;
        //$this->statuss   = $statuss;
    }

    public function array(): array
    {
        return [
            $this->regfactura
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'PERIODO_FISCAL',
            'MES_ID',
            'MES',
            'DIA',            
            'FOLIO_FACT',  
            'IMPORTE',
            'ACUM_PAGOS',
            'SALDO',
            'FECHA_REGISTRO',             
            'FECHA_PROXIMO_PAGO',        
            'CLIENTE_ID',
            'CLIENTE',
            'DOMICILIO',
            'COLONIA',
            'CP',
            'TELEFONO',
            'CELULAR',
            'STATUS_FACTURA'
        ];
    }


    public function collection()
    {
        //dd('reg:'.$this->regfactura, 'Periodo:'.$this->perr,'Mes:'.$this->mess,'Dia:'.$this->diaa,'Cliente:'.$this->cliee);
        //$arbol_id     = session()->get('arbol_id');  
        //$id           = session()->get('sfolio');   
        return $this->regfactura;
        //return regEfacturaModel::get();                
    }

    /**
     * @return string
     */
    //public function title(): string
    //{
    //    return 'Mes ' . $this->mess;
    //}

    public function query()
    {
        //return  regEfacturaModel::query()
        $regfactura = $this->regfactura;
        return  regEfacturaModel::query()
                                ->get();                                                           
    }

    //public function query()
    //{
    //    return  regAgendaModel::query()
    //            ->where( ['PERIODO_ID'   => $this->periodo, 
    //                      'MES_ID'       => $this->mes,
    //                      'VISITA_TIPO1' => $this->tipo]);   
    //                        //->get();                                                           
    //}

}
