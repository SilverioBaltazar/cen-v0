<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regPerModel extends Model
{
    protected $table      = "JP_CAT_PERIODICIDAD";
    protected $primaryKey = 'PER_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PER_ID',
        'PER_DESC',
        'PER_STATUS', //S ACTIVO      N INACTIVO
        'FECREG'
    ];
}