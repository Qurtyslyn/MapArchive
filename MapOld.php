<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Bor Map Archive</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.1/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.1/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.4.0/gpx.min.js"></script>
    <style>
      #map {
        width: 100%;
        height: 900px;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script type="text/javascript">
    
        var colors = ['red','teal','darkorange','darkgreen','hotpink','blue','yellow','violet','maroon','deepskyblue'];

        //https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png
        //http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png
        var topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            attribution: 'Map Data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | Map Display: &copy; <a href="http://opentopomap.org/"> OpenTopoMap </a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)',
            maxZoom: 18,
            subdomains: ['a','b','c']
        });

        var streets = L.tileLayer( 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 18,
            subdomains: ['a','b','c']
        });

        var map = L.map('map', {
            center: [40.643901, -114.131361],
            zoom: 14,
            layers: [streets]
        });

        var backgrounds = {
          "Basic" : streets,
          "Topographic" : topo
          };

        var files = [        <?php
            if($dh = opendir("Maps/")){
              while (($file = readdir($dh)) !== false){
                if ($file == '.' or $file == '..') continue;
                echo '"' . $file . '",';
              }
              closedir($dh);
            }

        ?>];

        var overlays = {}

        //Adding GPX Routes to the Map
        //https://github.com/mpetazzoni/leaflet-gpx
        var colorCount = 0;
        for(let item in files) {
            if(colorCount == colors.length)
            {
                colorCount = 0;
            }
          var course = 'Maps/' + files[item];

            var route = new L.GPX(course, {
                async: true,
                polyline_options: {
                    color: colors[colorCount],
                    opacity: 0.75,
                    weight: 5,
                    lineCap: 'round'
                },
                marker_options: {
                    startIconUrl: '',
                    endIconUrl: '',
                    shadowUrl: '',
                    wptIconUrls: {
                    '': 'Icons/' + colors[colorCount] + '-wpt.png'
                    }
                },
                
            }).on('loaded', function(e) {
                map.fitBounds(e.target.getBounds());
          }).addTo(map);

          overlays[(files[item].split('.')[0])] = route;
          
          colorCount++;
        }

        var control = L.control.layers(backgrounds,overlays, {collapsed : false}).addTo(map);


        //var marker = L.marker(
        //    [ 40.710777, -114.144231],{title: 'Check 1'}
        //    ).addTo(map);

    
        //map.removeLayer(marker);


    </script>
  </body>
</html>