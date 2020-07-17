<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regClientesModel extends Model 
{
    protected $table      = "CEN_CLIENTES";
    protected $primaryKey = 'CLIENTE_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PERIODO_ID',
        'CLIENTE_ID',
        'CLIENTE_FOLIO',
        'CLIENTE_AP',
        'CLIENTE_AM',
        'CLIENTE_NOMBRES',
        'CLIENTE_NOMBRECOMPLETO',
        'CLIENTE_CURP',
        'CLIENTE_FECING',
        'CLIENTE_FECING2',
        'CLIENTE_FECNAC',
        'CLIENTE_FECNAC2',
        'CLIENTE_SEXO',
        'CLIENTE_RFC',
        'CLIENTE_IDOFICIAL',
        'CLIENTE_DOM',
        'CLIENTE_COL',
        'CLIENTE_CP',
        'CLIENTE_ENTRECALLE',
        'CLIENTE_YCALLE',
        'CLIENTE_OTRAREF',
        'CLIENTE_TEL',
        'CLIENTE_CEL',
        'CLIENTE_EMAIL',
        'ENTIDADNAC_ID',
        'ENTIDADFED_ID',
        'MUNICIPIO_ID',
        'LOCALIDAD_ID',
        'LOCALIDAD',
        'EDOCIVIL_ID',
        'GRADOESTUDIOS_ID',
        'CLIENTE_PUESTO',
        'TIPOCLIENTE_ID',
        'CLASECLIENTE_ID',
        'CLIENTE_OBS1',
        'CLIENTE_OBS2',
        'CLIENTE_FOTO1',
        'CLIENTE_FOTO2',
        'CLIENTE_STATUS1',
        'CLIENTE_STATUS2',
        'CLIENTE_GEOREFLATITUD',
        'CLIENTE_GEOREFLONGITUD',
        'FECREG',
        'IP',
        'USU',
        'FECHA_M',
        'IP_M',
        'USU_M'
    ];

    public static function ObtCliente($id){
        return (regClientesModel::select('CLIENTE_NOMBRECOMPLETO')->where('CLIENTE_ID','=',$id)
                                ->get());
    }

    public static function obtcliEntfed($id){
        return regClientesModel::select('ENTIDADFED_ID')->where('CLIENTE_ID','=', $id)
                               ->get();
    }

    public static function obtcliMpio($id){
        return regClientesModel::select('MUNICIPIO_ID')->where('CLIENTE_ID','=', $id)
                               ->get();
    }

    public static function obtcliCol($id){
        return regClientesModel::select('CLIENTE_COL')->where('CLIENTE_ID','=', $id)
                               ->get();
    }

    public static function obtcliLoc($id){
        return regClientesModel::select('LOCALIDAD')->where('CLIENTE_ID','=', $id)
                               ->get();
    }

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopeName($query, $name)
    {
        if($name)
            return $query->where('CLIENTE_NOMBRECOMPLETO', 'LIKE', "%$name%");
    }

    public function scopeEmail($query, $email)
    {
        if($email)
            return $query->where('IAP_EMAIL', 'LIKE', "%$email%");
    }

    public function scopeBio($query, $bio)
    {
        if($bio)
            return $query->where('IAP_OBJSOC', 'LIKE', "%$bio%");
    } 

}