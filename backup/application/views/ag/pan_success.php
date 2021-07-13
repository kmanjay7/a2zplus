<div class="content">
<div class="container" style="min-height: 600px">


<section class="cash-area"> 
<div class="cash-payment"> 
<div class="common-heading">
<h2 class="color-blue"> <?= ucwords($title);?> </h2>
</div>

  
<div class="filter-area">  
    <div class="row"> 
        <div class="col-md-12 col-lg-12 col-12">
          
            <center><img src="<?=base_url('assets/images/ok.png');?>" width="150px">
                <h5>Application Submitted Successfully!</h5>
                <p><b>Order ID: <?php echo $orderid;?></b></p>
                <a href="<?=base_url('ag/individual_pan');?>"><button class="btn btn-primary ml-auto">Go Back!</button></a><br/><br/>
            </center>

        </div> 
    </div>
</div>
</div>
</section><br/><br/>
</div></div>


<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>