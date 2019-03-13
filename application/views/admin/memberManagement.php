<h1>會員管理</h1>
<div class="ui container">
    <table class="ui selectable celled table">
        <button class="ui button" id="addMember">新增使用者</button>
        <thead>
            <tr>
                <th>會員名稱</th>
                <th>帳號</th>
                <th>身分別</th>
                <th>狀態</th>
                <th>編輯</th>
                <th>刪除</th>
            </tr>
        </thead>

        <tbody>
            <?php
                foreach($data->result_array() as $row)
                {
                    echo "<tr id='member_tr".$row['id']."'>";
                    echo "<td>" .$row['name']. "</td>";
                    echo "<td>" .$row['email']. "</td>";
                    if($row['role']=='U')
                        echo "<td> 一般使用者 </td>";
                    if($row['role']=='A')
                        echo "<td> 管理者 </td>";
                    
                    if($row['locking']==0){
                        echo "<td class='positive'>"; 
                        echo "已開通";
                        echo "<button class='ui button' onclick='lock(".$row['id'].")'> 鎖定 </button>"; 
                        echo "</td>";
                    }
                    if($row['locking']==1){
                        echo "<td class='negative'>";
                        echo "鎖定中";
                        echo "<button class='ui button' onclick='lock(".$row['id'].")'> 解鎖 </button>";
                        echo "</td>";
                    }
                    echo "<td><button class='ui button green' id='editMember(".$row['id'].")'>編輯</button></td>";
                    echo "<td><button class='ui button red' id='delMember(".$row['id'].")'>刪除</button></td>";
                }
            ?>
        </tbody>
	
    </table>

</div>

<?php foreach($data->result_array() as $row) { ?>
<div class="ui tiny modal <?=$row['id']?>">
  <?php if($row['vacancy']!=0) { ?>
    <i class="close icon"></i>
    <div class="header">
      編輯 <?=$row['name']?>
    </div>
    <div class="content">
      <form action="<?=site_url('Space/modifySpace/').$row['id']?>" method="post" class="ui form">
        <div class="field">
          <label>會員名稱</label>
          <input type="text" name="spaceName" placeholder="名稱">
        </div>
        <div class="field" id="spaceVacancy">
          <label>容納人數</label>
          <input type="text" name="spaceVacancy" placeholder="羅馬數字" id="spaceVacancyInput">
        </div>
        <div class="field">
          <label>場地說明</label>
          <input type="text" name="spaceInfo" placeholder="文字稍微敘述一下場地吧">
        </div>

        <button class="ui right floated button" type="submit">確認</button>
        <br><br>
      </form>
    </div>
    <script>
      $('#modify<?=$row['id']?>').click(function(){
        $('.tiny.modal.<?=$row['id']?>').modal('show');
      })    
    </script>
<?php } } ?>
</div>

<script>
function lock(id) {
  swal(
    {
      title: "警告",
      text: "輸入 lock 後確定更改使用者狀況",
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
      
      if(inputValue === 'lock') {
        $.ajax({
          type:"POST", 
          data: {id : id},
          url: "<?=site_url('Member/lock')?>",
          dateType:"json",
          error:function(){
            swal("提示", "delete_member ajax connect error", "warning");
          },
          success:function(val){
            if(val == 'locked'){
              swal("提示", "已鎖定" + inputValue, "success");
              window.location.reload();
            }else if(val == 'unlock'){
              swal("提示", "已解鎖" + inputValue, "success");
              window.location.reload();
            }else {
              swal("提示", "delete_member ajax error", "warning")
            }
          }
        });
      }					
    }
  );
}
function delMember(id) {
	swal(
    {
      title: "警告",
      text: "輸入 delete 後確定刪除",
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
      
      if(inputValue === 'delete') {
        $.ajax({
          type:"POST", 
          data: {id : id},
          url: "<?=site_url('admin/delMember')?>",
          dateType:"json",
          error:function(){
            swal("提示", "delete_member ajax connect error", "warning");
          },
          success:function(val){
            if(val == 'yes'){
              $("#member_tr"+id).empty();
              swal("提示", "刪除成功" + inputValue, "success");
            }else if(val == 'no'){
              swal("提示", "刪除失敗" + inputValue, "error");
            }else {
              swal("提示", "delete_member ajax error", "warning")
            }
          }
        });
      }					
    }
  );
}
</script>
