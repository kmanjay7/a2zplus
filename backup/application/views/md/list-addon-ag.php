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
            
            <section class="add-view cash-area">
               <div class="report-area">
                  <div class="report-area-t">
                     <div class="row">
                        <div class="col-lg-9 col-md-9 col-6 ">
                           <div class="top-10-txt"> 
                           <h2 style="margin-bottom: 19px;">  DOWNLINE Agent <?= $parentdetail['ownername'].' | '.$parentdetail['uniqueid'].' | '.$parentdetail['user_type']?> </h2>
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
                                       <th> User ID/ Password </th>
                                       <th> Balance (Main Wallet/ <br> RBL Wallet)/ Agent ID</th>
                                       <th> Register Date / Status </th>
                                       <th> KYC/ KYC Status </th>
                                       <th> Action </th>
                                   
                                       <th> AEPS Status </th>
                                       <th class="text-center"> Action </th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                      <?php if(!empty($aguser)){
                                         $n = 1;
                                    foreach ($aguser as $aguserval) {
                                       $city = getftchSingleata( 'dt_city',array('id'=>$aguserval['cityid']),'cityname');
                                       $puniqueid = getftchSingleata( 'dt_users',array('id'=>$aguserval['parentid']),'uniqueid');
                                       ?>
                                    <tr>
                                       <td><?= $n;?></td>
                                       <td>
                                          <p> <?= $aguserval['ownername'];?> </p>
                                          <p> <?= $city['cityname'];?></p>
                                       </td>
                                       <td>
                                          <p> <?= $aguserval['uniqueid'];?></p>
                                          <p><?= $aguserval['password'];?> </p>
                                       </td>
                                       <td>
                                          <p><?= $aguserval['credit_amount'];?> </p>
                                          <p><?= $puniqueid['uniqueid'];?></p>
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
                                         <a href="<?=ADMINURL.'md/update_kyc?id='.md5($aguserval['id']);?>"><button class="edit-kyc"> Edit KYC </button></a>
                                          <p ><span class="not-applied">Not Applied</span> </p>
                                       </td>
                                       <td>
                                             <p>
                                              <select onchange="changestatus(<?= $aguserval['id']?>,this.value)" class="edit-kyc">
                                                  <option <?php if(empty($aguserval['status'])){echo 'selected';}?>> Select</option>
                                                  <option value="yes" <?php if($aguserval['status'] == 'yes'){echo 'selected';}?>> Active</option>
                                                <option value="no" <?php if($aguserval['status'] == 'no'){echo 'selected';}?>> Inactive</option>
                                             </select>
                                          </p>
                                          </td>
                                      
                                       <td>
                                            <a href="agent-view-popup.php"> <p class="text-center"><span class="down-line-agent">L</span></p></a>
                                       </td>
                                       <td> <a href="<?= ADMINURL.'md/agent?page=1&parentid='.$parentid.'&id='.md5($aguserval['id']);?>"> <button class="update-btn"> Update </button> </a> <button class="delete">Delete</button> </td>
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