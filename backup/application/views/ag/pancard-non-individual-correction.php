<style type="text/css">
 .auto_list {
    right: 6%;
    z-index: 99999;
    border: 1px solid #E3E3E3;
    border-radius: 5px 5px 5px 5px;
    position: absolute;
    top: 68px;
    color: black !important;
    width: 233px;
    background: #fff;
    border-radius: 0px;
}

#autoSuggestionsList ul > li {
        background: none repeat scroll 0 0 #F3F3F3;
        border-bottom: 1px solid #feb01a;
        list-style: none outside none;
        padding: 3px 15px 3px 15px;
        text-align: left;
        cursor: pointer;
    }
dl, ol, ul {
    margin-top: 0;
    margin-bottom: 0rem;
}
</style>

<link href="<?= ADMINURL ?>assets/css/select2.css" rel="stylesheet">
<div class="content">
    <div class="container">

        <section class="cash-area">

            <div class="cash-payment">
                <div class="cash-heading">
                    <h2>Non Individual Correction Form</h2>
                    <?= notification();?>
                </div>
                <form action="<?= ADMINURL.'ag/Non_individual_pan_corr/saveupdate';?>" method="post" enctype="multipart/form-data">

                    <div class="filter-area">

                        <div class="row m-15">
                            <div class="col-md-12 col-lg-12 col-12">
                                <div class="form-group">
                                    <label for="">Old PAN CARD NO </label>
                                    <input class="form-control bg-f" id=""  placeholder="Pan Card No." type="text" name="pancardno" required="">
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Status of Applicant</label>
                                    <select name="applicant_type" class="form-control" id="aplicanttype" required="">
                                        <option value="" selected="" disabled=""> Status of Applicant</option>
                                        <?php if(!empty($pancardtype)){
                                            foreach ($pancardtype as $pancardtypeval) {
                                            ?>
                                        <option value="<?= $pancardtypeval['id'];?>"> <?= $pancardtypeval['pancardtype'];?></option>   
                                            <?php  } }?>
                                        </select>
                                </div>

                            </div>

                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">City/ Area</label>
                                    <select name="ao_cityarea" class="js-select2 form-control" id="cityId" required="">
                                        <option value=""> Select City/ Area </option>
                                        <?php if(!empty($city)){
                                            foreach ($city as $cityval) {
                                            ?>
                                        <option value="<?= $cityval['id'];?>"> <?= $cityval['cityname'];?> </option>
                                        <?php }} ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Ward/ Circle</label>
                                    <select name="ao_ward" id="warcircle" class="js-select2 form-control">
                                        <option selected="" disabled=""> Select Ward/ Circle </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Date</label>
                                    <input class="form-control bg-f"  readonly placeholder="Auto Generated" value="<?= date('Y-m-d');?>" type="text" name="add_date">
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Area Code</label>
                                    <input class="form-control bg-f" id="areacode" readonly placeholder="Auto Generated" type="text" name="ao_areacode">
                                </div>

                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">AO Type</label>
                                    <input class="form-control bg-f" id="aotype" readonly placeholder="Auto Generated" type="text" name="ao_type">
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Range Code</label>
                                    <input class="form-control bg-f" id="rangecode" readonly placeholder="Auto Generated" type="text" name="ao_rangecode">
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">AO No.</label>
                                    <input class="form-control bg-f" id="aono" readonly placeholder="Auto Generated" type="text" name="ao_no">
                                </div>
                            </div>

                            <div class="col-md-2 col-lg-2 col-12">
                                <div class="form-group">
                                    <label for="">Applicant Title</label>
                                    <select class="form-control" id="" name="name_title">
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Dr">Dr</option>
                                        <option value="Phd">Phd</option>
                                    </select>

                                </div>
                            </div>

                            <div class="col-md-10 col-lg-10 col-12">
                                <div class="form-group">
                                    <label for="">Full Name</label>
                                    <input class="form-control " placeholder="Enter Last Name" type="text" name="first_name">
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-12">
                                <div class="form-group">
                                    <label for="">Name on Pancard</label>
                                    <input class="form-control bg-f" placeholder="Name on Pancard" type="text" name="name_on_pancard">
                                </div>
                            </div>


                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Address For Communication</label>
                                    <input class="form-control " placeholder="Resident" type="text" name="address_comunication">
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Pancard Dispached State</label>

                                    <select class="js-select2 form-control" name="pan_dispatch_stateid">
                                        <option value="choose city" selected> Select State</option>
                                        <?php 
                                        if(!empty($state)){
                                            foreach ($state as $stateval) {
                                        ?>
                                        <option value="<?= $stateval['id'];?>"> <?= $stateval['statename'];?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Flat/ Room/ Door/ Block No. </label>
                                    <input class="form-control " placeholder="Enter " type="text" name="c_flat_door_block">
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Premises/ Building/ Village</label>
                                    <input class="form-control " placeholder="Enter " type="text" name="c_build_vill_permis">
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Road/ Street/ Lane/ Post Office </label>
                                    <input class="form-control " placeholder="Enter" type="text" name="c_road_street_post">
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Area/ Locality/ Taluka/ Sub Divi. </label>
                                    <input class="form-control " placeholder="Enter" type="text" name="c_area_subdevision">
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Town/ City/ District </label>
                                    <input class="form-control " id="search_data" onkeyup="ajaxSearch();" placeholder="Enter" type="text" name="c_city_district">
                                <div id="suggestions">
                                    <div id="autoSuggestionsList">
                                    </div>
                                </div>
                            </div>
                                 </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Pin Code/ Zip Code</label>
                                    <input class="form-control " placeholder="Enter" type="text" name="c_pincode">
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">State/ Union Territory</label>
                                    <input class="form-control bg-f" id="stateunion" readonly placeholder="Auto Generated" type="text" name="c_stateid_ut">
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Country Name</label>
                                    <input class="form-control bg-f" readonly placeholder="India" value="India" type="text" name="c_country">
                                </div>
                            </div>

                            <div class="col-md-2 col-lg-2 col-12">
                                <div class="form-group">
                                    <label for="">Country Code</label>
                                    <input class="form-control bg-f" placeholder="+91" value="+91" type="text" name="countrycode">
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-12">
                                <div class="form-group">
                                    <label for="">Mobile Number</label>
                                    <input type="text" class="form-control" name="contact" placeholder="Enter Mobile Number" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" onkeydown="if(this.value.length==10 &amp;&amp; event.keyCode!=8) return false;">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-12">
                                <div class="form-group">
                                    <label for="">Email Address</label>
                                    <input class="form-control " placeholder="Enter Email Address" type="text" name="email">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-12">
                                <div class="form-group">
                                    <label for="">Registration Number</label>
                                    <input class="form-control " placeholder="Enter Aadhaar Card Number" type="text" name="aadhar_no">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-12">
                                <div class="form-group">
                                    <label for="">Name of Office</label>
                                    <input class="form-control " placeholder="Auto Generated" type="text" name="name_on_aadhar">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-12">
                                <div class="form-group">
                                    <label for="">Source of Income</label>
                                    <select name="status" class="form-control" id="" name="soure-income">
                                        <option selected="selected" value="Income from Other sources ">Income from Other sources </option>
                                        <option value="Salary">Salary</option>
                                        <option value="Capital Gains">Capital Gains</option>
                                        <option value="Income from Business / Profession ">Income from Business / Profession </option>
                                        <option value="Income from House property">Income from House property</option>
                                        <option value="No income ">No income </option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Proof of Identity</label>
                                    <select class="js-select2 form-control bg-f" name="id_proof" id="identity">
                                        <option selected="" disabled="">Select</option>
                                       
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">
                                        Proof of Address </label>
                                    <select class="js-select2 form-control bg-f" name="address_proof" id="address">
                                        <option selected="" disabled="">Select</option>
                                      
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Proof of Date of Birth </label>
                                    <select class="js-select2 form-control bg-f" name="dob_proof" id="dob">
                                       <option selected="" disabled="">Select</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Place of Verification </label>
                                    <input class="form-control bg-f" placeholder="Sitapur " type="text" name="verification_place">
                                </div>
                            </div>

                        </div>

                        <div class="row ">

                            <div class="col-md-6 col-lg-6 col-12">
                                <label class="green_c" for=""> Only Require Color Scan PDF in 200 DPI & PDF size below 3 MB
                                </label>
                                <div class="alert alert-success alert-dismissible" id="message" align="center" style="display:none;width:100%;padding:3px;position:absolute;z-index: 9999999999;"> <a href="#" class="close" data-dismiss="alert" aria-label="close" style="right:0px;">&times;</a> <div id="box"></div> </div>
                                <div class="row">

                                    <div class="col-md-6 col-lg-6 col-12">
                                        <div class="upload-btn-wrapper bg-f">
                                            <button class="btn "> Upload Document</button>
                                            <input type="file" id="uplodfile" name="image">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-12">
                                        <div class="form-group">
                                            <input class="form-control apply-filter up_bg w-100" id="submitt" value="Upload" type="submit" name="upload-doucment">
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="col-md-3 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for="">Processing Fee </label>
                                    <input class="form-control bg-f" placeholder="110" type="text" name="proccessing_fee">
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3 col-12 m-15">
                                <div class="form-group m-29">

                                    <input  id="Button" class="form-control apply-filter w-100" value="Submit" type="submit" name="submit">
                                </div>
                            </div>

                            <div class="col-md-12 col-lg-12 col-12 ">
                                <label> <span class="green_c">Note: </span> Aadhaar is mandatory for Pancard. This Application is Acknowledgement only, You Will have Send a Complete filed Pan form along with the valid documents to the reigister office address. </label>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

        </section>

        <br>
        <br>

    </div>
</div>
<script src="<?= ADMINURL ?>assets/js/validation.js"></script>
<?php include('includes/footer.php');?>
<?php include('includes/alljs.php');?>
        <script src="./assets/js/select2.js"></script>

        <script>
            $(document).ready(function() {
                $(".js-select2").select2();
                $(".js-select2-multi").select2();

                $(".large").select2({
                    dropdownCssClass: "big-drop",
                });
            });
        </script>
        <script>
            <?php if($this->session->flashdata('success')){?>
            swal(" <?= $this->session->flashdata('success')?>", "", "success");
            <?php }?>
            <?php if($this->session->flashdata('error')){?>
            swal({
                title: "Warning ?",
                text: "<?= $this->session->flashdata('error')?>!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                closeOnConfirm: false,
            });
            <?php }?>
        </script>
        </body>

        </html>