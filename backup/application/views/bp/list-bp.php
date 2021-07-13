<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
function changestatus(id,status){
        var id      = id;
        var status  = status;
        
        if(id){
            $.ajax({
                type:'POST',
                url:'<?php echo base_url('ajax/Changeuserstatus'); ?>',
                data:{ 
                  'id' : id,'status':  status,
               },
                success:function(data){
                    if(data == 'yes'){
                     document.getElementById(id).style.display = "none";
                     document.getElementById('yes'+id).style.display = "Block";
                    }else{
                     document.getElementById(id).style.display = "none";
                     document.getElementById('no'+id).style.display = "Block";
                    }
                }
            }); 
        }else{
           return false;
        }
}
</script>
      <div class="content">
         <div class="container">
            <section class="cash-area">
               <div class="cash-payment">
                  <div class="cash-heading">
                     <h2> BP  </h2> 
                  </div>
                  <div class="filter-area">
                     <form method="get" action="<?= base_url('bp/md/view');?>">
                     <div class="row">
                        <div class="col-lg-6 col-md-6 col-6">
                           <label> Search By </label>
                           <input class="form-control" placeholder="User ID/Name/Mobile" type="text" name="search_by">
                        </div> 
                        <div class="col-lg-3 col-md-3 col-3">
                           <input class=" search-btn" value="Search" type="submit"
                              name="search">
                        </div>
                     </div>
                   </form>
                     
                     </div>
                  </div>
               </div>
            </section>
            <section class="add-view">
               <div class="report-area">
                  <div class="report-area-t">
                     <div class="row">
                        <div class="col-lg-9 col-md-9 col-6 ">
                           <div class="top-10-txt">
                              <h2 style="margin-bottom: 19px;">BP List (<?= $total;?>)</h2>
                           </div>
                        </div>
                        <div class="border-tabl">
                        </div>
                        <div class="col-lg-12 col-md-12 col-12 ">
                           <div class="report-table adlist-table">
                              <table class="table">
                                 <thead>
                                    <tr style="background: transparent">
                                       <th> Sr.No.
                                       </th>
                                       <th> Owner Name/ City </th>
                                       <th> User ID </th>
                                       <th> Balance</th>
                                       <th> Register Date and Time/ Status </th>
                                       <th> KYC/ KYC Status </th> 
                                    </tr>
                                 </thead>
                                 <tbody>
                                     <?php if(!empty($bpuser)){
                                         $n = 1;
                                    foreach ($bpuser as $bpuserval) {
                                       $city = getftchSingleata( 'dt_city',array('id'=>$bpuserval['cityid']),'cityname'); 
                                       ?>
                                    <tr>
                                       <td><?= $n;?></td>
                                       <td>
                                          <p> <?= $bpuserval['ownername'];?> </p>
                                          <p> <?= $city['cityname'];?></p>
                                       </td>
                                       <td>
                                          <p> <?= $bpuserval['uniqueid'];?></p>
                                            
                                       </td>
                                       <td>
                                          <p><?= $bpuserval['credit_amount'];?> </p> 
                                       </td>
                                       <td>
                                          <p> <?= date( 'd-m-Y h:i A', strtotime( $bpuserval['register_date'] ) );?></p>
                                          <p style="display:none" id="yes<?=$bpuserval['id'];?>" class="ac-color"> <span class="active-s"></span> Active </p>
                                           <p style="display:none" id="no<?=$bpuserval['id'];?>" class="dc-color red_c"> <span class="deactive-s"></span> Inactive </p>
                                          <?php if($bpuserval['status'] == 'yes'){?>
                                          <p id="<?=$bpuserval['id'];?>" class="ac-color"> <span class="active-s"></span> Active </p>
                                          <?php }else{?>
                                          <p id="<?=$bpuserval['id'];?>" class="dc-color red_c"> <span class="deactive-s"></span> Inactive </p>
                                          <?php }?>
                                       </td>
                                       <td>
                                           <a href="<?=ADMINURL.'bp/update_kyc?id='.md5($bpuserval['id']);?>"><button class="edit-kyc"> Edit KYC </button></a>
                                          <p class="text-center"><span class="not-applied">Not Applied</span> </p>
                                       </td>
                                       
                                    </tr>
                                     <?php $n++; }} ?>
                               
                                 </tbody>
                              </table>
                           </div>
                           <div class="pagination_area text-center">

                              <ul class="pagination">
                                 <?= $this->pagination->create_links(); ?>
                              </ul>
  
                          </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <br> <br> <br> 
         </div>
      </div>
      
<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>
<?php $this->load->view('includes/common_session_popup');?>
</body>
</html>