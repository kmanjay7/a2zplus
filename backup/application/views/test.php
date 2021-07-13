<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> <?=$title;?> </title>
     

<body> 
 

<input type="text" value="0" name="latitude" id="latitude"> 
<input type="text" value="0" name="longitude" id="longitude">

<a href="#" onclick="checklocation()">Check Location</a>

<?php $this->load->view('includes/loadmap');?> 





        <!---footer---->
<?php $this->load->view('includes/alljs'); ?>

<script type="text/javascript">
    function checklocation(){
        var lat = $('#latitude').val();
        var long = $('#longitude').val();
        var murl = 'https://www.google.com/maps/dir/'+lat+','+long+'/'+lat+','+long;
        window.open( murl,'_blank');
    }
</script>
         
</body>

</html>