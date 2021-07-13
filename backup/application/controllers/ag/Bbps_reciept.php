<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Bbps_reciept extends CI_Controller{
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




        $data['title'] = 'BBPS Payment Receipt';
        $data['folder'] = 'ag/'; 
        


  

       if( $this->input->get('utp') ){

           if($apptype =='web'){
             $where['a.user_id'] = $this->session->userdata('id');
           }else if($apptype =='app'){
             $where['md5(a.user_id)'] = $this->input->get('id');
           }
         
        
           $where['md5(a.reqid)'] = $this->input->get('utp');  

           $select = 'a.id,a.user_id, a.reqid as sys_orderid,b.ownername, b.mobileno,a.cust_account_no, b.firmname, b.address, b.uniqueid ,a.operatorname,a.add_date, a.status, a.op_transaction_id,a.mobileno as rech_mobile, a.amount, c.image, d.service as servicename,e.op_code, a.operatorid, a.cons_name,a.duedate,a.billno,a.sur_charge '; 
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

            $join[3]['table'] = 'dt_operators_code as e';
            $join[3]['joinon'] = 'a.operatorid = e.operatorid' ;  
            $join[3]['jointype'] = 'LEFT';

            $groupby = null;
            $orderby = null;
            $getorcount = 'get';

           
            $slipData = $this->c_model->joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, null, null, $getorcount );
          
           // print_r( $slipData );exit;

            if(!empty($slipData)){
               $data['title'] = $slipData[0]['servicename'].' Bill Payment Receipt';
            }
         
        }else{ $slipData = array(); }


        //check valid data 
       if(empty($slipData)){ redirect( ADMINURL ); }



        $data['slipData'] = $slipData[0];
        $data['apptype'] = $apptype;

        $pagename = 'bbps_reciept';
        

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

<body style=" margin: 0px;padding: 0px;font-family: Arial, Helvetica, sans-serif;">

   
    <div style="display: block; margin: auto; margin-top: 41px; width: 668px">
        <div style="width: 668px; float: left;">
        <table style="width:100%; border:2px solid #111" border="1" cellspacing="0" cellpadding="0">
            <tr>
                <td width="25%;">
                   <center> <img style="width: 176px;padding: 6px 10px;" src="'.ADMINURL.'assets/images/logo.png" alt=""></center>
                </td>

            
                <td width="33%">
                    <h4 style="color: #000000;font-size: 16px;font-weight: 700; padding: 0px; margin: 0px;  margin-bottom: 4px; text-align: center;padding: 6px 10px;"><center> Transaction Receipt</center> </h4>
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding: 0px; margin: 0px"><center>Thank you for transacting at DigiCash India</center></h4>
                </td >
            
                <td width="25%;color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <center><img src="'.ADMINURL.'assets/images/bharat.png" alt=""></center>
                </td>
            </tr>

        </table>
        </div>


        <div style="width: 668px; float: left; margin-top: 12px">
        <table style="width:100%; border:2px solid #111" border="1" cellspacing="0" cellpadding="0">
            <tr> 
                <td style="width: 50%;color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Agent Details </h4> 
                </td> 
                <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Service Description </h4> 
                </td> 
            </tr>

             <tr> 
                <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 600;padding: 0px;  margin: 0px;  text-align: center;"> '.$slipData['firmname'].' </h4> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; margin-top: 10px; text-align: center;"> '.$slipData['ownername'].' - '.$slipData['uniqueid'].' </h4> 
                </td> 
                <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                   <h4 style="color: #000000;font-size: 12px;font-weight: 600;padding: 0px;  margin: 0px;  text-align: center;">  BILL PAYMENT </h4> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; margin-top: 10px; text-align: center;"> 
                    '.strtoupper($slipData['servicename']).' 
                    </h4>  
                </td> 
            </tr>

        </table>
        </div>



        <div style="width: 668px; float: left; margin-top: 12px">
        <table style="width:100%; border:2px solid #111" border="1" cellspacing="0" cellpadding="0">
            <tr> 
                <td style="width: 50%;color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Consumer Details </h4> 
                </td> 
                <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Biller Details </h4> 
                </td> 
            </tr>

             <tr> 
                <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 600;padding: 0px;  margin: 0px;  text-align: center;"> '.$slipData['cons_name'].' </h4> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; margin-top: 10px; text-align: center;"> '.$slipData['rech_mobile'].' </h4> 
                </td> 
                <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                   <h4 style="color: #000000;font-size: 12px;font-weight: 600;padding: 0px;  margin: 0px; text-align: center;">'.$slipData['operatorname'].'</h4> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; margin-top: 10px; text-align: center;"> 
                    '.strtoupper($slipData['op_code']).' 
                    </h4>  
                </td> 
            </tr>

        </table>
        </div>



<div style="width: 668px; float: left; margin-top: 12px">
        <table style="width:100%; border:2px solid #111" border="1" cellspacing="0" cellpadding="0">
            <tr> 
                <td style="width: 25%;color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Consumer ID </h4> 
                </td> 
                <td style="width: 25%;color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;">  Bill Number </h4> 
                </td> 
                <td style="width: 25%;color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;">  Due Date </h4> 
                </td>
                <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Bill Amount </h4> 
                </td>
            </tr>

             <tr> 
                <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;"> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; text-align: center;"> '.$slipData['cust_account_no'].' 
                     </h4> 
                </td> 
                <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;"> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; text-align: center;"> 
                    '.$slipData['billno'].'
                    </h4>  
                </td> 

                 <td  style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;"> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; text-align: center;"> 
                    '. (!empty($slipData['duedate'])?date('d-M-Y',strtotime($slipData['duedate'])):'').'
                    </h4>  
                </td> 

                 <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;"> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; text-align: center;"> 
                    '.$slipData['amount'].' 
                    </h4>  
                </td>   
            </tr>

        </table>
        </div>



<div style="width: 668px; float: left; margin-top: 12px">
        <table style="width:100%; border:2px solid #111" border="1" cellspacing="0" cellpadding="0">
            <tr> 
                <td style="width: 25%;color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Order ID </h4> 
                </td> 
                <td style="width: 25%;color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Date & Time </h4> 
                </td> 
                <td style="width: 25%;color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Transaction ID </h4> 
                </td>
                <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Transaction Status </h4> 
                </td>
            </tr>

             <tr> 
                <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;"> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; text-align: center;"> '.$slipData['sys_orderid'].'
                     </h4> 
                </td> 
                <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;"> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; text-align: center;"> 
                    '.date('d-m-Y h:i:s A',strtotime($slipData['add_date'])).'
                    </h4>  
                </td> 

                 <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;"> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px;text-align: center;"> 
                    '.$slipData['op_transaction_id'].'
                    </h4>  
                </td> 

                 <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;"> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; text-align: center;"> 
                    '.ucfirst( strtolower($slipData['status'])).'
                    </h4>  
                </td>   
            </tr>

        </table>
        </div>


<div style="width: 668px; float: left; margin-top: 12px">
        <table style="width:100%; border:2px solid #111" border="1" cellspacing="0" cellpadding="0">
            <tr> 
                <td style="width: 25%;color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Payment Mode </h4> 
                </td> 
                <td style="width: 25%;color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Payment Channel </h4> 
                </td> 
                <td style="width: 25%;color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Cust. Convenience Fee </h4> 
                </td>
                <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Total Amount </h4> 
                </td>
            </tr>

             <tr> 
                <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;"> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; text-align: center;"> CASH 
                     </h4> 
                </td> 
                <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;"> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; text-align: center;"> AGT
                    </h4>  
                </td> 

                 <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;"> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px;text-align: center;"> 
                    '.$slipData['sur_charge'].'
                    </h4>  
                </td> 

                 <td style="color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;"> 
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; text-align: center;"> 
                    '.$slipData['amount'].'
                    </h4>  
                </td>   
            </tr>

        </table>
        </div>


<div style="width: 668px; float: left; margin-top: 12px">
        <table style="width:100%; border:2px solid #111" border="1" cellspacing="0" cellpadding="0">
            <tr> 
                <td style="width: 100%;color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;">
                    <h4 style="color: #000000;font-size: 12px;font-weight: 700;padding: 0px;
                    margin: 0px; text-align: center;"> Total Amount In Words </h4> 
                </td> 
            </tr>

             <tr>  
                <td style="width: 100%;color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; text-align: center;" >
                     <h4 style="color: #000000;font-size: 11px;font-weight: 500;padding:0px;
                    margin: 0px; text-align: center;"> 
                    '.convert_number_to_words($slipData['amount']).'
                    </h4>  
                </td>  
                 
            </tr>

        </table>
        </div>



<div style="width: 668px; float: left; margin-top: 12px">
<table style="width:100%; border:2px solid #111" border="1" cellspacing="0" cellpadding="0">
    <tr> 
        <td style="text-align: left;;color: #000000;font-weight: 400;font-size: 12px;padding: 6px 10px; ">
        <p><b>Please Note:</b> </p>
        <ol style="margin-left: -25px;">
        <li style="font-size: 12px;font-weight: 500;color: #000;"> This is a system generated receipt hence does not require any signature..</li> 
        <li style="font-size: 12px;font-weight: 500;color: #000;"> For Feedback, Comments, Suggestions or Compliments - Do write to <b> info@mydigicash.in</b>.</li>
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
     $where['reqid'] = $orderid; 
     $getorcount = 'get';
     $infield = 'status';
     $invalue = 'PENDING,PROCESSED';
     $keys = 'id, status, reqid, apiid as apiname ';
     $logarr = $this->c_model->getfilter('dt_rech_history',$where, null, null , null, null, null, null, null, null, $getorcount, $infield, $invalue , $keys ); 


  if(!empty($logarr)){
       
echo $is_repeat;

  }else if(empty($logarr)){
echo 'go';
   exit; 
  } 
echo 'go';
} 

}
?>