@extends('sicinar.principal')

@section('title','Usuarios')

@section('nombre')
    {{$nombre}}
@endsection

@section('usuario')
    {{$usuario}}
@endsection

@section('content')
    <div class="content-wrapper" id="principal">
        <section class="content-header">
            <h1><i class="fa fa-users"></i>Usuarios del sistema </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">BackOffice </a></li>
                <li class="active">Usuarios</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Usuarios</b></h3>
                            <a href="{{route('nuevoUser')}}" class="btn btn-info pull-right" title="Dar de alta un nuevo usuario"><i class="fa fa-user-plus"></i> Nuevo</a>
                        </div>
                        <div class="box-body">
                            <table id="tabla1" border="1" style="border: 2px solid slategray;" class="table table-bordered table-sm">
                                <thead style="border-color:brown;color: brown;" class="justify">
                                    <tr>
                                        <th colspan="1" style="text-align:center; vertical-align: middle;border: 2px solid slategray;"><b style="color: green">Id.</b>
                                        </th>
                                        <th colspan="1" style="text-align:left; vertical-align: middle;border: 2px solid slategray;"><b style="color: green">Login</b>
                                        </th>
                                        <th colspan="1" style="text-align:left; vertical-align: middle;border: 2px solid slategray;"><b style="color: dodgerblue">Password</b>
                                        </th>
                                        <th colspan="1" style="text-align:left; vertical-align: middle;border: 2px solid slategray;">Rol
                                        </th>
                                        <th colspan="1" style="text-align:left; vertical-align: middle;border: 2px solid slategray;"><b style="color:green">Usuario</b>
                                        </th>
                                        <th colspan="1" style="text-align:left; vertical-align: middle;border: 2px solid slategray;"><b style="color:green">Depto.</b>
                                        </th>
                                        <th colspan="1" style="text-align:center; vertical-align: middle;border: 2px solid slategray;"><b style="color: green">Status</b>
                                        </th>
                                        <th colspan="1" style="text-align:center; vertical-align: middle;border: 2px solid slategray;"><b style="color: green">Acciones</b>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td style="text-align:center; vertical-align:middle;font-size:11px;">{{$user->user_id}}  </td>
                                        <td style="text-align:left; vertical-align:middle;font-size:11px;"  >{{$user->user_name}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;" >{{$user->user_password}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">
                                            {{$user->rol_name}} - {{$user->rol_desc}}
                                        </td>
                                        <td style="text-align:left; vertical-align:middle;font-size:10px;width:180px;">{{Trim($user->user_completo)}}</td>
                                        <td style="text-align:left; vertical-align:middle;font-size:10px;width:230px;">  
                                            @foreach($deptos as $depto) 
                                                @if($depto->depto_id == $user->depto_id)
                                                    {{$depto->depto_id.'-'.$depto->depto_desc}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>   
                                        @if($user->status == 'S')
                                            <td style="color:darkgreen; text-align:center; vertical-align: middle;font-size:10px;">
                                                <i class="fa fa-check">Activo</i>
                                            </td>
                                        @else
                                            <td style="color:darkred;text-align:center; vertical-align: middle;font-size:10px;">
                                                <i class="fa fa-times">Inactivo</i>
                                            </td>
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;">
                                            <a href="{{route('editarUser',$user->user_id)}}" class="btn badge-warning" title="Editar"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarUser',$user->user_id)}}" class="btn badge-danger" title="Borrar usuario" onclick="return confirm('¿Seguro que desea borrar el usuario?')"><i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {!! $users->appends(request()->input())->links() !!}
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