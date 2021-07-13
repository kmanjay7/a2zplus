<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/sweet.css');?>">

<div class="content">
        <div class="container">

            <section class="cash-area">

                <div class="cash-payment">
                    <div class="cash-heading">
                        <h2> <?=$title;?> </h2>
                    </div>
                    <div class="filter-area">
 
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-4">
                                <label for=""> User Mobile No. </label>
                                 <?= form_input(array('name'=>'mobileno','class'=>'form-control','id'=>'mobileno','oninput'=>"this.value = this.value.replace(/[^0-9.]/g, '');",'maxlength'=>'10','placeholder'=>'Enter 10 digit Mobile Number','onkeyup'=>"getusertype('mobileno')") );?>   
                            </div>
                            <div class="col-md-4 col-lg-4 col-4"> 
                                <label for=""> Select User Type </label>
                                 <?= form_dropdown(array('name'=>'usertype','class'=>'form-control','id'=>'usertype','onchange'=>"getuserinfo('usertype')"),array(''=>'Select User Type') );?>                            
                            </div>
                            <div class="col-md-4 col-lg-4 col-4"> 
                                <label for=""> Select User </label>
                                <?= form_dropdown(array('name'=>'userid','class'=>'form-control','id'=>'userid'),array(''=>'Select User') );?> 
                            </div>
                    </div>


                        <div class="">
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-4 m-29">
                                    <label for=""> Credit Amount</label>
            <?= form_input(array('name'=>'credit_amount','class'=>'form-control','id'=>'credit_amount','oninput'=>"this.value = this.value.replace(/[^0-9.]/g, '');", 'placeholder'=>'Enter Credit Amount') );?>

                                    
                                </div>

                                <div class="col-md-4 col-lg-4 col-4 m-29">
                                    <label for=""> Comments </label>
                                    <?= form_input(array('name'=>'comments','class'=>'form-control','id'=>'comments','placeholder'=>'Enter Comments') );?> 
                                </div>

                                <div class="col-md-3 col-lg-3 col-12 m-29">
                                    <?php echo form_submit(array('name'=>'search','class'=>'form-control apply-filter m-29 ap_bg','id'=>'submit','onclick'=>'credit_Amount()','value'=>'Credit') );?>
                                    <img src="<?=ADMINURL.'assets/images/loadergif.gif'?>" style="display:none ; width: 83px; margin-top: 10px" id="pleasewait"></img> 
                                </div>


                            </div>
                        </div>

                    </div>


                     
                </div>



            </section>



<?php /*?>
            <section class="">
                <div class="report-area">
                    <div class="report-area-t">
                        <div class="row">


                            <div class="col-lg-4 col-md-4 col-4 ">
                                <div class="top-10-txt">
                                    <h2> Recent Report</h2>

                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 col-8">
                                <div class="row">

                                    <div>

                                        <span class="date-h">Date From</span>
                                    </div>

                                    <div class="col">

                                        <div class="input-group">
                                            <input id="datepicker" class="form-control date-font"
                                                placeholder="Start Date" type="text" name="start_date">


                                        </div>
                                    </div>

                                    <div>
                                        <span class="date-h">To</span>
                                    </div>
                                    <div class="col">

                                        <div class="input-group">

                                            <input id="datepicker1" class="form-control date-font"
                                                placeholder="End Date" type="text" name="to_date">


                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="top-10-txt down_btn">

                                            <button class="export ap_bg">Filter</button>


                                        </div>
                                    </div>
                                </div>


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

            </section> <?php */?> 

            <br/> <br/><br/> <br/><br/> <br/><br/> <br/>  
        </div>
    </div>


<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>
<?php $this->load->view('includes/common_session_popup');?>

<script type="text/javascript"> 
// function getuserinfo(id){ 
//     $('#submit').attr("disabled", true);
//     $('#userid').html('');
//     var inputfield = $('#'+id).val(); 
//     var checkvalue = $.isNumeric( inputfield ); 
//     if( checkvalue ){ 
//         $('#userid').html('<option value="">Select User</option>'); 
//         $("#usertype").find('option:selected').removeAttr("selected");
//     }else{
//         $('#mobileno').val(''); 
//     }

//     if(inputfield.length > 0 && inputfield != '0000000000' ){   
//         $.ajax({
//             type: 'POST',
//             url: '<?= base_url($folder.'/'.$pagename.'/getuserinfo');?>',
//             data:{'inputfield': inputfield },
//             beforeSend: function(){ /*$('#submit').hide(); $('#pleasewait').show(); */},
//             //dataType:'html', 
//             success:function(res){ 
//                 $('#userid').html('<option value="">Select User</option>');
//                 var obj = JSON.parse(res);
//                 if( obj.status ){
//                     var dataObj = obj.list; 
//                     $(dataObj).each(function(){ 
//                             var option = $('<option />');
//                             option.attr('value', this.id).text(this.ownername); 
//                             $('#userid').append(option);
//                             if( obj.usertype == 'mobileno' ){
//                             $("#usertype").find("option[value="+this.user_type+"]").attr('selected', 'selected');
//                             $("#userid option[value="+this.id+"]").attr('selected', 'selected');
//                              $("#mobileno").val(this.uniqueid);
//                             }

//                         }); 

//                      $('#submit').removeAttr("disabled");
//                 } 
//                 $('#submit').show(); $('#pleasewait').hide(); 
//             }
//         });
//      }else if(inputfield.length == 0 ){
//         swalpopup('error', 'Please select usertype OR enter 10 digit mobile number' );
//      }  
//   }  

function getuserinfo(id){ 
    $('#submit').attr("disabled", true);
    $('#userid').html('');
    var inputfield = $('#'+id).val(); 
    var uniqueid = $('#mobileno').val();
    if(inputfield.length > 0 && inputfield != '0000000000' ){   
        $.ajax({
            type: 'POST',
            url: '<?= base_url($folder.'/'.$pagename.'/getuserinfo');?>',
            data:{'inputfield': inputfield, uniqueid: uniqueid },
            beforeSend: function(){ 
            },
            success:function(res){ 
                var obj = JSON.parse(res);
                if( obj.status ){
                    var dataObj = obj.list; 
                    $(dataObj).each(function(){ 
                            var option = $('<option />');
                            option.attr('value', this.id).text(this.ownername); 
                            $('#userid').append(option);
                    });

                     $('#submit').removeAttr("disabled");
                } 
                $('#submit').show(); $('#pleasewait').hide(); 
            }
        });
     }else if(inputfield.length == 0 ){
        swalpopup('error', 'Please select user type' );
     }  
  }  

function getusertype(id){ 
    $('#submit').attr("disabled", true);
    $('#userid').html('');
    var inputfield = $('#'+id).val(); 
    if(inputfield.length > 0 && inputfield != '0000000000' ){   
        $.ajax({
            type: 'POST',
            url: '<?= base_url($folder.'/'.$pagename.'/getusertype');?>',
            data:{'inputfield': inputfield },
            beforeSend: function(){ 
            },
            success:function(res){ 
                $('#userid').html('<option value="">Select User</option>');
                var obj = JSON.parse(res);
                if( obj.status ){
                    var dataObj = obj.list; 
                    $("#usertype").html('');
                    var option = '<option value="">Select User Type</option>';
                    $(dataObj).each(function(){ 
                        option += `<option value="${this.user_type}">${this.user_type}</option>`
                    });
                    $("#usertype").append(option);
                } 
                $('#submit').show(); 
                $('#pleasewait').hide(); 
            }
        });
     } else if(inputfield.length == 0 ){
        swalpopup('error', 'Please enter 10 digit mobile number' );
     }  
  }

function credit_Amount(){
    var userid = $('#userid').val();
    var amount = $('#credit_amount').val();
    var comments = $('#comments').val();
    var usertype = $('#usertype').val();
    if( userid == ''){
        swalpopup('error', 'Please select usertype OR enter 10 digit mobile number' );
        return false;
    }else if( amount == ''){
        swalpopup('error', 'Please enter credit amount' );
        return false;
    }else if( comments == ''){
        swalpopup('error', 'Please enter some comments' );
        return false;
    }else if( usertype == ''){
        swalpopup('error', 'Please select usertype' );
        return false;
    }

    if(userid.length > 0 && amount != 0 && comments.length > 0 ){   
        $.ajax({
            type: 'POST',
            url: '<?= base_url($folder.'/'.$pagename.'/credit');?>',
            data:{'userid': userid,'amount':amount,'comments':comments,'usertype':usertype },
            beforeSend: function(){ 
                $('#submit').hide(); $('#pleasewait').show(); }, 
            success:function(res){  
                var obj = JSON.parse(res);
                if(obj.status){
                    swalpopup('success', obj.message );
                }else{
                     swalpopup('error', obj.message );
                } 
                $('#submit').show(); $('#pleasewait').hide(); 
                setTimeout(function(){ window.location.href="<?=base_url($folder.'/'.$pagename);?>";},3000);
            }
        });

     }  

} 
</script>