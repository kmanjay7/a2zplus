<script type="text/javascript">
    function complaint(id,type){
        var type = type;
        var userid = '<?=getloggeduserdata('id');?>'; 
            $.ajax({
            type:'POST',
            url:'<?php echo base_url('ajax/Make_complaint' );?>',
            data:{'servicename':type,'tableid':id,'userid':userid},
            beforeSend:function(){ 
            $(".cp-"+id).attr("disabled", true); 
            $('#plwait-'+id).html('Please Wait..'); },
            success:function(res){  
            var obj = JSON.parse(res);  
            if(obj.status){   
                //$(".cp-"+id).hide();
                $(".cp-"+id).removeAttr("disabled");
                $('#plwait-'+id).html('Pending');
                $(".cp-"+id).addClass("cp-warn"); 
                swalpopup('success', obj.message ); 
            }else{
                swalpopup('error', obj.message );
                $(".cp-"+id).removeAttr("disabled"); 
                $('#plwait-'+id).html('Complaint');

            }
            }, 

            });

    }
</script>