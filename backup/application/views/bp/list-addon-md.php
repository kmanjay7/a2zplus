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
                              <h2 style="margin-bottom: 19px;">  DOWNLINE MD <?= $parentdetail['ownername'].' | '.$parentdetail['uniqueid'].' | '.$parentdetail['user_type']?></h2>
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
                                       <th> Balance/ Agent ID</th>
                                       <th> Register Date and Time/ Status </th>
                                       <th> KYC/ KYC Status </th>
                                       <th class="text-center"> Action </th>
                                       <th> Downline AD	</th>
                                       <th> DownLine Agent </th>
                                       <th class="text-center"> Action </th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                      <?php if(!empty($mduser)){
                                           $n = 1;
                                    foreach ($mduser as $mduserval) {
                                       $city = getftchSingleata( 'dt_city',array('id'=>$mduserval['cityid']),'cityname');
                                       $puniqueid = getftchSingleata( 'dt_users',array('id'=>$mduserval['parentid']),'uniqueid');
                                       $countad =  countitem('dt_users',array('user_type'=>'AD','parentid'=>$mduserval['id']));
                                       $countag =  countitem('dt_users',array('user_type'=>'Retailer','parentid'=>$mduserval['id']));
                                       ?>
                                    <tr>
                                       <td><?= $n;?></td>
                                       <td>
                                          <p> <?= $mduserval['ownername'];?> </p>
                                          <p> <?= $city['cityname'];?></p>
                                       </td>
                                       <td>
                                         <p> <?= $mduserval['uniqueid'];?></p>
                                         <p><?= $mduserval['password'];?> </p>
                                       </td>
                                       <td>
                                           <p><?= $mduserval['credit_amount'];?> </p>
                                          <p><?= $puniqueid['uniqueid'];?></p>
                                       </td>
                                       <td>
                                          <p> <?= date( 'd-m-Y h:i A', strtotime( $mduserval['register_date'] ) );?></p>
                                          <p style="display:none" id="yes<?=$mduserval['id'];?>" class="ac-color"> <span class="active-s"></span> Active </p>
                                           <p style="display:none" id="no<?=$mduserval['id'];?>" class="dc-color red_c"> <span class="deactive-s"></span> Inactive </p>
                                          <?php if($mduserval['status'] == 'yes'){?>
                                          <p id="<?=$mduserval['id'];?>" class="ac-color"> <span class="active-s"></span> Active </p>
                                          <?php }else{?>
                                          <p id="<?=$mduserval['id'];?>" class="dc-color red_c"> <span class="deactive-s"></span> Inactive </p>
                                          <?php }?>
                                       </td>
                                       
                                       <td>
                                        <a href="<?=ADMINURL.'bp/update_kyc?id='.md5($mduserval['id']);?>">  <button  class="edit-kyc"> Edit KYC </button></a>
                                          <p class="text-center"><span class="not-applied">Not Applied</span> </p>
                                       </td>
                                       <td>
                                          <p>
                                              <select onchange="changestatus(<?= $mduserval['id']?>,this.value)" class="edit-kyc">
                                                  <option <?php if(empty($mduserval['status'])){echo 'selected';}?>> Select</option>
                                                  <option value="yes" <?php if($mduserval['status'] == 'yes'){echo 'selected';}?>> Active</option>
                                                <option value="no" <?php if($mduserval['status'] == 'no'){echo 'selected';}?>> Inactive</option>
                                             </select>
                                          </p>
                                       </td>
                                       <td>
                                          <a target="_blank" href="<?=ADMINURL.'addon_ad_list/'.md5($mduserval['id']);?>">   <p class="text-center"> <span class="down-line-agent"><?= $countad;?></span></p></a>
                                       </td>
                                       <td>
                                          <a  target="_blank" href="<?=ADMINURL.'addon_ag_list/'.md5($mduserval['id']);?>">   <p class="text-center"> <span class="down-line-agent"><?= $countag;?></span></p></a>
                                       </td>
                                       <td>  <a href="<?= ADMINURL.'add_md?page=1&parentid='.$parentid.'&id='.md5($mduserval['id']);?>"> <button class="update-btn"> Update </button> </a>
                                           <button class="delete">Delete</button> </td>
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
                        <?= $this->pagination->create_links(); ?>
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