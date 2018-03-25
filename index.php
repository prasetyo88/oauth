<?php
session_start();
require_once 'config.php';
$fb = new Facebook\Facebook([
  'app_id' => $appId,
  'app_secret' => $appSecret,
]);

if(empty($_SESSION['facebook_session'])) {
  echo "

  <html>
  <head>
  <title>Belajar Login With Facebook</title>
  <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
  <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'></script>
   <script src='https://www.google.com/recaptcha/api.js'></script>
  </head>
  <body>
  <style>
        body {
        background: url(http://il8.picdn.net/shutterstock/videos/8543482/thumb/1.jpg) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        }
        .panel-default {
        opacity: 0.9;
        margin-top:30px;
        }
        .form-group.last { margin-bottom:0px; }
  </style>
  <div class='container'>
    <div class='row'>
      <div class='col-md-12'>
        <div class='panel panel-default'>
          <div class='panel-heading'>
          <span class='glyphicon glyphicon-option-vertical'></span> BELUM LOGIN TERDETEKSI
          </div>
          <div class='panel-body' style='word-wrap: break-word;'>
           <div class='col-md-12'>
             <h1 style='text-align:center;'>Kelihatanya anda belum login, Silahkan login terlebih dahulu dengan klik tombol dibawah ini.</h1><br /><br />
             <a href='login_fb.php'><img src='login_fb.png' style='margin-left:35%;'></img></a>
         </div>
              <div class='form-group'>
                <div class='col-md-12'>
                  <hr>
                  <p style='text-align:center;'>Copyright &copy; " . date('Y') ." rizalfakhri</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  </html>

  ";
} else {
  $token    = $_SESSION['facebook_session'];
  $data     = $fb->get("/me?fields=id,name,email,picture,link,gender",$token);
  $user     = $data->getGraphUser();
  $input    = new db();
  $nama     = $user['name'];
  $email    = $user['email'];
  if($cek = $input->mysqli->query("SELECT * FROM user WHERE token = '$token' ")) {
    if($cek->num_rows < 1 ) {
      if($input->mysqli->query("INSERT INTO user(nama,email,token) VALUES('$nama','$email','$token')")) {
        $input->redirect("http://" . $_SERVER['SERVER_NAME']);
      }
    }
  }
 $cek_pass = "SELECT * FROM user WHERE token = '$token' ";
  if($cek_pass = $input->mysqli->query($cek_pass)) {
    if($cek_pass->num_rows > 0 ) {
      if($data = mysqli_fetch_array($cek_pass)) {
        if($data['password'] == "") {
          $pesan = "
           <div class='col-md-12'>
            <div class='panel panel-default'>
             <div class='panel-heading'>
              <span class='glyphicon glyphicon-lock'></span>
               SET PASSWORD
             </div>
             <div class='panel-body'>
              <h1 style='text-align:center'>Tingal 1 Langkah Lagi, Silahkan Buat Password Anda :D </h1><br />
              <div class='col-md-6 col-md-offset-3'>
              <form method='post'>
              <div class='form-group'>
               <label for='password'>Password </label>
               <input type='password' class='form-control' placeholder='Password' name='password'>
              </div>
              <div class='form-group'>
               <label for='cpassword'>Confirm Password</label>
               <input type='password' class='form-control' name='cpassword' placeholder='Confirm Password'>
              </div>
               <button class='btn btn-success' style='width:100%' type='submit' name='btn-save-password'>SAVE!</button>
             </form>
            </div>
           </div>
          </div>
          ";
        }
      }
    }
  }
  ?>

  <!DOCTYPE html>
  <html>
  <head>
  <title>Belajar Login With Facebook</title>
  <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
  <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' rel='stylesheet' integrity='sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1' crossorigin='anonymous'>
  <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'></script>
   <script src='https://www.google.com/recaptcha/api.js'></script>
  </head>
  <body>
  <style>
        body {
        background: url(http://il8.picdn.net/shutterstock/videos/8543482/thumb/1.jpg) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        }
        .panel-default {
        opacity: 0.9;
        margin-top:30px;
        }
        .form-group.last { margin-bottom:0px; }
  </style>
  <div class='container'>
    <div class='row'>
      <?php

      if(isset($_POST['btn-save-password'])) {
        $password  = md5(md5($_POST['password']));
        $cpassword = md5(md5($_POST['cpassword']));
        if($password != $cpassword) {
          echo "

          <br />
          <div class='alert alert-danger'>
           Password Konfirmasi Harus Sama!
          </div>

          ";
        } else {
          if($input->mysqli->query("UPDATE user SET password = '$cpassword' WHERE token = '$token'")) {
            echo "
            <br />
            <div class='alert alert-success'>
             Password Sukses Disimpan :)
            </div>
            ";

            $input->redirect("http://" . $_SERVER['SERVER_NAME']);
          }
        }
      }

      if(isset($pesan)) {
        echo $pesan;
      }

      ?>
     <div class='col-md-3'>
       <div class='panel panel-default' style='position:fixed;'>
         <div class='panel-heading'>
           <i class='fa fa-user'></i> DATA FACEBOOK
         </div>
         <div class='panel-body'>
           USERNAME   : <?php echo $user['name']; ?>
           <hr>
           EMAIL   : <?php echo $user['email']; ?>
           <hr>
           USER ID    : <?php echo $user['id']; ?>
           <hr>
           GENDER : <?php echo $user['gender']; ?>
          <div class='form-group'>
            <div class='col-md-12 '>
             <hr>
              Copyright &copy; <?php echo date("Y"); ?> rizalfakhri
               </div>
             </div>
           </div>
         </div>
     </div>


      <div class='col-md-9'>
        <div class='panel panel-default'>
          <div class='panel-heading'>
          <i class='fa fa-info'></i> WELCOME TO MY SITE
          </div>
          <div class='panel-body' style='word-wrap: break-word;'>
           <div class='col-md-12'>
             <h1 style='text-align:center;'>Selamat Datang Di Website Saya, Anda Telah Berhasil Login Dengan Facebook :)</h1>
         </div>
              <div class='form-group'>
                <div class='col-md-12'>
                  <hr>
                  <p style='text-align:center;'>Copyright &copy; <?php echo date("Y"); ?> rizalfakhri</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  </html>
<?php
}
?>
