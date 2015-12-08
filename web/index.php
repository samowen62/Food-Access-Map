<?php
  $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

  $server = $url["host"];
  $user = $url["user"];
  $pass = $url["pass"];
  $db = substr($url["path"], 1);
  //$db = 'heroku_f89aaf7dbb9da32';

  $conn = new mysqli($server, $user, $pass, $db);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } 

  $stores = $conn->query("SELECT id, name, type, address, date_surveyed FROM store");

  

?>

<!doctype html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,600italic,400italic,300italic,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/css/main.css" type="text/css">
    <link rel="stylesheet" href="/css/ol.css" type="text/css">
    <script src="js/jquery.js"></script>
    <script src="js/ol.js"></script>
    <title>Madison Food Access Map</title>
  </head>
  <body>
    <div id="content" >
      <div class="container">
        <div id="main">
          <div class="row">
           <h1>Madison Food Access Map</h1>
          </div>
          <div class="row">
            <div class="col-md-8">
             <div id="map" class="map"></div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <h2><span id="_neighborhood">Neighborhood</span>&nbsp;/&nbsp;<span id="_lsad"></span></h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php 

    if ($stores->num_rows > 0) {

      while($row = $stores->fetch_assoc()) {
        var_dump($row);
      }
    }
  ?>

    <script type="text/javascript">
    //function displayFunct ($scope) {

      var style = new ol.style.Style({
        fill: new ol.style.Fill({
          color: 'rgba(255, 255, 255, 0.3)'
        }),
        stroke: new ol.style.Stroke({
          color: '#319FD3',
          width: 1
        }),
        text: new ol.style.Text({
          font: '10px Calibri,sans-serif',
          fill: new ol.style.Fill({
            color: '#000'
          }),
          stroke: new ol.style.Stroke({
            color: '#fff',
            width: 3
          })
        })
      });

      var highlight = new ol.style.Style({
        fill: new ol.style.Fill({
          color: 'rgba(0, 255, 0, 0.6)'
        }),
        stroke: new ol.style.Stroke({
          color: '#009900',
          width: 3
        }),
        text: new ol.style.Text({
          font: '10px Calibri,sans-serif',
          fill: new ol.style.Fill({
            color: '#000'
          }),
          stroke: new ol.style.Stroke({
            color: '#fff',
            width: 3
          })
        })
      });

      var styles = [style];

      var vectorLayer = new ol.layer.Vector({
        source: new ol.source.Vector({
          url: 'vote.geojson',
          format: new ol.format.GeoJSON()
        }),
        style: function(feature, resolution) {
          style.getText().setText(resolution < 50 ? feature.get('NAME10') : '');
          return styles;
        }
      });

      var lastFeat = null;
      var neighbor_div = $('#_neighborhood');
      var lsad_div = $('#_lsad');
     // $scope.lastFeat = null;
      //not working for no reason but whatever
      //$scope.name = '';
     // $scope.lsad = '';
      
      var map = new ol.Map({
        target: 'map',
        layers: [
          new ol.layer.Tile({
            source: new ol.source.OSM({layer: 'sat'})
          }),
          vectorLayer
        ],
        view: new ol.View({
          center: ol.proj.fromLonLat([-89.4,43.0699]),
          zoom: 13
        })
      });

      map.getViewport().addEventListener("click", function(e) { 
        map.forEachFeatureAtPixel(map.getEventPixel(e), function (feature, layer) {
            if(lastFeat)
              lastFeat.setStyle(style);

            feature.setStyle(highlight);
            lastFeat = feature;
            setProps(feature.get('NAME10'), feature.get('LSAD10'));
        });
      });

      function setProps(name, lsad){
        neighbor_div.text(name);
        lsad_div.text(lsad);
        console.log(name, lsad);
      }

     // }
    </script>
<?php 
  $conn->close();
?>
  </body>
</html>
