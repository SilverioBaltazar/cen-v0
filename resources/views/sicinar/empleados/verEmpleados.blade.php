@extends('sicinar.principal')

@section('title','Ver empleados')

@section('links')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('nombre')
    {{$nombre}}
@endsection

@section('usuario')
    {{$usuario}}
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Empleados
                <small> Seleccionar para editar o registrar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Recursos Humanos   </a></li>   
                <li><a href="#">Empleados </a></li>               
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="page-header" style="text-align:right;">
                            Buscar  
                            {{ Form::open(['route' => 'buscarEmpleado', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Empleado']) }}
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <a href="{{route('ExportEmpleadosExcel')}}" class="btn btn-success" title="Exportar catalogo de empleados (formato Excel)"><i class="fa fa-file-excel-o"></i> Excel
                                    </a>                            
                                    <a href="{{route('nuevoEmpleado')}}" class="btn btn-primary btn_xs" title="Nuevo empleado"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nuevo empleado
                                    </a>
                                </div>                                
                            {{ Form::close() }}
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id             </th>
                                        <th style="text-align:left;   vertical-align: middle;">Empleado       </th>
                                        <th style="text-align:left;   vertical-align: middle;">CURP           </th>
                                        <th style="text-align:left;   vertical-align: middle;">Domicilio      </th>
                                        <th style="text-align:left;   vertical-align: middle;">Municipio      </th>
                                        <th style="text-align:center; vertical-align: middle;">Solicitud      </th>                                        
                                        <th style="text-align:center; vertical-align: middle;">Activa <br>Inact.</th>
                                        
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regempleado as $emp)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$emp->emp_id}}    </td>
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($emp->emp_nombrecompleto)}}
                                        </td>                                        
                                        <td style="text-align:left; vertical-align: middle;">{{$emp->emp_curp}}  </td>
                                        <td style="text-align:left; vertical-align: middle;">{{$emp->emp_dom}}   </td>
                                        <td style="text-align:left; vertical-align: middle;">                                                        
                                        @foreach($regmunicipio as $mun)
                                            @if($mun->municipioid == $emp->municipio_id)
                                                {{$mun->municipionombre}}
                                                @break
                                            @endif
                                        @endforeach
                                        </td>                                          

                                        @if(!empty(trim($emp->emp_foto1))&&(!is_null($emp->emp_foto1)))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Formato de solicitud">
                                                <a href="/images/{{$emp->emp_foto1}}" class="btn btn-danger" title="Solicitud"><i class="fa fa-file-pdf-o"></i>PDF
                                                </a>
                                                <a href="{{route('editarEmpleado1',$emp->emp_id)}}" class="btn btn-warning" title="Editar Formato de solicitud del empleado"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Formato de solicitud">
                                                <i class="fa fa-times"></i>
                                                <a href="{{route('editarEmpleado1',$emp->emp_id)}}" class="btn btn-warning" title="Editar Formato de solicitud del empleado"><i class="fa fa-edit"></i>
                                                </a>
                                                
                                            </td>   
                                        @endif   
                                                                                                                        
                                        @if($emp->emp_status1 == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editarEmpleado',$emp->emp_id)}}" class="btn btn-warning" title="Editar datos del empleado"><i class="fa fa-edit"></i>
                                            </a>
                                            @if($role->rol_name == 'user')
                                            @else 
                                                <a href="{{route('borrarEmpleado',$emp->emp_id)}}" class="btn btn-danger" title="Borrar empleado" onclick="return confirm('¿Seguro que desea borrar datos del empleado?')"><i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regempleado->appends(request()->input())->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('request')
@endsection

@section('javascrpt')
@endsection

