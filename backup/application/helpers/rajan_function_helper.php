<?php

    function upload_files($path=false, $types=false, $files, $name_prefix="General_")
    {
        if($path) $config["upload_path"]=$path;
        else $config["upload_path"]="./uploads/";
        if($types) $config["allowed_types"]=$types;
        else $config["allowed_types"]="jpg|png|jpeg|gif|pdf";

        $config["overwrite"]="1";

        ci()->load->library('upload', $config);

        $images = array();

         foreach ($files['name'] as $key => $image) 
        {
            $_FILES['files[]']['name']= $files['name'][$key];
            $_FILES['files[]']['type']= $files['type'][$key];
            $_FILES['files[]']['tmp_name']= $files['tmp_name'][$key];
            $_FILES['files[]']['error']= $files['error'][$key];
            $_FILES['files[]']['size']= $files['size'][$key];

            $ext = pathinfo($files['name'][$key], PATHINFO_EXTENSION);

            $fileName = $name_prefix.md5(time().rand(1000000,9999999)).'.'.$ext;  

            $config['file_name'] = $fileName;

            ci()->upload->initialize($config);
            if( in_array( $ext, ['jpg','png','jpeg','gif','pdf'])){
                
                $status = ci()->upload->do_upload('files[]');
                if($status){ $images[] = $fileName; }
            }

           
        }
        return $images;
    }