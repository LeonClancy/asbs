<?php
class Testing extends CI_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function testapi()
    {
        $this->load->view('testapi/testpage');
    }
}