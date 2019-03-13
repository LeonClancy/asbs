
<title>借用申請列表</title>

<div class="ui container">
  <h1>借用申請列表</h1>
  <div class="ui top attached tabular menu" id="tabs">
    <a class="item" data-tab="first" id="tab">七天內</a>
    <a class="item" data-tab="second" id="tab">一月內</a>
    <a class="item active" data-tab="third" id="tab">已審核通過</a>
  </div>
  <div class="ui bottom attached tab segment" data-tab="first">
    <!-- 七天內的申請 -->
    <table class="ui celled table">
      <thead>
        <tr>
          <th>申請人</th>
          <th>借用單位</th>
          <th>活動名稱</th>
          <th>借用場地</th>
          <th>借用日期</th>
          <th>借用時間</th>
          <th>審核狀況</th>
          <th>動作</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php
            foreach ($data->result_array() as $row) 
            {
              // if(strtotime($today)>strtotime($thisday))
              if( strtotime($row['date']) > strtotime(date('Y-m-d',strtotime('+1 day'))) && strtotime($row['date']) < strtotime(date('Y-m-d',strtotime('+7 day'))) && $row['status_code']==0) {
                echo "<tr>";
                echo "<td>".$row['member']."</td>";
                echo "<td>".$row['department']."</td>";
                echo "<td>".$row['activity']."</td>";
                echo "<td>".$row['space']."</td>";
                echo "<td>".$row['date']."</td>";
                echo "<td>".$row['time']."</td>";
                if($row['status_code']==0) {
                    echo "<td id='status".$row['id']."' class='warning'>".$row['status']."</td>";
                    echo "<td><button class='positive ui button' onClick='check(".$row['id'].")'>通過</button>";
                }
                if($row['status_code']==1) {
                    echo "<td id='status".$row['id']."' class='positive'>".$row['status']."</td>";
                }
                echo "</tr>";
              }
            }
          ?>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="ui bottom attached tab segment" data-tab="second">
    <!-- 一個月內的申請 -->
    <table class="ui celled table">
      <thead>
        <tr>
          <th>申請人</th>
          <th>借用單位</th>
          <th>活動名稱</th>
          <th>借用場地</th>
          <th>借用日期</th>
          <th>借用時間</th>
          <th>審核狀況</th>
          <th>動作</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php
              foreach ($data->result_array() as $row) {
                if( strtotime($row['date']) > strtotime(date('Y-m-d',strtotime('+1 day'))) &&
                    strtotime($row['date']) < strtotime(date('Y-m-d',strtotime('+1 month')) ) &&
                    $row['status_code']==0 ) {
                  echo "<tr>";
                  echo "<td>".$row['member']."</td>";
                  echo "<td>".$row['department']."</td>";
                  echo "<td>".$row['activity']."</td>";
                  echo "<td>".$row['space']."</td>";
                  echo "<td>".$row['date']."</td>";
                  echo "<td>".$row['time']."</td>";
                  if($row['status_code']==0) {
                      echo "<td id='status".$row['id']."' class='warning'>".$row['status']."</td>";
                      echo "<td><button class='positive ui button' onClick='check(".$row['id'].")'>通過</button>";
                  }
                  if($row['status_code']==1) {
                      echo "<td id='status".$row['id']."' class='positive'>".$row['status']."</td>";
                      echo "<td><button class='ui orange button' onClick='check(".$row['id'].")'>否決</button>";
                  }
                  echo "</tr>";
                }
              }
          ?>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="ui bottom attached tab segment active" data-tab="third">
    <!-- 已經審核通過的申請 -->
    <table class="ui celled table">
      <thead>
        <tr>
          <th>申請人</th>
          <th>借用單位</th>
          <th>活動名稱</th>
          <th>借用場地</th>
          <th>借用日期</th>
          <th>借用時間</th>
          <th>審核狀況</th>
          <th>動作</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php
          
            foreach ($data->result_array() as $row) 
              if($row['status_code']==1) {
                {
                  echo "<tr>";
                  echo "<td>".$row['member']."</td>";
                  echo "<td>".$row['department']."</td>";
                  echo "<td>".$row['activity']."</td>";
                  echo "<td>".$row['space']."</td>";
                  echo "<td>".$row['date']."</td>";
                  echo "<td>".$row['time']."</td>";
                  if($row['status_code']==0) {
                      echo "<td id='status".$row['id']."' class='warning'>".$row['status']."</td>";
                      echo "<td><button class='positive ui button' onClick='check(".$row['id'].")'>通過</button>";
                  }
                  if($row['status_code']==1) {
                      echo "<td id='status".$row['id']."' class='positive'>".$row['status']."</td>";
                      echo "<td><button class='ui orange button' onClick='check(".$row['id'].")'>否決</button>";
                  }
                  echo "</tr>";
                }
            }
          ?>
        </tr>
      </tbody>
    </table>
  </div>
  

<script>
  $('#tabs #tab')
    .tab()
  ;
  function check(id) {
    swal(
      {
        title: "警告",
        text: "輸入 check 後確定修改",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Write something"
      },
      function(inputValue){
        if (inputValue === false) {
          return false;
        }

        if (inputValue === "") {
          swal.showInputError("You need to write something!");
          return false
        }
        
        if(inputValue === 'check') {
          $.ajax({
            type:"POST", 
            data: {id : id},
            url: "<?=site_url('admin/check')?>",
            dateType:"json",
            error:function(){
              swal("提示", "booking_check ajax connect error", "warning");
            },
            success:function(val){
              if(val == 'yes'){
                swal("提示", "審核成功" + inputValue, "success");
                window.location.reload();
              }else if(val == 'no'){
                swal("提示", "審核失敗" + inputValue, "error");
              }else {
                swal("提示", "booking_check ajax error", "warning")
              }
            }
          });
        }					
      }
    );
  }
</script>