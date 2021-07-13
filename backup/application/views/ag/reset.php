   <script>
 
function confirmPass() {
    var pass = document.getElementById("pass").value
    var confPass = document.getElementById("c_pass").value
    if(pass != confPass) {
        //alert('Wrong confirm password !');
        document.getElementById('error').innerHTML='wrong confirm password';
    }
    else
    {
        document.getElementById('error').innerHTML='';
    }
}
       </script>
    <div class="content">
        <div class="container">

            <div class="agent-login">
                <div class="login-area">
                    <div class="row">
                        <div class="col-md-5 pr-0">
                            <div class="login-from">
                                <h2 style="font-size: 20px;"> Change Password </h2>
                                <?= notification();?>
                                    <div class="border-login">

                                    </div>
                                    <form action="<?= ADMINURL.'ag/Reset_pass/resetpass';?>" method="post" class="">
                                         <div class="form-group">
                                            <input type="password" id="pldpass" name="oldpass" class="form-control" placeholder="Enter Old Password">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" id="pass" name="password" class="form-control" placeholder="Enter Password">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" id="c_pass" name="cpassword" class="form-control" placeholder="Confirm Password" onblur="confirmPass()">
                                            <span id="error" style="color:#F00;"> </span>

                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class=" submit_btn" value="Submit">
                                        </div>

                                    </form>

                                    <a href="<?= ADMINURL.'login';?>"> Login Now</a>
                            </div>

                        </div>
                        <div class="col-md-7 pl-0">
                            <div class="login-img">
                                <img src="<?= base_url('uploads/login.png');?>" alt="">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <br>
            <br>

        </div>
    </div>
    <?php include('includes/footer.php');?>
        <?php include('includes/alljs.php');?>
</body>

</html>