<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Print_reciept extends CI_Controller{
	public function __construct(){
		parent::__construct();  
		}

 

public function index(){ 

        $apptype = 'web';
        if($this->input->get('apptype')){ 
          $apptype = 'app'; 
        }

        $data['apptype'] = $apptype;
        $data['id'] = '';

 
 


if($apptype =='web'){
  $this->load->library('session');   
               agsession_check();   
}//type web end here        




        $data['title'] = 'Print Reciept';
        $data['folder'] = 'ag/'; 
        





        if( $apptype == 'app' ){
          $agentid = $this->input->get('id');
          $userdb = $this->c_model->getSingle( 'dt_users',['md5(id)'=>$agentid],'id,ownername,mobileno,firmname,address');
          $userid = $userdb['id'];   
          $data['agentname'] = $userdb['ownername'];
          $data['agentmobileno'] = $userdb['mobileno'];
          $data['outletname'] = $userdb['firmname'];
          $data['agentaddress'] = $userdb['address'];
        }else{
          $userid = $this->session->userdata('id');   
          $data['agentname'] = $this->session->userdata('ownername');
          $data['agentmobileno'] = $this->session->userdata('mobileno');
          $data['outletname'] = $this->session->userdata('firmname');
          $data['agentaddress'] = $this->session->userdata('address');
        }
        







       if( $this->input->get('utp') && $userid ){
         
        $where['userid'] = $userid;
        $where['md5(sys_orderid)'] = $this->input->get('utp');

        $trwhere['dt_dmtlog.userid'] = $userid;  
        $trwhere['md5(dt_dmtlog.sys_orderid)'] = $this->input->get('utp'); 
        


            $select = 'dt_dmtlog.sys_orderid,dt_dmtlog.id,dt_sender.name as s_name, dt_sender.mobile as s_mobile, dt_benificiary.name as b_name, dt_benificiary.ac_number, dt_dmtlog.mode, dt_dmtlog.apiname, dt_dmtlog.orderid, dt_dmtlog.amount, dt_bank.bankname, dt_dmtlog.add_date, dt_dmtlog.status, dt_dmtlog.sur_charge, dt_dmtlog.commission, dt_dmtlog.tds, dt_dmtlog.banktxnid, dt_dmtlog.sur_charge ,dt_benificiary.ifsc_code,dt_dmtlog.ptm_rrn  '; 
            $from = 'dt_dmtlog';
            $jointable = 'dt_sender';
            $joinon = 'dt_dmtlog.sender_id = dt_sender.id';
            $jointype = 'LEFT'; 

            $jointable3 = 'dt_benificiary' ;
            $joinon3 = 'dt_benificiary.id = dt_dmtlog.benifi_id' ;  
            $jointype3 = 'LEFT';

            $jointable4 = 'dt_bank' ;
            $joinon4 = 'dt_bank.id = dt_benificiary.bankname' ;  
            $jointype4 = 'LEFT';

            $groupby = null;
            $orderby = 'dt_dmtlog.id DESC' ; 
            $getorcount = 'get';

           
            $slipData = $this->c_model->joindata( $select, $trwhere, $from, $jointable, $joinon, $jointype, $groupby, $orderby, $jointable3, $joinon3, $jointype3, null, null, $getorcount, $jointable4, $joinon4, $jointype4  );

         
        }else{ $slipData = array(); }


        //check valid data 
        if(empty($slipData)){ redirect( ADMINURL ); }



        $data['slipData'] = $slipData;
        $data['apptype'] = $apptype;

        if($apptype=='web'){
           agview('print_reciept',$data); 
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
     $outletname = $data['outletname'];
     $agentname = $data['agentname'];
     $agentmobileno = $data['agentmobileno']; 

     $agentarray = $slipData[0];
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
<table style="width:100%; border:1px solid #111;height:61px;" border="0" cellspacing="5" cellpadding="5">
<tr>
<td width="25%" style="border-right:1px solid #111">
<img style="width: 176px;" src="'.ADMINURL.'assets/images/logo.png" alt="">
</td>


<td width="33%" style="border-right:1px solid #111">
<h4 style="color: #000000;font-size: 16px;font-weight: 700; padding: 0px; margin: 0px;  margin-bottom: 4px;"> <center>Transaction Receipt</center> </h4>
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding: 0px; margin: 0px">Thank you for transacting at DigiCash India</h4>
</td >

<td width="25%">
<img style="width: 176px; margin-top: -6px; float: right;" src="'.ADMINURL.'assets/images/paytm-bank.png" alt="">
</td>
</tr>

</table>
</div>


<div style="width: 668px; float: left; margin-top: 12px; clear:both;height:82px;">
<table style="width:100%; border:1px solid #111; text-align:center" border="0" cellspacing="5" cellpadding="5">
<tr> 
<td style="width: 32%; border-bottom:1px solid #111;border-right:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px; margin: 0px; text-align: center;"> Agent Details </h4> 
</td> 
<td style="width: 25% ; border-bottom:1px solid #111" >
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;margin: 0px; text-align: center;"> Service Description </h4> 
</td> 
</tr>

<tr> 
<td style="width: 25% ; border-right:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 600;padding: 0px;  margin: 0px;  text-align: center;"> '.$outletname.' </h4> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> '.$agentname.' - '.$agentmobileno.' </h4> 
</td> 
<td style="">
<h4 style="color: #000000;font-size: 12px;font-weight: 600;padding: 0px; margin: 0px;text-align: center;"> DOMESTIC MONEY TRANSFER</h4> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> 
'.$agentarray['mode'].' TRANSFER 
</h4>  
</td> 
</tr>

</table>
</div>


<div style="width: 668px; float: left; margin-top: 12px;clear:both;height:70px;">
<table style="width:100%; border:1px solid #111;text-align:center" border="0" cellspacing="5" cellpadding="5">
<tr> 
<td style="width: 50%;border-right:1px solid #111;border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
margin: 0px; text-align: center;"> Sender Details </h4> 
</td> 
<td style="border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
margin: 0px; text-align: center;"> Beneficiary Bank Details </h4> 
</td> 
</tr>

<tr> 
<td style="border-right:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 600;padding: 0px;  margin: 0px;  text-align: center;"> '.strtoupper($agentarray['s_name']).' </h4> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> '.$agentarray['s_mobile'].' - NON KYC </h4> 
</td> 
<td>
<h4 style="color: #000000;font-size: 12px;font-weight: 600;padding: 0px; margin: 0px;text-align: center;"> '.$agentarray['b_name'].' - '.$agentarray['ac_number'].' </h4> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;margin: 0px; margin-top: 10px; text-align: center;"> 
'.$agentarray['bankname'].' - '.$agentarray['ifsc_code'].' 
</h4>  
</td> 
</tr>

</table>
</div>




<div style="width: 668px; float: left; margin-top: 12px;clear:both;height:67px;">
<table style="width:100%; border:1px solid #111;text-align:center" border="0" cellspacing="5" cellpadding="5">
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

$txnamount = 0; $success_amt = 0; $sur_charge = 0; $id = '';
foreach ($slipData as $key => $bvalue) {
if( $bvalue['status'] == 'SUCCESS' ){
$success_amt = $success_amt + $bvalue['amount'];
$sur_charge = $sur_charge + $bvalue['sur_charge'] ;
} 
$txnamount = $txnamount + $bvalue['amount']; 

$id = $bvalue['sys_orderid']; 

$html .= ' <tr> 
<td style="border-right:1px solid #111"> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> '.$bvalue['orderid'].' 
</h4> 
</td> 
<td style="border-right:1px solid #111"> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> 
'.date('d-M-Y h:i:s A',strtotime($bvalue['add_date'])).' 
</h4>  
</td> 

<td style="border-right:1px solid #111"> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> 
'.$bvalue['ptm_rrn'].'
</h4>  
</td> 

<td> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> 
'.strtoupper($bvalue['status']).'
</h4>  
</td>   
</tr>';
}

$amountinwords = ( ($success_amt + $sur_charge) ); 
$html .= '</table>
</div>

<div style="width: 668px; float: left; margin-top: 12px;clear:both;height:67px;">
<table style="width:100%; border:1px solid #111;text-align:center" border="0" cellspacing="5" cellpadding="5">
<tr> 
<td style="width: 25%;border-right:1px solid #111;border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
margin: 0px; text-align: center;"> Transaction Amount </h4> 
</td> 
<td style="width: 25%;border-right:1px solid #111;border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
margin: 0px; text-align: center;"> Success Amount </h4> 
</td> 
<td style="width: 25%;border-right:1px solid #111;border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
margin: 0px; text-align: center;"> Surcharge Amount </h4> 
</td> 
<td style="width: 25%;border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
margin: 0px; text-align: center;"> Total Amount </h4> 
</td>

</tr>

<tr> 
<td style="border-right:1px solid #111;">
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;margin: 0px; margin-top: 10px; text-align: center;"> '.$txnamount.'
</h4> 
</td> 
<td style="border-right:1px solid #111;"> 
<h4 style="color:#000000;font-size:11px;font-weight:500;padding:0px;
margin: 0px;margin-top: 10px;text-align: center;"> 
'.$success_amt.'
</h4>  
</td> 

<td style="border-right:1px solid #111;"> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> '.$sur_charge.'
</h4> 
</td> 
<td> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> 
'.$amountinwords.'
</h4>  
</td>  

</tr>

</table>
</div>


<div style="width: 668px; float: left; margin-top: 12px;clear:both;height:57px;">
<table style="width:100%; border:1px solid #111;text-align:center" border="0" cellspacing="5" cellpadding="5">
<tr> 
<td style="width: 50%;border-bottom:1px solid #111;">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px; margin: 0px; text-align: center;"> Total Amount In Words </h4> 
</td>  
</tr>

<tr> 
<td> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;margin: 0px; text-align: center;"> Rupees '.convert_number_to_words( $amountinwords ).' Only </h4> 
</td>  
</tr>

</table>
</div>




<div style="width: 668px; float: left; margin-top: 12px;clear:both;height:96px;">
<table style="width:100%; border:1px solid #111;font-size: 10px;" border="0" cellspacing="5" cellpadding="5">
<tr> 
<td style="">
<p><b>Please Note:</b> </p>
<ol style="margin-left: -25px;">
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


public function confirm(){
     $param = $this->input->post(); 
     $is_repeat = $param['isrepeat'];
     $orderid = $param['odr'];
     $dmtwhere['sys_orderid'] = $orderid; 
     $dmtgetorcount = 'get';
     $dmtinfield = 'status';
     $dmtinvalue = 'ACCEPTED,PENDING,REQUEST';
     $dmtkeys = 'id, status, orderid, apiname ';
     $dmtlogarr = $this->c_model->getfilter('dt_dmtlog',$dmtwhere, null, null , null, null, null, null, null, null, $dmtgetorcount, $dmtinfield, $dmtinvalue , $dmtkeys ); 


  if(!empty($dmtlogarr)){
      /*foreach ($dmtlogarr as $key => $dmt_value) { 
          //for paytm dmt api start script
          if( $dmt_value['apiname'] == 'paytm' ){
          $paytm_dib_url = APIURL.('webapi/paytm/Disbursestatus'); 
          $paytm_dib_postdata['orderId'] = $dmt_value['orderid'];
          $paytm_dib_postdata_where['dts'] = $paytm_dib_postdata;  
          $buffer = curlApis( $paytm_dib_url,'POST',$paytm_dib_postdata_where ); 
          } 
      }*/
echo $is_repeat;

  }else if(empty($dmtlogarr)){
echo 'go';
   exit; 
  } 
echo 'go';
}
    

}
?>