
<title>借用列表</title>

<div class="ui container">
    
    <a href="<?=site_url('booking/bookingForm/'.$space_id)?>">
        <button class="ui basic button disable">
            <i class="icon plus"></i>
            申請借用
        </button>
    </a>

    <table class="ui celled table">
        <thead>
            <tr>
                <th>借用單位</th>
                <th>活動名稱</th>
                <th>借用日期</th>
                <th>借用時間</th>
                <th>審核狀況</th>
            </tr>
        </thead>
        <tbody>
                <?php
                    foreach ($data->result_array() as $row) 
                    {
                        echo "<tr>";
                        echo "<td>".$row['department']."</td>";
                        echo "<td>".$row['activity']."</td>";
                        echo "<td>".$row['date']."</td>";
                        echo "<td>".$row['time']."</td>";
                        if($row['status_code']==0) {
                            echo "<td class='warning'>".$row['status']."</td>";
                        }
                        if($row['status_code']==1) {
                            echo "<td class='positive'>".$row['status']."</td>";
                        }
                        echo "</tr>";
                    }
                ?>
        </tbody>
    </table>

</div>