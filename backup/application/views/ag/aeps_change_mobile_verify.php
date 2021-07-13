 <style>label{ font-weight: 600; }</style>
 <div class="content"> 
            <div class="container" style="min-height: 500px">

                <section class="common-padding">

                    <div class="common-area">
                        <div class="common-heading">
                            <h2 class="color-blue"> <?=$title;?>  </h2>
                        </div>

                        <div class="common-sub-are">
                         
                            <div class="row">
                                    <div class="col-12 col-md-3 col-lg-3">
                                         <label>Old Mobile Number</label>  
                                        <input class="form-control" placeholder="Enter 10 digit mobile number" type="text" id="old_mobile" maxlength="10" autocomplete="off" readonly  value="<?=$old_mobile;?>" >
                                    </div>

                                    <div class="col-12 col-md-3 col-lg-3">
                                         <label>Old Mobile OTP</label>  
                                        <input class="form-control" placeholder="Enter Old Mobile OTP" type="text" id="old_mobile_otp" maxlength="10" autocomplete="off" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" value="" >
                                    </div>
                                    

                                    <div class="col-12 col-md-3 col-lg-3">
                                         <label>New Mobile Number</label>  
                                        <input class="form-control" placeholder="Enter 10 digit new mobile number" type="text" id="new_mobile" maxlength="10" autocomplete="off" readonly  value="<?=$new_mobile;?>" >
                                    </div>

                                    <div class="col-12 col-md-3 col-lg-3">
                                         <label>New Mobile OTP</label>  
                                        <input class="form-control" placeholder="Enter New Mobile OTP" type="text" name="old_mobile" id="new_mobile_otp" maxlength="10" autocomplete="off" value="" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" >
                                    </div>

                                </div>

                                <div class="row"> 
                                    <div class="col-12 col-md-3 col-lg-3 m-29"> 
                                    <button class="form-control  ap_bg db-auto" type="submit" id="continuebtn" style="color: #fff" onclick="checkuser();">Continue</button>  
                                    <center><span id="apimsg" style="color:red;font-size:12px"></span></center>
                                </div>

                            </div>
                            
                        </div>
                    </div>

                </section>





               </div>
            </section>
           <br/>
         </div>
</div>


<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?> 
<?php $this->load->view('includes/common_session_popup');?> 
 
   <script type="text/javascript">
     function checkuser(){ 
        var om = $('#old_mobile').val();
        var omtp = $('#old_mobile_otp').val();
        var nm = $('#new_mobile').val();
        var nmtp = $('#new_mobile_otp').val(); 
 
if(omtp.length == 0){ swalpopup('error','Enter Old Mobile OTP'); return false;}
else if(nmtp.length == 0){ swalpopup('error','Enter New Mobile OTP'); return false;} 

        
             $.ajax({
                type:'POST',
                url:'<?=ADMINURL.$folder.'/'.$pagename.'/chang_mob_verify';?>',
                data:{ 'omtp':omtp, 'nmtp':nmtp},
                chache:false,
                beforeSend:function(){$('#continuebtn').html('Please Wait...'); $("#continuebtn").attr("disabled", true);},
                success:function(res){
                  console.log(res);
                   var obj = JSON.parse(res);
                   var status = obj.status;
                   if(status){ 
                        var marchurl = obj.gotourl;
                        swalpopup('success',obj.message); 
                        setTimeout(function(){ window.location.href=''+marchurl; },3000); 
                   }else{ swalpopup('error',obj.message); $('#continuebtn').html('Continue'); $('#continuebtn').removeAttr("disabled");}
                }
             });
         

     }

</script>
</body>

</html>