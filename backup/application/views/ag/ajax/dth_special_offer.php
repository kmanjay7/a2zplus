<div class="plan_customer">
            <h2> Plans Details </h2>
        <div>

           
                 
<!-- Nav tabs --> 
 <?php    
 if( isset($plans) && !empty($plans)){ ?>  
<!-- Tab panes -->
<div class="tab-content">
    <div class="popular_txt">
     <div class="row">
         <div class="col-2 col-lg-2">
             <h3>Recharge</h3>
         </div>
         <div class="col-2 col-lg-2">
             <h3>Planname</h3>
         </div>
         <div class="col-2 col-lg-6">
             <h3>Description</h3>
         </div>
         <div class="col-2 col-lg-2">
             <h3>Price</h3>
         </div>
     </div>
 
 </div>
<div  class=" tab-pane active">  

 <div class="mobil_recharge_txt"> 
  <?php  $k = 1; 
  foreach ($plans as $key => $value){ $rs = false; ?> 
  <div class="list-all">
         <div class="row">
             <div class="col-2 col-lg-2">
                 <h3><?php if(is_array($value['rs'])){
                    foreach ($value['rs'] as $key => $pvalue) {
                        echo $rs = $pvalue;
                    }
                 }?></h3>
             </div>
             <div class="col-2 col-lg-2">
                 <h3><?= $value['plan_name']; ?></h3>
             </div>
             <div class="col-2 col-lg-6">
                 <h3><?= $value['desc']; ?></h3>
             </div>
             <div class="col-2 col-lg-2">
                 <h3> <button type="button" onclick="putinamount('<?=$rs;?>')"> Rs.<?= $rs;?>  </button> </h3>
             </div>
         </div>
     
     </div> 
    <?php $k++; }  ?> 
   </div> 
</div>

<?php }else{?><center><h4>No Plans Available!</h4></center> <?php }  ?> 
