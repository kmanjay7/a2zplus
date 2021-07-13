<script language="javascript" type="text/javascript"> 

 $( document ).ready(function() {


       // SYSTEM.FETCH_RD_SERVICE_LIST(); 

            setTimeout(function(){
                // scan_all_rd_services();
SYSTEM.FETCH_RD_SERVICE_LIST();
            }, 3000);
        });

</script>


<script type="text/javascript">
    const potential_urls = [];
     var STOCK = {};
     STOCK.RD_SERVICES = [];

    var FLAG = {},CONFIG;
    FLAG.ROOT =  "";
    FLAG.RD_SERVICE_SCAN_DONE = false;



    var SYSTEM = 
    {
        FETCH_RD_SERVICE_LIST: function(forced = false) {
            var rd_service_cookie = $.cookie('RDSLS');
            if(rd_service_cookie) {
                FLAG.RD_SERVICE_SCAN_DONE = true;
                STOCK.RD_SERVICES = JSON.parse(rd_service_cookie);
            }
            
            if(forced || !rd_service_cookie || (rd_service_cookie && STOCK.RD_SERVICES.length == 0) ) {
                STOCK.RD_SERVICES = [];
                FLAG.RD_SERVICE_SCAN_DONE = false;
                scan_all_rd_services();
            }
        }
    };



if (window.location.host == 'localhost') {
    potential_urls.push(...[{
            url: 'https://078af445.ngrok.io',
            port: 11105
        },
        {
            url: 'https://fbfe5cb0.ngrok.io',
            port: 11100
        },
        {
            url: 'https://6be90cc9.ngrok.io',
            port: 11101
        },
        {
            url: 'https://15297ba5.ngrok.io',
            port: 11102
        }
    ]);
} else {
    var port = 11100;
    for (; port <= 11120; port++) {
        potential_urls.push({
            url: `http://127.0.0.1:${port}`,
            port
        });
    }
}

function scan_all_rd_services(scan_index = 0, network_err_count = 0) {
    if (potential_urls.length == scan_index || network_err_count >= 3) {
        $.cookie("RDSLS", JSON.stringify(STOCK.RD_SERVICES));
        FLAG.RD_SERVICE_SCAN_DONE = true;
        return;
    }

    invoke_request({
        type: 'RDSERVICE',
        url: potential_urls[scan_index].url,
        success(data) {
            if (!$.isXMLDoc(data)) {
                data = $.parseXML(data);
            }

            var deviceStatus = $(data).find('RDService').attr('status');
            var deviceInfo = $(data).find('RDService').attr('info');

            var deviceInfoPath = $(data).find('Interface[id="DEVICEINFO"]').attr('path');
            var deviceCapturePath = $(data).find('Interface[id="CAPTURE"]').attr('path');

            if (/morpho/i.test(deviceInfo)) {
                // Device is Morpho
                deviceInfoPath = /\/getDeviceInfo$/.test(deviceInfoPath) ? '/getDeviceInfo' : '/rd/info';
                deviceCapturePath = /\/capture$/.test(deviceCapturePath) ? '/capture' : '/rd/capture';
            }

            var error = 0;
            if (deviceStatus != 'READY') {
                error = 1;
            }else if (deviceStatus == 'READY') { 
        $('#captureport').val( potential_urls[scan_index].port+deviceCapturePath );
                 
            }

            STOCK.RD_SERVICES.push({
                port: potential_urls[scan_index].port,
                url: potential_urls[scan_index].url,
                deviceInfo,
                deviceInfoPath,
                deviceCapturePath
            });

            scan_all_rd_services(++scan_index);
        },
        error() {
            scan_all_rd_services(++scan_index, ++network_err_count);
        }
    });
}


function discover_device(callback, rd_index = 0, rd_error_bag = []) {
    if (rd_index == STOCK.RD_SERVICES.length) {
        callback(null, rd_error_bag);
        return;
    }

    invoke_request({
        type: 'RDSERVICE',
        url: STOCK.RD_SERVICES[rd_index].url,
        success(data) {
            if (!$.isXMLDoc(data)) {
                data = $.parseXML(data);
            }

            var deviceStatus = $(data).find('RDService').attr('status');
            var deviceInfo = $(data).find('RDService').attr('info');

            var deviceInfoPath = $(data).find('Interface[id="DEVICEINFO"]').attr('path');
            var deviceCapturePath = $(data).find('Interface[id="CAPTURE"]').attr('path');

            if (/morpho/i.test(deviceInfo)) {
                // Device is Morpho
                deviceInfoPath = /\/getDeviceInfo$/.test(deviceInfoPath) ? '/getDeviceInfo' : '/rd/info';
                deviceCapturePath = /\/capture$/.test(deviceCapturePath) ? '/capture' : '/rd/capture';
            }

            var error = 0;
            if (deviceStatus != 'READY') {
                error = 1;
                rd_error_bag.push({
                    error,
                    data: {
                        port: STOCK.RD_SERVICES[rd_index].port,
                        url: STOCK.RD_SERVICES[rd_index].url,
                        deviceStatus,
                        deviceInfo,
                        deviceInfoPath,
                        deviceCapturePath
                    }
                });
                discover_device(callback, ++rd_index, rd_error_bag);
                return;
            }

            callback({
                error,
                data: {
                    port: STOCK.RD_SERVICES[rd_index].port,
                    url: STOCK.RD_SERVICES[rd_index].url,
                    deviceStatus,
                    deviceInfo,
                    deviceInfoPath,
                    deviceCapturePath
                }
            });
        },
        error() {
            rd_error_bag.push({
                error: -1,
                data: {}
            });
            SYSTEM.FETCH_RD_SERVICE_LIST(true);
            var interval_id = setInterval(() => {
                if (FLAG.RD_SERVICE_SCAN_DONE) {
                    clearInterval(interval_id);
                    discover_device(callback);
                }
            }, 1000);
        }
    });
}

function invoke_request(options) {
    let { type, url, data, success, error } = options;
    if (!type || !url || !success || !error) {
        throw Error('call to invoke_request is not valid');
    }

    $.support.cors = true;

    $.ajax({
        crossDomain: true,
        // contentType: 'text/xml; charset=utf-8',
        processData: false,
        cache: false,
        type,
        url,
        data,
        success,
        error
    });
}


/*ready mantra device code end here */ 
    var finalUrl=""; 
    var MethodCapture=""; 


function Capture(){ 

    // S - Staging
    // PP - Pre-Production
    // P - Production

   var is_port = $('#captureport').val(); 
   var url = "http://127.0.0.1:"+is_port;

   var PIDOPTS='<PidOptions ver=\"1.0\">'+'<Opts fCount=\"1\" fType=\"0\" iCount=\"0\" iType=\"\" pgCount=\"2\" pCount=\"0\" pType=\"\" format=\"0\" pidVer=\"2.0\" timeout=\"10000\" pTimeout=\"20000\" otp=\"\" wadh=\"\" posh=\"UNKNOWN\" env=\"P\" />'+'<CustOpts>'+'<Param name=\"mantrakey\" value=\"\" />'+'</CustOpts>'+'</PidOptions>'; 
 
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
        
     var x, i, srno; 
     var x = xml.getElementsByTagName("Param"); 
     for (i = 0; i < x.length; i++) { 
        if( x[i].getAttribute('name')=='srno'){
          srno = x[i].getAttribute('value');
        }
     } 
 

      
      movebar( getEmnt(xml,'Resp','qScore') ); 

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
      document.getElementById('srno').value = srno;

      }else if( getEmnt(xml,'Resp','errCode') == '720'){ 
        window.location.href = "<?=ADMINURL.'ag/aeps/is_deviceready?port=11101';?>";
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




 function getEmnt(xml,tag,attri){
    var x, i, txt;
    var x = xml.getElementsByTagName(tag);
    for (i = 0; i < x.length; i++) { 
        txt = x[i].getAttribute(attri);
    }
    return txt;
}


    </script>