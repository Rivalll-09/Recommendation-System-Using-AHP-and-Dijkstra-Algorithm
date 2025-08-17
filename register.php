<?php
include('koneksi.php');

// Cek jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    
    // Cek apakah username sudah ada
    $query_check = "SELECT * FROM admin WHERE username = '$username'";
    $result_check = mysqli_query($koneksi, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
        $error_message = "Username sudah digunakan!";
    } else {
        // Insert data user baru ke database
        $query_insert = "INSERT INTO admin (username, password, role) VALUES ('$username', '$password', 'user')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            $success_message = "Akun berhasil dibuat! Silakan login.";
        } else {
            $error_message = "Gagal membuat akun: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
< lang="en">

<>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sistem Pemetaan RS</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="admin/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="assets/css/index_login.css" rel="stylesheet">
    <link rel="icon" href="assets/unimal.png">
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .input-group-text {
            background-color: #f8f9fa;
            border: none;
        }
        .btn-primary {
            background: #764ba2;
            border: none;
        }
        .btn-primary:hover {
            background: #5b3d91;
        }
    </style>
</head>
<body>
    <div class="login-box text-center">
        <h3 class="mb-3">Create an Account</h3>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"> <?php echo $error_message; ?> </div>
        <?php endif; ?>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"> <?php echo $success_message; ?> </div>
        <?php endif; ?>
        <form action="register.php" method="post">
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    <span class="input-group-text" onclick="togglePassword('password')"><i id="eye-icon-password" class="fa fa-eye"></i></span>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" required>
                    <span class="input-group-text" onclick="togglePassword('confirm_password')"><i id="eye-icon-confirm_password" class="fa fa-eye"></i></span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <br>
        <p>Already have an account? <a href="index.php">Login</a></p>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <script>
        function togglePasswordVisibility(inputId) {
            var passwordField = document.getElementById(inputId);
            var eyeIcon = document.getElementById('eye-icon-' + inputId);

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>