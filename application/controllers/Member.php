<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('member_model');
    // $this->load->library('cmrdb_member');
    $this->load->library('session');
    date_default_timezone_set("Asia/Taipei");
  }

  // 登入api
  public function loginApi() {
    header('Content-Type: application/json');
    $username = $this->input->post('username');
    $password = $this->input->post('password');
    $data = array();

    if (!empty($username) && !empty($password)) {
      // 有輸入帳號密碼
      $check = $this->member_model->getMember($username);
      $check = $check->result_object();
      // 以輸入帳號搜尋用戶

      if ($check) {
        // 有此用戶
        if ($check[0]->password == $password) {
          // 密碼正確
          $data = array(
            'response' => 'success',
            'data' => array(
              'name' => $check[0]->name,
              'role' => $check[0]->role,
              'lock' => $check[0]->locking,
            ),
          );
          $user_data = array(
            'logged' => true,
            'id' => $check[0]->id,
            'name' => $check[0]->name,
            'role' => $check[0]->role,
            'lock' => $check[0]->locking,
          );
          $this->session->set_userdata($user_data);
        } else {
          // 密碼錯誤
          $data = array(
            'response' => 'failed',
            'data' => '帳號或密碼錯誤',
          );
        }
      } else {
        // 查無帳號
        $data = array(
          'response' => 'failed',
          'data' => '帳號或密碼錯誤',
        );
      }
    } else {
      //其中一項無輸入
      $data = array(
        'response' => 'error',
        'data' => '無效的輸入',
      );
    }
    // 將結果josn傳出
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
  }

  // 登入驗證與轉址
  public function login() {
    if ($this->session->userdata('logged')) {
      if ($this->session->userdata('role') == "U") {
        //使用者登入
        header('Location:' . site_url('main/index'));
      } else if ($this->session->userdata('role') == "A") {
        //管理者登入
        header('Location:' . site_url('admin/spaceManagement'));
      } else {
        //身分不明確，清除session後轉址
        $this->session->sess_destroy();
        header('Location:' . site_url('home/login'));
      }
    } else {
      //並無登入
      header('Location:' . site_url('home/login'));
    }
  }

  // 登出，清除session
  public function logout() {
    $this->session->sess_destroy();
    header('Location:' . site_url('home/login'));
  }

  // 使用者註冊畫面
  public function register() {
    $this->load->view('register');
  }

  // 註冊用api
  public function registerApi() {
    header('Content-Type: application/json');
    $name = $this->input->post('name');
    $email = $this->input->post('email');
    $password = $this->input->post('password');
    $data = array();
    if (!empty($name) && !empty($email) && !empty($password)) {
      // 有輸入姓名帳號密碼
      $check = $this->member_model->getMember($email);
      $check = $check->result_object();
      // 以輸入帳號搜尋用戶
      if ($check) {
        // 有此用戶
        $data = array(
          'response' => 'failed',
          'data' => '此電子信箱已經過註冊',
        );
      } else {
        // 無此帳號，確定可註冊
        $input = (Object) array(
          'name' => $name,
          'email' => $email,
          'password' => $password,
          'role' => 'U',
          'locking' => 1,
        );
        $result = $this->member_model->addMember($input);
        if ($result == 1) {
          $data = array(
            'response' => 'success',
            'data' => '您已經可以登入，但借用功能要等待管理員開通',
          );
        } else {
          $data = array(
            'response' => 'failed',
            'data' => 'Oops,遇到技術上的問題',
          );
        }
      }
    } else {
      //其中一項無輸入
      $data = array(
        'response' => 'error',
        'data' => '無效的輸入',
      );
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
  }

  //將用戶鎖定或解鎖
  public function lock() {
    $id = $this->input->post('id');
    $status = $this->member_model->getMember($id)->result_array()['locking'];
    if ($status == 0) {
      $input = (Object) array(
        'id' => $id,
        'locking' => 1,
      );
      $query = $this->member_model->lock($input);
      echo 'locked';
    } else if ($status == 1) {
      $input = (Object) array(
        'id' => $id,
        'locking' => 0,
      );
      $query = $this->member_model->lock($input);
      echo 'unlock';
    } else {
      echo 'no';
    }
  }

}
