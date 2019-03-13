<?php
class Space extends CI_controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Space_model');
    }

    function addSpace($input)
    {
        $name = $this->input->post('spaceName');
        $info = $this->input->post('spaceInfo');
        $vacancy = $this->input->post('spaceVacancy');
        $f_id = $input;

        $this->Space_model->addSpace((Object)array(
            "name" => $name,
            "info" => $info,
            "vacancy" => $vacancy,
            "f_id" => $f_id
        ));

        header("Location:".site_url('main/spaceManagement/').$input);
        
    }

    function delSpace()
    {
        $id = $this->input->post('id');
        $query = $this->Space_model->delSpace($id);
        if($query == 0) {
            echo 'no';
        }else if($query == 1){
            echo 'yes';
        }else {
            echo 'error';
        }
    }

    function modifySpace($input)
    {

        $name = $this->input->post('spaceName');
        $info = $this->input->post('spaceInfo');
        $vacancy = $this->input->post('spaceVacancy');
        $id = $input;

        $query = $this->Space_model->getSpace($input);
        $query = $query->result_array();

        $this->Space_model->modifySpace((Object)array(
            'name' => $name,
            'info' => $info,
            'vacancy' => $vacancy,
            'id' => $id
        ));

        header("Location:".site_url('main/spaceManagement/').$query[0]['f_id']);
    }
}