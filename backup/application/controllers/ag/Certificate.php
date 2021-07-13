<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Certificate extends CI_Controller{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
          
    }

    public function index(){

        agsession_check(); 

        $data['title'] = 'Certificate';
        $data['folder'] = 'ag';
        $data['pagename'] = 'Certificate';
        $id = getloggeduserdata('id');
        $data["user"] = $this->c_model->getSingle("dt_users", ["id"=>$id ],'*');
        agview('certificate', $data);
    }  


    public function print_c(){  
        $data['title'] = 'Certificate';
        $data['folder'] = 'ag';
        $data['pagename'] = 'Certificate';
        $id = $this->input->get('uid');
        $user = $this->c_model->getSingle("dt_users", ["md5(id)"=>$id ],'*');
         
        $html = ' 

<div style="width:668px; padding: 5px 0px 5px 0px; font-family: Montserrat;font-family: sans-serif" >
<div style="padding: 11px 0px;
    max-width: 668px;
    border: 9px solid #58d234;
    display: block;
    margin-top: 0px;
    margin-bottom: 1px;
    margin: auto;
    background: #e6e6e5;">
<div style="padding: 14px 20px;
    width: auto;
    border: 9px solid #4168a8;
    display: block;
    margin: 7px 19px;
    background: #fff;">
<h3 style="font-size: 18px; text-transform: uppercase; font-weight: bold;">Authorization Certificate</h3>
<h4 style="font-size: 15px; font-weight: 500;"> Certificate Number : '.$user['uniquecode'].' </h4>
 <table style="width:100%; border:1px solid #fff; text-align:center" border="0" cellspacing="5" cellpadding="5">
<tr> 
<td style="width:100%;">
<img src="'.ADMINURL.'/assets/images/logo.png" alt="" style=" display: block;
    margin: auto;  width: 236px;"> 
</td>  
</tr></table>
 
 
<h5 style="text-align: center;
    font-size: 25px;
    margin-top: 14px;
    margin-bottom: 16px;
    color: #000;
    font-weight: bold;"> Certificate of Association </h5>
<h6 style="text-align: center;
    font-size: 20px;
    font-weight: 400;
    margin-bottom: 12px;">This is to certify that</h6>

<div class="certi_txt" style="text-align: center;">
<h1 style="font-size: 28px;
    text-transform: uppercase;
    margin-top: 1px;
    margin-bottom: 13px;
    font-weight: bold;
    color: #000;">'.$user['firmname'].'</h1>
<h2 style=" color: #000;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 22px;"> '.$user['address'].'</h2>
<h3 style="color: #000;
font-weight: 500;
padding: 0px;
text-transform: capitalize;
font-size: 19px;">Is our Authorised Business Associate for the Development of Business Sales.</h3>
<h4 style="font-size: 17px;
font-weight: 500;
margin-bottom: 25px;">Authorized Representatives of the firm is</h4>
<h5 style="text-transform: uppercase;
font-size: 28px;
margin-top: 20px;
margin-bottom: 5px;
font-weight: bold;
color: #040404;">'.$user['ownername'].'</h5>
<h6 style="text-transform: uppercase;
font-size: 19px;
margin-bottom: 25px;
font-weight: bold;
color: #040404;">('; 
 if($user['user_type']=='BP'){ $html .= 'Business Partner';}else if($user['user_type']=='MD'){ $html .= 'Master Distributor';}else if($user['user_type']=='AD'){ $html .= 'Area Distributor';}else if($user['user_type']=='AGENT'){ $html .= 'Agent';}?>
<?php $html .=')</h6>
</div>

<div class="certi_footer" style="text-align: center;">

<h2 style="font-size: 17px;
font-weight: 400;
margin-bottom: 21px;">Issue Date of the certification - <span style="font-weight: bold;"> '.date("d-M-Y h:i A", strtotime($user['register_date'])).'  </span></h2>
<h3 style="font-size: 17px;
text-transform: initial;
font-weight: 500;">To Verify Certificate Status and Detail</h3>
<h4 style="font-size: 17px;
font-weight: 500;
margin-top: 19px;
margin-bottom: 15px;">Kindly Log in <a href="http://www.mydigicash.in" style="text-decoration: underline;
font-weight: 600;"> www.mydigicash.in</a></h4>
<h5 style="font-size: 18px;">Email: info@mydigicash.in</h5>
</div>

</div>

                                            
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div> 

    </div>
</div>';

  //echo $html;       
      
  //exit;     
 // header('Content-Type: text/html; charset=utf-8');
  require_once APPPATH.'/third_party/mpdf/vendor/autoload.php'; 

  $mpdf = new \Mpdf\Mpdf(); 

  $filename = $id .'.pdf';

  $mpdf->SetFont('Arial');
  $mpdf->SetFont('Helvetica');
  $mpdf->SetFont('sans-serif'); 

  $mpdf->setAutoTopMargin = 'pad';
  $mpdf->autoMarginPadding = 10;

  $mpdf->WriteHTML($html,2);
 // download!  use D 
  $mpdf->Output($filename, "I"); 
 
  exit; 
 
    }

 

}