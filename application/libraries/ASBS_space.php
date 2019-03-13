<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ASBS_space{

    function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->model('Space_model');
        $this->ci->load->model('Booking_model');
    }

    public $space_train = array();

    public function space_traversal($input)
    {
        if($input = 0) {
            return $space_train;
        } else {
            $ready_to_push = $this->ci->space_model->getSpace($input);
        }
    }

    public function booking_limit($member_id, $space_id)
    {
        $bookingByMember = $this->ci->Booking_model->getBookingByMemberId($member_id);
        $bookingByMemberNum = $bookingByMember->num_rows();
        $input = (Object)array(
            'member_id' => $member_id,
            'space_id' => $space_id
        );
        $bookingByMemberAndSpace = $this->ci->Booking_model->getBookingByMemberAndSpace($input);
        $bookingByMemberAndSpaceNum = $bookingByMemberAndSpace->num_rows();

        if($bookingByMemberNum > 3 || $bookingByMemberAndSpaceNum == 1) {
            return false;
        } else {
            return true;
        }
    } 
}