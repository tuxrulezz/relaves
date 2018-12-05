<!DOCTYPE html>
<html lang="en">
<?php
    include("conexion.php");
    ?>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Relaves</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=geometry,places&ext=.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/typeahead.js"></script>
    <script src=”https://code.jquery.com/ui/1.12.0/jquery-ui.js”></script>
    <script type="text/javascript">
        var locations = [];
    </script>
<script>
    $(document).ready(function () {
        $('#MiId').typeahead({
            source: function (busqueda, resultado) {
                $.ajax({
                    url: "consulta.php",
                    data: 'busqueda=' + busqueda,            
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
                        resultado($.map(data, function (item) {
                            return item;
                        }));
                    }
                });
            }
        });
    });
    var aux = 0;
</script>    

<?php 
    $v1="";
    $v2="";
    $v3="";
    $q="0";
    $filtro ="";
    if(!empty($_GET["provincia"]))
    $v1=$_GET["provincia"];
    if(!empty($_GET["comuna"]))
    $v2=$_GET["comuna"];
    if(!empty($_GET["id"]))
    $v3=$_GET["id"];

    if($v1 && !$v2 && !$v3)
        $q="1";
    if($v1 && $v2 && !$v3)
        $q="2";
    if(!$v1 && $v2 && !$v3)
        $q="3";
    if(!$v1 && !$v2 && $v3)
        $q="4";
    switch ($q) {
        case "0":
            $filtro="";
        break;
        case "1":
            $filtro="WHERE provincia='$v1'";
        break;
        case "2":
            $filtro="WHERE provincia='$v1' AND comuna='$v2'";
        break;
        case "3":
            $filtro="WHERE comuna='$v2'";
        break;
        case "4":
            $filtro="WHERE id='$v3'";
        break;
    }

    $address="";
    $estado5="";
    $title = "'titulo'";
    $url ="";
    $empresa1 ="empresa";
    $id1 ="id";
    $faena1 ="faena";
    $deposito1 ="deposito";
    $estado1 ="estado_deposito";
    $volumen1 ="volumen_autorizado";
    $latitud1 ="latitud";
    $longitud1 ="longitud";
    $aire1 ="aire";
    $agua1 ="agua";
    $subterraneo1 ="subterraneo";
    $biota1 ="biota";
    $estructural1 ="estructural";
    $query = "SELECT * FROM relave $filtro LIMIT 12";
    $result = mysqli_query($conexion, $query);

    if($result)
    {
        while($row = mysqli_fetch_array($result))
        {   
            
            $id = $row[$id1];
            $faena = $row[$faena1];
            $empresa = $row[$empresa1];
            $deposito = $row[$deposito1];
            $estado = $row[$estado1];
            $volumen = $row[$volumen1];
            $latitud = $row[$latitud1];
            $longitud = $row[$longitud1];
            $aire = $row[$aire1];
            $agua = $row[$agua1];
            $subterraneo = $row[$subterraneo1];
            $biota = $row[$biota1];
            $estructural = $row[$estructural1];

            $address = "'".$latitud.",".$longitud."'";
            //$url = "url";
            $url = "<a href=ficha.php?id=".$id.">Ver ficha completa <li class=\"fas fa-arrow-alt-circle-right  \"></li></a><div style = font-size:12px;  line-height: 0.8; class=col-lg-12><div class=row><div class=col-lg-6><br><p><b>Empresa:</b> ".$empresa."</p></div><div class=col-lg-6><br><p><b>ID:</b> ".$id."</p></div></div><div class=row><div class=col-lg-6><p><b>Faena:</b> ".$faena."</p></div><div class=col-lg-6><p><b>Depósito:</b> ".$deposito."</p></div></div><div class=row><div class=col-lg-6><p><b>Estado:</b> ".$estado."</p></div><div class=col-lg-6><p><b>Volumen Aut.:</b> ".$volumen."m³</p></div><div style = font-size:15px;  line-height: 0.8; class=col-lg-12><div class=row><table width=100% align=center><tr><td class=\"td_list_center status\"><table width=100%><tbody><tr><td width=20% class=".ranking($aire)."><li class=\"fa fa-wind\"></li></td><td width=20% class=".ranking($agua)."><li class=\"fa fa-tint\"></li></td><td width=20% class=".ranking($subterraneo)."><li class=\"fa fa-water \"></li</td><td width=20% class=".ranking($biota)."><li class=\"fa fa-paw\"></li></td><td width=20% class=".ranking($estructural)."><li class=\"fa fa-mountain\"></li></td></tr><tr><td width=20% class=".ranking($aire).">".$aire."</td><td width=20% class=".ranking($agua).">".$agua."</td><td width=20% class=".ranking($subterraneo).">".$subterraneo."</td><td width=20% class=".ranking($biota).">".$biota."</td><td width=20% class=".ranking($estructural).">".$estructural."</td></tr></tbody></table></td></tr></table></div></div>";
            $sitio = "[".$address.",".$title.",'".$url."']";
?>
<script type="text/javascript">
locations.push(<?php echo$sitio;?>);
</script>
<?php
        }
    }
?>



    <?php   
        $provincia = "provincia";
        $comuna = "comuna";
        $estado = "estado_deposito";
        $estado1 = "NO ACTIVO";
        $estado2 = "ABANDONADO";
        $estado3 = "ACTIVO";
        $id = "id";
        $empresa = "empresa";
        $latitud = "latitud";
        $longitud = "longitud";
    ?>

</head>

<body>

    
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                
                <a class="navbar-brand" href="index.php">Inicio</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">
        <!-- Page Heading/Breadcrumbs -->
        <div class="col-lg-2 text-center"></div>
        <div class="col-lg-8 text-center">
            <div class="col-md-4">
                <img src="img/gyg.png" class="img-responsive">
            </div>
            <div class="col-md-4">
                <img src="img/gr.png" class="img-responsive">
            </div>
            <div class="col-md-4">
                <img src="img/crdp.png" class="img-responsive">
            </div>
        </div>
        <div class="col-lg-2 text-center"></div>
        <!-- /.row -->

        <!-- Content Row -->
        

<div class="row">
            <div class="col-md-12">
                <h3>Filtros de búsqueda para obtener información de los depósitos de relaves existentes en la región. </h3>
            </div>
            <div class="col-md-6">
                <form name="sentMessage" id="contactForm1" novalidate action="/relaves/mapa.php" method="get">
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Provicia:</label>
                            <select class="form-control" name="provincia"> 
                                <option disabled selected hidden>Seleccione Provincia</option>
                                <?php
                                    //Consulta1
                                    $query1 = "SELECT DISTINCT $provincia FROM relave";
                                    $result1 = mysqli_query($conexion, $query1);
                                    if($result1)
                                    {
                                        while($row = mysqli_fetch_array($result1))
                                        {   
                                            $provincia = $row["provincia"];
                                ?>
                                <option value="<?php echo $provincia;?>"> <?php echo $provincia;?> </option>
                                <?php
                                        }
                                    }
                                ?>
                            </select>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Comuna:</label>
                            <select class="form-control" name="comuna"> 
                                <option disabled selected hidden>Seleccione Comuna</option>
                                <?php
                                    //Consulta1
                                    $query1 = "SELECT DISTINCT $comuna FROM relave";
                                    $result1 = mysqli_query($conexion, $query1);
                                    if($result1)
                                    {
                                        while($row = mysqli_fetch_array($result1))
                                        {   
                                            $comuna = $row["comuna"];
                                ?>
                                <option value="<?php echo $comuna;?>"> <?php echo $comuna;?> </option>
                                <?php
                                        }
                                    }
                                ?>
                            </select>
                            <p class="help-block"></p>
                        </div>
                    </div>
                     <div class="col-md-12">
                        <button type="submit" value="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                    <!-- For success/fail messages -->
                </form>
            </div>
            <div class="col-md-6">
                <form name="sentMessage" id="contactForm2" novalidate action="/relaves/mapa.php" method="get">
                    <div class="control-group form-group row">
                        <div class="col-lg-8 controls">
                                <label>ID de Sernageomin:</label> 
                                <input type="text" name="id" name="MiId" id="MiId" class="form-control"/>
                        </div>
                        <div class="col-lg-4 controls text-center">
                                <label class>Buscar</label> 
                                <button type="submit" value="submit" class="btn btn-primary form-control"><li class="fa fa-search"></li></button> 
                        </div>
                    </div>
                    <!-- For success/fail messages -->
                </form>
            </div>

        </div>
<hr>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-9" id="map"></div>
                <div class="col-md-4">
                    <div class="row">
                        <div class=" col-md-12 panel panel-default">
                            <h3>Información Regional</h3>
                            <p>
                                <?php
                                    //Consulta activos
                                    $query2 = "SELECT COUNT($estado) as $estado FROM relave ";
                                    $result2 = mysqli_query($conexion, $query2);
                                    if($result2)
                                    {
                                        while($row = mysqli_fetch_array($result2))
                                        {   
                                            $total = $row[$estado];
                                ?>
                                    Total de Sitios: <?php echo $total;?><br>
                                <?php
                                        }
                                    }
                                    //Consulta activos
                                    $query2 = "SELECT COUNT($estado) as $estado FROM relave WHERE $estado = 'ACTIVO' ";
                                        $result2 = mysqli_query($conexion, $query2);
                                        if($result2)
                                        {
                                            while($row = mysqli_fetch_array($result2))
                                            {   
                                                $estado3 = $row[$estado];
                                    ?>
                                    Activos: <?php echo $estado3;?><br>
                                    <?php
                                            }
                                        }   
                                        //Consulta no activos
                                        $query2 = "SELECT COUNT($estado) as $estado FROM relave WHERE $estado = 'NO ACTIVO' ";
                                        $result2 = mysqli_query($conexion, $query2);
                                        if($result2)
                                        {
                                            while($row = mysqli_fetch_array($result2))
                                            {   
                                                $estado1 = $row[$estado];
                                    ?>
                                    No Activos: <?php echo $estado1;?><br>
                                    <?php
                                            }
                                        }
                                        //Consulta no activos
                                        $query2 = "SELECT COUNT($estado) as $estado FROM relave WHERE $estado = 'ABANDONADO' ";
                                        $result2 = mysqli_query($conexion, $query2);
                                        if($result2)
                                        {
                                            while($row = mysqli_fetch_array($result2))
                                            {   
                                                $estado2 = $row[$estado];
                                    ?>
                                    Abandonados: <?php echo $estado2;?><br>
                                    <?php
                                            }
                                        }
                                        $resto = $total-($estado1+$estado2+$estado3);

                                    ?>
                                    Sin Información: <?php echo $resto;?>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class=" col-md-12 panel panel-default">
                            <h3>Iconografía</h3>
                                <p>
                                <li class="fa fa-wind"></li> = Aire<br>
                                <li class="fa fa-tint"></li> = Agua superficial<br>
                                <li class="fa fa-water"></li> = Agua subterránea<br>
                                <li class="fa fa-paw"></li> = Biota<br>
                                <li class="fa fa-mountain"></li> = Estructural
                                </p>
                        </div>
                    </div>    
                    <div class="row">
                        <div class=" col-md-12 panel panel-default">
                            <h3>Ranking de Riesgo</h3>
                                <p>
                                <li class="fa fa-circle texto-ranking1"></li>  = 1 <br>
                                <li class="fa fa-circle texto-ranking2"></li>   = 2 <br>
                                <li class="fa fa-circle texto-ranking3"></li>    = 3  <br>
                                <li class="fa fa-circle texto-ranking4"></li>   = 4 <br>
                                <li class="fa fa-circle texto-ranking4"></li> = 5 
                                </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

        
        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript -->
    <!-- Do not edit these files! In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

    <style>
        #map 
        {
            margin-right: 10px;
            width: 750px;
            height: 650px;
            background-color: grey;
        }
    </style>

   
<script type="text/javascript">
var geocoder;
var map;
var bounds = new google.maps.LatLngBounds();

function initialize() {
    map = new google.maps.Map(
    document.getElementById("map"), {
        center: new google.maps.LatLng(-30.627728, -70.608807),
        zoom: 7.5,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    geocoder = new google.maps.Geocoder();

    for (i = 0; i < locations.length; i++) {
        geocodeAddress(locations, i);
    }
}
google.maps.event.addDomListener(window, "load", initialize);


function geocodeAddress(locations, i) {
    var address = locations[i][0];
    var title = locations[i][1];    
    var url = locations[i][2];
    geocoder.geocode({
        'address': locations[i][0]
    },
    function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var marker = new google.maps.Marker({
                icon: 'http://maps.google.com/mapfiles/ms/icons/red.png',
                map: map,
                position: results[0].geometry.location,
                title: title,
                animation: google.maps.Animation.DROP,
                address: address,
                url: url
            })
            infoWindow(marker, map, title, address, url);
            bounds.extend(marker.getPosition());
            map.fitBounds(bounds);
                
        } else {   
            alert("geocode of " + address + " failed:" + status);
        }
    });
}

function infoWindow(marker, map, title, address, url) {
    google.maps.event.addListener(marker, 'click', function () {
        var html = url;
        iw = new google.maps.InfoWindow({
            content: html,
            maxWidth: 2550
        });
        iw.open(map, marker);
    });
}

function createMarker(results) {
    var marker = new google.maps.Marker({
        icon: 'http://maps.google.com/mapfiles/ms/icons/blue.png',
        map: map,
        position: results[0].geometry.location,
        title: title,
        animation: google.maps.Animation.DROP,
        address: address,
        url: url
    })
    bounds.extend(marker.getPosition());
    map.fitBounds(bounds);
    infoWindow(marker, map, title, address, url);
    return marker;
}
</script>
<?php 
    function ranking($a)
    {
    $valor=$a;
    if($valor >= 1 && $valor <= 11)
        $q="1";
    if($valor >= 12 && $valor <= 22)
        $q="2";
    if($valor >= 23 && $valor <= 34)
        $q="3";
    if($valor >= 35 && $valor <= 45)
         $q="4";
    if($valor >= 46 && $valor <= 57)
        $q="5";
    switch ($q) 
    {
        case "1":
            $valor="ranking1";
            break;
        case "2":
            $valor = "ranking2";
            break;
        case "3":
            $valor = "ranking3";
            break;
        case "4":
            $valor = "ranking4";
            break;
        case "5":
            $valor = "ranking5";
            break;
        
    }
    return ($valor);

    }

?>

<style type="text/css">
    tbody {
    display: table-row-group;
    vertical-align: middle;
    text-align:center;
    border-color: inherit;
    color:  white ;

}
    td {
        border-right: 1px solid gray;
    }
    tr{
        height: 25px;
    }
    .ranking5{
    background-color: #7ABCF8;
    }
    .ranking4{
    background-color: #44A3FB;
    }
    .ranking3{
    background-color: #0283FB;
    }
    .ranking2{
    background-color: #01529D;
    }
    .ranking1{
    background-color: #01305C;
    }
    .texto-ranking5{
    color: #7ABCF8;
    }
    .texto-ranking4{
    color: #44A3FB;
    }
    .texto-ranking3{
    color: #0283FB;
    }
    .texto-ranking2{
    color: #01529D;
    }
    .texto-ranking1{
    color: #01305C;
    }
</style>


     <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFVxz7mqOQo5Iz7l8a7toLIhAwI5LLSDc&callback=initMap">
    </script>


</body>

</html>
