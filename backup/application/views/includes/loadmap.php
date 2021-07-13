<script type="text/javascript">
  /* *******************  locate  me scrip start ******************************/
var geocoder;
 
function locateMe(){ 
       var options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
        };
       if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showLocation, errorFunction, options);
        }  

}

function errorFunction(error){
  switch(error.code) {
    case error.PERMISSION_DENIED:
      x.innerHTML = "User denied the request for Geolocation."
      break;
    case error.POSITION_UNAVAILABLE:
      x.innerHTML = "Location information is unavailable."
      break;
    case error.TIMEOUT:
      x.innerHTML = "The request to get user location timed out."
      break;
    case error.UNKNOWN_ERROR:
      x.innerHTML = "An unknown error occurred."
      break;
  }
}

function showLocation(position) {
  var latitude = position.coords.latitude;
  var longitude = position.coords.longitude;
  $('#latitude').val(latitude);
  $('#longitude').val(longitude);
}
window.load = locateMe();
</script>
<?php /*?><script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBi058qOO9oTYjge0GqQqUcSWCEUhFIaUM&libraries=places&callback=initAutocomplete"></script><?php */?>
<?php //'AIzaSyB8N16ATuAIxLk_Ui1bO-rthjPemgYW0nE';?>