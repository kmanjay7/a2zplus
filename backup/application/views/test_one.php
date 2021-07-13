<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<script>
var count=0;
function RDService()
{

  var url = "http://127.0.0.1:11100";

  var xhr;
  var ua = window.navigator.userAgent;
  var msie = ua.indexOf("MSIE ");

  if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer, return version number
  {
    //IE browser
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } else {
    //other browser
    xhr = new XMLHttpRequest();
  }
        
  xhr.open('RDSERVICE', url, true);

   xhr.onreadystatechange = function () {
  // if(xhr.readyState == 1 && count == 0){
  //  fakeCall();
  //}
    if (xhr.readyState == 4){
            var status = xhr.status;

            if (status == 200) {

                alert(xhr.responseText);
        
        //Capture();                   //Call Capture() here if FingerPrint Capture is required inside RDService() call           
              console.log(xhr.response);

            } else {
                
              console.log(xhr.response);

            }
      }

        };

   /*setTimeout(function(){
   xhr.send();},1000);*/
   xhr.send();
}


function DeviceInfo()
{

  var url = "http://127.0.0.1:11100/getDeviceInfo";

         var xhr;
      var ua = window.navigator.userAgent;
      var msie = ua.indexOf("MSIE ");

      if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer, return version number
      {
        //IE browser
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
      } else {
        //other browser
        xhr = new XMLHttpRequest();
      }
        
        //
        xhr.open('DEVICEINFO', url, true);

         xhr.onreadystatechange = function () {
    // if(xhr.readyState == 1 && count == 0){
    //  fakeCall();
    //}
    if (xhr.readyState == 4){
            var status = xhr.status;

            if (status == 200) {

                alert(xhr.responseText);
               
              console.log(xhr.response);

            } else {
                
              console.log(xhr.response);

            }
      }

        };

   xhr.send();


}

function Capture()
{

  var url = "http://127.0.0.1:11100/capture";

   var PIDOPTS='<PidOptions ver=\"1.0\">'+'<Opts fCount=\"1\" fType=\"0\" iCount=\"\" iType=\"\" pCount=\"\" pType=\"\" format=\"0\" pidVer=\"2.0\" timeout=\"10000\" otp=\"\" wadh=\"\" posh=\"\"/>'+'</PidOptions>';
 
   /*
   format=\"0\"     --> XML
   format=\"1\"     --> Protobuf
   */
 var xhr;
      var ua = window.navigator.userAgent;
      var msie = ua.indexOf("MSIE ");

      if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer, return version number
      {
        //IE browser
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
      } else {
        //other browser
        xhr = new XMLHttpRequest();
      }
        
        xhr.open('CAPTURE', url, true);
    xhr.setRequestHeader("Content-Type","text/xml");
    xhr.setRequestHeader("Accept","text/xml");

        xhr.onreadystatechange = function () {
    //if(xhr.readyState == 1 && count == 0){
    //  fakeCall();
    //}
if (xhr.readyState == 4){
            var status = xhr.status;

            if (status == 200) {

              var xmlString = xhr.responseText;
              var parser = new DOMParser();
              var xml = parser.parseFromString(xmlString, "text/xml");
              var obj = xmlToJson(xml);
              //var jsonData = JSON.stringify(obj);
              //var objj = JSON.parse(jsonData);
 
 
 
      document.getElementById('text').value = obj.PidData.Resp.errCode;
       
            } else {
                
              console.log(xhr.response);

            }
      }

        };

        xhr.send(PIDOPTS);
  
}

function fakeCall(){
 var xhr1;
  var url = 'http://127.0.0.1:11100/getDeviceInfo';

      var ua = window.navigator.userAgent;
      var msie = ua.indexOf("MSIE ");

      if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer, return version number
      {
        //IE browser
        xhr1 = new ActiveXObject("Microsoft.XMLHTTP");
      } else {
        //other browser
        xhr1 = new XMLHttpRequest();
      }
        
        xhr1.open('DEVICEINFO', url, true);
    xhr1.send(); 
    count =1;
    xhr1.onreadystatechange = function () {
    if(xhr1.readyState == 4){
      xhr1.abort();
    }
    };
}


function xmlToJson(xml) {
  
  // Create the return object
  var obj = {};

  // console.log(xml.nodeType, xml.nodeName );
  
  if (xml.nodeType == 1) { // element
    // do attributes
    if (xml.attributes.length > 0) {
    obj = {};
      for (var j = 0; j < xml.attributes.length; j++) {
        var attribute = xml.attributes.item(j);
        obj[attribute.nodeName] = attribute.nodeValue;
      }
    }
  } 
  else if (xml.nodeType == 4) { // cdata section
    obj = xml.nodeValue
  }

  // do children
  if (xml.hasChildNodes()) {
    for(var i = 0; i < xml.childNodes.length; i++) {
      var item = xml.childNodes.item(i);
      var nodeName = item.nodeName;
      if (typeof(obj[nodeName]) == "undefined") {
        obj[nodeName] = xmlToJson(item);
      } else {
        if (typeof(obj[nodeName].length) == "undefined") {
          var old = obj[nodeName];
          obj[nodeName] = [];
          obj[nodeName].push(old);
        }
        if (typeof(obj[nodeName]) === 'object') {
          obj[nodeName].push(xmlToJson(item));
        }
      }
    }
  }
  return obj;
};

</script>
</head>
<body>
<center>
<h2>MORPHO RD TEST_PAGE</h2>
</center>
<div id="FingerPrint" style="width: 50$; height: 100%; float: left;">

<button type="button" onclick="RDService()">RDService</button>
<br/><br/>

<button type="button" onclick="DeviceInfo()">DeviceInfo</button>
<br/><br/>

<button type="button" onclick="Capture()">Capture</button>
<br/><br/>

<textarea rows=10 cols=100 id='text'></textarea>
<br/><br/>

</body>
</html>
