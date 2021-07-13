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
            <h3> Contact Us </h3>
            <p> DigiCash India is one of the fastest growing names... </p>
        </div>
       </div>
    </section>

    <section class="about-us">
        <div class="container">
            <div class="row">
                   <div class="col-md-6 col-lg-6 col-12 wow" style="animation-name:zoomIn;">
                    <div class="contact-map">
                        <iframe width="100%" src="https://maps.google.com/maps?q=Plot%2019%2C%20Katra%2C%20Lahapur%20Sitapur%2C%20Uttar%20Pradesh%20-%20261135%20India&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
                    </div>
                   </div>
                <div class="col-md-6 col-lg-6 col-12 wow " style="animation-name:slideInRight;">
                      <div class="contact-txt">
             
                        <div class="col-12">
                            <p class="write">Contact Us</p>
                        </div>
                        <form>
                           
                                    <div class="col-12 col-lg-12">
                                        <div class="form-group">
                                            <input type="text" name="name" class="form-control" placeholder="Your Name"
                                                value="" required="">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-12">
                                        <div class="form-group">
                                            <input type="text" name="contact-number" class="form-control" placeholder="Contact No."
                                                maxlength="10" value="" required="">
                                        </div>
                                    </div>
                         
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="text" name="" class="form-control" placeholder="Email Address" value=""
                                        required="">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea name="" class="form-control" placeholder="Your messages"
                                        style="width: 100%; height: 160px;" required=""></textarea>
                                </div>
                            </div>
                            <div class="col-12 pt-3">
                                <button type="submit" class="send-now">Send Now</button>
                            </div>
                        </form>
                    </div>
              

                    </div>
                </div>

        </div>
    </section>


        <section class="m-41">
             <div class="container">
             <div class="testimonial ">
                <div class="conct-txt">
                    <div class="row">

                    <div class="col-md-4 col-lg-4 col-12">
                        <div class="row">
                        <div class="col-2 social">
                            <div class="call-us">
                                <i class="fa fa-phone"></i>
                            </div>
                        </div>
                        <div class="col-10">
                            <h4> Call Us </h4>
                            <p class="call">9121-337-337,<br/> 9721-338-338</p>
                        </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-12">
                        <div class="row">
                            <div class="col-2 social">
                                <div class="call-us">
                                    <i class="fa fa-envelope "></i>
                                </div>
                            </div>
                            <div class="col-10">
                                <h4> Mail </h4>
                                <p class="call">support@mydigicash.in,<br/> info@mydigicash.in</p>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4 col-lg-4 col-12">
                        <div class="row">
                        <div class="col-2 social">
                            <div class="call-us">
                                <i class="fa fa-map-marker"></i>
                            </div>
                        </div>
                        <div class="col-10">
                            <h4>Address</h4>
                            <p class="call">19, Katra Road, Katra, Laharpur, Sitapur, Uttar Pradesh, India. - 261135</p>
                        </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>

             </div>
        </section>



        <!---footer---->
        <?php $this->load->view('includes/footer-front'); ?>

            <script src="./assets/js/jQuery.js"></script>
            <script src="./assets/js/bt_poper.js"></script>
            <script src="./assets/js/bootstrap.js"></script>
           
            <script src="./assets/js/owl-carousel.js"></script>
            <script src="./assets/js/wow.js"></script>
            <!-- slick Carousel CDN -->
            <script src="./assets/js/custom-front.js"></script>
     
</body>

</html>