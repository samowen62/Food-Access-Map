<?php

  include 'config.php';

  //run in shell to connect
  //mysql -h us-cdbr-iron-east-03.cleardb.net --user=b9599e5859242c --password


  $server = $url["host"];
  $user = $url["user"];
  $pass = $url["pass"];
  $db = substr($url["path"], 1);

  $conn = new mysqli($server, $user, $pass, $db);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } 

  $store_query = "SELECT id, name, type, address, date_surveyed, x, y FROM store";
  $stores_sql = $conn->query($store_query);
  $stores = array();
  while($r = mysqli_fetch_assoc($stores_sql)) {
      $stores[] = $r;
  }

  $stores = json_encode(($stores), JSON_PRETTY_PRINT,3);

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

    <script type="text/javascript">
      /*
       *  JSON objects from database
       */
      var stores = <?php echo $stores; ?>;


      /*
       *  Neighborhood Layer
       */
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


      /*
       *  Marker Layer
       */
      var markerFeatures=[];

      $.each(stores,function(k,v){ 
        markerFeatures.push(new ol.Feature({
          geometry: new ol.geom.Point(ol.proj.transform([parseFloat(v.y),parseFloat(v.x)], 'EPSG:4326',     
          'EPSG:3857')),
          address: v.address,
          date: v.date_surveyed,
          name: v.name,
          type: v.type
        }));
      });
      

      var markerSource = new ol.source.Vector({
        features: markerFeatures 
      });

      var markerStyle = new ol.style.Style({
        image: new ol.style.Icon(({
          anchor: [0.5, 46],
          anchorXUnits: 'fraction',
          anchorYUnits: 'pixels',
          opacity: 0.75,
          src: 'images/marker-green.png'
        }))
      });

      var markerLayer = new ol.layer.Vector({
        source: markerSource,
        style: markerStyle
      });


      /*
       *  DOM objects on page
       */
      var lastFeat = null;
      var neighbor_div = $('#_neighborhood');
      var lsad_div = $('#_lsad');
      

      /*
       *  Map and callbacks
       */
      var map = new ol.Map({
        target: 'map',
        layers: [
          new ol.layer.Tile({
            source: new ol.source.OSM({layer: 'sat'})
          }),
          vectorLayer,
          markerLayer
        ],
        view: new ol.View({
          center: ol.proj.fromLonLat([-89.4,43.0699]),
          zoom: 12
        })
      });

      map.getViewport().addEventListener("click", function(e) { 
        map.forEachFeatureAtPixel(map.getEventPixel(e), function (feature, layer) {
            
            var properties = feature.getProperties();
            console.log(properties);
        });
      });



      function setProps(name, lsad){
        neighbor_div.text(name);
        lsad_div.text(lsad);
        console.log(name, lsad);
      }

    </script>
<?php 
  $conn->close();
?>
  </body>
</html>
