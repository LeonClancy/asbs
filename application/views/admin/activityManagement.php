<h1>活動管理</h1>
<div class="ui container">
    <button class="ui button">
        <a href="<?=site_url('admin/attribManagement')?>">管理活動性質</a>
    </button>
    <table class="ui selectable celled table">

        <thead>
            <tr>
                <th>活動名稱</th>
                <th>活動性質</th>
                <th>活動發起人</th>
                <th>目前場地申請</th>
                <th>人數</th>
            </tr>
        </thead>

        <tbody>
            <?php
                foreach($data->result_array() as $row)
                {
                    echo "<tr id='member_tr".$row['id']."'>";
                    echo "<td>" .$row['name']. "</td>";
                    echo "<td>" .$row['attrib']. "</td>";
                    echo "<td>" .$row['member']. "</td>";
                    echo "<td><button class='ui button'>查看</button></td>";
                    echo "<td>" .$row['pop']. "</td>";
                }
            ?>
        </tbody>

    </table>

</div>

<script>
</script>