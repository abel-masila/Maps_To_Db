<!DOCTYPE html >
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Maps2DB</title>
    <style>
      #map {
        height: 70%;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      table{
      	padding: 60px;
      }
    </style>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  </head>
  <body>
   <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Maps To Db</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
      <br/>
      <br/>
      <br/>


    <div id="map" height="360px" width="100%"></div>
    <br/>
    <div id="form container-fluid">
      <table>
      <tr><td>Name:</td> <td><input type='text' id='name' class="form-control" /> </td> </tr>
      <tr><td>Address:</td> <td><input type='text' id='address' class="form-control"/> </td> </tr>
      <tr><td>Type:</td> <td><select id='type' class="form-control"> +
                 <option value='bar' SELECTED>Bar</option>
                 <option value='restaurant'>Restaurant</option>
                 </select> </td></tr>
                 <tr><td></td><td><input type='button' value='Save' class="btn btn-success" onclick='saveData()'/></td></tr>
      </table>
    </div>
    <div id="message">Location saved</div>
    <script>
      var map;
      var marker;
      var infowindow;
      var messagewindow;

      function initMap() {
        var Nairobi = {lat:-1.294491, lng: 36.817826};
        map = new google.maps.Map(document.getElementById('map'), {
          center: Nairobi,
          zoom: 13
        });

        infowindow = new google.maps.InfoWindow({
          content: document.getElementById('form')
        })

        messagewindow = new google.maps.InfoWindow({
          content: document.getElementById('message')
        });

        google.maps.event.addListener(map, 'click', function(event) {
          marker = new google.maps.Marker({
            position: event.latLng,
            map: map
          });


          google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map, marker);
          });
        });
      }

      function saveData() {
        var name = escape(document.getElementById('name').value);
        var address = escape(document.getElementById('address').value);
        var type = document.getElementById('type').value;
        var latlng = marker.getPosition();
        var url = 'phpsqlinfo_addrow.php?name=' + name + '&address=' + address +
                  '&type=' + type + '&lat=' + latlng.lat() + '&lng=' + latlng.lng();

        downloadUrl(url, function(data, responseCode) {

          if (responseCode == 200 && data.length <= 1) {
            infowindow.close();
            messagewindow.open(map, marker);
          }
        });
      }

      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request.responseText, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing () {
      }

    </script>
   <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap">
    </script>
  </body>
</html>