<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="jumbotron text-center">
  <h1>線上場地借用</h1>
  <p>slogan跟logo還在想</p> 
</div>
  
<div class="text-center">
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <a href="<?=site_url("home/login")?>" button type="button" style="width:200px;height:40px;" class="btn btn-primary">登入</a>
         或 
        <a href="<?=site_url("main/index")?>" button type="button" style="width:200px;height:40px;" class="btn btn-success">觀看場地列表</a>
    </div>
    <div class="col-sm-4"></div>
  </div>
</div>

</body>
</html>