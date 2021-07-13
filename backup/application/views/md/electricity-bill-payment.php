<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Electricity Bill Payment  </title>
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
                            <h2 class="color-blue"> Electricity Bill Payment </h2>
                        </div>

                        <div class="filter-area">

                            <div class="row m-15">
                                <div class="col-md-7 col-lg-7 col-12">
                                    <label for=""> Operator </label>
                                    <input class="form-control " placeholder="Uttar Pradesh Power Corporation Limited- URBAN" type="text" name="customer_id">
                                </div>

                       
                                <div class="col-md-3 col-lg-3 col-12">
                                    <label for=""> Consumer Number </label>
                                    <input class="form-control  " placeholder="96885688" type="text" name="amount">
                                </div>

                                <div class="col-md-2 col-lg-2 col-12 m-29">

                                    <input class="form-control apply-filter w-100 " value="GO" type="submit" name="filter">
                                </div>

                            </div>




                        </div>

                    </div>

                </section>


                <section>
                    <div class="electricity_bill">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>Bill Found</h2>
                            </div>
                            <div class="col-md-6">
                                <div class="bill-logo">
                                    <img src="./assets/images/bharat-logo.png" alt="">
                                </div>
                            </div>
                        
                   
                        <div class="col-md-12">
                            <div class="bill_txt">
                                <h3> Consumer Number:6038704000 </h3>
                                <h3> Uttar Pradesh Power Corporation Limited- URBAN </h3>
                                <div class="bill_table">
                                    <table>
                                        <tr>
                                            <td>
                                                Consumer Name
                                            </td>
                                            <td>: ANOOOP KUMAR GUPTA</td>
                                    
                                        </tr>
                                        <tr>
                                            <td>
                                                Bill Number
                                            </td>
                                            <td> : 602391465</td>
                                    
                                        </tr>
                                        <tr>
                                            <td>
                                                Bill Period
                                            </td>
                                            <td> : Monthly</td>
                                    
                                        </tr>
                                        <tr>
                                            <td>
                                                Due Date
                                            </td>
                                            <td>: 15-Oct-2019</td>
                                    
                                        </tr>
                                        <tr>
                                            <td>
                                                Bill Amount
                                            </td>
                                            <td class="price-bill"> : 1440.009 </td>
                                    
                                        </tr>
                                    
                                    </table>
                                </div>
                            </div>
                        
                            <div>
                                <button class="ap_bg pay-bill">Pay Bill</button>
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

                                    <h2 class=" report-heading"> Recent Transactions </h2>

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

</body>

</html>