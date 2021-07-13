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
                     <h2>  AD View  </h2> 
                  </div>
                  <div class="filter-area">
                     <form method="get" action="<?= base_url('md/ad/view');?>">
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
            </section>
            <section class="add-view">
               <div class="report-area">
                  <div class="report-area-t">
                     <div class="row">
                        <div class="col-lg-9 col-md-9 col-6 ">
                           <div class="top-10-txt">
                              <h2 style="margin-bottom: 19px;">AD List (<?= $total;?>)</h2>
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
                                      <?php if(!empty($aduser)){
                                         $n = 1;
                                    foreach ($aduser as $aduserval) {
                                       $city = getftchSingleata( 'dt_city',array('id'=>$aduserval['cityid']),'cityname');
                                       ?>
                                    <tr>
                                      <td><?= $n;?></td>
                                       <td>
                                          <p> <?= $aduserval['ownername'];?> </p>
                                          <p> <?= $city['cityname'];?></p>
                                       </td>
                                       <td>
                                          <p> <?= $aduserval['uniqueid'];?></p>
                                          
                                       </td>
                                       <td>
                                         <p><?= $aduserval['credit_amount'];?> </p>
                                           
                                       </td>
                                       <td>
                                           <p> <?= date( 'd-m-Y h:i A', strtotime( $aduserval['register_date'] ) );?></p>
                                          <p style="display:none" id="yes<?=$aduserval['id'];?>" class="ac-color"> <span class="active-s"></span> Active </p>
                                           <p style="display:none" id="no<?=$aduserval['id'];?>" class="dc-color red_c"> <span class="deactive-s"></span> Inactive </p>
                                          <?php if($aduserval['status'] == 'yes'){?>
                                          <p id="<?=$aduserval['id'];?>" class="ac-color"> <span class="active-s"></span> Active </p>
                                          <?php }else{?>
                                          <p id="<?=$aduserval['id'];?>" class="dc-color red_c"> <span class="deactive-s"></span> Inactive </p>
                                          <?php }?>
                                       </td>
                                       <td>
                                        <a href="<?=ADMINURL.'md/update_kyc?id='.md5($aduserval['id']);?>">  <button  class="edit-kyc"> Edit KYC </button></a>
                                          <p class="text-center"><span class="not-applied">Not Applied</span> </p>
                                       </td>
                                      
                                    </tr>
                                <?php $n++; }} ?>
                                 </tbody>
                              </table>
                           </div>
                           <div class="pagination_area text-center">

                              <ul class="pagination">
                                <?= $pagination; ?>
                              </ul>
  
                          </div>

                        </div>
                     </div>
                  </div>
               </div>
            </section>




            <!----- Add View KYC POPUP ------>

            <!-- Button trigger modal -->

 
 <!-- Modal -->

 <div class="kyc_model">
 <div class="modal fade" id="add_view_kyc" tabindex="-1" role="dialog" aria-labelledby="add_view_kyc_title" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
     <div class="modal-content">
       <div class="modal-header">
       
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
       </div>
       <div class="modal-body">
         <div class="cerficate_img"> 
            <img src="./assets/images/certifcate.png" alt="">
         </div>
         <div class="apro_btn">
        <div class="row">
           <div class="col-md-6 col-lg-6 col-6">
               <button id="approve" type="button" class="btn btn-primary approve">Approve</button>
           </div>
           <div class="col-md-6 col-lg-6 col-6">
               <button id="cancle" type="button" class="btn btn-primary disaprove">Disapprove</button>
            </div>

        </div>
      </div>


       </div>
     
     </div>
   </div>
 </div>
</div>

            <br> <br> <br> 
         </div>
      </div>
      
<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>
<?php $this->load->view('includes/common_session_popup');?>
</body>
</html>