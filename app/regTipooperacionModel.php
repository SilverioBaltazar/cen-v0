<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regTipooperacionModel extends Model
{
    protected $table      = "COMB_CAT_TIPOOPERACION";
    protected $primaryKey = 'TIPOO_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'TIPOO_ID',
        'TIPOO_DESC',
        'TIPOO_STATUS',
        'FECREG'
    ];
}  