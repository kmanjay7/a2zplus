<div class="plan_customer">
            <h2> Plans Details </h2>
        <div>

<?php if($type == 'simple'){?>           
  <!-- Nav tabs -->
<ul class="nav nav-tabs" id="navtb">

 <?php  $arraykeys = null;
 if( isset($plans['records']) && !empty($plans['records'])){ $i = 1; 
   
 $arraykeys = array_keys($plans['records']); $i = 1;   
 foreach ($arraykeys as $menu){?> 
 <li class="nav-item">
 <a class="nav-link <?php echo $i==1?'active':''?>" data-toggle="tab" href="#m<?php echo $i;?>"><span> <?=$menu;?> </span>  
 </a>
 </li>
    <?php $i++; } } ?> 

</ul>

<!-- Tab panes -->
<div class="tab-content" id="plantab">
<?php  if(!is_null($arraykeys)){ $j = 1; 
     foreach ($arraykeys as $menu){  
        ?>    
<div id="m<?php echo $j;?>" class=" tab-pane <?php echo $j==1?'active':''?> "> 
 <div class="popular_txt">
     <div class="row">
         <div class="col-2 col-lg-2">
             <h3>Talktime</h3>
         </div>
         <div class="col-2 col-lg-2">
             <h3>Validity</h3>
         </div>
         <div class="col-2 col-lg-6">
             <h3>Description</h3>
         </div>
         <div class="col-2 col-lg-2">
             <h3>Price</h3>
         </div>
     </div>
 
 </div>


 <div class="mobil_recharge_txt"> 
  <?php  $k = 1; 
  foreach ($plans['records'][$menu] as $key => $topup){?> 
  <div class="list-all">
         <div class="row">
             <div class="col-2 col-lg-2">
                 <h3>
    <?php if(strpos($topup['desc'],'Get Talktime of Rs.') !== false){ 
        echo 'Rs. '.(float)explodeme($topup['desc'],'Get Talktime of Rs.',1 );
    }else if(strpos($topup['desc'],'Full Talktime Rs.') !== false){ 
        echo 'Rs. '.(float)explodeme($topup['desc'],'Full Talktime Rs.',1 );
    }else if(strpos($topup['desc'],'Talktime Rs.') !== false){ 
        echo 'Rs. '.(float)explodeme($topup['desc'],'Talktime Rs.',1 );
    }else if(strpos($topup['desc'],'Talktime of Rs.') !== false){ 
        echo 'Rs. '.(float)explodeme($topup['desc'],'Talktime of Rs.',1 );
    }else if(strpos($topup['desc'],'IUC Minutes') !== false){
    $str = 'Rs. '.explodeme($topup['desc'],'IUC Minutes',0 ); 
        if(strpos( $str,'Rs. Talktime') !== false){
            echo 'Rs. '.(float)explodeme($str,'Rs. Talktime',1 );
        } 
    }else{ echo 'NA';} ?></h3>
             </div>
             <div class="col-2 col-lg-2">
                 <h3><?= $topup['validity']; ?></h3>
             </div>
             <div class="col-2 col-lg-6">
                 <h3><?= $topup['desc']; ?></h3>
             </div>
             <div class="col-2 col-lg-2">
                 <h3> <button type="button" onclick="putinamount('<?= trim($topup['rs']);?>')"> Rs.<?= $topup['rs'];?>  </button> </h3>
             </div>
         </div>
     
     </div> 
    <?php $k++; }  ?> 
   </div> 
</div>

<?php $j++;} }else{?><center><h4>No Plans Available!</h4></center> <?php }  ?> 








<?php }/// end of simple offer plan 
else if($type == 'roffer'){ 

 $arraykeys = null;
 if( isset($plans['records']) && !empty($plans['records'])){ $i = 1; ?> 
 


<!-- Tab panes -->
<div class="tab-content" id="plantab"> 
<div id="m1" class=" tab-pane active "> 
 <div class="popular_txt">
     <div class="row"> 
         <div class="col-10 col-lg-10">
             <h3>Description</h3>
         </div>
         <div class="col-2 col-lg-2">
             <h3>Price</h3>
         </div>
     </div>
 
 </div>


 <div class="mobil_recharge_txt"> 
  <?php  $k = 1; 
  foreach ($plans['records'] as $key => $topup){?> 
  <div class="list-all">
         <div class="row">
             <div class="col-10 col-lg-10">
                 <h3><?php echo $topup['desc'];?></h3>
             </div> 
             <div class="col-2 col-lg-2">
                 <h3> <button type="button" onclick="putinamount('<?= trim($topup['rs']);?>')"> Rs.<?= $topup['rs'];?>  </button> </h3>
             </div>
         </div>
     
     </div> 
    <?php $k++; }  ?> 
   </div> 
</div>

<?php }else{?><center><h4>No Plans Available!</h4></center> <?php }  ?>

<?php }?>   


</div></div> 
</div> 
