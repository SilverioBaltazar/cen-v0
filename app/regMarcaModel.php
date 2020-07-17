<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regMarcaModel extends Model
{
    protected $table      = "COMB_CAT_MARCAS";
    protected $primaryKey = 'MARCA_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'MARCA_ID',
        'MARCA_DESC',
        'MARCA_STATUS',
        'FECREG'
    ];
}  