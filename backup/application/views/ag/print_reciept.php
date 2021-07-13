     
        <div id="preloader">
                <div id="status">&nbsp;</div>
              </div>


    <div class="<?php if($apptype=='web'){ echo 'content'; }?>">
         <div class="container">
            <section class="cash-area ">
               <div class="cash-payment ">
                  <div class="cash-heading">
                     <h2><?=$title;?>
                     <button id="hrefPrint" style="float: right; margin-right: 10px; margin-top: -5px;" class="btn btn-sm btn-success pull-right">Print Receipt</button>   
                     </h2> 
                  </div>

<div class="col-lg-12" id="printTable" style="width: 780px; margin:0 auto;">
<!-- -----------------  Print reciept script start ------------   -->
 <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> My Digi Cash </title>
    <link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,400,400i,700,700i,800,800i,900,900i&display=swap'>
    <style>
        body {
            margin: 0px;
            padding: 0px; 
            font-family: Arial, Helvetica, sans-serif;
        }
        
        a {
            font-family: Arial, Helvetica, sans-serif;
        }
        
        td {
            color: #000000;
            font-weight: 400;
            font-size: 12px;
            padding: 6px 10px; text-align: center;
        } 
        #tableid tbody td{ padding-left: 21px; } 
    </style>
</head>
 <?php $agentarray = $slipData[0];?>
<body>

    <?php //print_r($slipData);?>
    <div style="display: block; margin: auto; margin-top: 41px; width: 668px">
        <div style="width: 668px; float: left;">
        <table style="width:100%; border:2px solid #111" border="1" cellspacing="0" cellpadding="0">
            <tr>
                <td width="25%">
                    <img style="width: 176px;" src="<?=ADMINURL.'assets/'?>images/logo.png" alt="">
                </td>

            
                <td width="33%">
                    <h4 style="color: #000000;font-size: 16px;font-weight: 700; padding: 0px; margin: 0px;  margin-bottom: 4px; text-align: center;"> Transaction Receipt </h4>
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding: 0px; margin: 0px">Thank you for transacting at DigiCash India</h4>
                </td >
            
                <td width="25%">
                   <img style="width: 176px; margin-top: -6px; float: right;" src="<?=ADMINURL.'assets/'?>images/paytm-bank.png" alt="">
                </td>
            </tr>

        </table>
        </div>


        <div style="width: 668px; float: left; margin-top: 12px">
        <table style="width:100%; border:2px solid #111" border="1" cellspacing="0" cellpadding="0">
            <tr> 
                <td style="width: 50%">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Agent Details </h4> 
                </td> 
                <td>
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Service Description </h4> 
                </td> 
            </tr>

             <tr> 
                <td>
                    <h4 style="color: #000000;font-size: 12px;font-weight: 600;padding: 0px;  margin: 0px;  text-align: center;"> <?=$outletname;?> </h4> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; margin-top: 10px; text-align: center;"> <?=$agentname;?> - <?=$agentmobileno;?> </h4> 
                </td> 
                <td>
                   <h4 style="color: #000000;font-size: 12px;font-weight: 600;padding: 0px; margin: 0px;text-align: center;"> DOMESTIC MONEY TRANSFER</h4> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; margin-top: 10px; text-align: center;"> 
                    <?=$agentarray['mode'];?> TRANSFER 
                    </h4>  
                </td> 
            </tr>

        </table>
        </div>


        <div style="width: 668px; float: left; margin-top: 12px">
        <table style="width:100%; border:2px solid #111" border="1" cellspacing="0" cellpadding="0">
            <tr> 
                <td style="width: 50%">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Sender Details </h4> 
                </td> 
                <td>
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Beneficiary Bank Details </h4> 
                </td> 
            </tr>

             <tr> 
                <td>
                    <h4 style="color: #000000;font-size: 12px;font-weight: 600;padding: 0px;  margin: 0px;  text-align: center;"> <?=strtoupper($agentarray['s_name']);?> </h4> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; margin-top: 10px; text-align: center;"> <?=$agentarray['s_mobile'];?> - NON KYC </h4> 
                </td> 
                <td>
                   <h4 style="color: #000000;font-size: 12px;font-weight: 600;padding: 0px; margin: 0px;text-align: center;"> <?=$agentarray['b_name'];?> - <?=$agentarray['ac_number'];?></h4> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;margin: 0px; margin-top: 10px; text-align: center;"> 
                    <?=$agentarray['bankname'];?> - <?=$agentarray['ifsc_code'];?> 
                    </h4>  
                </td> 
            </tr>

        </table>
        </div>




<div style="width: 668px; float: left; margin-top: 12px">
        <table style="width:100%; border:2px solid #111" border="1" cellspacing="0" cellpadding="0">
            <tr> 
                <td style="width: 25%">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Order ID </h4> 
                </td> 
                <td style="width: 25%">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Date & Time </h4> 
                </td> 
                <td style="width: 25%">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Transaction ID </h4> 
                </td>
                <td>
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Transaction Status </h4> 
                </td>
            </tr>

<?php $txnamount = 0; $success_amt = 0; $sur_charge = 0;
foreach ($slipData as $key => $bvalue) {
        if( $bvalue['status'] == 'SUCCESS' ){
        $success_amt = $success_amt + $bvalue['amount'];
        $sur_charge = $sur_charge + $bvalue['sur_charge'] ;
        } 
        $txnamount = $txnamount + $bvalue['amount']; 
    ?>
             <tr> 
                <td> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; margin-top: 10px; text-align: center;"> <?=$bvalue['orderid'];?> 
                     </h4> 
                </td> 
                <td> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; margin-top: 10px; text-align: center;"> 
                    <?= date('d-m-Y h:i:s A',strtotime($bvalue['add_date']));?> 
                    </h4>  
                </td> 

                 <td> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; margin-top: 10px; text-align: center;"> 
                    <?=$bvalue['ptm_rrn'];?>
                    </h4>  
                </td> 

                 <td> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; margin-top: 10px; text-align: center;"> 
                    <?= strtoupper($bvalue['status']);?> 
                    </h4>  
                </td>   
            </tr>
<?php } $amountinwords = ( ($success_amt + $sur_charge) );  ?>
        </table>
        </div>

<div style="width: 668px; float: left; margin-top: 12px">
        <table style="width:100%; border:2px solid #111" border="1" cellspacing="0" cellpadding="0">
            <tr> 
                <td style="width: 25%">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Transaction Amount </h4> 
                </td> 
                <td style="width: 25%">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Success Amount </h4> 
                </td> 
                <td style="width: 25%">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Surcharge Amount </h4> 
                </td> 
                <td style="width: 25%">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Total Amount </h4> 
                </td>
                 
            </tr>

             <tr> 
                <td> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; margin-top: 10px; text-align: center;"> <?=$txnamount;?>
                     </h4> 
                </td> 
                <td> 
                     <h4 style="color:#000000;font-size:11px;font-weight:500;padding:0px;
                    margin: 0px;margin-top: 10px;text-align: center;"> 
                   <?=$success_amt;?>
                    </h4>  
                </td> 

                <td> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; margin-top: 10px; text-align: center;"> <?=$sur_charge;?>
                     </h4> 
                </td> 
                <td> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; margin-top: 10px; text-align: center;"> 
                   <?=$amountinwords;?>
                    </h4>  
                </td>  
                 
            </tr>

        </table>
        </div>


         <div style="width: 668px; float: left; margin-top: 12px">
        <table style="width:100%; border:2px solid #111" border="1" cellspacing="0" cellpadding="0">
            <tr> 
                <td style="width: 50%">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px; margin: 0px; text-align: center;"> Total Amount In Words </h4> 
                </td>  
            </tr>

             <tr> 
                <td> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;margin: 0px; text-align: center;"> Rupees <?=convert_number_to_words( $amountinwords );?> Only </h4> 
                </td>  
            </tr>

        </table>
        </div>




<div style="width: 668px; float: left; margin-top: 12px">
<table style="width:100%; border:2px solid #111" border="1" cellspacing="0" cellpadding="0">
    <tr> 
        <td style="text-align: left;">
        <p><b>Please Note:</b> </p>
        <ol style="margin-left: -25px;">
        <li style="font-size: 12px;font-weight: 500;color: #000;">  This is a system generated receipt hence does not require any signature.</li> 
        <li style="font-size: 12px;font-weight: 500;color: #000;"> For Feedback, Comments, Suggestions or Compliments - Do write to <b> info@mydigicash.in</b>.</li>
        </ol>
        </td>  
    </tr>  
</table>
</div>



 <br> <br> <br>   
<div style="clear: both; margin-bottom: 20px">&nbsp;</div>
 
 </div></body></html></div> 

<!-- -----------------  Print reciept script end ------------   -->


               </div>
            </section>
             
         </div>
      </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
    <script type="text/javascript">
        $(function () {
            $("#hrefPrint").click(function () {
                var contents = $("#printTable").html();
                var frame1 = $('<iframe />');
                frame1[0].name = "frame1";
                frame1.css({ "position": "absolute", "top": "-1000000px" });
                $("body").append(frame1);
                var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
                frameDoc.document.open();
                //Create a new HTML document.
                frameDoc.document.write('<html><head><title><?php echo $title;?></title>');
                frameDoc.document.write('</head><body>');
                //Append the external CSS file.
                frameDoc.document.write('<link href="<?php echo ADMINURL;?>assets/css/printslip.css" rel="stylesheet" type="text/css" />');
                //Append the DIV contents.
                frameDoc.document.write(contents);
                frameDoc.document.write('</body></html>');
                frameDoc.document.close();
                setTimeout(function () {
                    window.frames["frame1"].focus();
                    window.frames["frame1"].print();
                    frame1.remove();
                }, 500);
            });
        });
    </script>


<script type="text/javascript">
  function printData(){ 
   var divToPrint=document.getElementById("printTable");
   $(".btnPrint").printPage()
  }
</script>

<?php if($apptype=='web'){ $this->load->view( $folder.'includes/footer'); }?>
<?php $this->load->view( $folder.'includes/alljs');?> 
   

   <?php if($this->input->get('loader')=='yes'){?>   
   <script>
    $(window).on('load', function() { 
    $('#status').delay(40001).fadeOut(); 
    $('#preloader').delay(40001).fadeOut('slow'); 
    setTimeout(function(){
    window.location.href='<?=ADMINURL.'ag/Print_reciept?utp='.$this->input->get('utp');?>';
    },40000);
   
    $('body').delay(4000).css({'overflow':'visible'});
  })</script>
<?php } ?>

    </body>

</html>