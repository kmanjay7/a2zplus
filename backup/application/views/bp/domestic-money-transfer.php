<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Domestic Money Transfer </title>
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
                        <div class="common-heading">
                            <h2 class="color-blue"> Domestic Money Transfer  </h2>
                        </div>

                        <div class="common-sub-are">
                         
                            <div class="row ">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="sender_area">
                                            <h4 class="sender_h"> Sender Mobile Number </h4>
                                
                                        <input class="form-control " placeholder="8115171716" type="text" name="sender_mobile" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" onkeydown="if(this.value.length==10 &amp;&amp; event.keyCode!=8) return false;">
                                    </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-6 m-29">
                                
                                    <input data-toggle="modal"
                                    data-target="#sender_registration" class="form-control apply-filter  ap_bg db-auto" value="Continue" type="submit" name="filter">
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
                                    
                                                <h2 class="color-blue report-heading"> Recent Transactions  </h2>
                                        
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
                                                    <th > API Name/ API TID/ Amt.</th>
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
                                            <li class="page-item disabled"><a class="page-link" href="#"><i class="material-icons">
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




        <div class="modal fade bank_model" id="sender_registration" tabindex="-1" role="dialog" aria-labelledby="add_bank_detail"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content close_btn">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    <div class="model_h mt-3">
        
                        <h2 class="sender_h text-center color-blue"> Sender Registration </h2>
                    </div>

                    <div class="modal-body form_model sender_model">
        
                        <div class="row">
        
        
                            <div class="col-md-12 col-lg-12 col-12">
                                <label>Sender Name</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Mehtab Alam">
                                </div>
        
                            </div>
        
        
                        </div>
        
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-12">
                                <label>Pincode</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="261135">
                                </div>
        
                            </div>
                            <div class="col-md-6 col-lg-6 col-12">
        
                                <div class="form-group m-29">
                                    <input data-toggle="modal"
                                    data-target="#otp" class="form-control w-100 apply-filter  ap_bg " value="Register" type="submit"
                                        name="filter">
                                </div>
        
        
                            </div>
        
        
        
                        </div>
        
                    </div>
        
                </div>
            </div>
        </div>



        <div class="modal fade bank_model" id="otp" tabindex="-1" role="dialog" aria-labelledby="add_bank_detail"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content close_btn">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                <div class="model_h mt-3">
    
                    <h2 class="sender_h text-center color-blue"> Verify Sender </h2>
                </div>

                <div class="modal-body form_model sender_model otp_pad">
    
                    <div class="row">
    
    
                        <div class="col-md-12 col-lg-12 col-12">
                            <label class="text-center db-auto"> OTP </label>

                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="123456">
                            </div>
    
                        </div>
    
    
                    </div>
    
                    <div class="row m-29">
                        <div class="col-md-6 col-lg-6 col-12">
                         
                                <div class="form-group">
                                        <input class="form-control w-100 apply-filter  " value="Resend OTP" type="submit"
                                            name="filter">
                                    </div>
    
                        </div>
                        <div class="col-md-6 col-lg-6 col-12">
    
                            <div class="form-group ">
                                <input id="vf" class="form-control w-100 apply-filter  ap_bg " value="Verify" type="submit"
                                    name="filter">
                            </div>
    
    
                        </div>
    
    
    
                    </div>
    
                </div>
    
            </div>
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


</body>

</html>