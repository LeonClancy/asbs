<!DOCTYPE html>
<html lang="zh-TW">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="<?=base_url('assets/css/semantic.min.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/css/sweetalert.css')?>">
<style type="text/css">
    body {
      background-color: #DADADA;
    }
    body > .grid {
      height: 100%;
    }
    .image {
      margin-top: -100px;
    }
    .column {
      max-width: 450px;
    }
</style>
<body>
  <div class="ui middle aligned center aligned grid">
    <div class="column">
      <h2 class="ui teal image header">
        <div class="content">
          新建您的帳戶
        </div>
      </h2>
      <div class="ui large form">
        <div class="ui stacked segment">
          <div class="field">
            <div class="ui left icon input">
              <i class="user icon"></i>
              <input type="text" name="name" placeholder="姓名（中文）">
            </div>
          </div>
          <div class="field">
            <div class="ui left icon input">
              <i class="user icon"></i>
              <input type="text" name="email" placeholder="電子信箱">
            </div>
          </div>
          <div class="field">
            <div class="ui left icon input">
              <i class="lock icon"></i>
              <input type="password" name="password" placeholder="密碼">
            </div>
          </div>
          <div class="ui fluid large teal submit button" id="submit">新建</div>
        </div>

        <div class="ui error message"></div>

      </div>
    </div>
  </div>
</body>

<script type="text/javascript">
  $(document).ready(function() {
    $('.ui.form').form({
      fields: {
        name:{
          identifier : 'name',
          rules: [
            {
              type : 'empty',
              prompt : 'Please enter your name'
            }
          ]
        },
        email: {
          identifier  : 'email',
          rules: [{
            type   : 'empty',
            prompt : 'Please enter your e-mail'
          },{
            type   : 'email',
            prompt : 'Please enter a valid e-mail'
          }]
        },
        password: {
          identifier  : 'password',
          rules: [{
            type   : 'empty',
            prompt : 'Please enter your password'
          },{
            type   : 'length[6]',
            prompt : 'Your password must be at least 6 characters'
          }]
        }
      }
    });
    $('#submit').click(function(){
      name = $(':input[name=name]').val()
      email = $(':input[name=email]').val()
      password = $(':input[name=password]').val()
      $.ajax({
        type:"POST",
        url:"<?=site_url('member/registerApi')?>",
        data:{
          'name':name,
          'email':email,
          'password':password
        },
        success: function(data){
          console.log(data)
          if(data.response == "failed") {
            swal("註冊", data.data, "warning")
          }
          if(data.response == "success") {
            swal({
              title: "註冊成功",
              text: data.data,
              type: "success",
            },function(){
              document.location.href="<?=site_url('member/login')?>";
            })
          }
        }
      });
    });
  });
</script>

<script src="<?=base_url('assets/js/semantic.js')?>" type="text/javascript"></script>
<script src="<?=base_url('assets/js/sweetalert.min.js')?>" type="text/javascript"></script>
</html>