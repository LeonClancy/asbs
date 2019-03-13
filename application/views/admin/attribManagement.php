<h1>活動管理</h1>
<div class="ui container">
    <button class="ui button" id="addAttrib">新增活動性質</button>
    <table class="ui selectable celled table">

        <thead>
            <tr>
                <th>活動性質</th>
                <th>編輯</th>
                <th>刪除</th>
            </tr>
        </thead>

        <tbody>
            <?php
              foreach($data->result_array() as $row)
              {
                echo "<tr id='attrib_tr".$row['id']."'>";
                echo "<td>" .$row['name']. "</td>";
                echo "<td><button class='ui button green' onclick='edit(".$row['id'].")'>編輯</button></td>";
                echo "<td><button class='ui button red' onclick='del(".$row['id'].")'>刪除</button></td>";
              }
            ?>
        </tbody>

    </table>

</div>

<script>

$('#addAttrib').click(function(){
  swal({
    title: "輸入活動性質",
    text: "輸入即可新增:",
    type: "input",
    showCancelButton: true,
    closeOnConfirm: false,
    inputPlaceholder: "Write something"
  }, function (inputValue) {
    if (inputValue === false) {
      return false;
    } else if (inputValue === "") {
      swal.showInputError("You need to write something!");
      return false
    } else {
      $.ajax({
        type:"POST", 
        data: {name : inputValue},
        url: "<?=site_url('activity/addAttrib')?>",
        dateType:"json",
        error:function(){
          swal("提示", "addAttrib ajax connect error", "warning");
        },
        success:function(val){
          if(val == 'yes'){
            swal("提示", "新增成功" + inputValue, "success");
            window.location.reload();
          }else if(val == 'no'){
            swal("提示", "新增失敗" + inputValue, "error");
          }else {
            swal("提示", "addAttrib ajax error", "warning")
          }
        }
      });
    }
    
  });
})

function del(id) {
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
          url: "<?=site_url('activity/delAttrib')?>",
          dateType:"json",
          error:function(){
            swal("提示", "delete_member ajax connect error", "warning");
          },
          success:function(val){
            if(val == 'yes'){
              $("#attrib_tr"+id).empty();
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