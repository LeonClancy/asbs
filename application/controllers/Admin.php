<?php
class Admin extends CI_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Member_model');
        $this->load->model('Space_model');
        $this->load->model('Booking_model');
    }
    
    //會員管理
    public function memberManagement()
	{
        if($this->session->userdata('role') == "A") {
            $this->load->view('header');
            $this->load->view('admin/adminSidebar');
            $member_data = $this->Member_model->getAllUser();
            $this->load->view('admin/memberManagement',array(
                'data' => $member_data
            ));
            $this->load->view('footer');
        } else {
            header('Location: '.site_url('member/login'));
        }
    }
    
    //場地管理
    public function spaceManagement($input = 0)
    {
        if($this->session->userdata('role') == "A") {
            $this->load->view('header');
            $this->load->view('admin/adminSidebar');
            $space = $this->Space_model->getSpaceList($input);
            $spaceDetail = array();
            if($input==0) {
                $spaceDetail = array(
                    "id" => 0,
                    "name" => "根目錄"
                );
            } else {
                $data = $this->Space_model->getSpace($input);
                $data = $data->result_array();
                $spaceDetail = array(
                    "id" => $data[0]['id'],
                    "name" => $data[0]['name']
                );
            }
    
            $this->load->view('admin/space_management', array(
                'space' => $space,
                'spaceDetail' => $spaceDetail
            ));
            $this->load->view('footer');
        } else {
            header('Location: '.site_url('member/login'));
        }
    }

    //活動管理
    public function activityManagement()
    {
        if($this->session->userdata('role') == "A") {
            $this->load->view('header');
            $this->load->view('admin/adminSidebar');
            $data = $this->Activity_model->getAllActivity();
            $this->load->view('admin/activityManagement',array(
                'data'=>$data
            ));
            $this->load->view('footer');
        } else {
            header('Location:'.site_url('member/login'));
        }
    }

    //活動性質管理
    public function attribManagement()
    {
        if($this->session->userdata('role') == "A") {
            $this->load->view('header');
            $this->load->view('admin/adminSidebar');
            $data = $this->Activity_model->getAllAttrib();
            $this->load->view('admin/attribManagement',array(
                'data'=>$data
            ));
            $this->load->view('footer');
        } else {
            header('Location:'.site_url('home/login'));
        }
    }

    public function bookingManagement()
    {
        if($this->session->userdata('role') == "A") {
            $this->load->view('header');
            $this->load->view('admin/adminSidebar');
            $data = $this->Booking_model->getAllBooking();
            $this->load->view('admin/bookingManagement', array(
                'data' => $data
            ));
            $this->load->view('footer')
;        } else {
            header('Location:'.site_url('home/login'));
        }
    }

    public function check()
    {
        $id = $this->input->post('id');
        $input = (Object)array('id'=>$id);

        $status = (int)$this->Booking_model->getStatus($input)->result_array()[0]['status'];

        if($status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        $data = (Object)array(
            'id' => $id,
            'status' => $status
        );

        $query = $this->Booking_model->bookingCheck($data);

        if($query == 0) {
            echo 'no';
        } else if($query == 1) {
            echo 'yes';
        } else {
            echo 'error';
        }
    }
    public function delMember()
    {
        $id = $this->input->post('id');
        $query = $this->Member_model->deleteMember($id);
        if($query == 0) {
            echo 'no';
        }else if($query == 1){
            echo 'yes';
        }else {
            echo 'error';
        }
    }
}