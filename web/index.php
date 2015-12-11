<?php

  include 'config.php';

  //run in shell to connect
  //this is how we'll update the database
  //mysql -h us-cdbr-iron-east-03.cleardb.net --user=b9599e5859242c --password


  $server = $url["host"];
  $user = $url["user"];
  $pass = $url["pass"];
  $db = substr($url["path"], 1);

  $conn = new mysqli($server, $user, $pass, $db);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } 
  

  $store_query = "SELECT milk_avail, milk_price, fruit_avail, fruit_qual, veg_avail, veg_qual, hot_dog_avail, hot_dog_price, beef_avail, beef_price, frozen_dinner_avail, frozen_dinner_price, baked_avail, baked_price, bev_avail, bev_price, bread_avail, bread_price, chips_avail, chips_price, cereal_avail, cereal_price, latino_avail, latino_price, asian_avail, asian_price, name, type, address, date_surveyed, x, y FROM store LEFT JOIN scores ON scores.store_id = store.id;";

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
    <link rel="stylesheet" href="/css/fa/font-awesome.min.css" type="text/css">
    <script src="js/jquery.js"></script>
    <script src="js/ol.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
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
                <h2><span id="_store">Select a Store</span></h2>
                <span style="margin-left: 10px;" id="_address"></span>
              </div>
              <div id="info">
                <div class="row">
                  <h4>Topic Ratings</h4>
                </div>
                <div class="row rating-title">
                  <span onclick="dropDown('#_general')">General Info</span>
                </div>
                <div class="rating" id="_general">
                  <div class="row rating-title" >
                    <span onclick="dropDown('#_milk')">Milk</span> <i class="fa fa-info-circle" onclick="infoModal('Milk Info')"></i>
                  </div>
                  <div class="row rating" id="_milk">
                    Milk Availability Rating: <span id="_milk_avail"></span>&nbsp;/&nbsp;2<br />
                    Milk Price Rating: <span id="_milk_price"></span>&nbsp;/&nbsp;2
                  </div>
                  <div class="row rating-title" >
                    <span onclick="dropDown('#_fruit')">Fruit</span> <i class="fa fa-info-circle" onclick="infoModal('Fruits Info')"></i>
                  </div>
                  <div class="row rating" id="_fruit">
                    Fruit Availability Rating: <span id="_fruit_avail"></span>&nbsp;/&nbsp;3<br />
                    Fruit Quality Rating: <span id="_fruit_qual"></span>&nbsp;/&nbsp;3
                  </div>
                  <div class="row rating-title" >
                    <span onclick="dropDown('#_veg')">Vegetables</span> <i class="fa fa-info-circle" onclick="infoModal('Vegetable Info')"></i>
                  </div>
                  <div class="row rating" id="_veg">
                    Vegetable Availability Rating: <span id="_veg_avail"></span>&nbsp;/&nbsp;3<br />
                    Vegetable Quality Rating: <span id="_veg_qual"></span>&nbsp;/&nbsp;3
                  </div>
                  <div class="row rating-title" >
                    <span onclick="dropDown('#_beef')">Ground Beef</span> <i class="fa fa-info-circle" onclick="infoModal('Ground Beef Info')"></i>
                  </div>
                  <div class="row rating" id="_beef">
                    Ground Beef Availability Rating: <span id="_beef_avail"></span>&nbsp;/&nbsp;2<br />
                    Ground Beef Price Rating: <span id="_beef_price"></span>&nbsp;/&nbsp;2
                  </div>
                  <div class="row rating-title" >
                    <span onclick="dropDown('#_hot_dog')">Hot Dogs</span> <i class="fa fa-info-circle" onclick="infoModal('Hot Dog Info')"></i>
                  </div>
                  <div class="row rating" id="_hot_dog">
                    Hot Dog Availability Rating: <span id="_hot_dog_avail"></span>&nbsp;/&nbsp;2<br />
                    Hot Dog Price Rating: <span id="_hot_dog_price"></span>&nbsp;/&nbsp;2
                  </div>
                  <div class="row rating-title" >
                    <span onclick="dropDown('#_frozen_dinner')">Frozen Dinner</span> <i class="fa fa-info-circle" onclick="infoModal('Frozen Dinner Info')"></i>
                  </div>
                  <div class="row rating" id="_frozen_dinner">
                    Frozen Dinner Availability Rating: <span id="_frozen_dinner_avail"></span>&nbsp;/&nbsp;3<br />
                    Frozen Dinner Price Rating: <span id="_frozen_dinner_price"></span>&nbsp;/&nbsp;2
                  </div>
                  <div class="row rating-title" >
                    <span onclick="dropDown('#_beverage')">Beverages</span> <i class="fa fa-info-circle" onclick="infoModal('Beverage Info')"></i>
                  </div>
                  <div class="row rating" id="_beverage">
                    Beverage Availability Rating: <span id="_beverage_avail"></span>&nbsp;/&nbsp;1<br />
                    Beverage Price Rating: <span id="_beverage_price"></span>&nbsp;/&nbsp;2
                  </div>
                  <div class="row rating-title">
                    <span onclick="dropDown('#_bread')">Bread</span> <i class="fa fa-info-circle" onclick="infoModal('Bread Info')"></i>
                  </div>
                  <div class="row rating" id="_bread">
                    Bread Availability Rating: <span id="_bread_avail"></span>&nbsp;/&nbsp;2<br />
                    Bread Price Rating: <span id="_bread_price"></span>&nbsp;/&nbsp;2
                  </div>
                  <div class="row rating-title">
                    <span onclick="dropDown('#_baked')">Baked Goods</span> <i class="fa fa-info-circle" onclick="infoModal('Baked Good Info')"></i>
                  </div>
                  <div class="row rating" id="_baked">
                    Baked Good Availability Rating: <span id="_baked_avail"></span>&nbsp;/&nbsp;2<br />
                    Baked Good Price Rating: <span id="_baked_price"></span>&nbsp;/&nbsp;2
                  </div>
                  <div class="row rating-title">
                    <span onclick="dropDown('#_chips')">Baked Chips</span> <i class="fa fa-info-circle" onclick="infoModal('Baked Chip Info')"></i>
                  </div>
                  <div class="row rating" id="_chips">
                    Baked Chip Availability Rating: <span id="_chips_avail"></span>&nbsp;/&nbsp;2<br />
                    Baked Chip Price Rating: <span id="_chips_price"></span>&nbsp;/&nbsp;2
                  </div>
                  <div class="row rating-title">
                    <span onclick="dropDown('#_cereal')">Cereal</span> <i class="fa fa-info-circle" onclick="infoModal('Cereal Info')"></i>
                  </div>
                  <div class="row rating" id="_cereal">
                    Cereal Availability Rating: <span id="_cereal_avail"></span>&nbsp;/&nbsp;2<br />
                    Cereal Price Rating: <span id="_cereal_price"></span>&nbsp;/&nbsp;2
                  </div>
                </div>
                <div class="row rating-title" >
                  <span onclick="dropDown('#_latino')">Latino Foods</span> <i class="fa fa-info-circle" onclick="infoModal('Latino Food Info')"></i>
                </div>
                <div class="row rating" id="_latino">
                  Latino Food Availability Rating: <span id="_latino_avail"></span>&nbsp;/&nbsp;3<br />
                  Latino Food Price Rating: <span id="_latino_price"></span>&nbsp;/&nbsp;3
                </div>
                <div class="row rating-title" >
                  <span onclick="dropDown('#_asian')">Asian Foods</span> <i class="fa fa-info-circle" onclick="infoModal('Asian Food Info')"></i>
                </div>
                <div class="row rating" id="_asian">
                  Asian Food Availability Rating: <span id="_asian_avail"></span>&nbsp;/&nbsp;3<br />
                  Asian Food Price Rating: <span id="_asian_price"></span>&nbsp;/&nbsp;3
                </div>
                

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="infoModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="_modal_title">Modal Header</h4>
          </div>
          <div class="modal-body">
            <p>Future spot for data</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

      //maybe get rid of text since it looks cluttered
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
        result = {
          geometry: new ol.geom.Point(ol.proj.transform([parseFloat(v.y),parseFloat(v.x)], 'EPSG:4326',     
          'EPSG:3857')), 
        };
        $.each(v, function(_k, _v) {
          result[_k] = _v;
        });

        markerFeatures.push(new ol.Feature(result));
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
       *  UI functions
       */
      function dropDown(id){
        $(id).css('display') == "block" ? $(id).slideUp(500) : $(id).slideDown(500);
      }

      function infoModal(title){
        $("#_modal_title").text(title);
        $("#infoModal").modal('show');
      }

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

            if(properties.name != null){
              $('#info').show();
              $('#_store').text(properties.name);
              $('#_address').text(properties.address);
              $('#_milk_price').text(properties.milk_price);
              $('#_milk_avail').text(properties.milk_avail);
              $('#_fruit_avail').text(properties.fruit_avail);
              $('#_fruit_qual').text(properties.fruit_qual);
              $('#_veg_avail').text(properties.veg_avail);
              $('#_veg_qual').text(properties.veg_qual);
              $('#_hot_dog_avail').text(properties.hot_dog_avail);
              $('#_hot_dog_price').text(properties.hot_dog_price);
              $('#_beef_avail').text(properties.beef_avail);
              $('#_beef_price').text(properties.beef_price);
              $('#_frozen_dinner_avail').text(properties.frozen_dinner_avail);
              $('#_frozen_dinner_price').text(properties.frozen_dinner_price);
              $('#_baked_avail').text(properties.baked_avail);
              $('#_baked_price').text(properties.baked_price);
              $('#_beverage_avail').text(properties.bev_avail);
              $('#_beverage_price').text(properties.bev_price);
              $('#_bread_avail').text(properties.bread_avail);
              $('#_bread_price').text(properties.bread_price);
              $('#_chips_avail').text(properties.chips_avail);
              $('#_chips_price').text(properties.chips_price);
              $('#_cereal_avail').text(properties.cereal_avail);
              $('#_cereal_price').text(properties.cereal_price);
              $('#_latino_avail').text(properties.latino_avail);
              $('#_latino_price').text(properties.latino_price);
              $('#_asian_avail').text(properties.asian_avail);
              $('#_asian_price').text(properties.asian_price);

            }
        });
      });



    </script>
<?php 
  $conn->close();
?>
  </body>
</html>
