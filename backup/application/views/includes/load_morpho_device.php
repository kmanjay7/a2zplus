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

              //  alert(xhr.responseText);
               
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

      if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)){ 
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
      } else { 
        xhr = new XMLHttpRequest();
      }
        
        xhr.open('CAPTURE', url, true);
        xhr.setRequestHeader("Content-Type","text/xml");
        xhr.setRequestHeader("Accept","text/xml");

        xhr.onreadystatechange = function () { 
        if (xhr.readyState == 4){
            var status = xhr.status;

            if (status == 200) {  

              var parser, xml;
              var xmlString = xhr.responseText;
              var parser = new DOMParser();
              var xml = parser.parseFromString(xmlString, "text/xml");


     if(getEmnt(xml,'Resp','errCode') == '0'){ 
        //load progress bar
        movebar('80');

      document.getElementById('pidDataType').value =  getEmnt(xml,'Data','type');
      document.getElementById('pidData').value = xml.getElementsByTagName("Data")[0].childNodes[0].nodeValue;
      document.getElementById('ci').value = getEmnt(xml,'Skey','ci');
      document.getElementById('dc').value = getEmnt(xml,'DeviceInfo','dc');
      document.getElementById('dpId').value = getEmnt(xml,'DeviceInfo','dpId');
      document.getElementById('errCode').value = getEmnt(xml,'Resp','errCode');
      document.getElementById('errInfo').value = getEmnt(xml,'Resp','errInfo');
      document.getElementById('fCount').value = getEmnt(xml,'Resp','fCount');
      document.getElementById('tType').value = getEmnt(xml,'Resp','tType');
      document.getElementById('hmac').value = xml.getElementsByTagName("Hmac")[0].childNodes[0].nodeValue;
      document.getElementById('iCount').value = getEmnt(xml,'Resp','iCount');
      document.getElementById('mc').value = getEmnt(xml,'DeviceInfo','mc');
      document.getElementById('mi').value = getEmnt(xml,'DeviceInfo','mi');
      document.getElementById('nmPoints').value = getEmnt(xml,'Resp','nmPoints');
      document.getElementById('pCount').value = getEmnt(xml,'Resp','pCount');
      document.getElementById('pType').value = getEmnt(xml,'Resp','pType');
      document.getElementById('qScore').value = getEmnt(xml,'Resp','qScore'); 
      document.getElementById('rdsId').value = getEmnt(xml,'DeviceInfo','rdsId');
      document.getElementById('rdsVer').value = getEmnt(xml,'DeviceInfo','rdsVer');
      document.getElementById('sessionKey').value = xml.getElementsByTagName("Skey")[0].childNodes[0].nodeValue;
      document.getElementById('srno').value = getEmnt(xml,'Param','value');
      }else if( getEmnt(xml,'Resp','errCode') == '700'){ 
        swalpopup('error','Capture timed out!');  resetV('res'); }
       
            } else { //movebar();
              swalpopup('error','Please Connect Your Device First!');
               resetV('res');
              //console.log(xhr.response);  
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



function getEmnt(xml,tag,attri){
    var x, i, txt;
    var x = xml.getElementsByTagName(tag);
    for (i = 0; i < x.length; i++) { 
        txt = x[i].getAttribute(attri);
    }
    return txt;
}
</script> 