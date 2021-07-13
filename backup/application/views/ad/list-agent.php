<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
function changestatus(id,status){
        var id      = id;
        var status  = status;
        document.getElementById('yes'+id).style.display = "none";
        document.getElementById('no'+id).style.display = "none";
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
                     <h2> Agent  </h2>
                      
                  </div>
                  <div class="filter-area">
                     <form method="get" action="<?= base_url('ad/agent/view');?>">
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
                              <h2 style="margin-bottom: 19px;">Agent List (<?= $total;?>)</h2>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6 ">

                              <div class="top-10-txt">
      
<!--                                 <button class="export">Export</button>-->
      
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
                                       <th> Balance </th>
                                       <th> Register Date / Status </th>
                                       <th> KYC/ KYC Status </th>
                                      
                                    </tr>
                                 </thead>
                                 <tbody>
                                      <?php if(!empty($aguser)){
                                         $n = 1;
                                    foreach ($aguser as $aguserval) {
                                       $city = getftchSingleata( 'dt_city',array('id'=>$aguserval['cityid']),'cityname');
                                       ?>
                                    <tr>
                                       <td><?= $n;?></td>
                                       <td>
                                          <p> <?= $aguserval['ownername'];?> </p>
                                          <p> <?= $city['cityname'];?></p>
                                       </td>
                                       <td>
                                          <p> <?= $aguserval['uniqueid'];?></p>
                                       </td>
                                       <td>
                                          <p><?= $aguserval['credit_amount'];?> </p>
                                       </td>
                                       <td>
                                          <p> <?= date( 'd-m-Y h:i A', strtotime( $aguserval['register_date'] ) );?></p>
                                          <p style="display:none" id="yes<?=$aguserval['id'];?>" class="ac-color"> <span class="active-s"></span> Active </p>
                                           <p style="display:none" id="no<?=$aguserval['id'];?>" class="dc-color red_c"> <span class="deactive-s"></span> Inactive </p>
                                          <?php if($aguserval['status'] == 'yes'){?>
                                          <p id="<?=$aguserval['id'];?>" class="ac-color"> <span class="active-s"></span> Active </p>
                                          <?php }else{?>
                                          <p id="<?=$aguserval['id'];?>" class="dc-color red_c"> <span class="deactive-s"></span> Inactive </p>
                                          <?php }?>
                                       </td>
                                       <td>
                                         <a href="<?=ADMINURL.'ad/update_kyc?id='.md5($aguserval['id']);?>"><button class="edit-kyc"> Edit KYC </button></a>
                                          <p ><span class="not-applied">Not Applied</span> </p>
                                       </td>
                                       
                                    </tr>
                                <?php $n++; }} ?>
                                   
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>

                  <div class="pagination_area text-center">

                     <ul class="pagination">
                          <?= $pagination; ?>
                     </ul>

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