<div class="report-area">
<div class="report-area-t">
<div class="row"> 
  <style>.span12{ font-size: 13px; }</style>
<?php if(!empty($details)){  ?>
<div class="tab-content" style="width: 100%"><div class=" active "> 
 <div class="popular_txt">
      <div class="row">
         <div class="col-4 col-lg-4">
             <h3>Name</h3>
         </div>
          <div class="col-8 col-lg-8 span12">
           <?=$details['customerName'];?>
         </div>
      </div>

      <div class="row">
         <div class="col-4 col-lg-4">
             <h3>Description</h3>
         </div>
          <div class="col-8 col-lg-8 span12">
           <?=$details['desc'];?>
         </div>
      </div>


</div> 
</div>
</div></div> 
<?php } ?>
</div>
</div>
</div>