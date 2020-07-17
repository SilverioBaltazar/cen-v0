@extends('sicinar.principal')

@section('title','Estadística por tipo de gasto presupuestal ')

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
      <h1>
        Numeralia Básica
        <small> Gráfica</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
        <li><a href="#">Numeralia</a></li>
        <li><a href="#">Estadísticas</a></li>
        <li class="active">Placas por tipo de gasto presupuestal </li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-6">
          <div class="box box-success">
            <div class="box-header">
              <h3 class="box-title"><b>Estadística de placas por tipo de gasto presupuestal</b></h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
              <table id="tabla1" class="table table-striped table-bordered table-sm">
                <thead style="color: brown;" class="justify">
                  <tr>
                    <th rowspan="2" style="text-align:left;"  >Id.         </th>
                    <th rowspan="2" style="text-align:left;"  >TIPO GASTO PRESUPUESTAL</th>
                    <th rowspan="2" style="text-align:center;">TOTAL       </th>
                  </tr>
                  <tr>
                  </tr>
                </thead>

                <tbody>
                  @foreach($regplaca as $placa)
                      <tr>
                        <td style="color:darkgreen;">{{$placa->tipog_id}}</td>
                        <td style="color:darkgreen;">{{$placa->tipo_gasto}}   </td>
                        <td style="color:darkgreen; text-align:center;">{{$placa->total}}   </td>
                      </tr>
                  @endforeach
                  @foreach($regtotxtipog as $totales)
                      <tr>
                        <td></td>
                        <td style="color:green;"><b>TOTAL</b></td>                         
                        <td style="color:green; text-align:center;"><b>{{$totales->totalxtipog}} </b></td>              
                      </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Grafica de pie en 3D-->
        <div class="col-md-6">
          <div class="box">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title" style="text-align:center;">Gráfica de Pay 3D </h3> 
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
                <div class="box-body">
                  <camvas id="piechart_3d" style="width: 900px; height: 500px;"></camvas>
                </div>
              </div> 
            </div>
          </div>
        </div>

      </div>

      <!-- Grafica de barras 2D-->
      <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="box box-success">
              <!-- <div class="box-header with-border"> -->
                <!--<h3 class="box-title" style="text-align:center;">Gráfica por Rubro social 2D </h3> -->
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
                <div class="box-body">
                  <camvas id="top_x_div" style="width: 900px; height: 500px;"></camvas>
                </div>
              <!-- </div> -->
            </div>
          </div>
        </div>

        <!-- Grafica de dona 
        Making a donut chart
        https://developers.google.com/chart/interactive/docs/gallery/piechart#donut
        -->
        <div class="col-md-6">
          <div class="box">
            <div class="box box-danger">
              <!-- <div class="box-header with-border"> -->
                <!-- <h3 class="box-title" style="text-align:center;">Gráfica por Rubro social 3D </h3> -->
                <!-- BOTON para cerrar ventana x -->
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
                <!-- Pinta la grafica de barras 2-->
                <div class="box-body">
                  <camvas id="donutchart" style="width: 900px; height: 500px;"></camvas>
                </div>
              <!-- </div> -->
            </div>
          </div>
        </div>        

      </div>      

    </section>
  </div>
@endsection

@section('request')
  <script src="{{ asset('bower_components/chart.js/Chart.js') }}"></script>
  
  <!-- Grafica de pay, barras y otras
    https://google-developers.appspot.com/chart/interactive/docs/gallery/piechart
    https://www.youtube.com/watch?v=Y83fxTpNSsY
  -->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@endsection

@section('javascrpt')

  
  <!-- Grafica de pie (pay) 2D Google/chart -->
  <script type="text/javascript">
      // https://www.youtube.com/watch?v=Y83fxTpNSsY ejemplo de grafica de pay google
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['id', 'Tipo gasto'],
          @foreach($regplaca as $placa)
             ['{{$placa->tipo_gasto}}', {{$placa->total}} ],
          @endforeach
          //['Work',     11],
          //['Eat',      2],
          //['Commute',  2],
          //['Watch TV', 2],
          //['Sleep',    7]
        ]);

        var options = {
          title: 'Tipo de gasto presupuestal',
          //chart: { title: 'Gráfica de Pay',
          //         subtitle: 'IAPS por Rubro Social' },          
          is3D: true,
          width: 500,                   // Ancho de la pantalla horizontal
          height: 300,                  // Alto de la pantall '75%',          
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
  </script>

  <!-- Grafica de barras 2D Google/chart -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Tipo gasto', 'Total'],
          @foreach($regplaca as $placa)
             ['{{$placa->tipo_gasto}}', {{$placa->total}} ],
          @endforeach          
          //["King's pawn (e4)", 44],
          //["Queen's pawn (d4)", 31],
          //["Knight to King 3 (Nf3)", 12],
          //["Queen's bishop pawn (c4)", 10],
          //['Other', 3]
        ]);

        var options = {
          title: 'Por Tipo de gasto presupuestal',
          width: 500,                   // Ancho de la pantalla horizontal
          height: 300,                  // Alto de la pantall '75%',          
          legend: { position: 'none' },
          chart: { title: 'Gráfica de Barras',
                   subtitle: 'Placas por tipo de gasto presupuestal ' },
          bars: 'horizontal', // Required for Material Bar Charts.
          axes: {
            x: {
              //0: { side: 'top', label: 'Total de IAPS'} // Top x-axis.
              1: { side: 'top', label: 'Total de placas'} // Top x-axis.
            }
          },
          bar: { groupWidth: "50%" }
        };

        var chart = new google.charts.Bar(document.getElementById('top_x_div'));
        chart.draw(data, options);
      };
  </script>  

  <!-- Grafica de dona 
  Making a donut chart
  https://developers.google.com/chart/interactive/docs/gallery/piechart#donut
  -->
  <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Tipo gasto', 'Total'],
          @foreach($regplaca as $placa)
             ['{{$placa->tipo_gasto}}', {{$placa->total}} ],
          @endforeach            
          //['Work',     11],
          //['Eat',      2],
          //['Commute',  2],
          //['Watch TV', 2],
          //['Sleep',    7]
        ]);

        var options = {
          title: 'Gráfica por Tipo de gasto presupuestal',
          pieHole: 0.4,
          width: 500,                   // Ancho de la pantalla horizontal
          height: 300,                  // Alto de la pantall '75%',
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
  </script>  
@endsection