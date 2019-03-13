<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity extends CI_Controller{

    public function __construct()
    {
      parent::__construct();
      $this->load->library('session');
      $this->load->model('Activity_model');
    }
    
    public function addActivity()
    {
      if($this->session->userdata('logged')) {
        $id = $this->session->userdata('id');
        $name = $this->input->post('name');
        $attrib = $this->input->post('attrib');
        $pop = $this->input->post('pop');

        $input = (Object)array(
          'name' => $name,
          'attrib' => $attrib,
          'member_id' => $id,
          'pop' => $pop
        );

        $this->Activity_model->addActivity($input);

        header('Location:'.site_url('main/activityList'));

      } else {
        header('Location:'.site_url('home/login'));
      }
    }

    public function addAttrib()
    {
      $name = $this->input->post('name');
      $input = (Object)array(
        'name'=>$name
      );
      $query = $this->Activity_model->addAttrib($input);
      if($query == 0) {
        echo 'no';
      }else if($query == 1){
        echo 'yes';
      }else {
        echo 'error';
      }
    }

    public function delAttrib()
    {
      $id = $this->input->post('id');
      $query = $this->Activity_model->delAttrib($id);
      if($query == 0) {
        echo 'no';
      }else if($query == 1){
        echo 'yes';
      }else {
        echo 'error';
      }
    }
}
?>