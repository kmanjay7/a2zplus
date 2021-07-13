<style type="text/css">
.progress{width: 71%;background-color: #fff; border:6px solid #4CAF50; margin: 0 auto; }
.progress-bar{ width: 10%;height: 20px !important;background-color: #4CAF50;text-align: center;line-height: 19px;font-size: 12px;color: white;}
</style>
<div class="content"> 
            <div class="container">

                <section class="common-padding">

                    <div class="common-area">
                        <div class="common-heading">
                            <h2 class="color-blue"> <?=$title;?>  </h2>
                        </div>

<div class="common-sub-are">  
    <form id="uploadForm" enctype="multipart/form-data">
        <input type="hidden" name="userid" value="<?=getloggeduserdata('id');?>">
        <div class="row" style="margin-top:10px"> 
            <div class="col-12 col-md-6 col-lg-6">
                <div class="sender_area">
                    <h4 class="sender_h">Browse File (E-Aadhaar Copy) </h4> 
                <input type="file" name="eaadhaar" class="upload-btn" id="eaadhaar" style="background: #1c1c80;color: #fff;padding: 6px;">  
                </div>
            </div> 

            <div class="col-12 col-md-6 col-lg-6 m-29"> 
            <button class="form-control apply-filter ap_bg db-auto" type="submit" id="continuebtn" >Upload & Continue</button> 

                 <!-- Progress bar start-->
                <div class="progress" id="progress" style="display: none"><div class="progress-bar"></div></div>
                <!-- Progress bar start-->

            </div> 

        </div>   

    </form> 


        <div class="row" style="margin-top:100px; border-top: 1px solid #ccc"> 
            <div class="col-12 col-md-12 col-lg-12 mt-5">
                <div class="sender_area">
                <h4 class="sender_h">Note: </h4> 
                 <ol>
                    <li>Only E-aadhaar will be accepted.</li>
                    <li>Mobile Number in Aadhaar & Portal must be the same.</li>
                    <li>It must have a download date of not older than 3 days.</li>
                    <li>The aadhaar's first 8 digits must me masked.(i.e only the last 4 digits should be visible for KYC)</li>
                 </ol>
                </div>
            </div> 

            <div class="col-12 col-md-12 col-lg-12">
                <div class="sender_area">
                <h4 class="sender_h">Steps to be followed: </h4> 
                 <ol>
                    <li>Download MASKED E-aadhaar from <a href="https://eaadhaar.uidai.gov.in" target="_blank">https://eaadhaar.uidai.gov.in</a> (not older than 3 days).</li>
                    <li>Take a print and self-attest the copy of E-Aadhaar.</li>
                    <li>Scan and upload the self-attested copy here (only JPG/JPEG/PNG formats) </li>
                 </ol>
                </div>
            </div> 
        </div>
 

    
</div>
                    </div><br/><br/>  

                </section>





               </div>
            </section>
           <br/>
         </div>
</div>


<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?> 
<?php $this->load->view('includes/common_session_popup');?> 
 <script>
$(document).ready(function(){

        // File type validation
        $("#eaadhaar").change(function(){
            var allowedTypes = ['image/jpeg', 'image/png', 'image/jpg']; 
            var file = this.files[0];
            var fileType = file.type;
            if(!allowedTypes.includes(fileType)){ 
              swalpopup('error','Please select a valid format like (JPEG/JPG/PNG)');
                $("#eaadhaar").val(''); 
                return false;
            }  
        });


    // File upload via Ajax
    $("#uploadForm").on('submit', function(e){
        e.preventDefault(); 

        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = ((evt.loaded / evt.total) * 100);
                        $(".progress-bar").width(percentComplete + '%');
                        $(".progress-bar").html(percentComplete+'%');
                    }
                }, false);
                return xhr;
            },
            type: 'POST',
            url: '<?=ADMINURL;?>webapi/aeps/Upload_e_aadhaar/uploadimage',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            crossOrigin: true,
            beforeSend: function(){ $('#progress').show(); $('#continuebtn').html('Please Wait...'); $('#continuebtn').attr("disabled", true); 
                $(".progress-bar").width('0%'); 
            },
            error:function(){
                swalpopup('error','File upload failed, please try again'); 
                $('#continuebtn').removeAttr("disabled");
                $('#progress').hide();$('#continuebtn').show();
            },
            success: function(resp){
              var obj = JSON.parse(JSON.stringify(resp));
                if(obj.status){
                    checkuser();  
                }else if(!obj.status){
                    swalpopup('error',obj.message);  
                    $('#continuebtn').removeAttr("disabled");
                    $('#continuebtn').html('Upload & Continue');
                    $(".progress-bar").width('0%');
                    $('#progress').hide(); 
                }  
                
            }

        });
    }); 
       
});
</script>
   <script type="text/javascript">
     function checkuser(){   
             $.ajax({
                type:'POST',
                url:'<?=ADMINURL.$folder.'/'.$pagename.'/upload_kyc';?>',
                data:{'adhaar':''},
                chache:false,
                beforeSend:function(){$('#continuebtn').html('Please Wait...'); $("#continuebtn").attr("disabled", true);},
                success:function(res){ 
                   var obj = JSON.parse(res);
                   var statv = obj.status;
                   var goto = obj.gotourl; 
                   if(statv){ 
                        var marchurl = obj.gotourl;
                        //swalpopup('success',obj.message); 
                        window.location.href=''+marchurl; 
                   }else{ swalpopup('error',obj.message); $('#continuebtn').html('Upload & Continue'); $('#continuebtn').removeAttr("disabled");}
                }
             });
         

     }

</script>



</body>

</html>