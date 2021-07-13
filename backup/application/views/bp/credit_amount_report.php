<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/sweet.css');?>">

<div class="content">
        <div class="container"> 
        

            <section class="cash-area" >
                <div class="report-area">
                    <div class="report-area-t">
                        <div class="row">


                            <div class="col-lg-4 col-md-4 col-4 ">
                                <div class="top-10-txt">
                                    <h2> Recent Report</h2>

                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-8">
                                <?=form_open( ADMINURL.$folder.'/'.$pagename,array('method'=>'get'));?>
                                <div class="row"> 
                                    <div> 
                                        <span class="date-h">Date From</span>
                                    </div>

                                    <div class="col"> 
                                        <div class="input-group">
                                        <input id="datepicker" class="form-control date-font" placeholder="Start Date" onchange="putintodate(this.value)" type="text" name="start_date">
                                        </div>
                                    </div>

                                    <div>
                                        <span class="date-h">To</span>
                                    </div>
                                    <div class="col"> 
                                        <div class="input-group"> 
                                            <input id="datepicker1" class="form-control date-font"  placeholder="End Date" type="text" name="to_date">
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="top-10-txt down_btn"> 
                                            <button type="submit" class="export ap_bg">Filter</button> 
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="top-10-txt down_btn"> 
                                           <button onclick="fnExcelReport();" class="export">Export</button>
                                    <a href="#" id="testAnchor"></a>
                                    <div id='MessageHolder'></div> 
                                        </div>
                                    </div>
                                </div>
<?=form_close();?>

                            </div>

                            <div class="border-tabl">

                            </div>

                            <div class="col-lg-12 col-md-12 col-12 ">

                                <div class="report-table pan_table">

                                    <table class="table ">
                                        <thead>

                                            <tr style="background: transparent">
                                                <th> Sr.No./ Order ID
                                                </th>
                                                <th> Credit User
                                                    <span class="su_head">( Name/ User ID/ User Type )</span>
                                                </th>
                                                <th> Debit User
                                                    <span class="su_head">( Owner Name/ User ID/ User Type )</span>
                                                </th>

                                                <th> Payment Type/ Amount </th>
                                                <th> Date/ Time </th>
                                                <th>Old Balance/ New Bal. </th>
                                                <th>Remarks </th>


                                            </tr>
                                        </thead>
                                        <tbody>

<?php if(!empty($trans_list)){ $i = 1; 
    foreach($trans_list as $key=>$value ){?>

    <tr>
        <td>
            <p> <?=$i;?></p>
             <p> <?=$value['referenceid'];?></p>
    
        </td>
        <td>
            <p class="name"><?=$value['cname'];?></p>
            <p>/<?=$value['cuid'];?></p>
            <p>/<?=$value['cusertype'];?></p>
    
    
        </td>
        <td>
            <p class="name"><?=$value['dname'];?></p>
            <p>/<?=$value['duid'];?></p>
            <p>/<?=$value['dusertype'];?></p>
        </td>
        <td>
            <p><?=$value['paymode'];?></p>
    
            <p><?=$value['amount'];?></p>
    
        <td>
            <p><?=date('d-M-Y',strtotime($value['add_date']));?></p>
            <p><?=date('h:i A',strtotime($value['add_date']));?></p>
    
    
        </td>
        <td>
            <p><?=$value['beforeamount'];?></p>
            <p>/<?=$value['finalamount'];?></p>
        </td>
        <td class="messg">
        <a href="#" data-toggle="tooltip" data-placement="right"
                title="<?=$value['remark'];?>">Show Remarks</a>
    
        </td>
    
    </tr>
                                            
   <?php $i++; } }else{ echo '<tr><td colspan="7"><center>No Record in Our Database</center></td></tr>';} ?>

</tbody>
</table>

                                </div>
                                <div class="pagination_area text-center">

                                    <ul class="pagination">
                                        <?= $pagination;?>
                                    </ul>

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

    TableMarkUp+='<thead><tr><th>Sr. No.</th><th>Order Id</th><th>Order date and time</th><th>Credit User(Name)</th><th>Credit User(User ID)</th><th>Credit User(User Type)</th><th>Debit User(Name)</th><th>Debit User(User ID)</th><th>Debit User(User Type)</th><th>Payment Type</th><th>Transaction Type</th><th>Amount</th><th>Old Balance</th><th>New Balance</th><th>Remarks</th></tr></thead>';
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
        <?php $i=1; foreach ($trans_list as $value){?>
            [
                "<?=$i++?>",
                "<?=$value['referenceid']?>",
                "<?=date('d-M-Y',strtotime($value['add_date']))?> (<?= date('h:i:s A',strtotime($value['add_date']))?>)", 
                "<?=$value['cname']?>",                
                "<?=$value['cuid']?>",
                "<?=$value['cusertype']?>",
                "<?=$value['dname']?>",
                "<?=$value['duid']?>",
                "<?=$value['dusertype']?>",
                "<?=$value['paymode']?>",
                "<?=ucwords($value['credit_debit']);?>",
                "<?=$value['amount']?>", 
                "<?=$value['beforeamount']?>",
                "<?=$value['finalamount']?>",
                "<?=$value['remark']?>",                
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
                navigator.msSaveBlob(blob, 'CreditReport.xls');
            }
        } else { 
          $('#testAnchor')[0].click()
        }
    $('#MessageHolder').html("");
}

$($("#testAnchor")[0]).click(function(){ 
      $('#testAnchor').attr('href', data_type + ', ' + encodeURIComponent(tab_text));
            $('#testAnchor').attr('download', 'CreditReport.xls');
});

</script>
</body>
</html>