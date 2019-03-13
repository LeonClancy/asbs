<title>
  新增活動
</title>

<h3 class="ui header">
  新增活動
</h3>

<div class="ui container">

  <form class="ui form" method="post" action="<?=site_url('activity/addActivity')?>">

    <h4 class="ui dividing header">活動資訊</h4>

    <div class="field">
      <label>活動名稱</label>
      <input type="text" name="name">
    </div>

    <div class="field">
      <label>活動性質</label>
      <select class="ui fluid dropdown" name="attrib">
        <?php
          foreach ($attrib->result_array() as $row) {
            echo "<option value='".$row['id']."'>".$row['name']."</option>";
          }
        ?>
      </select>
    </div>

    <div class="field">
      <label>參與人數</label>
      <input type="text" name="pop" id="">
    </div>

    <input class="ui submit button" type="submit" value="送出">
  </form>

</div>