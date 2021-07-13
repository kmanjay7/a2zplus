<?php  $check_kyc = getloggeduserdata('kyc_status');
       $lg_id = getloggeduserdata('id');
       $getcontroller = strtolower( $this->uri->segment(2) ); //exit;
       $foldheader = strtolower( $this->uri->segment(1) );
       //$ck_fromdate = getloggeduserdata('fromdate');
       //$ck_todate = getloggeduserdata('todate');
/*$is_open[] = 'profile';
$is_open[] = 'add_fund';
$is_open[] = 'contact';
$is_open[] = 'dashboard';
$in_array = in_array($getcontroller, $is_open );*/
if(($check_kyc != 'yes') && ($getcontroller!='update_kyc') ){
   redirect( ADMINURL.$foldheader.'/update_kyc?id='.md5($lg_id) );
}/*else if( ($check_kyc == 'yes') && ($getcontroller!='subscribe_plan') ){ 
  if(($ck_fromdate < date('Y-m-d H:i:s')) && ($ck_todate < date('Y-m-d H:i:s')) || (!$ck_fromdate && !$ck_todate) ){
   redirect( ADMINURL.$foldheader.'/subscribe_plan');
  }
}*/
?>
<div class="main fixed-top">
   <div class="header-top">
      <div class="container">
         <div class="row">
            <div class="col-md-2 col-lg-2 col-2">
                <a href="<?= ADMINURL.'bp/dashboard' ?>"> <img src="<?= ADMINURL;?>assets/images/logo.png" style="width:90%;padding:12px 0px;"></a>
            </div>
             <div class="col-md-10 col-lg-10 col-10">
               <div class="balance-txt">

<?php $wallet_Array['userid'] = getloggeduserdata('id');;
$wallet_Array['user_type'] = getloggeduserdata('user_type'); ?>
                  <h3 style="width: 75%;"> <div class="row">
                
                <div class="col-lg-5">
                <span>Balance:- </span> ₹ <span id="mainwt">0.000</span>
                </div> 
                <div class="col-lg-6">
                <p style="line-height: 17px;margin-top: 17px;">
                <span><?= $this->session->userdata('ownername')?></span></br>
                <span style="font-size: 11px; font-style: italic;">Business Partner </span>
                </p>
                </div> 
                <div class="col-lg-1">
                <span onclick="menu()" class="dropbtn"><i class="admin dropbtn"> <?= substr($this->session->userdata('ownername'),0,1)?> </i> </span>
                <div class="dropdown">
                <div id="login_menu" class="dropdown-content">
                <a href="<?=ADMINURL.'bp/profile';?>"> Profile </a>
                <a href="<?=ADMINURL.'bp/update_kyc?id='.md5($this->session->userdata('id'));?>"> Update Kyc </a>
                <a href="<?=ADMINURL.'bp/reset_pass';?>"> Reset password </a>
                <a href="<?=ADMINURL.'bp/Subscribe_plan';?>"> Subscribe </a>
                <a href="<?= ADMINURL.'Logout';?>"> Log Out </a>
                </div>
                </div>
                </div></div>
                </h3>
               </div>
            </div>

         </div>
      </div>
   </div>
   <div style="width: 100%; background: #2B2F6F" class=" shadow menu-fix">
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
                           <li><a href="<?= ADMINURL.'bp/dashboard' ?>">Dashboard <span class="sr-only">(current)</span></a></li>
                           
                           <li class="dropdown">
                              <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reports</a>
                              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a href="<?=ADMINURL.'bp/Wallet_ladger_report';?>">Wallet Ledger Report</a></li>
                                  <li><a href="<?=ADMINURL.'bp/credit_report';?>">Credit Report</a></li> 
                                  <li><a href="<?=ADMINURL.'bp/debit_report';?>">Debit Report</a></li> 
                                  <li><a href="<?=ADMINURL.'bp/Add_fund_online_report';?>">Online Fund Report</a></li> 
                              </ul>
                           </li>
                     

                           <li class="dropdown">
                              <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Support</a>
                              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                 <li><a href="<?=ADMINURL.'bp/contact';?>">Contacts</a></li> 
                                 <li><a href="<?=ADMINURL.'bp/complaints/manual';?>">My Complaints</a></li>
                                 <li><a href="<?=ADMINURL.'bp/dashboard';?>">My Tickets</a></li> 
                              </ul>
                           </li>
                           
                           
                           <li class="dropdown">
                              <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                 data-toggle="dropdown" aria-haspopup="true"
                                 aria-expanded="false">Manage Customer</a>
                              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                 <li class="dropdown">
                                    <a class="dropdown-toggle" href="#" id="navbarDropdown"
                                       role="button" data-toggle="dropdown" aria-haspopup="true"
                                       aria-expanded="false">MD</a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                       <li><a href="<?=ADMINURL.'bp/md';?>">Add</a></li>
                                       <li><a href="<?=ADMINURL.'bp/md/view';?>">View</a></li> 
                                    </ul>
                                 </li>
                                 <li class="dropdown">
                                    <a class="dropdown-toggle" href="#" id="navbarDropdown"
                                       role="button" data-toggle="dropdown" aria-haspopup="true"
                                       aria-expanded="false">AD</a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                       <li><a href="<?=ADMINURL.'bp/ad';?>">Add</a></li>
                                       <li><a href="<?=ADMINURL.'bp/ad/view';?>">View</a></li>
                                    </ul>
                                 </li>
                                  <li class="dropdown">
                                    <a class="dropdown-toggle" href="#" id="navbarDropdown"
                                       role="button" data-toggle="dropdown" aria-haspopup="true"
                                       aria-expanded="false">Agent</a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                       <li><a href="<?=ADMINURL.'bp/agent';?>">Add</a></li>
                                       <li><a href="<?=ADMINURL.'bp/Agent/view';?>">View</a></li>
                                    </ul>
                                 </li>
                                 <li><a href="<?= ADMINURL;?>bp/credit_amount">Credit</a></li>
                                 <li><a href="<?= ADMINURL;?>bp/debit_amount">Debit</a></li>
                              </ul>
                           </li>
      <li class="dropdown">
        <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button"
           data-toggle="dropdown" aria-haspopup="true"
           aria-expanded="false">Manage Fund</a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
        <li><a href="<?= ADMINURL.'bp/add_fund';?>">Add Fund Online </a></li>
        <li><a href="javascript:void(0)">Add Fund Offline </a></li> 
        </ul>
     </li> 
                           <li> <a href="<?= ADMINURL;?>bp/downloads"> Downloads </a></li>
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
         // $('#aepswt').html(hobj.aepswt);
          $('#mainwt').html(hobj.mainwt);

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
  },5000);
  };
</script> 