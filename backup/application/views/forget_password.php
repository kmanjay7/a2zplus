<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Forgot Password </title>
    <link href="<?= ADMINURL ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
   <link href="<?= ADMINURL ?>assets/css/style.css" rel="stylesheet" type="text/css">
   <link href="<?= ADMINURL ?>assets/css/main.css" rel="stylesheet" type="text/css">
   <link href="<?= ADMINURL ?>assets/css/owl-carousel.css" rel="stylesheet" type="text/css">
   <link href="<?= ADMINURL ?>assets/css/animate.css" rel="stylesheet" type="text/css">
   <link href="<?= ADMINURL ?>assets/css/chart.css" rel="stylesheet" type="text/css">
   <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
   <link href="<?= ADMINURL ?>assets/css/bootstrap-datepicker.css" rel="stylesheet">
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
                                <h2 style="font-size: 20px;"> Forgot Password </h2>
                                <?= notification();?>
                                    <div class="border-login">

                                    </div>
                                    <form action="<?= ADMINURL.'Forgot/check';?>" method="post" class="">
                        <div class="form-group">
                        <select name="usertype" class="form-control" id="">
                        <option value="">Select User Type</option>
                        <option value="MD">Master Distributor</option>
                        <option value="AD">Area Distributor</option>
                        <option value="AGENT">Agent</option>
        </select>         
    </div>
                                        <div class="form-group">
                                        <input type="number" name="mobile" class="form-control" maxlength="10" placeholder="Mobile No.">
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