@extends('sicinar.principal')

@section('title','Ver cita de comprobación de combustible')

@section('links')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Exo&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
@endsection

@section('nombre')
    {{$nombre}}
@endsection

@section('usuario')
    {{$usuario}}
@endsection

@section('content')

    <body>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Menú
                <small>Agenda - Cita de comprobación -Editar </small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="col-xs-12 form-group" style="color:green;text-align:right;">
                                    <label>Folio de cita:{{$regcitas->evento_id}}</label>
                        </div> 
                        {!! Form::open(['route' => ['actualizarCita',$regcitas->evento_id], 'method' => 'PUT', 'id' => 'actualizarCita', 'enctype' => 'multipart/form-data']) !!}
                        @csrf
                        <div class="box-body"> 
                            <div class="row">
                                <div class="col-xs-8 form-group">
                                    <label >Motivo de la cita (100 carcácteres)</label>
                                    <input type="text" class="form-control" name="evento_desc" id="evento_desc" placeholder="Motivo de la cita" value="{{trim($regcitas->evento_desc)}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label>Fecha dd/mm/aaaa</label>
                                    <input type="text" class="form-control" name="evento_fecha" value="{{date('d/m/Y', strtotime($regcitas->evento_fecha))}}" required>
                                </div>                                                                
                                <div class="col-xs-2 form-group">
                                    <label >Hr. de la cita hh:mm</label>
                                    <input type="text" class="form-control" name="evento_hora" id="evento_hora" placeholder="Hr. de cita de comprobación de combustible hh:mm (" value="{{trim($regcitas->evento_hora)}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Servidor público </label>
                                    <input type="text" class="form-control" name="evento_nomb" id="evento_nomb" placeholder="Servidor público" value="{{trim($regcitas->evento_nomb)}}" required>
                                </div>                                                                
                            </div>
                            
                            <div class="row">
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @endif

                                <div class="col-xs-12 form-group"> 
                                    <a href="{{route('vercalendario')}}" role="button" id="cancelar" class="btn btn-danger">Regresar
                                    </a>
                                    {!! Form::submit('Guardar',['class' => 'btn btn-info']) !!}
                                </div>                                
                            </div>                            

                        </div>
                        {!! Form::close() !!}


                    </div>
                </div>
            </div>
        </section>
    </div>
    </body>
@endsection

@section('request')
@endsection

@section('javascrpt')
@endsection
