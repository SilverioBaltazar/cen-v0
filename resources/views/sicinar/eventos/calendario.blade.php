@extends('sicinar.principal')

@section('title','Agendar cita para comprobación de combustible')

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
    <head>
    <title></title>
    <meta content="">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Exo&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
      body{
          font-family: 'Exo', sans-serif;
        }
        .header-col{
          background: #E3E9E5;
          color:#536170;
          text-align: center;
          font-size: 20px;
          font-weight: bold;
        }
        .header-calendar{
           background:green;color:white; 
        }
        .box-day{
          border:1px solid #E3E9E5;
          height:150px;
        }
        .box-dayoff{
          border:1px solid #E3E9E5;
          height:150px;
          background-color:pink;
        }
    </style>

    </head>

    <div class="content-wrapper">
        <section class="content-header">
            <p class="lead">
              <a class="btn btn-default" href="{{route('nuevaCita')}}">Agendar cita para comprobación</a>
              <a href="{{route('calendarioPdf')}}" class="btn btn-danger" title="Exportar calendario de citas a formato PDF"><i class="fa fa-file-pdf-o"></i> PDF
              </a>
            </p>
            <hr>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Agenda                 </a></li>
                <li><a href="#">Cita para comprobación </a></li>         
            </ol>
        </section>
        <section class="content">

          <div class="container">
              <!--
              <p class="lead">
              <a class="btn btn-default" href="{{route('nuevaCita')}}">Agendar cita para comprobación</a></p>
              <hr>
              -->
              <div class="row header-calendar"  >
                  <div class="col" style="display: flex; justify-content: space-between; padding: 10px;">
                      <a  href="{{ route('vercalendariomes', $data['last'])}}" style="margin:10px;">
                          <i class="fas fa-chevron-circle-left" style="font-size:30px;color:white;"></i>
                      </a>
                      <h2 style="font-weight:bold;margin:10px;"><?= $mespanish; ?> <?= $data['year']; ?></h2>
                      <a  href="{{route('vercalendariomes', $data['next'])}}" style="margin:10px;">
                          <i class="fas fa-chevron-circle-right" style="font-size:30px;color:white;"></i>
                      </a>
                  </div>
              </div>
              <div class="row">
                  <div class="col header-col">Lunes</div>
                  <div class="col header-col">Martes</div>
                  <div class="col header-col">Miercoles</div>
                  <div class="col header-col">Jueves</div>
                  <div class="col header-col">Viernes</div>
                  <div class="col header-col">Sabado</div>
                  <div class="col header-col">Domingo</div>
              </div>
              <!-- inicio de semana -->
              @foreach ($data['calendar'] as $weekdata)
                <div class="row">
                    <!-- ciclo de dia por semana -->
                    @foreach  ($weekdata['datos'] as $dayweek)
                        @if  ($dayweek['mes']==$mes)
                            <div class="col box-day">
                            {{ $dayweek['dia']  }}
                            <!-- evento -->
                            @foreach  ($dayweek['evento'] as $event) 
                                <a class="badge badge-success" href="{{route('editarcita',array($event->evento_id) )}}">
                                  {{'Hr :'.$event->evento_hora.'-'.trim($event->evento_nomb)}} 
                                </a> 
                            @endforeach
                            </div>
                        @else
                            <div class="col box-dayoff"></div>
                        @endif
                    @endforeach
                </div>
              @endforeach
          </div> <!-- /container -->

          <!-- Footer -->
          <footer class="page-footer font-small blue pt-4">
                <!-- Copyright -->
                <div class="footer-copyright text-center py-3">
                  ...
                  <a href="https://www.tutofox.com/">  <Desarrollo de Proyectos/></a>
                </div>
                <!-- Copyright -->
          </footer>
          <!-- Footer --> 

        </section>
    </div>
@endsection

@section('request')
@endsection

@section('javascrpt')
@endsection
