 <div class="content"> 
            <div class="container">

                <section class="common-padding">

                    <div class="common-area">
                        <div class="common-heading">
                            <h2 class="color-blue"> <?=$title;?>  </h2>
                        </div>
 


                                <div class="row" style="margin-top:10px">

                                    <div class="col-12 col-md-12 col-lg-12">
                                        <br/><br/>
                                       <h2 align="center" style="color:<?=$textcolor;?>"><?=$text;?></h2>
                                       <?php if($textcolor =='#ff003b'){?>
                                        <h4 align="center" >Kindly contact Customer Support..!!</h4>
                                       <?php }?>
                                       <br/><br/>
                                    </div> 
                                </div>
                            
                        </div>
                    </div><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/> 

                </section>





               </div>
            </section>
           <br/>
         </div>
</div>


<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>  
</body>

</html>