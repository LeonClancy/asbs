<h1>api 測試頁面</h1>

1. 登入api
<form method="post" action="<?=site_url('member/login')?>">
    <input type="text" name="username">
    <input type="text" name="password">
    <input type="submit">
</form>