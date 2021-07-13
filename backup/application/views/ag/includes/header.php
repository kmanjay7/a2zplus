<?php 
/*LOGOUT BLOCKED NUMBER */
$blocked = getloggeduserdata('uniqueid');
if(in_array($blocked,['9883114661','6297814348'])){ 
   redirect( ADMINURL.'Logout');
}

  $check_kyc = getloggeduserdata('kyc_status');
  $getcontroller = strtolower( $this->uri->segment(2) ); //exit;
  $foldheader = strtolower( $this->uri->segment(1) );
  $ck_fromdate = getloggeduserdata('fromdate');
  $ck_todate = getloggeduserdata('todate');
  $validityendon = strtotime(date('Y-m-d',strtotime($ck_todate)));
  $todaytimestump = strtotime(date('Y-m-d'));
 

$is_open[] = 'profile';
$is_open[] = 'add_fund';
$is_open[] = 'contact';
$is_open[] = 'dashboard';
$is_open[] = 'wallet_ladger_report';
$is_open[] = 'wallet_ladger_report_aeps';
$is_open[] = 'money_transaction_report';
$is_open[] = 'prepaid_mobile_report';
$is_open[] = 'dth_recharge_report';
$is_open[] = 'add_fund_online_report';
$is_open[] = 'aeps_transaction_report';
$is_open[] = 'aeps_settlement_report';
$is_open[] = 'complaints';
$in_array = in_array($getcontroller, $is_open );
if(($check_kyc != 'yes') && ($getcontroller != 'update_kyc') ){
   redirect( ADMINURL.$foldheader.'/update_kyc');
}else if( ($check_kyc == 'yes') && ($getcontroller != 'subscribe_plan') ){
     if( empty($in_array) && ( $validityendon < $todaytimestump ) ){
   redirect( ADMINURL.$foldheader.'/subscribe_plan'); 
  }
}
?> 

<div class="main fixed-top">
   <div class="header-top">
      <div class="container">
         <div class="row">
            <div class="col-md-3 col-lg-3 col-3">
                <a href="<?= ADMINURL.'ag/dashboard' ?>"> <img src="<?= ADMINURL;?>assets/images/logo.png" style="width:60%;padding:12px 0px;"></a>
            </div>
             

            <div class="col-md-9 col-lg-9 col-9">
               <div class="balance-txt">

                  <h3> <div class="row">
                    <div class="col-lg-4" style="width:350px">
                      <span>AEPS Balance:- </span> ₹ <span id="aepswt">0.000</span> <span class="line"> | </span>  
                    </div>
                    <div class="col-lg-4" style="width:350px">
                    <span>Main Balance:- </span> ₹ <span id="mainwt"><?php //echo checkwallet();?>0.000</span> <span class="line"> | </span> 
                  </div> 
                  <div class="col-lg-3">
                    <p style="line-height: 17px;margin-top: 17px;">
                    <span><?= $this->session->userdata('ownername')?></span></br>
                    <span style="font-size: 11px; font-style: italic;"><?=getloggeduserdata('user_type');?> </span>
                    </p>
                  </div> 
                  <div class="col-lg-1">
                    <span onclick="menu()" class="dropbtn"><i class="admin dropbtn"> <?= substr($this->session->userdata('ownername'),0,1)?> </i> </span>
                     <div class="dropdown">
                        <div id="login_menu" class="dropdown-content">
                           <a href="<?=ADMINURL.'ag/profile';?>"> Profile </a>
                           <a href="<?=ADMINURL.'ag/reset_pass';?>"> Reset password </a>
                           <a href="<?=ADMINURL.'ag/update_kyc';?>"> Update Kyc </a>
                           <a href="<?=ADMINURL.'ag/Subscribe_plan';?>"> Subscribe </a>
                           <a href="<?=ADMINURL.'Logout';?>"> Log Out </a>
                        </div>
                     </div>
                   </div></div>
                  </h3>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div style="width: 100%" class="bg-footer shadow menu-fix">
      <div class="container mobile-bg ">
         <div id="menu_area" class="menu-area">
            <div class="container">
               <div class="row">
                  <nav class="navbar navbar-light navbar-expand-lg mainmenu">
                     <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                     <span class="navbar-toggler-icon"></span>
                     </button>
<div class="collapse navbar-collapse" id="navbarSupportedContent">
<ul class="navbar-nav mr-auto">
   <li><a href="<?= ADMINURL.'ag/dashboard' ?>">Dashboard <span class="sr-only">(current)</span></a></li> 
  <li class="dropdown">
      <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Services</a>
      <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
        <li><a href="<?=ADMINURL.'ag/Aeps';?>">AEPS</a></li>
        <!-- <li><a href="<?=ADMINURL.'ag/electricity';?>">Electricity Bill</a></li> -->
        <li><a href="<?=ADMINURL.'ag/electricity_bill';?>">Electricity Bill</a></li>
        <li><a href="<?=ADMINURL.'ag/fastag';?>">Fastag Payment</a></li>

        <?php if( in_array( getloggeduserdata('uniqueid'), ['6386695694','7007092221'] ) ){?>
        <li class="dropdown"><a class="dropdown-toggle" href="#" id="navbarDropdown"
        role="button" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">BBPS</a>

        <ul class="dropdown-menu" style="float: 0" aria-labelledby="navbarDropdown">
        <li><a href="<?=ADMINURL.'ag/gas';?>">Gas Payment</a></li>
        <li><a href="<?=ADMINURL.'ag/loan';?>">Loan Payment</a></li>
        <li><a href="<?=ADMINURL.'ag/post_paid';?>">Postpaid Recharge</a></li>
        <li><a href="<?=ADMINURL.'ag/water';?>">Water Bill</a></li>
        </ul>
        </li> 
        <?php }else{?>
        <li><a href="javascript:void(0)">BBPS</a></li>
        <?php }?>

                                
        <li><a href="<?=ADMINURL.'ag/Domestic_money_transfer';?>">Domestic Money Transfer</a></li> 
        <li><a href="<?=ADMINURL.'ag/Dthrecharge';?>">DTH Recharge</a></li>
        <li><a href="<?=ADMINURL.'ag/mobilerecharge';?>">Prepaid Mobile Recharge</a></li> 
    </ul>
  </li>


<?php //if( in_array( getloggeduserdata('uniqueid'), ['6386695694','7007092221'] ) ){?>
  <li class="dropdown">
  <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pan Card</a>
      <ul class="dropdown-menu" aria-labelledby="navbarDropdown"> 
          <li class="dropdown">
            <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">New </a>
           
                  <ul class="dropdown-menu" style="float: 0" aria-labelledby="navbarDropdown">
                     <li><a href="<?=ADMINURL.'ag/individual_pan';?>">Apply New</a></li>
                     <li><a href="<?=ADMINURL.'ag/pan_upload_list';?>">Upload Pending</a></li>
                  </ul>
          </li> 
 
          <li class="dropdown">
            <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Correction </a>
        
              <ul class="dropdown-menu" style="float: 0" aria-labelledby="navbarDropdown">
                 <li><a href="<?=ADMINURL.'ag/individual_pan_corr';?>">Apply Correction</a></li>
                 <li><a href="<?=ADMINURL.'ag/pan_upload_list';?>">Upload Pending</a></li> 
              </ul>
          </li>   
          <li><a href="<?=ADMINURL.'ag/pancard_approved';?>">Approved Report</a></li>
          <li><a href="<?=ADMINURL.'ag/pancard_hold';?>">Hold Report</a></li>
          <li><a href="<?=ADMINURL.'ag/pancard_pending';?>">Pending Report</a></li>
          <li><a href="<?=ADMINURL.'ag/pancard_rejected';?>">Rejected Report</a></li>
    
    </ul>
  </li>

<?php //} ?>


                           
<li class="dropdown">
    <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reports</a>
    <ul class="dropdown-menu" aria-labelledby="navbarDropdown"> 

  
  <li><a href="<?=ADMINURL.'ag/Wallet_ladger_report_aeps';?>">AEPS Ledger Report</a></li>
  <li><a href="<?=ADMINURL.'ag/aeps_transaction_report';?>">AEPS Transaction Report</a></li>
  <li><a href="<?=ADMINURL.'ag/aeps_settlement_report';?>">AEPS Settlement Report</a></li>
    <li><a href="<?=ADMINURL.'ag/bbps_report/index/4';?>">Electricity Bill Reports</a></li> 
  <li><a href="<?=ADMINURL.'ag/bbps_report/index/12';?>">Fastag Payment Reports</a></li>
  <?php if( in_array( getloggeduserdata('uniqueid'), ['6386695694','7007092221'] ) ){?>
        <li class="dropdown">
          <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">BBPS Reports </a>

          <ul class="dropdown-menu" style="float: 0" aria-labelledby="navbarDropdown"> 
            <li><a href="<?=ADMINURL.'ag/bbps_report/index/11';?>">Gas Payment Reports</a></li>
            <li><a href="<?=ADMINURL.'ag/bbps_report/index/13';?>">Loan Payment Reports</a></li>
            <li><a href="<?=ADMINURL.'ag/bbps_report/index/9';?>">Postpaid Recharge Reports</a></li>
            <li><a href="<?=ADMINURL.'ag/bbps_report/index/10';?>">Water Bill Reports</a></li>
           </ul>
        </li> 

  <?php }?>
  <li><a href="<?=ADMINURL.'ag/Dth_recharge_report';?>">DTH Recharge Report</a></li>
  <li><a href="<?=ADMINURL.'ag/Money_transaction_report';?>">Money Transfer Report</a></li>
  <li><a href="<?=ADMINURL.'ag/Prepaid_mobile_report';?>">Mobile Recharge Report</a></li> 
  <li><a href="<?=ADMINURL.'ag/add_fund_online_report';?>">Online Fund Report</a></li>
  <li><a href="<?=ADMINURL.'ag/Wallet_ladger_report';?>">Wallet Ledger Report</a></li> 
   
    </ul>
 </li> 

 <li class="dropdown">
    <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Support</a>
    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
       <li><a href="<?=ADMINURL.'ag/contact';?>">Contacts Details</a></li> 
       <li><a href="<?=ADMINURL.'ag/mytickets';?>">My Tickets</a></li>  
       <li><a href="<?=ADMINURL.'ag/complaints/manual';?>">Make Complaints</a></li>
    </ul>
  </li>
                           
  <li class="dropdown">
      <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button"
         data-toggle="dropdown" aria-haspopup="true"
         aria-expanded="false">Manage Fund</a>
      <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
      <li><a href="<?= ADMINURL.'ag/add_fund';?>">Add Fund Online </a></li>
      <li><a href="javascript:void(0)">Add Fund Offline </a></li>
      <li><a href="javascript:void(0)">Add Fund By Aeps</a></li> 
      </ul>
  </li>

      
  <li> <a href="<?= ADMINURL.'ag/downloads';?>"> Downloads </a></li>
  <li> <a href="<?= ADMINURL.'Logout';?>"> Logout </a></li>
                        </ul>
                     </div>
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </div>

</div>
<style>
.balance-txt .dropbtn {
    background-color: #ffffff;
    color: #000;
    padding: 16px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}
.balance-txt .dropdown {
  position: relative;
  display: inline-block;
}

 .balance-txt .dropdown-content {
    display: none;
    position: absolute;
    background-color: #f1f1f1;
    min-width: 160px;
    overflow: auto;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    margin-left: -144px;
    margin-top: 23px;
}

.balance-txt .dropdown-content a {
    color: black;
    padding: 0px 11px;
    text-decoration: none;
    display: block;
    font-size: 15px;
}

.balance-txt .dropdown a:hover {background-color: #ddd;}

.balance-txt .show {display: block;}
.balance-txt .dropbtn i {
    width: 46px;
    height: 46px;
    color: #fff;
    background: #0b1e61;
    display: inline-block;
    border-radius: 50%;
    position: absolute;
    text-align: center;
    line-height: 13px;
    margin-top: 6px;
    font-style: normal;
    border: 1px solid #0b1e61;
}

</style>
<script> 
      function menu() {
        document.getElementById("login_menu").classList.toggle("show");
      }
      window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
          var dropdowns = document.getElementsByClassName("dropdown-content");
          var i;
          for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
              openDropdown.classList.remove('show');
            }
          }
        }
      }
      </script>

<script type="text/javascript">
  function header_bal(){
    var huserid = '<?=getloggeduserdata('id');?>';
    var huser_type = '<?=getloggeduserdata('user_type');?>';
    if(huserid && huser_type){
      
       $.ajax({
        type:'POST',
        url:'<?=ADMINURL.'ajax/get_bal_status'?>',
        data:{'id':huserid,'type':huser_type},
        success: function(res){
          var hobj = JSON.parse(res);
          $('#aepswt').html(hobj.aepswt);
          $('#mainwt').html(hobj.mainwt);
          <?php if( ci()->uri->segment(2)=='aeps_fund' ){?>
          $('.aepswt').html(hobj.aepswt);
          <?php } ?>

            setTimeout(function(){ 
            header_bal();
            },55000);

        }
       }); 

    }
  }
  window.onload = function(){
  setTimeout(function(){ 
  header_bal();
  },500);
  };
</script>      