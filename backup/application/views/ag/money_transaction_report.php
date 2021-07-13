 <div class="content">
        <div class="container">

            <section class="cash-area">

                <div class="cash-payment">
                    <div class="cash-heading">
                        <h2><?=$title;?> </h2>
                    </div>
                    
                    <div class="aeps-area pan_card_area aeps-agent">
                        <div class="row">
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="aeps-inner" style="background-image:url('<?=ADMINURL;?>assets/images/apes-bg.png')">
                                    <h3> Successful Transactions Amount </h3> 
                                    <h4> ₹ <?=$success_amt;?> </h4>

                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="aeps-inner" style="background-image:url('<?=ADMINURL;?>assets/images/apes-bg.png')">
                                    <h3> Total Surcharge </h3> 
                                    <h4> ₹ <?=$t_surcharge;?> </h4>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="aeps-inner" style="background-image:url('<?=ADMINURL;?>assets/images/apes-bg.png')">
                                    <h3> Total Commission  </h3> 
                                    <h4> ₹ <?=$t_comi;?> </h4>
                                </div>
                            </div> 
                           

                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="aeps-inner" style="background-image:url('<?=ADMINURL;?>assets/images/apes-bg.png')">
                                    <h3> Total TDS </h3> 
                                    <h4> ₹  <?=$total_tds;?> </h4>
                                </div>
                            </div> 
                        </div> 
                    </div>




 <div class="filter-area">
       
 <form action="<?=ADMINURL.$posturl;?>">
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
        <label for=""> Transaction </label>
        <?=form_dropdown(array('name'=>'transaction','class'=>'form-control'),[''=>'All','success'=>'Success','failed'=>'Failed','pending'=>'Pending','imps'=>'IMPS Transfer','neft'=>'NEFT Transfer','dmt1'=>'DMT Wallet – 1','dmt2'=>'DMT Wallet – 2'],set_value('transaction',$transaction));?>
    </div>


    <div class="co-12 col-md-2 col-lg-2">
        <label for=""> Select Filter </label>
        <?=form_dropdown(array('name'=>'filterby','class'=>'form-control'),[''=>'All','orderid'=>'Order ID','txnid'=>'Transaction ID','mob'=>'Mobile Number','ac'=>'Account Number'],set_value('filterby',$filterby));?>
    </div>


    <div class="co-12 col-md-2 col-lg-2 m-31">
        <?=form_input(array('name'=>'fvalue','class'=>'form-control','id'=>'datepicker1','placeholder'=>'Enter Value'),set_value('fvalue',$fvalue));?> 
    </div>

<div class="co-12 col-md-2 col-lg-2 m-31"> 
<input class="form-control apply-filter w-100" value="Search" type="submit" name="apply_filter">
</div>

      </div>
    </div>
</form>
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
                                    <button onclick="fnExcelReport();" class="export">Export</button>
                                    <a href="#" id="testAnchor"></a>
                                    <div id='MessageHolder'></div> 
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
                        <th> Sender Info </th>
                        <th> Account Info </th>
                        <th> Opt / Wallet / Mode </th>
                        <th> Amt / TID </th>
                        <th> Charge / Com / TDS </th>
                        <th> Status Info</th>
                        <th> Action </th> 
                    </tr>
                  </thead>

<tbody>
    <?php if(!is_null($trans_list) && !empty($trans_list) ){
        $i = 1;
        foreach($trans_list as $key=>$value):?>
<tr>
<td>
<p> <?=$i;?></p>
</td>

<td><p> <?=$value['orderid'];?> </p>
<p><?= date('d-M-Y',strtotime($value['add_date']));?> </p>
<p> <?= date('h:i:s A',strtotime($value['add_date']));?> </p>
</td>

<td> 
            <p><?=$value['s_name'];?></p>
            <P><?=$value['s_mobile'];?></P>
            <p><?=kycstatus($value['kyc_status']);?></p>
        </td>
        <td>
            <p><?=$value['bankname'];?></p> 
            <p><?=$value['b_name'];?></p>
            <p><?=$value['ac_number'];?></p> 
    
        </td>
        <td>
            <p><?=$value['operatorname'];?></p>
            <p><?=$value['apiname']=='paytm'?'DMT 1st':'DMT 2nd';?>/</p>  
            <p> <?=$value['mode'];?> </p> 
        </td>

        <td>
            <p><b><?=$value['amount'];?></b></p> 
            <p><?=$value['ptm_rrn'];?></p>  
        </td>

        <td>
            <p><?= twodecimal($value['sur_charge']);?></p> 
            <p><?=($value['ag_comi']+$value['ag_tds']);?></p>
            <p><?=twodecimal($value['ag_tds']);?></p>  
        </td>
      

        <td class="messg succes_t">

                <p class="<?=statusbtn_c($value['status'],'class');?>"> <span></span> <?=statusbtn_c($value['status']);?> </p>
                <?php if($value['add_date']){
                    $datetime  = $value['status_update'] ? $value['status_update'] : $value['add_date'];
                } ?>
                <p><?= date('d-M-Y',strtotime($datetime));?></p>
                <p><?= date('h:i:s A',strtotime($datetime));?></p>
        </td>

<td><a href="<?=ADMINURL.$folder.'/print_reciept?utp='.md5($value['sys_orderid']);?>" target="_blank" > <button id="ap1" class="update-btn w-101"> Print Receipt </button></a>
<button id="ap2" class="update-btn w-101 <?=complaint_status($value['complaint'],'cl')?> cp-<?=$value['id'];?>" <?php if($value['complaint']==''){?> onclick="complaint('<?=$value['id'];?>','dmt_1')"> <?php }else{ echo 'disabled';}?> <span id="plwait-<?=$value['id'];?>"><?=complaint_status($value['complaint'],'')?></span> </button>
</td>
</tr>
<?php $i++; endforeach; } ?>    

    </tbody> 
        <tfoot style="display: none;">
            <th> Sr.No.</th> 
            <th> Order Info </th> 
            <th> Sender Info </th>
            <th> Account Info </th>
            <th> Opt / Wallet / Mode </th>
            <th> Amt / TID </th>
            <th> Charge / Com / TDS </th>
            <th> Status Info</th>
            <th> Action </th>

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



<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?> 
<?php $this->load->view('includes/dataTable');?>
<?php $this->load->view('includes/common_session_popup');?>
<?php $this->load->view('includes/make_complaint');?> 

<script type="text/javascript">
    function putintodate(id){
        $('#datepicker1').val(id);
}
</script>
<script>
var tab_text;
var data_type = 'data:application/vnd.ms-excel';


function CreateHiddenTable(ListOfMessages)
{
    var TableMarkUp='<table id="myModifiedTable" class="visibilityHide">';

    TableMarkUp+='<thead><tr><th>Sr. No.</th><th>Order Id</th><th>Order date and time</th><th>Sender Name</th><th>Sender Mobile</th><th>KYC Status</th><th>Bank name</th><th>Benificiary name</th><th>Account Number</th><th>Operator</th><th>Wallet</th><th>Mode</th><th>Amount</th><th>TID</th><th>Charges</th><th>Commission</th><th>TDS</th><th>Status</th><th>Status on</th></tr></thead>';
    TableMarkUp+="<tbody>";
    for(i=0; i<ListOfMessages.length; i++)
    {
        TableMarkUp += '<tr>';
        for(j=0;j<ListOfMessages[i].length; j++)
        {
            TableMarkUp += '<td>' + ListOfMessages[i][j] +'</td>';
        }
        TableMarkUp+='</tr>';
    }
    TableMarkUp += "</tbody></table>";
    $('#MessageHolder').append(TableMarkUp);
}

function fnExcelReport() 
{
  
    var Messages = [
        <?php $i=1; foreach ($trans_list as $key=>$value) { ?>
            [
                "<?=$i++?>",
                "<?=$value['sys_orderid']?>",
                "<?=date('d-M-Y',strtotime($value['add_date']))?> (<?= date('h:i:s A',strtotime($value['add_date']))?>)",
                "<?=$value['s_name']?>",
                "<?=$value['s_mobile']?>",
                "<?=kycstatus($value['kyc_status'])?>",
                "<?=$value['bankname']?>",
                "<?=$value['b_name']?>",
                "<?=$value['ac_number']?>",
                "<?=$value['operatorname']?>",
                "<?=$value['apiname']=='paytm'?'DMT 1st':'DMT 2nd'?>",
                "<?=$value['mode']?>",
                "<?=$value['amount']?>",
                "<?=$value['ptm_rrn']?>",
                "<?=$value['sur_charge']?>",
                "<?=($value['ag_comi']+$value['ag_tds']);?>",
                "<?=$value['ag_tds']?>",
                "<?=statusbtn_c($value['status'])?>",
                <?php if($value['add_date']){
                    $datetime  = $value['status_update'] ? $value['status_update'] : $value['add_date'];
                } ?>
                "<?=date('d-M-Y',strtotime($datetime))?> (<?=date('h:i:s A',strtotime($datetime))?>)"
            ],
        <?php } ?>
    ];
    // var ListOfMessages = Messages.split("\n");

    CreateHiddenTable(Messages);

        tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';

        tab_text = tab_text + '<x:Name>Error Messages</x:Name>';

        tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
        tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';

        tab_text = tab_text + "<table border='1px'>";
        tab_text = tab_text + $('#myModifiedTable').html();;
        tab_text = tab_text + '</table></body></html>';

        data_type = 'data:application/vnd.ms-excel';

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
            if (window.navigator.msSaveBlob) {
                var blob = new Blob([tab_text], {
                    type: "application/csv;charset=utf-8;"
                });
                navigator.msSaveBlob(blob, 'MoneyTransferReport.xls');
            }
        } else { 
          $('#testAnchor')[0].click()
        }
        $('#MessageHolder').html("");
}

$($("#testAnchor")[0]).click(function(){ 
      $('#testAnchor').attr('href', data_type + ', ' + encodeURIComponent(tab_text));
      $('#testAnchor').attr('download', 'MoneyTransferReport.xls');
});

</script>
</body>
</html>