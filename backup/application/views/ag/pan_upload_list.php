   <div class="content">
      <div class="container" style="min-height: 500px">

         <section class="cash-area">
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
                                 
                            </div>
                           

                            <div class="border-tabl"></div>

<div class="col-lg-12 col-md-12 col-12">  
    <div class="report-table"> 
            <table id="example1" class="table table-striped" >
                              <thead> 
                                 <tr style="background: transparent">
                                    <th> Sr.No.</th>
                                    <th> Order Info </th> 
                                    <th> Pan Info </th>
                                    <th> Applicant Info </th>  
                                    <th> Status </th>  
                                    <th class="text-center"> Action </th> 
                                 </tr>
                              </thead>
                              <tbody>
<?php  if(!empty($list)){
  $n = 1;  $uploaduri = '#';
  foreach ( $list as $key=>$value) { 

if($value['pancardtype']=='Individual' && $value['category']=='new'){
$uploaduri = base_url($folder.'/pan_new_uploads?id='.md5($value['id']).'&tab=2');
}else if($value['pancardtype']=='Individual' && $value['category']=='correction'){
$uploaduri = base_url($folder.'/individual_pan_corr_uploads?id='.md5($value['id']).'&tab=2');
}else if($value['pancardtype']!='Individual' && $value['category']=='new'){
$uploaduri = base_url($folder.'/non_individual_pan_uploads?id='.md5($value['id']).'&tab=2');
}else if($value['pancardtype']!='Individual' && $value['category']=='correction'){
$uploaduri = base_url($folder.'/non_individual_pan_corr_uploads?id='.md5($value['id']).'&tab=2');
}
    ?>
                                            
          <tr>
              <td><p><?= $n;?></p></td>
              <td><p><?=$value['orderid'];?>  </p>
                <p><?= date('d/m/Y',strtotime($value['fill_date']));?></p>
              </td> 
              <td> 
                  <p> <?= $value['pancardtype'];?> </p>
                  <p> <?= ucwords($value['category']);?></p>
              </td> 
              <td>
              <p> <?= $value['name_on_aadhar'];?> </p>
              <p> <?= $value['contact'];?></p>
              </td>


              <td>
              <p class="<?=statusbtn_c('failure','class');?>"> <span></span> Pending For Upload</p> 
              </td>
 
            <td><a href="<?=$uploaduri;?>" >
            <button id="ap1" class="update-btn ap_bg "> Upload </button> </a> 
            </td>
</tr>
<?php $n++; }} ?>
</tbody>

  <tfoot style="display: none;">                                 
  <tr>
  <th> Sr.No.</th>
  <th> Order Info </th> 
  <th> Pan Info </th>
  <th> Applicant Info </th>  
  <th> Status </th>  
  <th class="text-center"> Action </th>  
  </tr>
  </tfoot>

</table>

                        </div>
                        


                     </div>

                  </div>

               </div>

              

            </div>

         </section>


         <br> <br>

      </div>
   </div>

<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?> 
<?php $this->load->view('includes/dataTable');?> 
</body>
</html>
