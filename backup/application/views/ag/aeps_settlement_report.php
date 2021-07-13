<style type="text/css">div#example1_wrapper > div.row{ width: 100% } #example1_length,#example1_filter{ display: none; } .filter-area { padding: 15px 41px 0px 41px;
}</style>
<div class="content">
        <div class="container">

            <section class="cash-area">

                <div class="cash-payment">
                    <div class="cash-heading">
                        <h2><?=$title;?> </h2>
                    </div>
                    

 <div class="filter-area">
       
 <form action="<?=ADMINURL.$folder.'/'.$pagename;?>">
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
        <?=form_dropdown(array('name'=>'transaction','class'=>'form-control'),[''=>'All','SUCCESS'=>'Success','FAILED'=>'Failed','PENDING'=>'Pending','WALLET'=>'Wallet Transfer','BANK'=>'Bank Transfer','IMPS'=>'IMPS','NEFT'=>'NEFT'],set_value('transaction',$transaction));?>
    </div>


    <div class="co-12 col-md-2 col-lg-2">
        <label for=""> Select Filter </label>
        <?=form_dropdown(array('name'=>'filterby','class'=>'form-control'),[''=>'All','orderid'=>'Order ID','acno'=>'Account Number'],set_value('filterby',$filterby));?>
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
                                     <button onclick="fnExcelReport();" class="export">Export</button>
                                    <a href="#" id="testAnchor"></a>
                                    <div id='MessageHolder'></div>
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
                        <th> Settlement Type  </th>
                        <th> Wallet / Bank Info </th>
                        <th> Amt / Charge </th>
                        <th> Status Info </th> 
                        <th> Action </th> 
                    </tr>
                </thead>


<tbody>
    <?php if(!empty($list)){  $i =1; 
    foreach ($list as $key => $value) { ?>
<tr>
<td style="display: none;"><?=$i;?></td>
<td> 
<p><?=$value['orderid']?$value['orderid']:'NA';?></p>
<p><?php echo $date = date('d-m-Y',strtotime($value['add_date']));?> <?=date('h:i:s A',strtotime($value['add_date']));?></p>
</td>

<td> <p> <?=$value['remark'];?> </p> 
 <p> <b><?=$value['mode'];?></b></p> 
</td>


<td>
<p><?=$value['bankinfo'];?></p>
<p><?=$value['accountno'];?></p> 
</td>

<td>
<p> <b><?=$value['amount'];?></b></p>
<p> <?=$value['surcharge'];?></p> 
</td>

<td> 
<p class="<?=statusbtn_c($value['statushtm'],'class');?>"> <span></span> <?=statusbtn_c($value['statushtm']);?> </p>
<p> <?=$value['status_on'];?></p> 
</td>
 

<td><button id="ap2" class="update-btn w-101 <?=complaint_status($value['complaint'],'cl')?> cp-<?=$value['id'];?>" <?php if($value['complaint']==''){?> onclick="complaint('<?=$value['id'];?>','aepssettle')"> <?php }else{ echo 'disabled';}?> <span id="plwait-<?=$value['id'];?>"><?=complaint_status($value['complaint'],'')?></span> </button>
</td>
</tr>
<?php $i++; } }?>

</tbody>

<tfoot style="display: none;">                                                             
                    <tr style="background: transparent">
                        <th style="display: none;"> id </th>
                        <th> Order Info</th>
                        <th> Settlement Type  </th>
                        <th> Wallet / Bank Info </th>
                        <th> Amt / Charge </th>
                        <th> Status Info </th> 
                        <th> Action </th> 
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

    TableMarkUp+='<thead><tr><th>Sr. No.</th><th>Order Id</th><th>Order date and time</th><th>Settlement Remark</th><th>Mode</th><th>Bank Info.</th><th>Account No.</th><th>Amount</th><th>Charges</th><th>Status</th><th>Status on</th><th>Remarks</th></tr></thead>';
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
        <?php $i=1; foreach ($list as $value) { ?>
            [
                "<?=$i++?>",
                "<?=$value['orderid']?>",
                "<?=date('d-M-Y',strtotime($value['add_date']))?> (<?= date('h:i:s A',strtotime($value['add_date']))?>)",
                "<?=$value['remark']?>",
                "<?=$value['mode']?>",
                "<?=$value['bankinfo']?>",
                "<?=$value['accountno']?>",
                "<?=$value['amount']?>",
                "<?=$value['surcharge']?>",
                "<?=statusbtn_c($value['status'])?>",
                <?php if($value['add_date']){
                    $datetime  = $value['status_on'] ? $value['status_on'] : $value['add_date'];
                } ?>
                "<?=date('d-M-Y',strtotime($datetime))?> (<?=date('h:i:s A',strtotime($datetime))?>)",
                "<?=$value['respmsg']?$value['respmsg']:'Status is pending'?>"
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
                navigator.msSaveBlob(blob, 'AepsSettlementReport.xls');
            }
        } else {
          $('#testAnchor')[0].click()
        }
        $('#MessageHolder').html("");
}

$($("#testAnchor")[0]).click(function(){
      $('#testAnchor').attr('href', data_type + ', ' + encodeURIComponent(tab_text));
      $('#testAnchor').attr('download', 'AepsSettlementReport.xls');
});

</script>
</body>
</html>