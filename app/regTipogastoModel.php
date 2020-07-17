<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regTipogastoModel extends Model
{
    protected $table      = "COMB_CAT_TIPOGASTO";
    protected $primaryKey = 'TIPOG_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'TIPOG_ID',
        'TIPOG_DESC',
        'TIPOG_STATUS',
        'FECREG'
    ];
}  