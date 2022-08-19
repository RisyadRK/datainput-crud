<?php
session_start();

//atur koneksi ke database
$host    = "localhost";
$user    = "root";
$pass    = "";
$db      = "datainput";
$koneksi = mysqli_connect($host, $user, $pass, $db);

//atur variabel
$error        = "";
$username     = "";
$remember_me  = "";

if (isset($_COOKIE['cookie_username'])) {
    $cookie_username = $_COOKIE['cookie_username'];
    $cookie_password = $_COOKIE['cookie_password'];

    $sql1 = "select * from login where username = '$cookie_username'";
    $q1   = mysqli_query($koneksi, $sql1);
    $r1   = mysqli_fetch_array($q1);
    if ($r1['password'] == $cookie_password) {
        $_SESSION['session_username'] = $cookie_username;
        $_SESSION['session_password'] = $cookie_password;
    }
}

if (isset($_SESSION['session_username'])) {
    header("location:index.php");
    exit();
}

if (isset($_POST['login'])) {
    $username   = $_POST['username'];
    $password   = $_POST['password'];
    $remember_me   = $_POST['remember_me'];

    if ($username == '' or $password == '') {
        $error = "Silakan masukkan username dan juga password!";
    } else {
        $sql1 = "select * from login where username = '$username'";
        $q1   = mysqli_query($koneksi, $sql1);
        $r1   = mysqli_fetch_array($q1);

        if ($r1['username'] != $username) {
            $error = "Username <b>$username</b> tidak tersedia!";
        } elseif ($r1['password'] != md5($password)) {
            $error = "Password yang dimasukkan tidak sesuai!";
        }

        if (empty($error)) {
            $_SESSION['session_username'] = $username; //server
            $_SESSION['session_password'] = md5($password);

            if ($remember_me == 1) {
                $cookie_name = "cookie_username";
                $cookie_value = $username;
                $cookie_time = time() + (60 * 60 * 24 * 30);
                setcookie($cookie_name, $cookie_value, $cookie_time, "/");

                $cookie_name = "cookie_password";
                $cookie_value = md5($password);
                $cookie_time = time() + (60 * 60 * 24 * 30);
                setcookie($cookie_name, $cookie_value, $cookie_time, "/");
            }
            header("location:index.php");
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, intial-scale=1.0">
    <title>Halaman Login - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 600px
        }

        .card {
            margin-top: 150px
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <div class="card">
            <div class="card-header">Halaman Login - Admin</div>
            <div class="card-body">
                <!--PESAN ERROR JIKA USERNAME/PASSWORD TIDAK ADA YANG DI INPUT-->
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <ul><?php echo $error ?></ul>
                    </div>
                <?php
                }
                ?>
                <form action="" method="POST">
                    <!--BARIS 1 - USERNAME-->
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="login-username" type="text" class="form-control" name="username" value="<?php echo $username ?>" placeholder="Username">
                    </div>
                    <!--BARIS 2 - PASSWORD-->
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="login-password" type="password" class="form-control" name="password" placeholder="Password">
                    </div>
                    <!--BARIS 3 - CHECKBOX-->
                    <div class="input-group">
                        <div class="checkbox">
                            <label>
                                <input id="login-remember" type="checkbox" name="remember_me" value="1" <?php if ($remember_me == '1') echo "checked" ?>> Remember me
                            </label>
                        </div>
                    </div>
                    <!--BUTTON LOGIN-->
                    <div style="margin-top: 10px; margin-bottom: 10px" class="col-sm-12">
                        <input type="submit" name="login" class="btn btn-success" value="Login" />
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>

</html>