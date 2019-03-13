<style>
html{
  height:90%;
}
</style>

<title>場地列表</title>

<h1>場地列表</h1>

<!--  <div class="ui big breadcrumb">
   <a class="section">台中學苑</a>
   <i class="right chevron icon divider"></i>
   <a class="section">B棟</a>
    <i class="right chevron icon divider"></i>
   <div class="active section">7樓</div>
</div> -->
<div class="ui container">

  <div class="ui container">
    <table class="ui selectable celled table">
      <thead>
        <tr>
          <th>單位名稱</th>
          <th>備註</th>
        </tr>
      </thead>
    <tbody>
    <?php
    foreach($space->result_array() as $row) {
      if($row['vacancy']==0)
      {
        echo "<tr>";
        echo "<td> <a href=".site_url('main/index/'.$row['id']).">".$row['name']."</td>";
        echo "<td>".$row['info']."</td>";
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
          <th>借用</th>
          <th>備註</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          foreach($space->result_array() as $row) {
            if($row['vacancy']!=0)
            {
              echo "<tr>";
              echo "<td>" .$row['name']. "</td>";
              echo "<td>" .$row['vacancy']. "</td>";
              echo "<td class='selectable positive'>";
                echo "<a href=".site_url('booking/bookingView/').$row['id'].">可借用</a>";
              echo "</td>";
              echo "<td>" .$row['info']. "</td>";
              echo "</tr>";
            }
          }
        ?>
      </tbody>
    </table>
  </div>
</div>