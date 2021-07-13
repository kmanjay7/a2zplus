
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/sweet.css');?>">

<div id="reply_modal" class="modal fade" role="dialog">
  <div class="modal-dialog  modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Reply Message</h4>
      </div>
      <div class="modal-body">
         Dear <?=$this->session->userdata("ownername")?>,<br/><br/>
        <p id="reply_message"></p>
        <br/>
        -----<br/>
        Regards,<br/>
        Team Support<br/>
        <b>DigiCash India</b>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="image_modal" class="modal fade" role="dialog">
  <div class="modal-dialog  modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Uploaded Document</h4>
      </div>
      <div class="modal-body">
        <img width="400px" src="" id="image_in_modal"/>
      </div>
      <div class="modal-footer">
        <a href="">
              <button class=" del-bank-btn hvr-rectangle-out" data-dismiss="modal">Close</button>
        </a>
        <a download="" id="image_link_in_modal">
          <button class="upd-bank-btn hvr-shutter-out-horizontal">Download</button>
        </a>
      </div>
    </div>
  </div>
</div>

<div class="content">
   <div class="container">
    <form action="" method="post" enctype="multipart/form-data">
      <section class="cash-area">
        <?=$this->session->flashdata("msg")?>
         <div class="cash-payment">
            <div class="common-heading">
               <h2 class="color-blue"> </h2>
            </div>
            <div class="filter-area">
               <div class="row m-15">
                  <div class="col-md-4 col-lg-4 col-12">
                     <label for=""> Name </label>
                     <input readonly="" ="" name="name" type="name" class="form-control" placeholder="Enter Name" id="name" maxlength="50" autocomplete="off" value="<?=$this->session->userdata('ownername')?>">
                  </div>
                  <div class="col-md-4 col-lg-4 col-12">
                     <label for=""> User Id </label>
                     <input readonly="" type="text" class="form-control" placeholder="Enter User Id" id="user_id" autocomplete="off" value="<?=$this->session->userdata('uniqueid')?>">
                  </div>
                  <div class="col-md-4 col-lg-4 col-12">
                     <label for=""> Email Id </label>
                     <input readonly="" ="" name="email_id" type="email" class="form-control" placeholder="Enter Email Id" id="email_id"  autocomplete="off" value="<?=$this->session->userdata('emailid')?>">
                  </div>
                  <div class="col-md-12 col-lg-12 col-12" style="margin-top: 20px;">
                     <label for=""> Complaint Message </label>
                     <textarea style="height: 100px;" required="" name="complaint_message" type="text" class="form-control" placeholder="Enter Complaint Message" id="complaint_message"></textarea>
                  </div>
                  <div class="col-md-6 col-lg-6 col-12" style="margin-top: 20px;">
                     <label for=""> Upload Documents (Max-5)<sup>(png, jpg, pdf)</sup> </label>
                     <input name="files[]" type="file" multiple="" class="form-control" id="image">
                  </div>
               </div>
            </div>
            <div class="filter-area">
               <div class="row ">
                  <div class="col-md-3 col-lg-3 col-12  "> 
                     <input class="form-control apply-filter w-100 ap_bg" id="rc" value="Submit" type="submit">
                  </div>
               </div> 
            </div>
         </div>
      </section>
    </form>

    <form action="">
      <section class="cash-area">
         <div class="cash-payment">
            <div class="common-heading">
               <h2 class="color-blue">Filter</h2>
            </div>
            <div class="filter-area">
               <div class="row">
                  <div class="col-md-4 col-lg-4 col-12">
                     <label for=""> Date From </label>
                     <input name="from" type="text" class="form-control" placeholder="DD-MM-YYYY" id="from" autocomplete="off" value="<?=$this->input->get('from')?>">
                  </div>
                  <div class="col-md-4 col-lg-4 col-12">
                     <label for=""> Date To </label>
                     <input name="to" type="text" class="form-control" placeholder="DD-MM-YYYY" id="to" autocomplete="off" value="<?=$this->input->get('to')?>">
                  </div>
                  <div class="col-md-4 col-lg-4 col-12">
                     <label for=""> &nbsp; </label>
                     <input class="form-control apply-filter w-100 ap_bg" id="rc" value="Submit" type="submit">
                  </div>
               </div>
            </div>
            <div class="filter-area">
               <div class="row ">
                  <div class="col-md-3 col-lg-3 col-12  "> 
                     
                  </div>
               </div> 
            </div>
         </div>
      </section>
    </form>

  

      <div class="report-area">
         <div class="report-area-t">
            <div class="row">
               <div class="col-lg-12 col-md-12 col-12 ">
                  <div class="top-10-txt">
                     <h2 class=" report-heading"> List </h2>
                  </div>
               </div>
               <div class="border-tabl"></div>
               <div class="col-lg-12 col-md-12 col-12 ">
                  <div class="report-table">
                     <table class="table">
                        <thead>
                           <tr style="background: transparent">
                              <th> Sr.No.</th>
                              <th> Complaint Info. </th>
                              <th> Complaint Msg. </th>
                              <th> Reply </th>
                              <th> Attachments </th>
                              <th> Status </th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if(!is_null($rows) && !empty($rows) ){
                              $i = 1;
                              foreach($rows as $row):?>
                           <tr>
                              <td>
                                 <p> <?=$i;?></p>
                              </td>
                              <td>
                                 <p> <?=$row['id'];?></p>
                                 <p><?=date("d-M-Y <\b\\r>h:i A", strtotime($row['created']))?></p>
                              </td>
                              <td>
                                 <p style="max-width: 450px"><?=$row['complaint_message']?></p>
                              </td>
                              <td>
                                 <p>
                                    <?php if($row['reply_message']!=""): ?><a href="javascript:void(0)" onClick="getReply(<?=$row['id']?>)">View</a><?php endif ?>
                                 </p>
                              </td>
                              <td>
                                 <p>
                                    <?php $i=1; foreach($row['files'] as $doc): ?>
                                       <a onclick="open_image('<?=base_url('uploads/').$doc['file']?>')" href="javascript:void(0)">Doc <?=$i?></a><br/>
                                    <?php $i++; endforeach ?>
                                 </p>
                              </td>
                              <td>
                                 <span style="color: <?=$row['status']=='Pending'?'blue':''?><?=$row['status']=='Replied'?'green':''?>"><?=$row['status']?></span>
                                 <p><?=$row["reply_on"]!="0000-00-00 00:00:00"?date("d-M-Y <\b\\r>h:i A", strtotime($row["reply_on"])):""?></a></p>
                              </td>
                           </tr>
                           <?php $i++; endforeach; } ?>       
                        </tbody>
                     </table>
                  </div>
                  <div class="pagination_area text-center">
                     <ul class="pagination">
                     </ul>
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
   function getReply(id)
   {
      $.ajax({
         url:'<?php echo base_url().$folder.'/complaints/get_reply';?>',
         type:"post",
         dataType: 'json',
         data:{id:id},
         success: function(data)
         {
            if(data.status)
            {
               $("#reply_message").html(data.reply_message);
               $("#reply_modal").modal("toggle");
            }
            else
            {
               alert("failed to fetch");
            }
         }
      });
   }

   function open_image(url)
   {
      $("#image_in_modal").attr("src", url);
      $("#image_link_in_modal").attr("href", url);
      $("#image_modal").modal("toggle");
   }
   
   $("#image").change(function(){
      var $fileUpload = $("input[type='file']");
      if (parseInt($fileUpload.get(0).files.length) > 5){
         alert("You are only allowed to upload a maximum of 5 files");
         $("#image").val(''); 
      }
   });


</script>

