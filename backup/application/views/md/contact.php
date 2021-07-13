<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<div class="content">
    <div class="container">

        <section class="cash-area">
            <div class="cash-payment">
                <div class="">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-12" st>
                            <h2 style="font-size: 22px;font-weight: 700;margin-left:10px;">Contact Details</h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php foreach($users as $user): ?>
            <section class="">
                <div class="cash-payment" style="padding:0px !important;margin-top: 10px;">
                    <div class=""  style="padding: 30px 50px 30px;">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-12">
                                <table width="100%">
                                    <tr>
                                        <td width="5%">
                                            <i style="color:green; font-size: 22px;" class="fa fa-building"></i>
                                        </td>
                                        <td>
                                            <h3 style="font-size:22px; color:green"><?=$user["firmname"]?></h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h3 style="font-size: 20px"><i class="fa fa-user"></i></h3>
                                        </td>
                                        <td>
                                            <h3 style="font-size: 20px"><?=$user["ownername"]?></h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h3 style="font-size: 16px"><i class="fa fa-phone"></i></h3>
                                        </td>
                                        <td>
                                            <h3 style="font-size: 16px"><?php
                                            if(strtolower($user['id'])==1){echo trimfilter( $user["alt_mobileno"]);}else{ echo trimfilter( $user["mobileno"]); }?></h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h3 style="font-size: 14px"><i class="fa fa-envelope"></i></h3>
                                        </td>
                                        <td>
                                            <h3 style="font-size: 14px"><?=$user["emailid"]?></h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h3 style="font-size: 12px"><i class="fa fa-map-marker"></i></h3>
                                        </td>
                                        <td>
                                            <h3 style="font-size: 12px"><?=$user["address"]?></h3>
                                        </td>
                                    </tr>                                    
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endforeach ?>
<br/><br/>
    </div>
</div>

<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>
<script  src="<?php echo base_url('assets/js/sweet.js');?>"></script>
<?php ;?>

<script type="text/javascript">
</script>
