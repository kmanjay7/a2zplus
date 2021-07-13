<script type="text/javascript">
	 

$( document ).ready(function() {

        var GetCustomDomName='127.0.0.1';
        var GetPIString='';
        var GetPAString='';
        var GetPFAString='';
        var DemoFinalString=''; 
        var finalUrl="";
        var MethodInfo="";
        var MethodCapture="";
        var OldPort=false;
 
            var SuccessFlag=0;
            var primaryUrl = "http://"+GetCustomDomName+":"; 
                         try {
                             var protocol = window.location.href;
                             if (protocol.indexOf("https") >= 0) {
                                primaryUrl = "https://"+GetCustomDomName+":";
                            }
                         } catch (e)
                        { }


            url = "";
            SuccessFlag=0;
                for (var i = 11100; i <= 11105; i++)
                {
                    if(primaryUrl=="https://"+GetCustomDomName+":" && OldPort==true)
                    {
                       i="8005";
                    } 
                   //console.log("Discovering RD service on port : " + i.toString());

                        var verb = "RDSERVICE";
                        var err = "";

                        var res;
                        $.support.cors = true;
                        var httpStaus = false;
                        var jsonstr="";
                         var data = new Object();
                         var obj = new Object(); 

                            $.ajax({

                            type: "RDSERVICE",
                            async: false,
                            crossDomain: true,
                            url: primaryUrl + i.toString(),
                            contentType: "text/xml; charset=utf-8",
                            processData: false,
                            cache: false,
                            crossDomain:true, 
                            success: function (data) {

                                httpStaus = true;
                                res = { httpStaus: httpStaus, data: data };
                               // alert(data);
                                
                                finalUrl = primaryUrl + i.toString();
                                var $doc = $.parseXML(data);
                                var CmbData1 =  $($doc).find('RDService').attr('status');
                                var CmbData2 =  $($doc).find('RDService').attr('info');
                                if(RegExp('\\b'+ 'Mantra' +'\\b').test(CmbData2)==true)
                                {
                                   // $("#txtDeviceInfo").val(data);

                                    if($($doc).find('Interface').eq(0).attr('path')=="/rd/capture")
                                    {
                                      MethodCapture=$($doc).find('Interface').eq(0).attr('path');
                                    }
                                    if($($doc).find('Interface').eq(1).attr('path')=="/rd/capture")
                                    {
                                      MethodCapture=$($doc).find('Interface').eq(1).attr('path');
                                    }
                                    if($($doc).find('Interface').eq(0).attr('path')=="/rd/info")
                                    {
                                      MethodInfo=$($doc).find('Interface').eq(0).attr('path');
                                    }
                                    if($($doc).find('Interface').eq(1).attr('path')=="/rd/info")
                                    {
                                      MethodInfo=$($doc).find('Interface').eq(1).attr('path');
                                    }

                                    window.location.href = "<?=ADMINURL.'ag/aeps/is_deviceready?port='?>"+i.toString();  
                                    console.log( i.toString() );

                                    SuccessFlag=1;
                                }

                               // alert(CmbData1);
                              //  alert(CmbData2);

                            },
                            error: function (jqXHR, ajaxOptions, thrownError) {
                            if(i=="8005" && OldPort==true)
                            {
                                OldPort=false;
                                i="11099";
                            } 
                            //alert(thrownError);  
                            },

                        }); 

                        if(SuccessFlag==1){
                          //break;
                        }

                }

                if(SuccessFlag==0)
                {
         window.location.href = "<?=ADMINURL.'ag/aeps/is_deviceready?port=11100'?>"; 
                //swalpopup("error","Connection failed Please try again.");
                }
                else{
                swalpopup("success","RDSERVICE Discover Successfully");
                }  
                return res;
        });

</script>