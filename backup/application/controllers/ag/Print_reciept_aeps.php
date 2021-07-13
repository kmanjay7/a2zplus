<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Print_reciept_aeps extends CI_Controller{
	public function __construct(){
		parent::__construct();
         
		}



public function index(){ 

        $apptype = 'web';
        if($this->input->get('apptype')){ 
          $apptype = 'app'; 
        }




        if($apptype =='web'){
          $this->load->library('session');   
                       agsession_check(); 
        }//type web end here        




        $data['title'] = 'Print AEPS Receipt';
        $data['folder'] = 'ag/'; 
        





      if($apptype =='web'){
      $userid = $this->session->userdata('id');   
      $data['agentname'] = $this->session->userdata('ownername');
      $data['agentmobileno'] = $this->session->userdata('mobileno');
      $data['outletname'] = $this->session->userdata('firmname');
      $data['agentaddress'] = $this->session->userdata('address');
      }else{

      }
        







       if( $this->input->get('utp') ){
         
        
            if($apptype =='web'){
             $where['a.userid'] = $this->session->userdata('id');
           }else if($apptype =='app'){
             $where['md5(a.userid)'] = $this->input->get('id');
           }


           $where['md5(a.sys_orderid)'] = $this->input->get('utp'); 

           $select = 'a.id,a.userid, a.sys_orderid, a.aadharuid , a.mobileno, a.mode, b.bankname as bank, a.banktxnid, a.amount, a.ag_comi, a.ag_tds, a.api_status_on, a.api_orderid, a.add_date,a.status,a.complaint, c.ownername, c.mobileno, c.firmname, c.address,c.uniqueid,a.api_response '; 
            $from = 'dt_aeps as a';

            $join[0]['table'] = 'dt_bank as b';
            $join[0]['joinon'] = 'a.bankname = b.bank_iin' ;  
            $join[0]['jointype'] = 'LEFT'; 

            $join[1]['table'] = 'dt_users as c';
            $join[1]['joinon'] = 'a.userid = c.id' ;  
            $join[1]['jointype'] = 'LEFT'; 

            $groupby = null;
            $orderby = null;
            $getorcount = 'get';

           
            $slipData = $this->c_model->joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, null, null, $getorcount );
          
          
         
        }else{ $slipData = array(); }



        //check valid data 
        if(empty($slipData)){ redirect( ADMINURL ); }

        $data['slipData'] = $slipData[0];
        $data['apptype'] = $apptype;

        $pagename = 'print_reciept_aeps';
        

        if($apptype=='web'){
           agview( $pagename ,$data); 
        }else{ 

           

          $html =  $this->printrp($data);
          
          header('Content-Type: text/html; charset=utf-8');
          require_once APPPATH.'/third_party/mpdf/vendor/autoload.php'; 

          $mpdf = new \Mpdf\Mpdf();  
          $filename = $slipData[0]['sys_orderid'].".pdf";   
          $mpdf->SetFont('Arial');
          $mpdf->SetFont('Helvetica');
          $mpdf->SetFont('sans-serif');
          $mpdf->SetTitle($filename); 

          $mpdf->setAutoTopMargin = 'pad';
          $mpdf->autoMarginPadding = 10;

          $mpdf->WriteHTML($html,2);
          // download!  use D , for view use I
          $mpdf->Output($filename, "I"); 

          exit; 
        }
 
        
       
	}


public function printrp( $data ){
     $slipData = $data['slipData'];
     $outletname = $slipData['firmname'];
     $agentname = $slipData['ownername'];
     $agentmobileno = $slipData['mobileno']; 
   
$html = '<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
 
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,400,400i,700,700i,800,800i,900,900i&display=swap">
</head>

<body>


<div style="display: block; margin:auto; margin-top: 41px; width: 668px;float:left;">
<div style="width: 668px; float: left;clear:both; height:61px;">
<table style="width:100%; border:1px solid #111;height:61px;" border="0" cellspacing="2" cellpadding="5">
<tr>
<td width="25%" style="border-right:1px solid #111">
<img style="width: 166px;" src="'.ADMINURL.'assets/images/logo.png" alt="">
</td>


<td width="38%" style="border-right:1px solid #111">
<h4 style="color: #000000;font-size: 16px;font-weight: 700; padding: 0px; margin: 0px;  margin-bottom: 4px;"> <center>Transaction Receipt</center> </h4>
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding: 0px; margin: 0px">Thank you for transacting at DigiCash India</h4>
</td >

<td width="25%" >
<center><img style="width: 176px; margin-top: 0px;" src="'.ADMINURL.'assets/images/icici_bank_png.png" alt=""></center>
</td>
</tr>

</table>
</div>


<div style="width: 668px; float: left; margin-top: 10px; clear:both;height:82px;">
<table style="width:100%; border:1px solid #111; text-align:center" border="0" cellspacing="2" cellpadding="5">
<tr> 
<td style="width: 49.8%; border-bottom:1px solid #111;border-right:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px; margin: 0px; text-align: center;"> Agent Details </h4> 
</td> 
<td style="width: 50% ; border-bottom:1px solid #111" >
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;margin: 0px; text-align: center;"> Service Description </h4> 
</td> 
</tr>

<tr> 
<td style=" border-right:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 600;padding: 0px;  margin: 0px;  text-align: center;"> '.$outletname.' </h4> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> '.$agentname.' - '.$agentmobileno.' </h4> 
</td> 
<td style="">
<h4 style="color: #000000;font-size: 12px;font-weight: 600;padding: 0px; margin: 0px;text-align: center;"> AEPS </h4> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;">';
                        if($slipData['mode']=='WAP'){ $html .= 'CASH WITHDRAWAL';}
                        else if($slipData['mode']=='BAP'){ $html .= 'BALANCE INQUIRY';}
                        else if($slipData['mode']=='SAP'){ $html .= 'MINI STATEMENT';}

$html .= ' 
</h4>  
</td> 
</tr>

</table>
</div>



<div style="width: 668px; float: left; margin-top: 5px;clear:both;height:67px;">
<table style="width:100%; border:1px solid #111;text-align:center" border="0" cellspacing="2" cellpadding="5">
<tr> 
<td style="width: 25%;border-right:1px solid #111;border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;margin: 0px; text-align: center;"> Order ID </h4> 
</td> 
<td style="width: 25%;border-right:1px solid #111;border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;margin: 0px; text-align: center;"> Date & Time </h4> 
</td> 
<td style="width: 25%;border-right:1px solid #111;border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;margin: 0px; text-align: center;"> Transaction ID </h4> 
</td>
<td style="border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
margin: 0px; text-align: center;"> Transaction Status </h4> 
</td>
</tr>';
 

$html .= ' <tr> 
<td style="border-right:1px solid #111"> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> '.$slipData['sys_orderid'].' 
</h4> 
</td> 
<td style="border-right:1px solid #111"> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> 
'.date('d-M-Y h:i:s A',strtotime($slipData['add_date'])).' 
</h4>  
</td> 

<td style="border-right:1px solid #111"> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> 
'.$slipData['banktxnid'].'
</h4>  
</td> 

<td> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> 
'.strtoupper($slipData['status']).'
</h4>  
</td>   
</tr>';


$html .= '</table>
</div>

<div style="width: 668px; float: left; margin-top: 5px;clear:both;height:77px;">
<table style="width:100%; border:1px solid #111;text-align:center" border="0" cellspacing="2" cellpadding="5">
<tr> 
<td style="width: 24.8%;border-right:1px solid #111;border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
margin: 0px; text-align: center;"> Bank Name </h4> 
</td> 
<td style="width: 25%;border-right:1px solid #111;border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
margin: 0px; text-align: center;"> Aadhaar Number </h4> 
</td> 
<td style="width: 25%;border-right:1px solid #111;border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
margin: 0px; text-align: center;"> Txn Amount </h4> 
</td> 
<td style="width: 25%;border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
margin: 0px; text-align: center;"> Closing Account Balance </h4> 
</td> 

</tr>

<tr> 
<td style="border-right:1px solid #111;">
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;margin: 0px; margin-top: 10px; text-align: center;"> '.$slipData['bank'].'
</h4> 
</td> 
<td style="border-right:1px solid #111;"> 
<h4 style="color:#000000;font-size:11px;font-weight:500;padding:0px;
margin: 0px;margin-top: 10px;text-align: center;"> 
'.$slipData['aadharuid'].'
</h4>  
</td>

<td style="border-right:1px solid #111;"> 
<h4 style="color:#000000;font-size:11px;font-weight:500;padding:0px;
margin: 0px;margin-top: 10px;text-align: center;"> 
'.$slipData['amount'].'
</h4>  
</td>  
 
<td> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> ';

          $arr = json_decode($slipData['api_response'],true ); 
$html .=  $arr['data']['balance'];
$html .= '</h4>  
</td>  

</tr>

</table>
</div>




<div style="width: 668px; float: left; margin-top: 0px;clear:both;height:96px;">
<table style="width:100%; border:1px solid #111;font-size: 10px;" border="0" cellspacing="2" cellpadding="5">
<tr> 
<td style="">
<p><b>Please Note:</b> </p>
<ol style="margin-left: -25px;">
<li style="color: #000;">  Customer Convenience Fee (CCF) = Rs. 0.00 inclusive of GST.</li> 
<li style="color: #000;">  This is a system generated receipt hence does not require any signature.</li> 
<li style="color: #000;"> For Feedback, Comments, Suggestions or Compliments - Do write to <b> info@mydigicash.in</b>.</li>
</ol>
</td>  
</tr>  
</table>
</div>



<br> <br> <br>   
<div style="clear: both; margin-bottom: 20px">&nbsp;</div>

</div></body></html> '; 
return $html;
  }

    

}
?>