 <style>label{ font-weight: 600; }</style>
  <div class="content"> 
            <div class="container" style="min-height: 500px">

                <section class="common-padding">

                    <div class="common-area">
                        <div class="common-heading">
                            <h2 class="color-blue"> <?=$title;?>  </h2>
                        </div>

                        <div class="common-sub-are">
                         <div class="row ">
                                    <div class="col-12 col-md-12 col-lg-12">
                                    <p style="font-weight: 600; color: red">Note: Please Update Your AADHAAR Registered Mobile Number</p>
                                    </div></div>

                            <div class="row m-29">
                                    <div class="col-12 col-md-4 col-lg-4">
                                        <label>Old Mobile Number</label>  
                                        <input class="form-control numbersOnly" placeholder="Enter 10 digit mobile number" type="text" name="old_mobile" id="old_mobile" maxlength="10" autocomplete="off" readonly  value="<?=$aeps_mobile;?>" >
                                    </div> 

                                   <div class="col-12 col-md-4 col-lg-4">
                                        <label>New Mobile Number</label>  
                                        <input class="form-control numbersOnly" placeholder="Enter 10 digit mobile number" type="text" name="new_mobile" id="new_mobile" maxlength="10" autocomplete="off" onkeyup="this.value = this.value.replace(/[^0-9\.]/g,'');" value="" >
                                    </div>  

                                    <div class="col-12 col-md-4 col-lg-4 m-29">
                                
                                    <button class="form-control apply-filter ap_bg db-auto" type="submit" id="continuebtn" onclick="checkuser();">Continue</button>  
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
        var nmobile = $('#new_mobile').val();
        var omobile = $('#old_mobile').val(); 

        if(omobile.length != 10 ){ swalpopup('error','Enter 10 Digit old Mobile Number'); return false;} 
        else if(nmobile.length != 10 ){ swalpopup('error','Enter 10 Digit New Mobile Number'); return false;}
       

        
             $.ajax({
                type:'POST',
                url:'<?=ADMINURL.$folder.'/'.$pagename.'/chang_mob_post';?>',
                data:{'om':omobile,'nm':nmobile},
                chache:false,
                beforeSend:function(){$('#continuebtn').html('Please Wait...'); $("#continuebtn").attr("disabled", true);},
                success:function(res){ 
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