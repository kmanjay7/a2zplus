<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Non KYC User </title>
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
                <section class="common-padding">
                        <div class="common-area">
                         
        
                            <div class="common-sub-are">
        
                                <div class="row ">
                                        <div class="col-12 col-md-4 col-lg-4 ">
                                        <div class="kyc_txt">
        
                                        <h4> Hi, Mahtab Alam </h4>
                                        <h3 class="mobile_txt">8115171716</h3>
                                        <button class="ap_bg log-out">Log Out</button>
                                    </div>
        
                                      
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-4 ">
        
                                            <div class="kyc_txt kyc_center">
        
                                                    <h4> Non KYC User </h4>
                                                    <h6> To Increase Your Wallet Limit </h6>
                                                    <button class="verify-btn"> Verify KYC </button>
                                                </div>
        
                              
                                    </div>
        
                                    <div class="col-12 col-md-4 col-lg-4 ">
        
                                            <div class="kyc_txt kyc_right">
        
                                                    <h4> Available Limit </h4>
                                                    <h3> 25000 </h3>
                                                    <h4> Total Limit </h4>
                                                    <h3> 25000 </h3>
                                                </div>
        
                                    </div>
        
        
        
                                </div>
                   
        
                            </div>
                        </div>
                    </section>




            <section class="common-padding">
                <div class="common-area">
                    <div class="common-heading">
                        <h2 class="color-blue"> Add Beneficiary </h2>
                    </div>

                    <div class="common-sub-are">

                        <div class="row ">
                            <div class="col-12 col-md-6 col-lg-6">

                                <h4 class="sender_h"> Bank Name </h4>

                                <input class="form-control " placeholder="Union Bank of India" type="text"
                                    name="bank_name">
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 ">

                                <h4 class="sender_h"> IFSC Code </h4>

                                <input class="form-control " placeholder="Auto Generated" type="text" name="ifsc_code">
                            </div>

                            <div class="col-12 col-md-3 col-lg-3 ">

                                <h4 class="sender_h"> Account Number </h4>

                                <input class="form-control " placeholder="7686152426465646112" type="text"
                                    name="account_name">
                            </div>



                        </div>
                        <div class="row m-29">
                            <div class="col-12 col-md-6 col-lg-6">

                                <h4 class="sender_h"> Beneficiary Name </h4>
                                <input class="form-control " placeholder="Digicash India" type="text" name="benif_name">
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 ">

                                <h4 class="sender_h"> Varification Charge Rs.4 </h4>
                                <input id="vf" class="form-control apply-filter  w-100 " value="Verify Name"
                                    type="submit" name="verifi">
                            </div>

                            <div class="col-12 col-md-3 col-lg-3 m-26">

                                <input id="bn" class="form-control apply-filter  ap_bg w-100 " value="Add Beneficiary"
                                    type="submit" name="add_benif">
                            </div>

                        </div>

                    </div>
                </div>
            </section>


            <section class="">
                <div class="common-area">
                    <div class="common-heading">
                        <h2 class="color-blue"> Transfer Fund </h2>
                    </div>

                    <div class="common-sub-are">

                        <div class="row ">
                            <div class="col-12 col-md-6 col-lg-6">

                                <h4 class="sender_h"> Bank Name </h4>

                                <input class="form-control " readonly placeholder="Union Bank of India" type="text"
                                    name="bank_name">
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 ">

                                <h4 class="sender_h"> IFSC Code </h4>

                                <input class="form-control " readonly placeholder="Auto Generated" type="text" name="ifsc_code">
                            </div>

                            <div class="col-12 col-md-3 col-lg-3 ">

                                <h4 class="sender_h"> Account Number </h4>

                                <input class="form-control " readonly placeholder="7686152426465646112" type="text"
                                    name="account_name">
                            </div>



                        </div>
                        <div class="row m-29">
                            <div class="col-12 col-md-6 col-lg-6">

                                <h4 class="sender_h"> Beneficiary Name </h4>
                                <input class="form-control" readonly placeholder="Digicash India" type="text" name="benif_name">
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 m-26">

                                
                                <input   class="form-control apply-filter  w-100 " value="Verified"
                                    type="submit" name="verifi">
                            </div>

                            <div class="col-12 col-md-3 col-lg-3 m-26">

                                <input disabled id="bn" readonly class="form-control apply-filter  ap_bg w-100 " value="Add Beneficiary"
                                    type="submit" name="add_benif">
                            </div>

                        </div>
                        <div class="row m-29">
                            <div class="col-12 col-md-3 col-lg-3">

                                <h4 class="sender_h"> Mode </h4>
                                <select name=""  id="" class="form-control">
                                        <option value=""> Select Mode </option>
                                  <option value=""> IMPS</option>
                                  <option value=""> NEFT </option>

                                </select>
                               


                            </div>
                            <div class="col-12 col-md-3 col-lg-3">

                                <h4 class="sender_h"> Amount </h4>
                                <input class="form-control " placeholder="5000" type="text" name="benif_name">
                            </div>
                            <div class="col-12 col-md-3 col-lg-3">

                                <h4 class="sender_h"> Debit Amount </h4>
                                <input class="form-control " placeholder="5050" readonly type="text" name="benif_name">
                            </div>

                            <div class="col-12 col-md-3 col-lg-3 m-26">

                                <input id="bn" class="form-control apply-filter  ap_bg w-100 " value="Transfer Fund"
                                    type="submit" name="add_benif">
                            </div>

                        </div>

                    </div>
                </div>
            </section>




            <section class="report-section">
                <div class="report-area">
                    <div class="report-area-t">
                        <div class="row">

                            <div class="col-lg-6 col-md-6 col-6 ">

                                <h2 class="color-blue report-heading"> Beneficiary List </h2>

                            </div>
                            <div class="col-lg-6 col-md-6 col-6 ">

                                <div class="top-10-txt down_btn">

                                    <button class="export">Export</button>

                                </div>

                            </div>

                            <div class="border-tabl">

                            </div>

                            <div class="col-lg-12 col-md-12 col-12 ">

                                <div class="report-table pan_table">

                                    <table class="table non_kyc_table">
                                        <thead>

                                            <tr style="background: transparent">
                                                <th> Details
                                                </th>
                                            
                                                <th class="text-center"> Action </th>
                                            
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p class="name_d"> Digicash India </p>
                                                    <p> A/C No. : 67327788377 </p>
                                                    <p>Bank Name : Union Bank Of India</p>

                                                </td>
                                            
                                                <td>

                                                    <button  class="update-btn w-101"> Verified </button>
                                                    <button  class="update-btn w-101 ap_bg"> Transfer </button>
                                                    <button  class="update-btn w-101 cp-bg"> Delete </button>
                                                    <h5> Added on 12-Oct-2019, 02:23:56 </h5>

                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="name_d"> Digicash India </p>
                                                    <p> A/C No. : 67327788377 </p>
                                                    <p>Bank Name : Union Bank Of India</p>

                                                </td>
                                            
                                                <td>

                                                    <button  class="update-btn w-101 nv-bg"> Not Verified </button>
                                                    <button  class="update-btn w-101 ap_bg"> Transfer </button>
                                                    <button  class="update-btn w-101 cp-bg"> Delete </button>
                                                    <h5> Added on 12-Oct-2019, 02:23:56 </h5>

                                                </td>

                                            </tr>

                                       

                                        </tbody>
                                    </table>

                                </div>
                                <div class="pagination_area text-center">

                                    <ul class="pagination">
                                        <li class="page-item disabled"><a class="page-link" href="#"><i
                                                    class="material-icons">
                                                    keyboard_arrow_left
                                                </i></a></li>
                                        <li class="page-item active"><a class="page-link " href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                                        <li class="page-item"><a class="page-link" href="#">6</a></li>
                                        <li class="page-item"><a class="page-link" href="#"><i class="material-icons">
                                                    keyboard_arrow_right
                                                </i></a></li>
                                    </ul>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </section>




            <section class="report-section">
                <div class="report-area">
                    <div class="report-area-t">
                        <div class="row">

                            <div class="col-lg-6 col-md-6 col-6 ">

                                <h2 class="color-blue report-heading"> Recent Transactions </h2>

                            </div>
                            <div class="col-lg-6 col-md-6 col-6 ">

                                <div class="top-10-txt down_btn">

                                    <button class="export">Export</button>

                                </div>

                            </div>

                            <div class="border-tabl">

                            </div>

                            <div class="col-lg-12 col-md-12 col-12 ">

                                <div class="report-table pan_table">

                                    <table class="table">
                                        <thead>

                                            <tr style="background: transparent">
                                                <th class="w-101"> Sr.No./ Order ID
                                                </th>
                                                <th> Sender Name/ Sender Number </th>
                                                <th class="w-200"> Bank Name/ Beneficiary Name/ Account Number </th>
                                                <th> Opt. Name/ Mode </th>
                                                <th> API Name/ API TID/ Amt.</th>
                                                <th> Sur Charge/ Commision/ TDS </th>
                                                <th> Status/ Date & Time </th>
                                                <th class="text-center"> Action </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p> 1/776</p>

                                                </td>
                                                <td>
                                                    <p class="name"> Mahtab Alam </p>
                                                    <p>/9839279341</p>
                                                </td>
                                                <td>
                                                    <p> Union Bank of India </p>
                                                    <p>/Digicash India</p>
                                                    <P>/5938011100504545</P>

                                                </td>
                                                <td>
                                                    <p> 100-5000 </p>
                                                    <p>/IMPS</p>


                                                </td>
                                                <td>
                                                    <p>DMT 1st</p>
                                                    <p>1234545</p>
                                                    <p>/500</p>

                                                </td>
                                                <td>
                                                    <p>50</p>

                                                    <p> 25 </p>
                                                    <p>1.25</p>

                                                </td>

                                                <td class="messg succes_t">
                                                    <p class="sucess_icon"> <span></span> Success, </p>
                                                    <p> 08-Aug-2019 </p>
                                                    <p>06:40:33 PM</p>


                                                </td>
                                                <td>

                                                    <button id="ap1" class="update-btn w-101"> Print Receipt </button>
                                                    <button id="ap2" class="update-btn w-101 cp-bg"> Complaint </button>

                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <p> 2/776</p>

                                                </td>
                                                <td>
                                                    <p class="name"> Mahtab Alam </p>
                                                    <p>/9839279341</p>
                                                </td>
                                                <td>
                                                    <p> Union Bank of India </p>
                                                    <p>/Digicash India</p>
                                                    <P>/5938011100504545</P>

                                                </td>
                                                <td>
                                                    <p> 100-5000 </p>
                                                    <p>/IMPS</p>


                                                </td>
                                                <td>
                                                    <p>DMT 1st</p>
                                                    <p>1234545</p>
                                                    <p>/500</p>

                                                </td>
                                                <td>
                                                    <p>50</p>

                                                    <p> 25 </p>
                                                    <p>1.25</p>

                                                </td>

                                                <td class="messg succes_t">
                                                    <p class="sucess_icon"> <span></span> Success, </p>
                                                    <p> 08-Aug-2019 </p>
                                                    <p>06:40:33 PM</p>


                                                </td>
                                                <td>

                                                    <button id="ap1" class="update-btn w-101"> Print Receipt </button>
                                                    <button id="ap2" class="update-btn w-101 cp-bg"> Complaint </button>

                                                </td>

                                            </tr>

                                            <tr>
                                                <td>
                                                    <p> 3/776</p>

                                                </td>
                                                <td>
                                                    <p class="name"> Mahtab Alam </p>
                                                    <p>/9839279341</p>
                                                </td>
                                                <td>
                                                    <p> Union Bank of India </p>
                                                    <p>/Digicash India</p>
                                                    <P>/5938011100504545</P>

                                                </td>
                                                <td>
                                                    <p> 100-5000 </p>
                                                    <p>/IMPS</p>


                                                </td>
                                                <td>
                                                    <p>DMT 1st</p>
                                                    <p>1234545</p>
                                                    <p>/500</p>

                                                </td>
                                                <td>
                                                    <p>50</p>

                                                    <p> 25 </p>
                                                    <p>1.25</p>

                                                </td>

                                                <td class="messg succes_t">
                                                    <p class="sucess_icon"> <span></span> Success, </p>
                                                    <p> 08-Aug-2019 </p>
                                                    <p>06:40:33 PM</p>


                                                </td>
                                                <td>

                                                    <button id="ap1" class="update-btn w-101"> Print Receipt </button>
                                                    <button id="ap2" class="update-btn w-101 cp-bg"> Complaint </button>

                                                </td>

                                            </tr>

                                            <tr>
                                                <td>
                                                    <p> 4/776</p>

                                                </td>
                                                <td>
                                                    <p class="name"> Mahtab Alam </p>
                                                    <p>/9839279341</p>
                                                </td>
                                                <td>
                                                    <p> Union Bank of India </p>
                                                    <p>/Digicash India</p>
                                                    <P>/5938011100504545</P>

                                                </td>
                                                <td>
                                                    <p> 100-5000 </p>
                                                    <p>/IMPS</p>


                                                </td>
                                                <td>
                                                    <p>DMT 1st</p>
                                                    <p>1234545</p>
                                                    <p>/500</p>

                                                </td>
                                                <td>
                                                    <p>50</p>

                                                    <p> 25 </p>
                                                    <p>1.25</p>

                                                </td>

                                                <td class="messg succes_t">
                                                    <p class="sucess_icon"> <span></span> Success, </p>
                                                    <p> 08-Aug-2019 </p>
                                                    <p>06:40:33 PM</p>


                                                </td>
                                                <td>

                                                    <button id="ap1" class="update-btn w-101"> Print Receipt </button>
                                                    <button id="ap2" class="update-btn w-101 cp-bg"> Complaint </button>

                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <p> 5/776</p>

                                                </td>
                                                <td>
                                                    <p class="name"> Mahtab Alam </p>
                                                    <p>/9839279341</p>
                                                </td>
                                                <td>
                                                    <p> Union Bank of India </p>
                                                    <p>/Digicash India</p>
                                                    <P>/5938011100504545</P>

                                                </td>
                                                <td>
                                                    <p> 100-5000 </p>
                                                    <p>/IMPS</p>


                                                </td>
                                                <td>
                                                    <p>DMT 1st</p>
                                                    <p>1234545</p>
                                                    <p>/500</p>

                                                </td>
                                                <td>
                                                    <p>50</p>

                                                    <p> 25 </p>
                                                    <p>1.25</p>

                                                </td>

                                                <td class="messg succes_t">
                                                    <p class="sucess_icon"> <span></span> Success, </p>
                                                    <p> 08-Aug-2019 </p>
                                                    <p>06:40:33 PM</p>


                                                </td>
                                                <td>

                                                    <button id="ap1" class="update-btn w-101"> Print Receipt </button>
                                                    <button id="ap2" class="update-btn w-101 cp-bg"> Complaint </button>

                                                </td>

                                            </tr>

                                        </tbody>
                                    </table>

                                </div>
                                <div class="pagination_area text-center">

                                    <ul class="pagination">
                                        <li class="page-item disabled"><a class="page-link" href="#"><i
                                                    class="material-icons">
                                                    keyboard_arrow_left
                                                </i></a></li>
                                        <li class="page-item active"><a class="page-link " href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                                        <li class="page-item"><a class="page-link" href="#">6</a></li>
                                        <li class="page-item"><a class="page-link" href="#"><i class="material-icons">
                                                    keyboard_arrow_right
                                                </i></a></li>
                                    </ul>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

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

        document.getElementById('vf').onclick = function () {
            swal(" Verified  Successfully!", "", "success");
        };

    </script>

    <script>
        document.getElementById('bn').onclick = function () {
            swal("Beneficiary Added  Successfully !", "", "success")

        }
    </script>




</body>

</html>