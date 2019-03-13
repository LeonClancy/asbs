<title>活動管理</title>
<h1>活動管理</h1>
<div class="ui container">
    <table class="ui selectable celled table">
    <a href="<?=site_url('main/activityForm')?>">
        <button class="ui basic button">
            <i class="icon plus"></i>
            新增活動
        </button>
    </a>
        <thead>
            <tr>
                <th>活動名稱</th>
                <th>活動性質</th>
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
                    echo "<td>" .$row['pop']. "</td>";
                }
            ?>
        </tbody>

    </table>

</div>

<script>
</script>