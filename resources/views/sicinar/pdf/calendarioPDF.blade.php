<html>
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
    .header { position: fixed; left: 0px; top: -15px; right: 0px; height: 375px; }
    .content{ 
                  left: 50px; top: 0px; margin-bottom: 0px; right: 50px;
                  border: solid 0px #000;
                  font: 1em arial, helvetica, sans-serif;
                  color: black; text-align:center;vertical-align: middle; width:1020px;} 
    .header-col{
      background: #E3E9E5;
      color:#536170;
      text-align: center;
      font-size: 20px;
      font-weight: bold;
    }
    .header-calendar{
      background: #EE192D;color:white;
    }
    .box-day{
      border:1px solid #E3E9E5;
      height:150px;
    }
    .box-dayoff{
      border:1px solid #E3E9E5;
      height:150px;
      background-color: #ccd1ce;
    }
    .footer { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 50px; text-align:right; font-size: 8px;}
    .footer .page:after { content: counter(page, upper-roman); }   
    </style>

  </head>
  
  <body>
    <header id="header">
        <p style="border:0; font-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:center;">
            <img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;" align="left"/>
            &nbsp;&nbsp;AGENDA - CITAS DE COMPROBACIÓN DE COMBUSTIBLE
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
        </p>
        <p style="border:0; font-family:'HelveticaNeueLT Std'; font-size:8px; text-align:center;">
            “2020. Año de Laura Méndez de Cuenca; emblema de la mujer Mexiquense”
           <br>
           <b class="col" style="display:flex;justify-content:space-between; color:green;font-weight:bold;text-align:center;font-size:14px;">
            <?= $mespanish; ?> <?= $data['year']; ?>
           </b>
        </p>
    </header>

    <section id="content">
      <table class="table table-hover table-striped"> 
          <thead>
              <tr>
                <th class="col header-col" style="border:0;">Lunes</th>
                <th class="col header-col" style="border:0;">Martes</th>
                <th class="col header-col" style="border:0;">Miercoles</th>
                <th class="col header-col" style="border:0;">Jueves</th>
                <th class="col header-col" style="border:0;">Viernes</th>
                <th class="col header-col" style="border:0;">Sabado</th>
                <th class="col header-col" style="border:0;">Domingo</th>
              </tr> 
              <!-- inicio de semana -->
              @foreach ($data['calendar'] as $weekdata)
              <tr>
                  <!-- ciclo de dia por semana -->
                  @foreach  ($weekdata['datos'] as $dayweek)
                  <th >
                    @if  ($dayweek['mes']==$mes)
                        <p style="text-align:center;">
                        {{ $dayweek['dia']  }}
                        </p>
                        <p style="font-family:'Arial MT Std Bold'; font-size:9px;">
                        <!-- evento -->
                        @foreach  ($dayweek['evento'] as $event) 
                            {{$event->evento_hora.'-'.trim($event->evento_nomb)}} 
                        @endforeach
                        </p><br>
                    @else
                        <p></p>
                    @endif
                  </th>
                  @endforeach
              </tr>
              @endforeach
          </thead>
      </table>
      <p style="page-break-inside: avoid;">
          <div id="footer">
              <p style="border:0; text-align:right;font-size:08px;">
              <b>SECRETARIA DE DESARROLLO SOCIAL</b><br>JUNTA DE ASISTENCIA PRIVADA DEL ESTADO DE MÉXICO
              </p>
          </div>  
      </p>

    </section>  <!-- /container -->

  </body>
</html>