<!DOCTYPE html>
<html lang="en">

<head>
  <title>Login V4</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html" />
  <!--===============================================================================================-->
  <link rel="icon" type="image/png" href="<?=base_url('assets/images/icons/favicon.ico')?>" />
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/vendor/bootstrap/css/bootstrap.min.css')?>">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css')?>">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/fonts/iconic/css/material-design-iconic-font.min.css')?>">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/vendor/animate/animate.css')?>">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/vendor/css-hamburgers/hamburgers.min.css')?>">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/vendor/animsition/css/animsition.min.css')?>">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/vendor/select2/select2.min.css')?>">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/vendor/daterangepicker/daterangepicker.css')?>">
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/sweetalert.css')?>">
  <!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/util.css')?>">
  <link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/main.css')?>">
  <!--===============================================================================================-->
</head>

<body>
    <div class="limiter">
      <div class="container-login100" style="background-image: url('images/bg-01.jpg');">
        <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
          <div class="login100-form validate-form">
            <span class="login100-form-title p-b-49">
              Login
            </span>

            <div class="wrap-input100 validate-input m-b-23" data-validate="Username is reauired">
              <span class="label-input100">帳號</span>
              <input class="input100" type="text" name="username" placeholder="Type your username">
              <span class="focus-input100" data-symbol="&#xf206;"></span>
            </div>

            <div class="wrap-input100 validate-input" data-validate="Password is required">
              <span class="label-input100">密碼</span>
              <input class="input100" type="password" name="pass" placeholder="Type your password">
              <span class="focus-input100" data-symbol="&#xf190;"></span>
            </div>

            <div class="text-right p-t-8 p-b-31">
              <a href="#">
                忘記密碼?
              </a>
            </div>

            <div class="container-login100-form-btn">
              <div class="wrap-login100-form-btn">
                <div class="login100-form-bgbtn"></div>
                <button class="login100-form-btn" id="submitButton">
                  登入
                </button>

              </div>
            </div>


            <div class="flex-col-c p-t-100">

              <a href="<?=site_url('member/register')?>" class="txt2">
                註冊
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

  <div id="dropDownSelect1"></div>

  <!--===============================================================================================-->
  <script src="<?=base_url('assets/vendor/jquery/jquery-3.2.1.min.js')?>">
  </script>
  <!--===============================================================================================-->
  <script src="<?=base_url('assets/vendor/animsition/js/animsition.min.js')?>">
  </script>
  <!--===============================================================================================-->
  <script src="<?=base_url('assets/vendor/bootstrap/js/popper.js')?>">
  </script>
  <script src="<?=base_url('assets/vendor/bootstrap/js/bootstrap.min.js')?>">
  </script>
  <!--===============================================================================================-->
  <script src="<?=base_url('assets/vendor/select2/select2.min.js')?>">
  </script>
  <!--===============================================================================================-->
  <script src="<?=base_url('assets/vendor/daterangepicker/moment.min.js')?>">
  </script>
  <script src="<?=base_url('assets/vendor/daterangepicker/daterangepicker.js')?>">
  </script>
  <!--===============================================================================================-->
  <script src="<?=base_url('assets/vendor/countdowntime/countdowntime.js')?>">
  </script>
  <script src="<?=base_url('assets/js/sweetalert.min.js')?>"></script>
  <!--===============================================================================================-->
  <script src="<?=base_url('assets/js/main.js')?>">
  </script>
  
  <script>
    $('#submitButton').click(function(){
      username = $(':input[name=username]').val()
      password = $(':input[name=pass]').val()
      $.ajax({
        type:"POST",
        url:"<?=site_url('member/loginApi')?>",
        data:{
          'username':username,
          'password':password
        },
        success: function(data){
          console.log(data)
          if(data.response == "failed") {
            swal("登入失敗", data.data, "warning")
          }
          if(data.response == "success") {
            swal({
              title: "登入成功",
              text: data.data.name+",您好",
              type: "success",
            },function(){
              document.location.href="<?=site_url('member/login')?>";
            })
          }
        }
      });
    });
  </script>

</body>

</html>