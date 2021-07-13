<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> <?=$title;?> </title>
    <link rel="icon" href="assets/images/favicon-60x60.png" type="image/x-icon" />
     <link href="./assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"> 
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"> 
    <link href="./assets/css/owl-carousel.css" rel="stylesheet" type="text/css">
    <link href="./assets/css/style-front.css" rel="stylesheet" type="text/css">
    <link href="./assets/css/animate.css" rel="stylesheet" type="text/css">
</head>

<body>
    <!---Header Page-->
    <?php $this->load->view('includes/header-front'); ?>
         
    <section class="bredcrum">
       <div class="container">
        <div class="common-heading">
            <h3> <?=$title;?> </h3>
            <p> DigiCash India is one of the fastest growing names... </p>
        </div>
       </div>
    </section>

    <section>
        <div class="container">
            <div class="row"> 
                <div class="col-md-12 col-lg-12 col-12 wow " style="animation-name:slideInRight;">
                      <div class="contact-txt">
             
                        <div class="col-12">
                            <p class="write">Personal Details</p>
                        </div>


            <form action="<?=ADMINURL.$postdata.'/save';?>" method="post" onsubmit="return validatejoin()" >
            
            <div class="col-12 col-lg-12">
            <div class="form-group">
            <?=form_dropdown(array('name'=>'user','id'=>'user','class'=>'form-control select2'),[""=>"--Interested In--", "MD"=>"Master Distributor", "AD"=>"Area Distributor", "Agent"=>"Agent"] );?>
            </div>
            </div>

            <div class="col-12 col-lg-12">
            <div class="form-group">
            <input type="text" name="name" id="name" class="form-control lettersOnly" onkeyup="uc('name','name')" placeholder="Your Name" value="" required=""  />
            </div>
            </div>
            <div class="col-12 col-lg-12">
            <div class="form-group">
            <input type="text" name="mobileno" id="mobileno" class="form-control numbersOnly" placeholder="Contact No." maxlength="10" value="" required="" />
            </div>
            </div>

            <div class="col-12">
            <div class="form-group">
            <input type="email" name="email" id="email" class="form-control emailOnly" placeholder="Email Address" value="" required="" />
            </div>
            </div>

            <div class="col-12">
            <div class="form-group">
            <input type="text" name="pincode" id="pincode" class="form-control numbersOnly" placeholder="Enter 6 digit pincode" value="" maxlength="6" required="" />
            </div>
            </div>

            <div class="col-12 col-lg-12">
            <div class="form-group">
            <?=form_dropdown(array('name'=>'cityname','id'=>'cityname','class'=>'form-control select2'),create_dropdownfrom_array($list,'cityname','cityname','cityname--') );?>
            </div>
            </div>


            <div class="col-12">
            <div class="form-group">
            <textarea name="comment" id="comment" class="form-control address" placeholder="Your messages" style="width: 100%; height: 160px;" required=""></textarea>
            </div>
            </div>
            <div class="col-12 pt-3">
            <input type="submit" class="send-now" value="Submit" />
            </div>
            </form>
        </div>
              

                    </div>
                </div>
<br/><br/><br/><br/>
        </div>
</section>

  
<!---footer---->
<?php $this->load->view('includes/footer-front');?>
<?php $this->load->view('includes/alljs');?>
<?php $this->load->view('includes/common_session_popup');?>
<script type="text/javascript"> 

function validatejoin(){ 
var name = $('#name').val();

if( name ==""){
$('#name').css('border-color', 'red');
$("#name").focus();
  return(false);
 }

var mobileno = $('#mobileno').val();
if(mobileno.length !=10){
$('#mobileno').css('border-color', 'red');
$("#mobileno").focus();
  return(false);
}

var email = $('#email').val();
if(email.length == ""){
$('#email').css('border-color', 'red');
$("#email").focus();
  return(false);
}

var pincode = $('#pincode').val();
if(pincode.length !=6){
$('#pincode').css('border-color', 'red');
$("#pincode").focus();
  return(false);
}


var cityname = $('#cityname').val();
if(cityname.length == ""){
$('#cityname').css('border-color', 'red');
$("#cityname").focus();
  return(false);
}

var comment = $('#comment').val();
if(comment.length == ""){
$('#comment').css('border-color', 'red');
$("#comment").focus();
  return(false);
}

}


 $(document).ready(function () { 
                jQuery('.numbersOnly').keyup(function () { 
                this.value = this.value.replace(/[^0-9\.]/g,'');
                });

                jQuery('.lettersOnly').keyup(function () { 
                this.value = this.value.replace(/[^a-zA-Z\s]+$/g,'');
                });

                jQuery('.alphanimericOnly').keyup(function () { 
                this.value = this.value.replace(/[^A-Za-z0-9.\/\s]/g,'');
                });

                jQuery('.address').keyup(function () { 
                this.value = this.value.replace(/[^A-Za-z0-9//,.\/\s]/g,'');
                });
                jQuery('.emailOnly').keyup(function () { 
                this.value = this.value.replace(/[^@_a-zA-Z0-9\.]/g,'');
                });
                jQuery('.floatval').keyup(function () { 
                this.value = this.value.replace(/[^0-9.\/\s]/g,'');
                });
                });

$(document).ready(function() {
    $('.select2').select2();
});

 
</script>
<?php $this->load->view('includes/common_session_popup');?>
<link rel="stylesheet" type="text/css" href="<?=ADMINURL.'assets/css/select2.css';?>"> 
<script type="text/javascript" src="<?=ADMINURL.'assets/js/select2.js';?>"></script>
</body>

</html>