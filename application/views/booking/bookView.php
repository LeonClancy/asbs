<link rel="stylesheet" href="<?=base_url('assets/css/pickjs/default.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/css/pickjs/default.date.css')?>">

<title>
    借用申請
</title>

<h3 class="ui header">
    借用申請
</h3>

<div class="ui container">

    <form class="ui form" method="post" action="<?=site_url('booking/booking/'.$space_id)?>">

        <h4 class="ui dividing header">活動資訊</h4>

        <div class="two fields">
            <div class="field">
                <label>單位名稱</label>
                <input type="text" name="department">
            </div>
            <div class="field">
                <label>活動名稱</label>
                <input type="text" name='activity'>
            </div>
            
        </div>
    
        <h4 class="ui dividing header">選擇時間</h4>
        <div class="two fields">
            <div class="field">
                <label>日期</label>
                <input type="text" id="datepicker">
            </div>
            <div class="field">
                <label>時間</label>
                <select name="time">
                    <option value="A1">9:15-11:45</option>
                    <option value="A2">11:45-13:15</option>
                    <option value="P1">13:15-15:45</option>
                    <option value="P2">16:00-18:00</option>
                    <option value="P3">19:00-21:30</option>
                </select>
            </div>
        </div>
        <input class="ui submit button" type="submit" value="送出">

</form>

</div>
<script src="<?=base_url('assets/js/pickjs/picker.js')?>"></script>
<script src="<?=base_url('assets/js/pickjs/picker.date.js')?>"></script>
<script src="<?=base_url('assets/js/pickjs/picker.time.js')?>"></script>
<script src="<?=base_url('assets/js/pickjs/zh_TW.js')?>"></script>
<script>
$('#datepicker').pickadate({
    hiddenSuffix: 'date',
    formatSubmit: 'yyyy-mm-d',
    min:[ 
        <?php echo date("Y,m,j",strtotime('+3 day -1 month')); ?>
    ]
})
</script>