<div class="content">
        <div class="container">

            <section class="cash-area">

                <div class="cash-payment">
                    <div class="cash-heading">
                        <div class="row">
                            <div class="col-3 col-lg-4">
                                <h2> Topup Wallet Balance Online </h2>
                            </div>
                            <div class="col-9 col-lg-8">
                                <div class="transfer_head">
                                    <h2>Custid: <?=$uniquecode;?> </h2>
                                </div>
                            </div>
                        </div>

                    </div>

        
                    <div class="filter-area">

<form action="<?=ADMINURL.$folder.'/'.$pagename.'/gotopaytm';?>" method="post" onsubmit="return checkinputs();">
<div class="row m-15">

<div class="col-12 col-lg-4 m-15">
<label for=""> Name </label>
<input class="form-control" placeholder="Name..." value="<?=$ownername;?>" id="fname" type="text" name="fname">
</div>

<div class="col-12 col-lg-4">
<label for=""> User Name </label>
<input class="form-control " placeholder="User Name" value="<?=$uniqueid;?>" id="username" type="text" name="username">
</div>

<div class="col-12 col-lg-4">
<label for=""> Mobile No. </label>
<input class="form-control " placeholder="Mobile No" value="<?=$uniqueid;?>" id="mobile" type="text" name="mobile">
</div>

<div class="col-12 col-lg-4">
<label for=""> Email ID </label>
<input class="form-control" placeholder="example@domain.com" value="<?=$emailid;?>"  id="email" type="text" name="email">
</div>

<div class="col-12 col-lg-4">
    <label for=""> Amount </label>
    <input class="form-control" placeholder="Amount..." id="amount" type="text" name="amount">
</div>

<div class="col-12 col-lg-4 m-31">
<input class="form-control apply-filter w-100" value="Add Fund" type="submit" name="submit">
</div>


</div>
</form>
       
            </div>
        </div>
                

</section>


                
                <div class="report-area">
                    <div class="report-area-t">
                        <div class="row">  
                            <div class="col-lg-12 col-md-12 col-12 ">
                                <div class="top-10-txt tw_h">
                                    <h2>Wallet Loading Charges Using Payment Gateway</h2> 
                                </div>
                            </div>
                          
                           

                            <div class="border-tabl"> 
                            </div>

                            <div class="col-lg-12 col-md-12 col-12 ">




                               
<div class="report-table tw_table">

    <table class="table">
            <thead>                                                                  
                <tr style="background: transparent">
                    <th>S.N</th>
                    <th> Payment Mode
                    </th>
                    <th>  Applicable Charges  </th>
                  
                  
                
                
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1.</td>
                    <td> Internet Banking  </td>
                    <td> : 12 Rupees Per transaction  </td>     
                </tr>
                <tr>
                        <td>2.</td>
                        <td> UPI Payment  </td>
                        <td> : 0 Rupees Per transaction  </td>     
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td> Rupay Card  </td>
                        <td> : 0 Rupees Per transaction  </td>     
                    </tr>
                    <tr>
                            <td>4.</td>
                            <td> Debit Card  </td>
                            <td> : 1% of transaction amount  </td>     
                        </tr>
                        <tr>
                                <td>5.</td>
                                <td> Credit Card  </td>
                                <td> : 2% of transaction amount  </td>      
                            </tr>
                            <tr>
                                    <td>6.</td>
                                    <td> Paytm Wallet  </td>
                                    <td> : 2% of transaction amount  </td>      
                                </tr>  

            </tbody>
        </table>

                                        </div>
                                  
                                    
                            </div>

                        </div>

                    </div>

                </div>

         
            

            <br> <br>

        </div>
    </div>

<?php $this->load->view('includes/footer');
$this->load->view('includes/alljs');
$this->load->view('includes/common_session_popup');?>
<script type="text/javascript">
    function checkinputs(){
        var fname = $('#fname').val();
        var mobile = $('#mobile').val();
        var amount = $('#amount').val(); 
    if(fname == ''){ swalpopup('error','Please fill fullname!'); return false;}
    else if( mobile.length != 10 ){ swalpopup('error','Please enter 10 digit mobile number!'); return false;}
    else if(amount == ''){ swalpopup('error','Please fill amount!'); return false;}
    else if(amount == 0){ swalpopup('error',"Amount can't be 0!"); return false;}
    }
</script>

</html>
</body>