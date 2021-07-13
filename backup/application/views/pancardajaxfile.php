<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
       $('#ao_cityarea').on('change',function(){
        $('#ao_ward').html('');
        var cityId = $(this).val();
        if(cityId){
            $.ajax({
                type:'POST',
                url:'<?php echo base_url('ajax/Wardcircle/wardlist'); ?>',
                data:'id='+cityId,
                success:function(data){
                     var dataObj = jQuery.parseJSON(data);
                    if(dataObj){
                        $(dataObj).each(function(){
                            var option = $('<option />');
                            option.attr('value', this.id).text(this.ward_circle);           
                            $('#ao_ward').append(option);
                        });
                    }else{
                        $('#ao_ward').html('<option value="">City not available</option>');
                    }
                    
                    var pid = dataObj[0].id
                            $.ajax({
                                type: 'POST',
                                url: '<?php echo base_url('ajax/Wardcircle/detail');?>',
                                data: 'id=' + pid,
                                success: function(data) {
                                    var resultJson = jQuery.parseJSON(data);
                                    var status = resultJson.status;
                                    if (status == '1') {
                                        var id = resultJson.id;
                                        var areacode = resultJson.areacode;
                                        var aotype = resultJson.aotype;
                                        var rangecode = resultJson.rangecode;
                                        var aono = resultJson.aono;
                                        $('#ao_areacode').val(areacode);
                                        $('#ao_type').val(aotype);
                                        $('#ao_rangecode').val(rangecode);
                                        $('#ao_no').val(aono);
                                    }
                    }
                });
                    
                    
                    
                    
                }
            }); 
        }else{
            $('#ao_ward').html('<option value="">Select State first</option>');
        }
    }); 
        
        /* Populate data to Ward Circle dropdown */
        $('#ao_ward').on('change', function() {
        $('#ao_areacode').val('');
        $('#ao_type').val('');
        $('#ao_rangecode').val('');
        $('#ao_no').val('');
            var id = $(this).val();
            if (id) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('ajax/Wardcircle/detail');?>',
                    data: 'id=' + id,
                    success: function(data) {
                        var resultJson = jQuery.parseJSON(data);
                        var status = resultJson.status;
                        if (status == '1') {
                            var id = resultJson.id;
                            var areacode = resultJson.areacode;
                            var aotype = resultJson.aotype;
                            var rangecode = resultJson.rangecode;
                            var aono = resultJson.aono;
                            $('#ao_areacode').val(areacode);
                            $('#ao_type').val(aotype);
                            $('#ao_rangecode').val(rangecode);
                            $('#ao_no').val(aono);
                        }
                    }
                });
            } else {
               return false;
            }
        });
        
        $('#aplicanttype').on('change',function(){
        $('#identity').html('');
        $('#dob').html('');
        $('#address').html('');
        var aplicanttype = $(this).val();
        if(aplicanttype){
           $.ajax({
                type:'POST',
                url:'<?php echo base_url('ajax/Applicationtype/dob'); ?>',
                data:'id='+aplicanttype,
                success:function(data){
                     var dataObj = jQuery.parseJSON(data);
                    if(dataObj){
                        $(dataObj).each(function(){
                            var option = $('<option />');
                            option.attr('value', this.id).text(this.content);           
                            $('#dob').append(option);
                        });
                    }else{
                        $('#dob').html('<option value="">Record not available</option>');
                    }
                }
            }); 
            $.ajax({
                type:'POST',
                url:'<?php echo base_url('ajax/Applicationtype/Identity'); ?>',
                data:'id='+aplicanttype,
                success:function(data){
                     var dataObj = jQuery.parseJSON(data);
                    if(dataObj){
                        $(dataObj).each(function(){
                            var option = $('<option />');
                            option.attr('value', this.id).text(this.content);           
                            $('#identity').append(option);
                        });
                    }else{
                        $('#identity').html('<option value="">Record not available</option>');
                    }
                }
            }); 
            $.ajax({
                type:'POST',
                url:'<?php echo base_url('ajax/Applicationtype/Address'); ?>',
                data:'id='+aplicanttype,
                success:function(data){
                     var dataObj = jQuery.parseJSON(data);
                    if(dataObj){
                        $(dataObj).each(function(){
                            var option = $('<option />');
                            option.attr('value', this.id).text(this.content);           
                            $('#address').append(option);
                        });
                    }else{
                        $('#address').html('<option value="">Record not available</option>');
                    }
                }
            }); 
        }else{
            $('#ao_ward').html('<option value="">Select State first</option>');
        }
    }); 
        
    });
</script>

<script type="text/javascript">
	$(document).ready(function(){
           // $('#Button').attr('disabled','disabled');
                    $('#submitt').on('click',function(e){
		    e.preventDefault();
			var formData = new FormData();
			formData.append('file',$('#uplodfile')[0].files[0])
		         $.ajax({
		             url:'<?php echo base_url('ajax/Upload');?>',
		             type:"post",
                              beforeSend: function(){
                               $('#message').css("display", "Block");
                                    var duration = 5000; // it should finish in 5 seconds !
                                  $("#box").stop().css("width", 0).animate({
                                    width: 100
                                  }, {
                                    duration: duration,
                                    progress: function(promise, progress, ms) {
                                      $(this).text(Math.round(progress * 100) + '%');
                                    }
                                  })
                              },
		             data:formData,
		             processData:false,
		             contentType:false,
		             cache:false,
		             async:false,
		              success: function(data){
		           },
                              complete: function(){
                                $('#message').fadeOut(7500);
                                // $('#Button').removeAttr('disabled');
                             }    
		         });
		    });
	});
</script>
<script type="text/javascript">
 function ajaxSearch(){
    $('#autoSuggestionsList').html('');
                var input_data = $('#c_city_district').val();
                if (input_data.length === 0){
                    $('#suggestions').hide();
                }else{
                    var post_data = {
                        'search_data': input_data,
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                };
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('ajax/Searchstate'); ?>",
                        data: post_data,
                        success: function (data) {
                            if (data.length > 0) {
                                $('#suggestions').show();
                                $('#autoSuggestionsList').addClass('auto_list');
                                $('#autoSuggestionsList').html(data);
                            }
                             $(document).on('click','li',function(){
                                    $('#c_city_district').val($(this).text());
                                    var cityname = $('#c_city_district').val()
                                   $.ajax({
                                            type:'POST',
                                            url:'<?php echo base_url('ajax/Searchstate/state'); ?>',
                                            data:'cityname='+cityname,
                                            success:function(responce){
                                                $('#stateunion').val(responce);
                                            }
                                   });
                            });

                            $(document).on('click', function(e) {
                            $('#suggestions').hide();
                            });
                        }
                    });

                }
            }
            

function ajaxSearch2(){
                var input_data = $('#rep_district_town').val();
                if (input_data.length === 0)
                {
                    $('#suggestions2').hide();
                }
                else
                {
                    var post_data = {
                        'search_data': input_data,
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                    };
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('ajax/Searchstate'); ?>",
                        data: post_data,
                        success: function (data) {
                            if (data.length > 0) {
                                $('#suggestions2').show();
                                $('#autoSuggestionsList2').addClass('auto_list');
                                $('#autoSuggestionsList2').html(data);
                            }
                             $(document).on('click','#autoSuggestionsList2 li',function(){
                                    $('#rep_district_town').val($(this).text());
                                    var cityname = $('#rep_district_town').val()
                                   $.ajax({
                            type:'POST',
                            url:'<?php echo base_url('ajax/Searchstate/state'); ?>',
                            data:'cityname='+cityname,
                            success:function(responce){
                                $('#rep_stateid_ut').val(responce);
                            }
                        }); 
                            });
                            $(document).on('click', function(e) {
                            $('#suggestions2').hide();
                            });
                        }
                    });

                }
            }
           
        </script>

<script type="text/javascript">
    function validatePanform(){
      if(){  }

    } 
</script>
