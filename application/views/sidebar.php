<div class="ui top attached demo menu" id="menu">
  <a class="item">
    <i class="sidebar icon"></i>
    Menu
  </a>
</div>
<div class="ui bottom attached segment pushable" id="menu">
  <div class="ui inverted labeled icon left inline vertical sidebar menu" style="">
    <?php
      if($this->session->userdata('logged')){
        ?>
          <a class="item" href="<?=site_url('main/bookingList')?>">
            <i class="home icon"></i>
            已申請列表
          </a>
          <a class="item" href="<?=site_url('main/index')?>">
            <i class="tasks icon"></i>
            場地列表
          </a>
          <a class="item" href="<?=site_url('member/logout')?>">
            <i class="window close icon"></i>
            登出
          </a>
        <?php
      } else {
        ?>
          <a class="item" href="<?=site_url('home/login')?>">
            <i class="address card icon"></i>
            登入
          </a>
        <?php
      }
    ?>
    
  </div>
  <div class="pusher">
    <div class="ui basic segment" id='context'>