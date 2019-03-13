<title>場地管理</title>
<h1> <?=$spaceDetail['name']?> 場地管理</h1>

<div class="ui container">
  
  <table class="ui celled table">
    <button class="ui basic button" id="add">
      新增場地
    </button>
    <table class="ui selectable celled table">
    <thead>
      <tr>
        <th>單位名稱</th>
        <th>備註</th>
        <th>編輯</th>
        <th>刪除</th>
      </tr>
    </thead>
  <tbody>
<?php
foreach($space->result_array() as $row) {
  if($row['vacancy']==0)
  {
    echo "<tr id='space_tr".$row['id']."'>";
    echo "<td> <a href=".site_url('admin/spaceManagement/'.$row['id']).">".$row['name']."</td>";
    echo "<td>".$row['info']."</td>";
    echo "<td> <button class='ui teal button' id='modify".$row['id']."'>編輯</button></td>";
    echo "<td> <button class='ui red button' onClick='space_delete(".$row['id'].")' >刪除</button></td>";
    echo "</tr>";
  }
}
?>
  </tbody>
</table>

  <table class="ui selectable celled table">
    <thead>
      <tr>
        <th>單位名稱</th>
        <th>容納人數</th>
        <th>狀態</th>
        <th>備註</th>
        <th>編輯</th>
        <th>刪除</th>
      </tr>
    </thead>
    <tbody>
  <?php 
    foreach($space->result_array() as $row) {
      if($row['vacancy']!=0)
      {
        echo "<tr id='space_tr".$row['id']."'>";
        echo "<td>" .$row['name']. "</td>";
        echo "<td>" .$row['vacancy']. "</td>";
        echo "<td class='selectable positive'>可借用</a>";
        echo "</td>";
        echo "<td>" .$row['info']. "</td>";
        echo "<td> <button class='ui teal button' id='modify".$row['id']."'>編輯</button></td>";
        echo "<td> <button class='ui red button' onClick='space_delete(".$row['id'].")' >刪除</button></td>";
        echo "</tr>";
      }
    }
  ?>
    </tbody>
  </table>
</div>
<div class="ui tiny modal" id="addModal">
  <i class="close icon"></i>
  <div class="header">
    新增場地在 <?=$spaceDetail['name']?> 下
  </div>
  <div class="content">
    <form action="<?=site_url('Space/addSpace/').$spaceDetail['id']?>" method="post" class="ui form">
      <div class="field">
        <label>場地名稱</label>
        <input type="text" name="spaceName" placeholder="名稱">
      </div>
      <div class="field">
        <div class="ui checkbox" id="isFatherCheckbox">
          <input type="checkbox" tabindex="0" class="hidden" id="isFather">
          <label>它底下還有場地</label>
        </div>
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
</div>

<?php foreach($space->result_array() as $row) { ?>
<div class="ui tiny modal <?=$row['id']?>">
  <?php if($row['vacancy']!=0) { ?>
    <i class="close icon"></i>
    <div class="header">
      編輯 <?=$row['name']?>
    </div>
    <div class="content">
      <form action="<?=site_url('Space/modifySpace/').$row['id']?>" method="post" class="ui form">
        <div class="field">
          <label>場地名稱</label>
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

<?php foreach($space->result_array() as $row) { ?>
<div class="ui tiny modal <?=$row['id']?>" id="modify">
  <?php if($row['vacancy']==0) { ?>
    <i class="close icon"></i>
    <div class="header">
      編輯 <?=$row['name']?>
    </div>
    <div class="content">
      <form action="<?=site_url('Space/modifySpace/').$row['id']?>" method="post" class="ui form">
        <div class="field">
          <label>場地名稱</label>
          <input type="text" name="spaceName" placeholder="<?=$row['name']?>">
        </div>
        <div class="field">
          <div class="ui checkbox" id="isFatherCheckbox">
            <input type="checkbox" tabindex="0" class="hidden" id="isFather">
            <label>它底下還有場地</label>
          </div>
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
$('#add').click(function(){
  $('#addModal').modal('show');
})
$('#isFatherCheckbox').checkbox();
$('#isFather').change(function(){
  if(this.checked) {
    $('#spaceVacancy').hide()
    $('#spaceVacancyInput').val(0)
  } else {
    $("#spaceVacancy").show()
    $('#spaceVacancyInput').val("");
  }
})

function space_delete(id) {
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
          url: "<?=site_url('space/delSpace')?>",
          dateType:"json",
          error:function(){
            swal("提示", "delete_member ajax connect error", "warning");
          },
          success:function(val){
            if(val == 'yes'){
              $("#space_tr"+id).empty();
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