 <div class="content">
        <div class="container">

            <section class="cash-area">

                <div class="cash-payment">
                    <div class="cash-heading">
                        <h2><?=$title;?> </h2>
                    </div>
                    
                    <div class="aeps-area pan_card_area aeps-agent">
                        <div class="row">
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="aeps-inner" style="background-image:url('<?=ADMINURL;?>assets/images/apes-bg.png')">
                                    <h3> Successful Transactions Amount </h3> 
                                    <h4> ₹ <?=$success_amt;?> </h4>

                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="aeps-inner" style="background-image:url('<?=ADMINURL;?>assets/images/apes-bg.png')">
                                    <h3> Total Commission  </h3> 
                                    <h4> ₹ <?=$total_commission;?> </h4>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="aeps-inner" style="background-image:url('<?=ADMINURL;?>assets/images/apes-bg.png')">
                                    <h3> Total Surcharge </h3> 
                                    <h4> ₹ <?=$total_surcharge;?> </h4>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="aeps-inner" style="background-image:url('<?=ADMINURL;?>assets/images/apes-bg.png')">
                                    <h3> Total TDS </h3> 
                                    <h4> ₹  <?=$total_tds;?> </h4>
                                </div>
                            </div> 
                        </div> 
                    </div>




       
                </div> 

            </section>







            <section class="">
                <div class="report-area">
                    <div class="report-area-t">
                        <div class="row">


                            <div class="col-lg-6 col-md-6 col-6 ">
                                <div class="top-10-txt">
                                    <h2> List </h2>
                                  
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-6 ">

                                <div class="top-10-txt down_btn">

                                    <button class="export">Export</button>

                                </div>

                            </div>
                           

                            <div class="border-tabl">

                            </div>




                            <div class="col-lg-12 col-md-12 col-12 "> 
                               
                                    <div class="report-table"> 
                                            <table class="table">
                <thead>                                                             
                    <tr style="background: transparent">
                        <th>  Sr.No/ Order ID</th>
                        <th>  Subject   </th>
                        <th>  Old  Balance/ New Balance </th>
                        <th>  Amount Cr./ Dr. Type </th>
                        <th>  Date & Time  </th>
                        <th>  Message   </th> 
                    </tr>
                </thead>


<tbody>
    <?php if(!empty($walletlist)){  $i =1; 
    foreach ($walletlist as $key => $value) { ?>
<tr>
<td> 
<p><?=$i;?>/</p>
<p><?=$value['referenceid']?$value['referenceid']:'NA';?></p>
</td>

<td> <p> <?= ucwords( str_replace('_', ' ' , $value['subject']) );?> </p></td>
<td>
<p> <?=$value['beforeamount'];?></p>
<p> /<?=$value['finalamount'];?></p>
</td>

<td>
<p> <?=$value['amount'];?></p>
<p> <?=ucwords($value['credit_debit']);?> </p>
</td>


<td>
<p> <?=date('d/m/Y',strtotime($value['add_date']));?> </p>
<p> <?=date('h:i:s A',strtotime($value['add_date']));?></p>   
</td>

<td class="messg succes_t">
<a href="#" data-toggle="tooltip" data-placement="right"
title="<?=$value['remark'];?> ">  Show Message   </a>
</td>
</tr>
<?php $i++; } }?>

</tbody>


</table>

                                    </div>
                                    <div class="pagination_area text-center">

                              <ul class="pagination">
                                <?= $pagination;?>
                              </ul>

                                        </div>
                                  
                                    
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </section>


         <br> <br> 
        </div>
    </div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?> 
</body>
</html>