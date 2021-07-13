<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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
   <style> 
#password-strength-status {font-size: 10px;}
.medium-password{color: #ff0047 }
.weak-password{ color: red; }
.strong-password{ color: green; }
.login-from{padding: 50px 50px 20px;}
</style>

 <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script type="text/javascript">
function checkPasswordStrength() {
    var number = /([0-9])/;
    var alphabets = /([a-zA-Z])/;
    var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
    
    if($('#pass').val().length < 6) {
        $('#password-strength-status').removeClass();
        $('#password-strength-status').addClass('weak-password');
        $('#password-strength-status').html("Weak (should be atleast 6 characters.)");
    } else {    
        if($('#pass').val().match(number) && $('#pass').val().match(alphabets) && $('#pass').val().match(special_characters)) {            
            $('#password-strength-status').removeClass();
            $('#password-strength-status').addClass('strong-password');
            $('#password-strength-status').html("Strong");
            setTimeout(function(){ $('#password-strength-status').html(""); },3000); 
        } else {
            $('#password-strength-status').removeClass();
            $('#password-strength-status').addClass('medium-password');
            $('#password-strength-status').html("Medium (should include alphabets, numbers and special characters.)");
        } 
    }
}
</script>
<script>  
function confirmPass() {
    var pass = document.getElementById("pass").value
    var confPass = document.getElementById("c_pass").value
    if(pass != confPass) { 
        document.getElementById('error').innerHTML='wrong confirm password';
    }else {
        document.getElementById('error').innerHTML='';
    }
}
</script>
</head>

<body>
    <!---Header Page-->
    <div class="main fixed-top">
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-lg-3 col-3">
                        <a href="<?=ADMINURL;?>"> <img src="<?=ADMINURL;?>assets/images/logo.png"></a>
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
                                <h2 style="font-size: 20px;"> Change Password </h2> 
                                    <?= notification();?>
                                    <div class="border-login" style="margin-bottom: 20px;"> 
                                    </div> 
                                    <form action="<?= ADMINURL.'Forgot/resetpass';?>" method="post" class="">
                                        <div class="form-group">
                                            <input type="password" id="pass" name="password" onKeyUp="checkPasswordStrength();" class="form-control" autocomplete="off" placeholder="Enter Password"> 
                                            <div id="frmCheckPassword"><span id="password-strength-status"></span></div>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" id="c_pass" name="cpassword" class="form-control" autocomplete="off" placeholder="Confirm Password" onblur="confirmPass()">
                                            <span id="error" style="color:#F00;"> </span> 
                                        </div>

                                        <div class="form-group">
                                            <input type="number" name="otp" class="form-control" maxlength="4"  autocomplete="off" placeholder="Enter 4 digit OTP">
                                        </div>

                                        <div class="form-group">
                                            <input type="submit" class=" submit_btn" value="Submit">
                                        </div>

                                    </form>

                                    <a href="<?= ADMINURL.'login';?>"> Login Now</a>
                            </div>

                        </div>
                        <div class="col-md-7 pl-0">
                            <div class="login-img" style="border-left: 1px solid #ccc">
                                <img src="<?= ADMINURL;?>assets/images/login.jpg" > 
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