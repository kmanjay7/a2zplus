<style type="text/css">
.certificate {
    padding: 21px 0px;
    max-width: 849px;
    border: 9px solid #58d234;
    display: block;
    margin-top: 50px;
    margin-bottom: 41px;
    margin: auto;
    background: #e6e6e5;
}
.certificate_sub {
    padding: 41px 25px;
    width: auto;
    border: 9px solid #4168a8;
    display: block;
    margin: 17px 39px;
    background: #fff;
}
.cer_logo img {
    display: block;
    margin: auto;
    width: 249px;
    margin-top: 47px;
    margin-bottom: 23px;
}
.certificate_sub h3 {
    font-size: 18px;
    text-transform: uppercase;
    font-weight: 600;
}

.certificate_sub h4 {
    font-size: 15px;
    font-weight: 600;
}
.certificate_sub h5 {
    text-align: center;
    font-size: 25px;
    margin-top: 14px;
    margin-bottom: 16px;
    color: #000;
    font-weight: 600;
}


.certificate_area {
    padding: 25px 0px 45px 0px;
}

.certificate_sub h6 {
    text-align: center;
    font-size: 20px;
    font-weight: 500;
    margin-bottom: 21px;
}

.certi_txt{
text-align: center;
}

.certi_txt h1 {
    font-size: 31px;
    text-transform: uppercase;
    margin-top: 15px;
    margin-bottom: 13px;
    font-weight: 600;
    color: #000;
}
.certi_txt h2 {
    color: #000;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 22px;
}
.certi_txt h3 {
color: #000;
font-weight: 500;
padding: 10px;
text-transform: capitalize;
font-size: 19px;
}
.certi_txt h4 {
font-size: 17px;
font-weight: 500;
margin-bottom: 25px;
}
.certi_txt h5 {
text-transform: uppercase;
font-size: 29px;
margin-top: 20px;
margin-bottom: 5px;
font-weight: 600;
color: #040404;
}
.certi_txt h6 {
text-transform: uppercase;
font-size: 19px;
margin-bottom: 25px;
font-weight: 600;
color: #040404;
}
.certi_footer{
text-align: center;
}
.certi_footer h2 {
font-size: 17px;
font-weight: 500;
margin-bottom: 21px;
}
.certi_footer h2 span  {
font-weight: 600;
}
.certi_footer h3 {
font-size: 17px;
text-transform: initial;
font-weight: 500;
}
.certi_footer h4 {
font-size: 17px;
font-weight: 500;
margin-top: 19px;
margin-bottom: 15px;
}
.certi_footer h4 a{
text-decoration: underline;
font-weight: 600;
}



.certi_footer h5 {
font-size: 18px;
}
</style>

<div class="content">
    <div class="container">
        <section class="cash-area">
            <div class="report-area">
                <div class="report-area-t">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-6 ">
                            <div class="top-10-txt">
                                <h2> Certificate  </h2>

                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-6 ">

                            <div class="top-10-txt down_btn">

                                <button class="export" id="print_btn">Print</button>

                            </div>

                        </div>


                        <div class="border-tabl">

                        </div>

<div class="col-lg-12 col-md-12 col-12 " id="print_section">
<div class="certificate_area">
<div class="certificate">
<div class="certificate_sub">
<h3>Authorization Certificate</h3>
<h4> Certificate Number : <?=$user['uniquecode']?> </h4>
<div class="cer_logo">
<img src="<?=ADMINURL?>/assets/images/logo.png" alt="">
</div>
<h5> Certificate of Association </h5>
<h6>This is to certify that</h6>

<div class="certi_txt">
<h1><?=$user['firmname']?></h1>
<h2> <?=$user['address']?></h2>
<h3>Is our Authorised Business Associate for the Development of Business Sales.</h3>
<h4>Authorized Representatives of the firm is</h4>
<h5><?=$user['ownername']?></h5>
<h6>(<?php if($user['user_type']=='BP'){ echo 'Business Partner';}else if($user['user_type']=='MD'){ echo 'Master Distributor';}else if($user['user_type']=='AD'){ echo 'Area Distributor';}else if($user['user_type']=='AGENT'){ echo 'Agent';}?>)</h6>
</div>

<div class="certi_footer">

<h2>Issue Date of the certification - <span> <?=date("d-M-Y h:i A", strtotime($user['register_date']))?>  </span></h2>
<h3>To Verify Certificate Status and Detail</h3>
<h4>Kindly Log in <a href="http://www.mydigicash.in"> www.mydigicash.in</a></h4>
<h5>Email: info@mydigicash.in</h5>
</div>

</div>

                                            
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        <br> <br>

    </div>
</div>


</div></div></div></div>
<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>
<script  src="<?php echo base_url('assets/js/sweet.js');?>"></script>

<script type="text/javascript">
$("#print_btn").click(function(){
    window.print();
});
</script>