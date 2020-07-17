<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regEmpleadosModel extends Model
{
    protected $table      = "CEN_EMPLEADOS";
    protected $primaryKey = ['PERIODO_ID','EMP_ID'];
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PERIODO_ID',
        'PROGRAMA_ID',
        'EMP_ID',
        'DEPTO_ID',
        'EMP_AP',
        'EMP_AM',
        'EMP_NOMBRES',
        'EMP_NOMBRECOMPLETO',
        'EMP_CURP',
        'EMP_FECING',
        'EMP_FECING2',
        'EMP_FECNAC',
        'EMP_FECNAC2',
        'EMP_SEXO',
        'EMP_RFC',
        'EMP_IDOFICIAL',
        'EMP_DOM',
        'EMP_COL',
        'EMP_CP',
        'EMP_ENTRECALLE',
        'EMP_YCALLE',
        'EMP_OTRAREF',
        'EMP_TEL',
        'EMP_CEL',
        'EMP_EMAIL',
        'ENTIDADNAC_ID',
        'ENTIDADFED_ID',
        'MUNICIPIO_ID',
        'LOCALIDAD_ID',
        'LOCALIDAD',
        'EDOCIVIL_ID',
        'GRADOESTUDIOS_ID',
        'EMP_PUESTO',
        'TIPOEMP_ID',
        'CLASEEMP_ID',
        'EMP_SUELDO',
        'EMP_OBS1',
        'EMP_OBS2',
        'EMP_FOTO1',
        'EMP_FOTO2',
        'EMP_STATUS1',
        'EMP_STATUS2',
        'FECREG',
        'IP',
        'USU',
        'FECHA_M',
        'IP_M',
        'USU_M'
    ];

    public static function ObtpersonalIap($id){
        return (regPadronModel::select('IAP_ID')->where('IAP_ID','=',$id)
                                ->get());
    }

    public static function obtMunicipios($id){
        return regPadronModel::select('ENTIDADFEDERATIVAID','MUNICIPIOID','MUNICIPIONOMBRE')
                               ->where('ENTIDADFEDERATIVAID','=', $id)
                               ->orderBy('MUNICIPIOID','asc')
                               ->get();
    }

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopeName($query, $name)
    {
        if($name)
            return $query->where('EMP_NOMBRECOMPLETO', 'LIKE', "%$name%");
    }

    public function scopeEmail($query, $email)
    {
        if($email)
            return $query->where('EMP_EMAIL', 'LIKE', "%$email%");
    }

    public function scopeBio($query, $bio)
    {
        if($bio)
            return $query->where('IAP_OBJSOC', 'LIKE', "%$bio%");
    } 

}