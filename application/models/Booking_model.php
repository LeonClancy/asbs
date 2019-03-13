<?php
class Booking_model extends CI_Model {

  public function __construct() {
    parent::__construct();
  }

  public function getAllBooking() {
    $sql = "SELECT b.id,b.department, b.activity, m.name AS `member`,sp.name AS `space`, `date`,`time`, b.status AS `status_code`,st.name AS `status`
				 FROM booking b
					 LEFT JOIN `status` st ON b.status = st.id
					 LEFT JOIN `member` m ON b.member = m.id
					 LEFT JOIN `space` sp ON b.space = sp.id";

    $result = $this->db->query($sql);
    return $result;
  }

  public function getBookingBySpaceId($input) {
    $sql =
    "SELECT b.id, b.department, b.activity, `date`,`time`, b.status AS `status_code`,st.name AS `status`
				 FROM booking b
						LEFT JOIN `status` st ON b.status = st.id
				 WHERE space = " . $input->id;

    $result = $this->db->query($sql);
    return $result;
  }

  public function getBookingByMemberId($input) {
    $sql = "SELECT b.activity, b.department ,sp.name AS `space`, `date`, `time`,st.id AS `status_code`, st.name AS `status`
			FROM booking b
				LEFT JOIN `status` st ON b.status = st.id
				LEFT JOIN `member` m ON b.member = m.id
				LEFT JOIN `space` sp ON b.space = sp.id
			WHERE m.id = " . $input;

    $result = $this->db->query($sql);
    return $result;
  }

  public function getBookingByMemberAndSpace($input) {
    $sql = "SELECT b.activity AS `actName`, b.department ,sp.name AS `space`, `date`,`time`, b.status AS `status_code`,st.name AS `status`
			FROM booking b
				LEFT JOIN `status` st ON b.status = st.id
				LEFT JOIN `member` m ON b.member = m.id
				LEFT JOIN `space` sp ON b.space = sp.id
			WHERE m.id =" .$input->member_id. "AND sp.id = " .$input->space_id;

    $result = $this->db->query($sql);
    return $result;
  }

  public function booking($input) {
    $sql = "INSERT INTO `booking` (
				`id`,`member`, `activity`, `department`, `space`, `date`, `time`, `status`
				) VALUES (NULL, ?, ?, ?, ?, ?, ?, 0)";

    $result = $this->db->query($sql, array(
      $input->member,
      $input->activity,
      $input->department,
      $input->space,
      $input->date,
      $input->time,
    ));

    return $result;
  }

  public function bookingCheck($input) {
    $sql = "UPDATE `booking` SET `status` = ? WHERE `booking`.`id` = ?";
    $result = $this->db->query($sql, array(
      $input->status,
      $input->id,
    ));
    return $result;
  }

  public function sumBookingByMember($input) {
    $sql = "SELECT * FROM `booking` WHERE `member` = ? and `status` = '0'";
    $result = $this->db->query($sql,array(
      $input
    ));
    return $result;
  }

  public function getStatus($input) {
    $sql = "SELECT `status` FROM booking where id =" . $input->id;
    return $this->db->query($sql);
  }
}
