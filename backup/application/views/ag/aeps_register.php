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
                                    </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="sender_area">
                                            <h4 class="sender_h"> Pancard Number  </h4>
                                
                                        <input class="form-control" placeholder="Enter Pancard Number" type="text" name="pancard" id="pancard" autocomplete="off" readonly  value="<?=$pancard;?>" > 
                                    </div>
                                    </div>

                                </div>


                                <div class="row" style="margin-top:10px">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="sender_area">
                                            <h4 class="sender_h"> Owner Name </h4>
                                
                                        <input class="form-control" placeholder="Enter Owner Name" type="text" name="name" id="name"  autocomplete="off" readonly  value="<?=$name;?>" > 
                                    </div>
                                    </div>
                                
                                   <div class="col-12 col-md-6 col-lg-6">
                                        <div class="sender_area">
                                            <h4 class="sender_h"> Email ID </h4>
                                
                                        <input class="form-control" placeholder="Enter email address" type="text" name="email" id="email"  autocomplete="off" readonly  value="<?=$email;?>" >
                                         
                                    </div>
                                    </div>
                                    

                                </div>


                            <div class="row" style="margin-top:10px">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="sender_area">
                                            <h4 class="sender_h">Company Name </h4>
                                
                                        <input class="form-control" placeholder="Enter Owner Name" type="text" name="firmname" id="firmname"  autocomplete="off" readonly  value="<?=$firmname;?>" > 
                                    </div>
                                    </div>

                                   <div class="col-12 col-md-6 col-lg-6">
                                        <div class="sender_area">
                                            <h4 class="sender_h">Pincode </h4>
                                
                                        <input class="form-control" placeholder="Enter Pincode" type="text" name="pincode" id="pincode"  autocomplete="off" readonly  value="<?=$pincode;?>" > 
                                    </div>
                                    </div>

                                </div>



                                <div class="row" style="margin-top:10px">
                                    

                                    <div class="col-12 col-md-12 col-lg-12">
                                        <div class="sender_area">
                                            <h4 class="sender_h"> Address  </h4>
                                
                                        <input class="form-control" placeholder="Enter Address" type="text" name="address" id="address" autocomplete="off" readonly  value="<?=$address;?>" > 
                                    </div>
                                    </div>

                                </div>






                                <div class="row" style="margin-top:10px">

                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="sender_area">
                                            <h4 class="sender_h">OTP </h4>
                                
                                        <input class="form-control" placeholder="Enter OTP" type="text" name="otp" id="otp"  autocomplete="off"  value="" > 
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
           <br/>
         </div>
</div>


<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?> 
<?php $this->load->view('includes/common_session_popup');?> 
 
   <script type="text/javascript">
     function checkuser(){ 
        var mobile = $('#mobileno').val();
        var pancard = $('#pancard').val();
        var name = $('#name').val();
        var email = $('#email').val();
        var firm = $('#firmname').val(); 
        var pincode = $('#pincode').val();
        var address = $('#address').val();
        var otp = $('#otp').val(); 

        if(mobile.length != 10 ){ swalpopup('error','Enter 10 Digit Uniques ID'); return false;} 
        else if(pancard.length != 10 ){ swalpopup('error','Enter 10 Digit Pan number'); return false;}
        else if(name =='' ){ swalpopup('error','Enter Owner name'); return false;}
        else if(email =='' ){ swalpopup('error','Enter Email Address'); return false;}
        else if(firm =='' ){ swalpopup('error','Enter Firm Name'); return false;}
        else if(pincode =='' ){ swalpopup('error','Enter 6 digit pincode'); return false;}
        else if(address =='' ){ swalpopup('error','Enter address'); return false;}
        else if(otp =='' ){ swalpopup('error','Enter OTP recieved at '+mobile); return false;}

        
             $.ajax({
                type:'POST',
                url:'<?=ADMINURL.$folder.'/'.$pagename.'/register_user';?>',
                data:{'otp':otp},
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