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
                                <?= form_dropdown(array('name'=>'userid','class'=>'form-control','id'=>'userid','onchange'=>'getRecord( this.value );'),array(''=>'Select User') );?> 
                            </div>
                        </div> 

                    </div>

<!-- borrow listing start here  -->



<!-- borrow listing end here  --> 


 
                </div>
            </section> 

            

            <section class="">
                <div class="report-area">
                    <div class="report-area-t">  
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
                                                <th>Action </th>

                                            </tr>
                                        </thead>
                                        <tbody id="loadrecord">

<?php
    echo '<tr id="norecord"><td colspan="7"><center>No Record in Our Database</center></td></tr>';
    echo '<tr id="pleasewait"><td colspan="7"><center> <img src="'.ADMINURL.'assets/images/loadergif.gif" style="display:none ; width: 100px; margin-top: 10px" ></img></center></td></tr>';
   ?>

</tbody>
</table>

                                </div> 

                            </div>

                        </div>

                    </div>



                </div>

            </section>


            <br> <br>

        </div>
    </div>


<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>
<?php $this->load->view('includes/common_session_popup');?>

<script type="text/javascript"> 
// function getuserinfo(id){ 
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
//             beforeSend: function(){ /*$('#norecord').hide(); $('#pleasewait').show();*/ },
             
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
//                              getRecord( this.id );
//                             }

//                         }); 

                    
//                 } 
//                 $('#norecord').show(); $('#pleasewait').hide(); 
//             }
//         });
//      }else if(inputfield.length == 0 ){
//         swalpopup('error', 'Please select usertype OR enter 10 digit mobile number' );
//      }  
//   }  

function getuserinfo(id){ 
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
                $('#userid').html('<option value="">Select User</option>');
                var obj = JSON.parse(res);
                if( obj.status ){
                    var dataObj = obj.list; 
                    $(dataObj).each(function(){ 
                            var option = $('<option />');
                            option.attr('value', this.id).text(this.ownername); 
                            $('#userid').append(option);
                            if( obj.usertype == 'mobileno' ){
                            $("#usertype").find("option[value="+this.user_type+"]").attr('selected', 'selected');
                            $("#userid option[value="+this.id+"]").attr('selected', 'selected');
                             $("#mobileno").val(this.uniqueid);
                             getRecord( this.id );
                            }
                        }); 
                } 
                $('#norecord').show(); $('#pleasewait').hide(); 
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

function debit_Amount(id,vid){
    
    if(id.length > 0 ){   
        $.ajax({
            type: 'POST',
            url: '<?= base_url($folder.'/'.$pagename.'/debit');?>',
            data:{'id': id },
            beforeSend: function(){ 
                $('#vid'+vid ).html('<img src="<?=ADMINURL.'assets/images/loadergif.gif'?>" style="width: 53px;" />'); }, 
            success:function(res){  
                var obj = JSON.parse(res); 
                if(obj.status){
                    $('#rowid-'+vid).hide();
                    swalpopup('success', obj.message );
                }else{
                    swalpopup('error', obj.message );
                $('#vid'+vid ).html("<button class='update-btn' onclick='return confirm('Are You Sure?'),debit_Amount("+id+","+vid+")'> Debit </button>"); 
                }    
            }
        });

     }  

}


function getRecord(id){ 
  if( id ){
    
      $.ajax({
            type: 'POST',
            url: '<?= base_url($folder.'/'.$pagename.'/getRecord');?>',
            data:{'id': id },
            beforeSend: function(){ 
                $('#norecord').hide(); $('#pleasewait').show(); }, 
            success:function(res){  
                 $('#loadrecord').html(res);
                $('#norecord').show(); $('#pleasewait').hide(); 
            }
        });

  }
}

</script>