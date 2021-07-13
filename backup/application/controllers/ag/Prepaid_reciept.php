<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Prepaid_reciept extends CI_Controller{
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




        $data['title'] = 'Prepaid Mobile Recharge Receipt';
        $data['folder'] = 'ag/'; 
        


  

       if( $this->input->get('utp') ){

           if($apptype =='web'){
             $where['a.user_id'] = $this->session->userdata('id');
           }else if($apptype =='app'){
             $where['md5(a.user_id)'] = $this->input->get('id');
           }
         
        
           $where['md5(a.reqid)'] = $this->input->get('utp');  

           $select = 'a.id,a.user_id, a.reqid as sys_orderid,b.ownername, b.mobileno, b.firmname, b.address, b.uniqueid ,a.operatorname,a.add_date, a.status, a.op_transaction_id,a.mobileno as rech_mobile, a.amount, c.image, d.service as servicename '; 
            $from = 'dt_rech_history as a';

            $join[0]['table'] = 'dt_users as b';
            $join[0]['joinon'] = 'a.user_id = b.id' ;  
            $join[0]['jointype'] = 'LEFT'; 

            $join[1]['table'] = 'dt_operators as c';
            $join[1]['joinon'] = 'a.operatorid = c.id' ;  
            $join[1]['jointype'] = 'LEFT';

            $join[2]['table'] = 'dt_services as d';
            $join[2]['joinon'] = 'a.serviceid = d.id' ;  
            $join[2]['jointype'] = 'LEFT';

            $groupby = null;
            $orderby = null;
            $getorcount = 'get';

           
            $slipData = $this->c_model->joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, null, null, $getorcount );
          
         // print_r( $slipData );
         
        }else{ $slipData = array(); }


        //check valid data 
        if(empty($slipData)){ redirect( ADMINURL ); }



        $data['slipData'] = $slipData[0];
        $data['apptype'] = $apptype;

        $pagename = 'prepaid_reciept';
        

        if($apptype=='web'){
           agview( $pagename ,$data); 
        }else{ 
 
          
          $html =  $this->printrp($data);
          //echo $html; 
          //exit;
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
<table style="width:100%; border:1px solid #111;height:61px;text-align:center" border="0" cellspacing="5" cellpadding="5">
<tr>
<td width="25%" style="border-right:1px solid #111">
<img style="width: 166px;" src="'.ADMINURL.'assets/images/logo.png" alt="">
</td>


<td width="38%" style="border-right:1px solid #111">
<h4 style="color: #000000;font-size: 16px;font-weight: 700; padding: 0px; margin: 0px;  margin-bottom: 4px;"> Transaction Receipt </h4>
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding: 0px; margin: 0px">Thank you for transacting at DigiCash India</h4>
</td >

<td width="25%" >
<center><img style="width: 46px; margin-top: 0px;" src="'.ADMINURL.'assets/images/'.$slipData['image'].'" alt=""></center>
</td>
</tr>

</table>
</div>


<div style="width: 668px; float: left; margin-top: 20px; clear:both;height:82px;">
<table style="width:100%; border:1px solid #111; text-align:center" border="0" cellspacing="5" cellpadding="5">
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
<h4 style="color: #000000;font-size: 12px;font-weight: 600;padding: 0px; margin: 0px;text-align: center;"> '.strtoupper($slipData['servicename']).' RECHARGE </h4> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> 
'.$slipData['operatorname'].' 
</h4>  
</td> 
</tr>

</table>
</div>



<div style="width: 668px; float: left; margin-top: 20px;clear:both;height:67px;">
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
'.$slipData['op_transaction_id'].'
</h4>  
</td> 

<td> 
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
margin: 0px; margin-top: 10px; text-align: center;"> 
'.strtoupper($slipData['status']).'
</h4>  
</td>   
</tr>';


//$amountinwords = ( ($success_amt + $sur_charge) ); 
$html .= '</table>
</div>

<div style="width: 668px; float: left; margin-top: 20px;clear:both;height:67px;">
<table style="width:100%; border:1px solid #111;text-align:center" border="0" cellspacing="5" cellpadding="5">
<tr> 
<td style="width: 24.8%;border-right:1px solid #111;border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
margin: 0px; text-align: center;"> Mobile Number </h4> 
</td> 
<td style="width: 25%;border-right:1px solid #111;border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
margin: 0px; text-align: center;"> Recharge Amount </h4> 
</td> 
<td style="width: 50%;border-bottom:1px solid #111">
<h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
margin: 0px; text-align: center;"> Amount In Words </h4> 
</td> 


</tr>

<tr> 
<td style="border-right:1px solid #111;">
<h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;margin: 0px; margin-top: 10px; text-align: center;"> '.$slipData['rech_mobile'].'
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
margin: 0px; margin-top: 10px; text-align: center;"> 
'.convert_number_to_words($slipData['amount']).'
</h4>  
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

    

}
?>