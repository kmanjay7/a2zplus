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
    <link href="./assets/css/owl-carousel.css" rel="stylesheet" type="text/css"> <link href="./assets/css/style-front.css" rel="stylesheet" type="text/css"> <link href="./assets/css/animate.css" rel="stylesheet" type="text/css"> </head>

<body>
    <!---Header Page-->
    <?php $this->load->view('includes/header-front');?> 

    <section class="about-us">
        <div class="container">
            <div class="row">
            <div class="col-md-12 col-lg-12 col-12 wow" style="animation-name:zoomIn; margin-top: 75px;"> 
            
            <div class="common-heading">
           
            </div>

                <div class="about-txt about-p">
                     <h3 align="left"> <?=$list['heading'];?> </h3> 
                    <p align="justify"><?=$list['content'];?> </p>
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