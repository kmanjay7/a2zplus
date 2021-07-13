<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Login </title>
    <link href="<?= ADMINURL ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
   <link href="<?= ADMINURL ?>assets/css/style.css" rel="stylesheet" type="text/css">
   <link href="<?= ADMINURL ?>assets/css/main.css" rel="stylesheet" type="text/css">
   <link href="<?= ADMINURL ?>assets/css/owl-carousel.css" rel="stylesheet" type="text/css">
   <link href="<?= ADMINURL ?>assets/css/animate.css" rel="stylesheet" type="text/css">
   <link href="<?= ADMINURL ?>assets/css/chart.css" rel="stylesheet" type="text/css">
   <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
   <link href="<?= ADMINURL ?>assets/css/bootstrap-datepicker.css" rel="stylesheet">
   <script type="text/javascript">  
function resend(){
    $.ajax({
          url:'<?php echo base_url('Otp/resendotp'); ?>',
          type:'POST',

          success: function(data) {
       $("#message").fadeIn('slow', function () {
    $(this).delay(3000).fadeOut('slow');
  });
          }
        });
}
</script>  

<body>
    <!---Header Page-->
    <div class="main fixed-top">
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-lg-3 col-3">
                        <a href="<?=ADMINURL;?>"> <img src="<?= ADMINURL ?>assets/images/logo.png"></a>
                    </div>
                    <div class="col-md-9 col-lg-9 col-9">
                        <div class="balance-txt agent_txt">
                           
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>

    <div class="content">
        <div class="container">

            <div class="agent-login">
                <div class="login-area">
                    <div class="row">
                        <div class="col-md-5 pr-0">
                            <div class="login-from">
                                <h2> OTP </h2>
                                <?= notification();?>
                                <div class="alert alert-success alert-dismissible" style="display:none" id="message" align="center" style="width:100%;padding:3px;"> OTP send successfully! </div>
                                    <div class="border-login">

                                    </div>
                                    <form action="<?= ADMINURL.'Otp/check';?>" method="post" class="">
                                        <div class="form-group">
                                            <input type="text" name="otp" class="form-control" autocomplete="off" placeholder="Enter OTP">
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class=" submit_btn" value="Login">
                                        </div>

                                    </form>

                                    <a href="javascript:void(0)" onclick="resend(); return false;"> Resend OTP</a>
                            </div>

                        </div>
                        <div class="col-md-7 pl-0">
                            <div class="login-img" style="border-left: 1px solid #ccc">
                                <img src="./assets/images/login.jpg" style=" width: 100%"> 
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <br>
            <br>

        </div>
    </div>
    <?php include('includes/footer.php');?>
        <?php include('includes/alljs.php');?>
</body>

</html>