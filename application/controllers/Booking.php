<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Booking_model');
    $this->load->view('header');
    $this->load->view('sidebar');
  }

  public function bookingView($input) {
    $space_id = $input;
    $input = (Object) array(
      'id' => $space_id,
    );
    $data = $this->Booking_model->getBookingBySpaceId($input);
    $member_id = $this->session->userdata('id');
    $check = $this->Booking_model->sumBookingByMember($member_id)->result_array();
    
    $this->load->view('booking/bookingList', array(
      'space_id' => $space_id,
      'data' => $data,
      'check' => $check
    ));
    
    $this->load->view('footer');
  }
 
  public function bookingForm($input) {
    if ($this->session->userdata('logged')) {
      $id = $this->session->userdata('id');
      $data = (Object) array(
        'id' => $id,
      );
      $this->load->view('booking/bookView', array(
        'space_id' => $input,
      ));
      $this->load->view('footer');
    } else {
      header('Location:' . site_url('home/login'));
    }
  }

  public function booking($input) {

    // foreach ($_POST as $key => $value)  
    //  echo "key:".htmlspecialchars($key)." vale:".htmlspecialchars($value). "<br>";

    $member_id = $this->session->userdata('id');
    $space_id = $input;
    $activity = $this->input->post('activity');
    $department = $this->input->post('department');
    $date = $this->input->post('date');
    $time = $this->input->post('time');

    $data = (Object) array(
      'activity' => $activity,
      'member' => $member_id,
      'department' => $department,
      'space' => $space_id,
      'date' => $date,
      'time' => $time,
    );
    $this->Booking_model->booking($data);
    header('Location:' . site_url('booking/bookingView/' . $space_id));

  }
}