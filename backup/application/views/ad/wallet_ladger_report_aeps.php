<style type="text/css">div#example1_wrapper > div.row{ width: 100% } #example1_length,#example1_filter{ display: none; }</style>
<div class="content">
        <div class="container">

            <section class="cash-area">

                <div class="cash-payment">
                    <div class="cash-heading">
                        <h2><?=$title;?> </h2>
                    </div>
                    
                   <?php /*?> <div class="aeps-area pan_card_area aeps-agent">
                        <div class="row">
                            <div class="col-md-3 col-lg-3 col-12">
                                 <div class="aeps-inner" style="background-image:url('<?=ADMINURL;?>assets/images/apes-bg.png')">
                                    <h3> Successful Transactions Amount </h3> 
                                    <h4> ₹ <?=$success_amt;?> </h4> 
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                               <div class="aeps-inner" style="background-image:url('<?=ADMINURL;?>assets/images/apes-bg.png')">
                                    <h3> Total Commission  </h3> 
                                    <h4> ₹ <?=$total_commission;?> </h4>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3 col-12">
                                 <div class="aeps-inner" style="background-image:url('<?=ADMINURL;?>assets/images/apes-bg.png')">
                                    <h3> Total Surcharge </h3> 
                                    <h4> ₹ <?=$total_surcharge;?> </h4>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="aeps-inner" style="background-image:url('<?=ADMINURL;?>assets/images/apes-bg.png')">
                                    <h3> Total TDS </h3> 
                                    <h4> ₹  <?=$total_tds;?> </h4>
                                </div>
                            </div>

                        </div>

                    </div><?php */?>




 <div class="filter-area">
       
 <form action="<?=ADMINURL.$folder.'/'.$pagename;?>">
 <div class="row m-15">
 
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
        <label for=""> Transaction </label>
        <?=form_dropdown(array('name'=>'transaction','class'=>'form-control'),[''=>'All','aeps_m_t'=>'Cash Withdrawal','aeps_tr_wt'=>'Transfer to Wallet','aeps_tr_bk'=>'Transfer to Bank A/C'],set_value('transaction',$transaction));?>
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
                           

                            <div class="border-tabl"></div>

<div class="col-lg-12 col-md-12 col-12">  
    <div class="report-table"> 
            <table id="example1" class="table table-striped" >
                <thead>                                                             
                    <tr style="background: transparent">
                        <th style="display: none;"> id </th>
                        <th> Order Info</th>
                        <th> Description  </th>
                        <th> Order Amt </th>
                        <th> CR/DR Amt </th>
                        <th> Balance </th> 
                        <th> Details </th> 
                    </tr>
                </thead>


<tbody>
    <?php if(!empty($walletlist)){  $i =1; 
    foreach ($walletlist as $key => $value) { ?>
<tr>
<td style="display: none;"><?=$i;?></td>
<td> 
<p><?=$value['referenceid']?$value['referenceid']:'NA';?></p>
<p><?php echo $date = date('d-m-Y',strtotime($value['add_date']));?> <?=date('h:i:s A',strtotime($value['add_date']));?></p>
</td>

<td> <p> <?=$value['remark'];?> </p></td>
<td><p> <?=$value['odr_amount'];?></p> </td>


<td>
<p><?=$value['crdr_amount'];?></p>
</td>

<td>
<p> <?=$value['close_bal'];?></p> 
</td>

 

<td class="messg succes_t">
<a href="javascript:void(0)" onclick="viewTrans('<?=$value['referenceid']?>')" > View Details </a>
</td>
</tr>
<?php $i++; } }?>

</tbody>

<tfoot style="display: none;">                                                
    <tr>
        <th > id </th>
        <th> Order Info</th>
        <th> Description  </th>
        <th> Order Amt </th>
        <th> CR/DR Amt </th>
        <th> Balance </th> 
        <th> Details </th> 
    </tr>
</tfoot>

</table>
 </div>                       

</div>  



                            </div> 
                        </div> 
                    </div> 
                </div> 
</section>


            <br> <br>

        </div>
    </div>


<div class="modal fade" id="showmodel" tabindex="-1" role="dialog" aria-labelledby="addnewbank" aria-hidden="true" >
                        <div class="modal-dialog modal-dialog-centered" id="addsize" role="document">
                            <div class="modal-content "> 
                                <div class="modal-body form_model" id="msg">
                                </div>
                            </div>
                        </div>
   </div> 


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?> 
<?php $this->load->view('includes/dataTable');?> 
<script type="text/javascript">
    function viewTrans(id){
        if(id){
            $.ajax({
                type: 'POST',
                url: '<?=ADMINURL.'ajax/Getdetailswt';?>',
                data:{'id':id,'type':'aepswt'},
                beforeSend:function(){},
                success: function(res){
                     $("#msg").html(res); 
                     $('#showmodel').modal('show');
                }
            })
        }
    }
    
function putintodate(id){
        $('#datepicker1').val(id);
}
</script>
</body>
</html>