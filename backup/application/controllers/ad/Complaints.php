<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Complaints extends CI_Controller{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');  
        $this->load->model("general_model");
        $this->load->model("complaint_model");
        $this->load->helper("rajan_function");
        adsession_check();
    }

    public function manual()
    {
        // $closeId=$this->input->get("closeId");
        // if($closeId)
        // {
        //     $this->general_model->update("complaint_manual", ["is_closed"=>1], ["id"=>$closeId]);
        //     $this->session->set_flashdata("success", "Complaint closed successfully.");
        //     redirect("complaints");
        // }

        $this->form_validation->set_rules("name", "Name", "required");
        $this->form_validation->set_rules("email_id", "Email ID", "required");
        $this->form_validation->set_rules("complaint_message", "Complaint Message", "required");
        if($this->form_validation->run())
        {
            if(count($_FILES["files"]["name"])>5)
            {
                $this->session->set_flashdata("error", "Max 5 documents can be uploaded.");
                redirect("ad/complaints/manual");
            }

            $post=$this->input->post();
            $save["name"]=$this->session->userdata('ownername');
            $save["email_id"]=$this->session->userdata('emailid');
            $save["complaint_message"]=$post["complaint_message"];
            $save["user_id"]=$this->session->userdata("uniqueid");
            $save["userid"]=$this->session->userdata("id");


            $save["created"]=date("Y-m-d H:i:s");
            $id=$this->general_model->save("complaint_manual", $save);


            if($_FILES["files"]["name"][0]!="")
            {
                $files=upload_files(false, false, $_FILES["files"], "Complaint_manual_");

                foreach ($files as $file) 
                {
                    $fileData["reference"]="complaint_manual";
                    $fileData["ref_id"]=$id;
                    $fileData["file"]=$file;
                    $fileData["created"]=date("Y-m-d H:i:s");
                    $this->general_model->save("files", $fileData);
                }
            }

            $this->session->set_flashdata("success", "Saved Successfully.");
            redirect("ad/complaints/manual");
        }

        $data['title'] = 'Manual Complaints';
        $data['folder'] = 'ad';
        $data['pagename'] = 'complaint_manual';

        $dateFrom=$this->input->get("from");
        $dateTo=$this->input->get("to");

        $where=[];

        if($dateFrom) $where["DATE(created)>="]=date("Y-m-d", strtotime($dateFrom));
        if($dateTo) $where["DATE(created)<="]=date("Y-m-d", strtotime($dateTo));

        $where["userid"]=$this->session->userdata("id");

        $userIds=$this->general_model->getAll("users", ["parentid"=>$this->session->userdata("id")], "id");

        $ids=[];
        foreach($userIds as $userid)
        {
            array_push($ids, $userid["id"]);
        }
        array_push($ids, $this->session->userdata("id"));
        
        $rows=$this->complaint_model->getComplaints($ids, $where);

        $i=0;
        foreach($rows as $row)
        {
            $files=$this->general_model->getAll("files", ["reference"=>"complaint_manual", "ref_id"=>$row["id"]]);
            $rows[$i]["files"]=$files;
            $i++;
        }
        $data["rows"]=$rows;

        adview('complaint_manual', $data);
    }  
    
    function get_reply()
    {
        $id=$this->input->post("id");
        $complaint=$this->general_model->getSingle("complaint_manual", ["id"=>$id]);
        echo json_encode(["status"=>true, "reply_message"=>$complaint["reply_message"]]);
    }
 


}