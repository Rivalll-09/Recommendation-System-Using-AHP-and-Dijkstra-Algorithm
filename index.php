<!DOCTYPE html>
<html lang="en">

<head>

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

</head>

<body class="bg-gradient-primary">

  <?php
  include('koneksi.php');
  session_start();

  // Jika form login disubmit
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Menangani input dari pengguna
// menangkap data yang dikirim dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // menyeleksi data admin dengan username dan password yang sesuai
    $data = mysqli_query($koneksi, "select * from admin where username='$username' and password='$password'");

    // Cek apakah user ditemukan
    if (mysqli_num_rows($data) > 0) {
      // Ambil data user
      $user = mysqli_fetch_assoc($data);

      // Set session user
      $_SESSION['username'] = $user['username'];
      $_SESSION['role'] = $user['role'];

      // Redirect ke halaman sesuai role
      if ($user['role'] == 'admin') {
        header("Location: admin/index.php");
        exit();
      } else {
        header("Location: page_user/index.php");
        exit();
      }
    } else {
      $error_message = "Username atau Password salah!";
    }
  }
  ?>
<body>
    <div class="login-box">
        <img src="assets/unimal.png" alt="Logo">
        <h3 class="mb-3">Login</h3>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"> <?php echo $error_message; ?> </div>
        <?php endif; ?>

        <form action="index.php" method="post">
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>
        
        <p class="mt-3">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
