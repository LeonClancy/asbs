
<title>已借用列表</title>

<div class="ui container">
  <table class="ui celled table">
      <thead>
        <tr>
          <th>借用單位</th>
          <th>活動名稱</th>
          <th>借用場地</th>
          <th>借用日期</th>
          <th>借用時間</th>
          <th>審核狀況</th>
        </tr>
      </thead>
      <tbody>
          <tr>
            <?php
              foreach ($data->result_array() as $row) {
                echo "<tr>";
                echo "<td>" . $row['department'] . "</td>";
                echo "<td>" . $row['activity'] . "</td>";
                echo "<td>" . $row['space'] . "</td>";
                echo "<td>" . $row['date'] . "</td>";
                echo "<td>" . $row['time'] . "</td>";
                if ($row['status_code'] == 0) {
                  echo "<td class='warning'>" . $row['status'] . "</td>";
                }
                if ($row['status_code'] == 1) {
                  echo "<td class='positive'>" . $row['status'] . "</td>";
                }
                echo "</tr>";
              }
            ?>
          </tr>
      </tbody>
  </table>
</div>