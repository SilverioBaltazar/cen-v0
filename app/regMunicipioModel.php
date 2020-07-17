<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regMunicipioModel extends Model
{
    protected $table      = "CEN_CAT_MUNICIPIOS";
    protected $primaryKey = ['ENTIDADFEDERATIVAID','MUNICIPIOID'];
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'ENTIDADFEDERATIVAID',
        'MUNICIPIOID',
        'MUNICIPIONOMBRE', //S ACTIVO      N INACTIVO
        'REGIONID'
    ];
}