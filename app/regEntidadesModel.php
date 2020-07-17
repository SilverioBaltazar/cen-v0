<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regEntidadesModel extends Model
{
    protected $table      = "CEN_CAT_ENTIDADES_FED";
    protected $primaryKey = 'ENTIDADFEDERATIVA_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'ENTIDADFEDERATIVA_ID',
        'ENTIDADFEDERATIVA_DESC',
        'ENTIDADFEDERATIVA_ABREVIACION'
    ];
}