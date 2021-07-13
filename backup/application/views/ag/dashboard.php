<div class="content">
      <div class="container">
         <section class="common-pad">
            <div class="container">

               <div class="notification-panel">
                         <div class="row">
                            <div class="col-md-6 col-lg-6 col-12">
                               <div class="notification_news">
                                  <div class="noti_head">
                                   <h3>  Notification Panel </h3>
                                  </div>
                                   <div class="noti-news">

                                       <marquee width="100%" behavior="scroll" direction="up" scrollamount="2" onmouseover="this.stop();" onmouseout="this.start();">
                                        <?php foreach ($notifications as $noti): ?>
                                          <p><?=$noti["content"]?></p>
                                        <?php endforeach ?>
                                      </marquee>
                                       </div>
                               </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-12">
                             <div class="slider-area">
                            <div id="news-slide" class="carousel slide" data-ride="carousel" data-interval="3000">

                                 <!-- Indicators -->
                                 <ul class="carousel-indicators">
                                  <?php $i=0; foreach($banner as $ban): ?>
                                   <li data-target="#news-slide" data-slide-to="<?=$i?>" class="<?=$i==0?'active':''?>"></li>
                                  <?php $i++; endforeach ?>
                                 </ul>
                               
                                 <!-- The slideshow -->
                                 <div class="carousel-inner">
                                  <?php $i=0; foreach($banner as $ban): ?>
                                   <div class="carousel-item<?=$i==0?' active':''?>">
                                       <img src="<?=ADMINAPIURL?>uploads/<?=$ban['image']?>" alt="">
                                   </div>
                                  <?php $i=1; endforeach ?>
                                 </div>
                               
                                 <!-- Left and right controls -->
                                 <a class="carousel-control-prev" href="#news-slide" data-slide="prev">
                                   <span class="carousel-control-prev-icon"></span>
                                 </a>
                                 <a class="carousel-control-next" href="#news-slide" data-slide="next">
                                   <span class="carousel-control-next-icon"></span>
                                 </a>
                               
                               </div>
                              </div>

                            </div>

                          

                              


                         </div>
               </div>

<style type="text/css">.font-12{ font-size: 12px; }</style>
               <div class="recharge_main">
                  <div class="row">
                       <div class="col-md-3 col-lg-3 col-12" onclick="goto('aeps')" >
                           <div class="rgr-box cursor">
                              <div class="rgr-img">
                               <img src="<?=ADMINURL;?>assets/images/icons/aeps.png" alt="">
                              </div>

                              <div class="rgr-head">
                                 <h3> Aeps</h3> 
                              </div>

                           </div>
                       </div>
                       <!-- <div class="col-md-3 col-lg-3 col-12" onclick="goto('electricity')">
                           <div class="rgr-box cursor">
                              <div class="rgr-img">
                               <img src="<?=ADMINURL;?>assets/images/icons/bbps.png" alt="">
                              </div>

                              <div class="rgr-head">
                                 <h3> Electricity Bill </h3> 
                              </div>

                           </div>
                       </div> -->
                       <div class="col-md-3 col-lg-3 col-12" onclick="goto('electricity_bill')">
                           <div class="rgr-box cursor">
                              <div class="rgr-img">
                               <img src="<?=ADMINURL;?>assets/images/icons/bbps.png" alt="">
                              </div>

                              <div class="rgr-head">
                                 <h3> Electricity Bill </h3> 
                              </div>

                           </div>
                       </div>
                       <div class="col-md-3 col-lg-3 col-12" onclick="goto('domestic_money_transfer')" >
                           <div class="rgr-box cursor">
                              <div class="rgr-img">
                               <img src="<?=ADMINURL;?>assets/images/icons/money-transfer.png" alt="">
                              </div>

                              <div class="rgr-head">
                                 <h3> Money Transfer (1<sup>st</sup>) </h3>
                              </div>

                           </div>
                       </div>
                       <div class="col-md-3 col-lg-3 col-12"  onclick="goto('domestic_money_transfer')" >
                           <div class="rgr-box cursor">
                              <div class="rgr-img">
                                    <img src="<?=ADMINURL;?>assets/images/icons/money-transfer.png" alt="">
                              </div>

                              <div class="rgr-head">
                                    <h3> Money Transfer (1st) </h3>
                              </div>

                           </div>
                       </div>
                  </div>
               </div>



               <div class="recharge_sub">
                     <div class="row">
                          <div class="col-md-2 col-lg-2 col-6" onclick="goto('mobilerecharge')" >
                              <div class="rgr-box cursor">
                                 <div class="rgr-img">
                                  <img src="<?=ADMINURL;?>assets/images/icons/mbl-recharge.png" alt="">
                                 </div>
   
                                 <div class="rgr-head">
                                    <h3> Mobile Recharge </h3>
                                 </div>
   
                              </div>
                          </div>
                          <div class="col-md-2 col-lg-2 col-6" onclick="goto('dthrecharge');">
                              <div class="rgr-box cursor">
                                 <div class="rgr-img">
                                  <img src="<?=ADMINURL;?>assets/images/icons/dth-recharge.png" alt="">
                                 </div>
   
                                 <div class="rgr-head">
                                    <h3> DTH Recharge </h3>
                                 </div>
   
                              </div>
                          </div>
                          <div class="col-md-2 col-lg-2 col-6" onclick="goto('individual_pan');">
                              <div class="rgr-box cursor">
                                 <div class="rgr-img">
                                  <img src="<?=ADMINURL;?>assets/images/icons/new-pan.png" alt="">
                                 </div>
   
                                 <div class="rgr-head">
                                    <h3> New Pan Card </h3>
                                     
                                 </div>
   
                              </div>
                          </div>
                          <div class="col-md-2 col-lg-2 col-6" onclick="goto('individual_pan_corr');">
                              <div class="rgr-box cursor">
                                 <div class="rgr-img">
                                       <img src="<?=ADMINURL;?>assets/images/icons/correct-pan.png" alt="">
                                 </div>
   
                                 <div class="rgr-head">
                                       <h3> Correct Pancard </h3> 
                                 </div>
   
                              </div>
                          </div>
                          <div class="col-md-2 col-lg-2 col-6">
                              <div class="rgr-box cursor">
                                 <div class="rgr-img">
                                       <img src="<?=ADMINURL;?>assets/images/icons/water-payment.png" alt="">
                                 </div>
   
                                 <div class="rgr-head">
                                       <h3> Water Bill Payment </h3>
                                       <p class="font-12">Coming soon..</p>
                                 </div>
   
                              </div>
                          </div>
                          <div class="col-md-2 col-lg-2 col-6">
                              <div class="rgr-box cursor">
                                 <div class="rgr-img">
                                       <img src="<?=ADMINURL;?>assets/images/icons/gas-payment.png" alt="">
                                 </div>
   
                                 <div class="rgr-head">
                                       <h3> Gas Bill Payment </h3>
                                      <p class="font-12">Coming soon..</p>
                                 </div>
   
                              </div>
                          </div>

                          <div class="col-md-2 col-lg-2 col-6" >
                              <div class="rgr-box cursor">
                                 <div class="rgr-img">
                                  <img src="<?=ADMINURL;?>assets/images/icons/mbl-recharge.png" alt="">
                                 </div>
   
                                 <div class="rgr-head">
                                    <h3> Post Paid Bill Payment </h3>
                                    <p class="font-12">Coming soon..</p>
                                 </div>
   
                              </div>
                          </div>
                          <div class="col-md-2 col-lg-2 col-6">
                              <div class="rgr-box cursor">
                                 <div class="rgr-img">
                                  <img src="<?=ADMINURL;?>assets/images/icons/micro-atm.png" alt="">
                                 </div>
   
                                 <div class="rgr-head">
                                    <h3> Micro ATM </h3>
                                     <p class="font-12">Coming soon..</p>
                                 </div>
   
                              </div>
                          </div>
                          <div class="col-md-2 col-lg-2 col-6">
                              <div class="rgr-box cursor">
                                 <div class="rgr-img">
                                  <img src="<?=ADMINURL;?>assets/images/icons/vechile-insurence.png" alt="">
                                 </div>
   
                                 <div class="rgr-head">
                                    <h3> Vehicle Insurance </h3>
                                     <p class="font-12">Coming soon..</p>
                                 </div>
   
                              </div>
                          </div>
                          <div class="col-md-2 col-lg-2 col-6">
                              <div class="rgr-box cursor">
                                 <div class="rgr-img">
                                       <img src="<?=ADMINURL;?>assets/images/icons/hotel-banking.png" alt="">
                                 </div>
   
                                 <div class="rgr-head">
                                       <h3> Hotel Booking </h3>
                                        <p class="font-12">Coming soon..</p>
                                 </div>
   
                              </div>
                          </div>
                          <div class="col-md-2 col-lg-2 col-6">
                              <div class="rgr-box cursor">
                                 <div class="rgr-img">
                                       <img src="<?=ADMINURL;?>assets/images/icons/bus-tracking.png" alt="">
                                 </div>
   
                                 <div class="rgr-head">
                                       <h3> Bus Booking </h3>
                                        <p class="font-12">Coming soon..</p>
                                 </div>
   
                              </div>
                          </div>
                          <div class="col-md-2 col-lg-2 col-6">
                              <div class="rgr-box cursor">
                                 <div class="rgr-img">
                                       <img src="<?=ADMINURL;?>assets/images/icons/flight-booking.png" alt="">
                                 </div>
   
                                 <div class="rgr-head">
                                       <h3> Flight Booking </h3>
                                        <p class="font-12">Coming soon..</p>
                                 </div>
   
                              </div>
                          </div>
                     </div>
                  </div>


  
                  

                  <section class="report-section">
                        <div class="report-area">
                            <div class="report-area-t">
                                <div class="row">
    
                                    <div class="col-lg-6 col-md-6 col-6 ">
                                        
                                               <h2 class="color-blue report-heading"> Recent Transactions  </h2>
                                            
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-6 ">
    
                                        <div class="top-10-txt down_btn fl-right">
    
                                           <select name="" id="" class="bg-gray">
                                                 <option value=""> Today</option>
                                                 <option value="">  Months </option>
                                                 <option value=""> Year </option>

                                           </select>
    
                                        </div>
    
                                    </div>
                                    <div class="recent-txt">
                                       <p> Recharge To which the users belonged while on the current date range </p>
                                    </div>
    
                                    <div class="border-tabl">
    
                                    </div>
    
                                    <div class="col-lg-12 col-md-12 col-12 ">
    
                                        <div class="report-table pan_table">
    
                          <table class="table">
                              <thead>

                                  <tr style="background: transparent">
                                      <th class="w-101"> Sr.No.</th>
                                      <th class="w-200">Order ID </th>
                                      <th> Service Name</th>
                                      <th> Txn. Amt. </th>
                                      <th> Date &amp; Time </th>
                                      <th class="text-center"> Status </th>

                                  </tr>
                              </thead>
<tbody>
   <?php if(!empty($trans_list)){  $i =1; 
    foreach ($trans_list as $key => $value) { ?>
<tr>
<td>
<p><?=$i;?></p>
</td>
<td> 
<p><?=$value['referenceid'];?></p>  
</td>
<td> 
<p><?php echo ucwords( str_replace('_', ' ', $value['subject']));?></p>
</td>
<td>
<p> <?=$value['amount'];?> </p> 
</td>
<td class="messg succes_t">
<p> <?=date('d/m/Y',strtotime($value['add_date']));?> </p>
<p> <?=date('h:i:s A',strtotime($value['add_date']));?></p> 
</td>
<td>
<button id="ap1" class="update-btn w-101"> Success </button>
</td>
</tr>
<?php $i++; } }?>
</tbody>
</table>
     </div> 
                                    </div>
    
                                </div>
    
                            </div>
    
                        </div>
    
                    </section>
            
            </div>
         </section>

         <br> <br>

      </div>
   </div>

<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>
<script type="text/javascript">
  function goto(data){
window.location.href='<?=ADMINURL.'ag/';?>'+data;
  }
</script> 