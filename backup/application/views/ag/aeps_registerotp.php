 <div class="content"> 
            <div class="container">

                <section class="common-padding">

                    <div class="common-area">
                        <div class="common-heading">
                            <h2 class="color-blue"> <?=$title;?>  </h2>
                        </div>

                        <div class="common-sub-are">
                         
                            <div class="row ">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="sender_area">
                                            <h4 class="sender_h"> Mobile Number </h4>
                                
                                        <input class="form-control numbersOnly" placeholder="Enter 10 digit mobile number" type="text" name="sender_mobile" id="mobileno" maxlength="10" autocomplete="off" readonly  value="<?=$mobile;?>" >
                                        <span id="mobilerror" style="color: red; font-size: 12px"></span>
                                    </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-6 m-29">
                                
                                    <button class="form-control apply-filter ap_bg db-auto" type="submit" id="continuebtn" onclick="checkuser();">Continue</button>  
                                    <center><span id="apimsg" style="color:red;font-size:12px"></span></center>
                                </div>

                            </div>
                            
                        </div>
                    </div>

                </section>





               </div>
            </section>
            <br> <br> <br>  <br> <br> <br>  <br> <br> <br>  <br> <br> <br>  <br> <br> <br>   
         </div>
</div>


<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?> 
<?php $this->load->view('includes/common_session_popup');?> 
 
   <script type="text/javascript">
     function checkuser(){ 
        var mobile = $('#mobileno').val(); 
        if( mobile.length == 10 ){
             $.ajax({
                type:'POST',
                url:'<?=ADMINURL.$folder.'/'.$pagename.'/register_otp';?>',
                data:{'mobile':mobile},
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
                   }else{ swalpopup('error',obj.status); $('#continuebtn').html('Continue'); $('#continuebtn').removeAttr("disabled");}
                }
             });
        } 
     }

</script>
</body>

</html>