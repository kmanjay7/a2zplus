<div class="report-area">
<div class="report-area-t">
<div class="row"> 
  <style>.span12{ font-size: 13px; }</style>
<?php if(!empty($details)){ foreach ($details as $key => $mvalue){ ?>
<div class="tab-content" style="width: 100%"><div class=" active "> 
 <div class="popular_txt">
      <div class="row">
         <div class="col-4 col-lg-4">
             <h3>Name</h3>
         </div>
          <div class="col-8 col-lg-8 span12">
           <?=$mvalue['customerName'];?>
         </div>
      </div>

      <div class="row">
         <div class="col-4 col-lg-4">
             <h3>Planname</h3>
         </div>
          <div class="col-8 col-lg-8 span12">
           <?=$mvalue['planname'];?>
         </div>
      </div>

      <div class="row">
         <div class="col-4 col-lg-4">
             <h3>Monthly Recharge</h3>
         </div>
          <div class="col-8 col-lg-8 span12">
           <?=$mvalue['MonthlyRecharge'];?> (INR)
         </div>
      </div>


       <div class="row">
         <div class="col-4 col-lg-4">
             <h3>Last Recharge</h3>
         </div>
         <div class="col-8 col-lg-8 span12">
<?php if(isset($mvalue['lastrechargedate']) && !empty($mvalue['lastrechargedate'])){
    echo date('d-F-Y',strtotime($mvalue['lastrechargedate'])).'  &nbsp; '.date('h:i A',strtotime($mvalue['lastrechargedate']));
} ?> 
       </div> 
     </div>

       <div class="row">
         <div class="col-4 col-lg-4">
             <h3>Status</h3>
         </div>
          <div class="col-8 col-lg-8 span12">
            <?=$mvalue['status'];?>
          </div> 
       </div> 
     </div> 
 </div>

</div> 
</div>
</div></div> 
<?php } } ?>
</div>
</div>
</div>