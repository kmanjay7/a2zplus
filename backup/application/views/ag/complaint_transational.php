<div class="content">
   <div class="container"> 

    <form action="">
      <section class="cash-area">
         <div class="cash-payment">
            <div class="common-heading">
               <h2 class="color-blue">Filter { <?=$title?> }</h2>
            </div>
            <div class="filter-area">
               <div class="row">
                  <div class="col-md-4 col-lg-4 col-12">
                     <label for=""> Date From </label>
                     <input name="from" type="text" class="form-control" placeholder="DD-MM-YYYY" id="datepicker" autocomplete="off" value="<?=$this->input->get('from')?>">
                  </div>
                  <div class="col-md-4 col-lg-4 col-12">
                     <label for=""> Date To </label>
                     <input name="to" type="text" class="form-control" placeholder="DD-MM-YYYY" id="datepicker1" autocomplete="off" value="<?=$this->input->get('to')?>">
                  </div>
                  <div class="col-md-4 col-lg-4 col-12">
                     <label for=""> &nbsp; </label>
                     <input class="form-control apply-filter w-100 ap_bg" id="rc" value="Filter" type="submit">
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
                              <th> Ticket No.</th>
                              <th> Order Id </th>
                              <th> Txn Date/Time </th> 
                              <th> Txn Amt </th>
                              <th> Status </th>
                              <th> Txn Status Date/Time </th>
                              <th> Complaint On </th>
                              <th> Replied On </th>
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
                                 <p> <?=$row['ticketno'];?></p>
                              </td>
                              <td>
                                 <p style="max-width: 250px"> <?=$row['orderinfo'];?> </p>
                              </td>
                              <td>
                                 <p><?=$row['txndate'];?></p>
                              </td>
                              <td>
                                 <p><?=$row['amount'];?></p>
                              </td>
                              <td>
                                 <p><?=$row['txnstatus'];?></p>
                              </td>
                              <td>
                                 <p><?=$row['statusinfo'];?></p>
                              </td>
                              <td>
                                 <p><?=$row['complainton'];?></p>
                              </td>
                              <td>
                                 <p><?=$row['replyon'];?></p>
                              </td>
                               
                              <td>
                                 <span style="color: <?=$row['status']=='pending'?'blue':''?><?=$row['status']=='solved'?'green':''?><?=$row['status']=='rejected'?'red':''?>"><?=ucwords($row['status']);?></span>
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
<?php $this->load->view('includes/common_session_popup');?>