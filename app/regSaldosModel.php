<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regSaldosModel extends Model
{
    protected $table      = "CEN_SALDOS_CLIENTES";
    protected $primaryKey = 'CLIENTE_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PERIODO_ID',
        'CLIENTE_ID',
        'CARGO_M01',
        'ABONO_M01',
        'CARGO_M02',
        'ABONO_M02',
        'CARGO_M03',
        'ABONO_M03',
        'CARGO_M04',
        'ABONO_M04',
        'CARGO_M05',
        'ABONO_M05',
        'CARGO_M06',
        'ABONO_M06',
        'CARGO_M07',
        'ABONO_M07',
        'CARGO_M08',
        'ABONO_M08',
        'CARGO_M09',
        'ABONO_M09',
        'CARGO_M10',
        'ABONO_M10',
        'CARGO_M11',
        'ABONO_M11',
        'CARGO_M12',
        'ABONO_M12',
        'SALDO',
        'STATUS_1',
        'STATUS_2',
        'FECREG',
        'USU',
        'IP',
        'FECHA_M',
        'USU_M',
        'IP_M'
    ];
}

