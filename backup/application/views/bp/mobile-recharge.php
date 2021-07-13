<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Mobile Recharge  </title>
    <link rel="icon" href="assets/images/favicon-60x60.png" type="image/x-icon" />
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="./assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="./assets/css/main.css" rel="stylesheet" type="text/css">
    <link href="./assets/css/owl-carousel.css" rel="stylesheet" type="text/css">
    <link href="./assets/css/animate.css" rel="stylesheet" type="text/css">
    <link href="./assets/css/chart.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="./assets/css/bootstrap-datepicker.css" rel="stylesheet">
    <link href="./assets/css/sweet.css" rel="stylesheet">
</head>

<body>
    <!---Header Page-->
    <?php include_once('header-agent.php'); ?>

        <div class="content">
            <div class="container">

                <section class="cash-area">

                    <div class="cash-payment">

                        <div class="common-heading">
                            <h2 class="color-blue"> Mobile Recharge </h2>
                        </div>

                        <div class="filter-area">

                            <div class="row m-15">
                                    <div class="col-md-3 col-lg-3 col-12">
                                            <label for=""> Mobile Number </label>
                                            <input type="text" class="form-control" placeholder="Enter Mobile Number" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" onkeydown="if(this.value.length==10 &amp;&amp; event.keyCode!=8) return false;">
                                        </div>

                       
                                <div class="col-md-3 col-lg-3 col-12">
                                    <label for=""> Operator </label>
                          
                                    <select  class="form-control  " name="" id="">
                                        <option value=""> Select Operator </option>

                                    </select>

                                </div>
                                <div class="col-md-3 col-lg-3 col-12">
                                        <label for=""> Circle </label>
                                        <select  class="form-control  " name="" id="">
                                                <option value=""> Select Circle </option>
        
                                            </select>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-12">
                                            <label for=""> Amount </label>
                                            <input class="form-control  " placeholder="Enter Amount" type="text" name="amount">
                                        </div>

                            

                            </div>

                        </div>



                        <div class="filter-area">

                                <div class="row ">
                                        <div class="col-md-3 col-lg-3 col-12">
                                        <button class="form-control apply-filter w-100"> Check Plans  </button>
                                    </div>
    
                           
                                    <div class="col-md-3 col-lg-3 col-12">
                                            <button class="form-control apply-filter w-100"> Special Offer  </button>
                                    </div>
    
                                    <div class="col-md-3 col-lg-3 col-12 offset-md-3 ">
    
                                        <input class="form-control apply-filter w-100 ap_bg" id="rc" value="Recharge" type="submit" name="filter">
                                    </div>
    
                                </div>
    
                            </div>

                    </div>

                </section>


           

                <div class="report-area">
                        <div class="report-area-t">
                            <div class="row">
    
                                <div class="col-lg-12 col-md-12 col-12 ">
                                    <div class="top-10-txt">
    
                                        <h2 class=" report-heading"> Check Plans/ Special Offer </h2>
    
                                    </div>
                                </div>
    
                                <div class="border-tabl">
    
                                </div>
    
                                <div class="col-lg-12 col-md-12 col-12 ">
    
                                    <div class="plans_para">
                                            <p> This Screen Will Only Appear When a User Clicks Plans or Special Offers of the customer info button will show all running plans or special offers for a particular Customer number or customer info as per response from the API. After entering Customer id the Operator name will be also auto fetch from API. "Heavy Refresh" is a Command to refresh the DTH account after Recharge, this command will generate a request number with API Response. You will have to show the response got from API on this Screen.
                                                </p>
                                    </div>
    
                                </div>
    
                            </div>
    
                        </div>
    
                    </div>

       

                <div class="report-area">
                    <div class="report-area-t">
                        <div class="row">

                            <div class="col-lg-12 col-md-12 col-12 ">
                                <div class="top-10-txt">

                                    <h2 class=" report-heading"> Recent Recharge </h2>

                                </div>
                            </div>

                            <div class="border-tabl">

                            </div>

                            <div class="col-lg-12 col-md-12 col-12 ">

                                <div class="plans_para">
                                    <p>
                                        This Screen Will Show recent DTH Recharge Transaction from the "Recharge Report" Which is already designed.
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <br>
                <br>

            </div>
        </div>

        <?php include_once('footer.php'); ?>

            <script src="./assets/js/jQuery.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
            <script src="./assets/js/bootstrap.min.js"></script>
            <script src="./assets/js/owl-carousel.js"></script>
            <script src="./assets/js/wow.js"></script>
            <script src="./assets/js/chart.js"></script>
            <script src="./assets/js/moment.js"></script>
            <!-- slick Carousel CDN -->
            <script src="./assets/js/custom.js"></script>
            <script src="./assets/js/sweet.js" type="text/javascript"></script>
            <script src="./assets/js/time-picker.js" type="text/javascript"></script>

            <script>

                    document.getElementById('rc').onclick = function () {
                        swal(" Recharged Successfully!", "", "success");
                    };
    
                </script>

</body>

</html>