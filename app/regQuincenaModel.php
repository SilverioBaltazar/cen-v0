<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regQuincenaModel extends Model
{
    protected $table      = "COMB_CAT_QUINCENAS";
    protected $primaryKey = 'QUINCENA_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'QUINCENA_ID',
        'QUINCENA_DESC',
        'MES_ID_QAPLICA',
        'QUINCENA_STATUS',
        'FECREG'
    ];
 

}