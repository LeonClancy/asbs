<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller{

    public function __construct(){

        parent::__construct();
        $this->load->library('session');
        $this->load->model('Booking_model');
        $this->load->model('Space_model');
        $this->load->view('header');
        $this->load->view('sidebar');
    }

    public function welcome()
    {
        $this->load->view('index');
    }

    public function index($input = 0)
    {
        // 蒐集父類別以下的場地
        $space = $this->Space_model->getSpacelist($input);

        // 蒐集到根的場地
        // $space_traversal = $this->ASBS_space->space_traversal();

        $this->load->view('list', array('space'=>$space));
        $this->load->view('footer');
    }

    public function bookingList()
    {
      if($this->session->userdata('logged')){
        $id = $this->session->userdata('id');
        $memeber_id = $this->session->userdata('id');
        $result = $this->Booking_model->getBookingByMemberId($memeber_id);

        $this->load->view('booking/bookByMember',array(
          'data' => $result
        ));
        $this->load->view('footer');
      } else {
        header("Location:".site_url('home/login'));
      }
    }

    public function activityForm()
    {
      $attrib = $this->Activity_model->getAllAttrib();
      $this->load->view('activity/activityForm', array(
        'attrib'=>$attrib
      ));
      $this->load->view('footer');
    }
    
}