<div class="content">
            <div class="container">

                <section class="cash-area">

                    <div class="cash-payment">
                        <div class="cash-heading">
                            <h2><?=$title;?> </h2>
                        </div>


<div class="filter-area">
       
<form action="<?=ADMINURL.$folder.'/'.$pagename;?>">
 <div class="row">
 
  <div class="co-12 col-md-2 col-lg-2">
        <div class="form-group">
        <label for=""> From Date </label>
        <div class="input-group">
        <?=form_input(array('name'=>'from_date','class'=>'form-control','id'=>'datepicker','placeholder'=>'From Date','onchange'=>'putintodate(this.value)'),set_value('from_date',$from_date));?>
        <div class="input-group-append">
        <span class="input-group-text"> <i style="font-size: 20px;" class="material-icons">event</i> </span>
        </div>

        </div>
        </div>
    </div>


    <div class="co-12 col-md-2 col-lg-2">
        <div class="form-group">
        <label for=""> To Date </label>
        <div class="input-group">
        <?=form_input(array('name'=>'to_date','class'=>'form-control','id'=>'datepicker1','placeholder'=>'To Date'),set_value('to_date',$to_date));?>
        <div class="input-group-append">
        <span class="input-group-text"> <i style="font-size: 20px;" class="material-icons">event</i> </span>
        </div>

        </div>
        </div>
    </div>

    <div class="co-12 col-md-2 col-lg-2">
        <?php $arr1 = [''=>'All','new'=>'New','correction'=>'Correction'];  
        $arr2 = get_dropdowns('dt_pancardtype',['status'=>'yes'],'id','pancardtype','Pan Type' );
        $txnlist = $arr1 + $arr2 ;?>
        <label for=""> Transaction </label>
        <?=form_dropdown(array('name'=>'transaction','class'=>'form-control'), $txnlist,set_value('transaction',$transaction));?>
    </div>


    <div class="co-12 col-md-2 col-lg-2">
        <label for=""> Select Filter </label>
        <?=form_dropdown(array('name'=>'filterby','class'=>'form-control'),[''=>'All','orderid'=>'Order ID'],set_value('filterby',$filterby));?>
    </div>


    <div class="co-12 col-md-2 col-lg-2 m-31">
        <?=form_input(array('name'=>'fvalue','class'=>'form-control','id'=>'datepicker1','placeholder'=>'Enter Value'),set_value('fvalue',$fvalue));?> 
    </div>

    
  <div class="co-12 col-md-2 col-lg-2 m-31"> 
  <input class="form-control apply-filter w-100" value="Search" type="submit" name="apply_filter">
  </div>
</div>
</div></form>
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
                <table id="example1" class="table table-striped" >
                              <thead>

                                 <tr style="background: transparent">
                                    <th> Sr.No.</th>
                                    <th> Order Info </th> 
                                    <th> Pan Info </th>
                                    <th> Applicant Info </th>  
                                    <th> Status Info</th> 
                                    <th class="text-center"> PAN Details </th> 
                                 </tr>
                              </thead>
                              <tbody>
<?php  if(!empty($list)){
  $n = 1; 
  foreach ( $list as $key=>$value) { ?>
                                            
          <tr id="hd<?=$value['id'];?>">
              <td><p><?= $n;?></p></td>
              <td><p><?=$value['orderid'];?>  </p>
                <p><?= $value['fill_date'];?></p>
                <p><b><?= $value['ackno'];?></b></p>
              </td>
              
              <td> 
                  <p> <?= ucwords($value['category']);?>  </p>
                  <p> <?= $value['pancardtype'];?> </p> 
                  <p> <?= $value['statename'];?> </p>
              </td>
               
              <td>
              <p> <?= $value['name_on_aadhar'];?> </p>
              <p> <?= $value['contact'];?></p>
              </td>


              <td>
              <p class="<?=statusbtn_c('SUCCESS','class');?>"> <span></span> Approved </p><p><?php echo $value['reapprveon']?$value['reapprveon']:$value['approvaldate'];?></p>
              </td>


      
  <td>   
        <a href="<?=base_url('ag/individual_pan?id=').md5($value['id']).'&odr='.$value['orderid'];?>" target="_blank"><button class="update-btn"> View </button></a>

        <a href="<?=$value['ackfile'];?>" target="_blank" download ><button class="update-btn" title="Download ACk Slip"> Ack Slip </button></a>
       

  </td>
   
</tr>
<?php $n++; }} ?>

</tbody>
<tfoot>
  <tr style="display: none;">
  <th> Sr.No.</th>
  <th> Order Info </th> 
  <th> Pan Info </th>
  <th> Applicant Info </th>  
  <th> Status info </th> 
  <th> PAN Details </th> 
</tr>
</tfoot>
</table>

                        </div> 

                     </div>

                  </div>

               </div>

              

            </div>

         </section>


         <br/> <br/>

      </div>
   </div>
</section>




<script src="<?=ADMINURL;?>assets/js/popper.min.js"></script>
<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?> 
<?php $this->load->view('includes/dataTable');?>
<?php  $this->load->view('includes/common_session_popup');?>  

<script type="text/javascript">
function putintodate(id){
        $('#datepicker1').val(id);
}
</script>
</body>
</html>

