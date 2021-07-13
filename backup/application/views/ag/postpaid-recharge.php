<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/sweet.css');?>"><div class="content">
<div class="container">


<section class="cash-area"> 
<div class="cash-payment">

<div class="common-heading">
<h2 class="color-blue"> <?=$title;?> </h2>
</div>

 <?=form_open( base_url($folder.'/'.$pagename.'/recharge'),array('method'=>'post'));?>

<div class="filter-area"> 
   
    <div class="row m-15">

        <div class="col-md-3 col-lg-3 col-12">
        <label for=""> Mobile Number </label>
        <input name="mobileno" type="text" class="form-control" placeholder="Enter Mobile Number" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" onkeydown="if(this.value.length==10 &amp;&amp; event.keyCode!=8) return false;" id="mobileno" autocomplete="off" value="<?=$mobileno;?>">
        </div>


        <div class="col-md-3 col-lg-3 col-12">
        <label for=""> Operator </label> 
        <?php echo form_dropdown(array('name'=>'operatorcode','class'=>'form-control','id'=>'operator','onchange'=>'getcircle()'),$operatorlist, set_value('operatorcode',$operatorcode ) );?>
        </div>


        <div class="col-md-3 col-lg-3 col-12">
        <label for=""> Circle </label>
         <?php echo form_dropdown(array('name'=>'circle','class'=>'form-control','id'=>'circle','onchange'=>'getcircle()'),get_dropdowns('operator_circle',array('serviceid'=>'5','status'=>'yes'),'circle','circle','Circle--'),set_value('circle',$circle) );?> 
        </div>


        <div class="col-md-3 col-lg-3 col-12">
        <label for=""> Amount </label>
        <input class="form-control" placeholder="Enter Amount" type="text" name="amount" required="required" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" onkeydown="if(this.value.length==10 &amp;&amp; event.keyCode!=8) return false;" id="amount" >
        </div>


        </div>

    </div>


<div class="filter-area">
    <div class="row ">
     <div class="col-md-3 col-lg-3 col-12">
      <?php /*?> <button type="button" class="form-control apply-filter w-100" onclick="checkplan()"> Check Plans  </button> <?php */?>
         </div>

    <div class="col-md-3 col-lg-3 col-12 offset-md-6 "> 
      <input class="form-control apply-filter w-100 ap_bg" id="rc" value="Recharge" type="submit" name="filter">
        </div>
    </div>
    <?=form_close();?> 
        </div>

  

</div>
</section>



<div class="report-area">
<div class="report-area-t">
<div class="row">

<div class="col-lg-12 col-md-12 col-12 ">
<div class="top-10-txt">
 <h2 class=" report-heading"> Recent Recharge </h2>
</div>
</div>



<div class="border-tabl"></div>

<div class="col-lg-12 col-md-12 col-12 ">

<div class="plans_para">

<?php if(!is_null($recentrecharge) && !empty($recentrecharge)){
    foreach ($recentrecharge as $key => $rcvalue):?>
<div class="recent-recharge-txt">
<div class="row">
<div class="col-md-3 col-lg-3 col-3">
<div class="rechrage_img">
<img src="<?=ADMINURL;?>assets/images/<?=$rcvalue['image'];?>" alt="">
</div>
</div> 
<div class="col-md-2 col-lg-2 col-2">
<h3> <?=$rcvalue['mobileno'];?> </h3>
</div>
<div class="col-md-2 col-lg-2 col-2">
<h3> <?=$rcvalue['operator'];?> </h3>
</div>
<div class="col-md-2 col-lg-2 col-2">
<h3> Rs <span><?= round($rcvalue['amount']);?></span> </h3>
</div>
<div class="col-md-3 col-lg-3 col-3">
<h3><?=$rcvalue['reqid'];?> </h3>
</div>
</div> 
</div>
<?php endforeach; } ?>


 


</div>

</div>

</div>

</div>

</div>

<br>
<br>

</div>
</div>
</div></div></div></div>

<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>
<script  src="<?php echo base_url('assets/js/sweet.js');?>"></script>
<?php ;?>
<script type="text/javascript">
function getcircle(){
<?php /* ?>var mobile = $('#mobileno').val();
var operator = $('#operator').val();
 if(mobile.length > 0 && operator.length > 0 ){
   $.ajax({
    type:'POST',
    url:'<?php echo base_url( $folder.'/Mobilerecharge/getcircle' );?>',
    data:{'mobileno':mobile,'operator':operator},
    success:function(res){
        if(res){
        $('#circle').html(res);
        }
    }, 

   });
 }<?php */ ?>
}
</script>

<script type="text/javascript">
function checkplan(){ 
var operator = $('#operator').val();
var circle = $('#circle').val();
var mobile = $('#mobileno').val();
 if( operator.length > 0 && circle.length > 0 ){
    var url = '<?= base_url( $folder.'/'.$pagename.'/' );?>?operator='+operator+'&circle='+circle+'&mobile='+mobile;
    window.location.href=''+url;  
 }else if(!operator){ $('#operator').focus(); }
 else if(!circle){ $('#circle').focus(); }
}

function putinamount(amnt){
 $('#amount').val(amnt);
 $('html,body').animate({scrollTop: '0px'}, 1000);
}
</script>

 <script>
            <?php if($this->session->flashdata('success')){?>
            swal(" <?= $this->session->flashdata('success')?>", "", "success");
            <?php }?>
            <?php if($this->session->flashdata('error')){?>
            swal({
                title: "Warning ?",
                text: "<?= $this->session->flashdata('error')?>!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                closeOnConfirm: false,
            });
            <?php }?>
        </script>