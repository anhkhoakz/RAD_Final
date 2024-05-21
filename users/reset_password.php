<?php
$error = '';

if (isset($_GET['reset_token']) && isset($_GET['email'])) {
    $email = $_GET['email'];
    $token = $_GET['reset_token'];

    $pass = $_POST['pass'];
    $pass_confirm = $_POST['pass-confirm'];

    if (empty($email)) {
        $error = 'Please enter your email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'This is not a valid email address';
    } elseif (empty($pass)) {
        $error = 'Please enter your password';
    } elseif (strlen($pass) < 6) {
        $error = 'Password must have at least 6 characters';
    } elseif ($pass != $pass_confirm) {
        $error = 'Passwords do not match';
    } else {
        require_once "db.php";

        $stmt = $conn->prepare("SELECT * FROM reset_password WHERE email = ? AND token = ?");
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $error = 'Invalid reset token';
        } else {
            $row = $result->fetch_assoc();
            $expire_on = $row['expire_on'];

            if ($expire_on < time()) {
                $error = 'Reset token has expired';
            } else {
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
                $stmt->bind_param("ss", $hashed_password, $email);

                if ($stmt->execute()) {
                    $stmt = $conn->prepare("DELETE FROM reset_password WHERE email = ? AND token = ?");
                    $stmt->bind_param("ss", $email, $token);
                    $stmt->execute();

                    header("Location: login.php?password_reset=success");
                    exit();
                } else {
                    $error = 'Error updating password: ' . $conn->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Reset user password</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include_once "../assets/cdns.html" ?>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <h3 class="text-center text-secondary mt-5 mb-3">Reset Password</h3>
                <form novalidate method="post" action="" class="border rounded w-100 mb-5 mx-auto px-3 pt-3 bg-light">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input readonly value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>" name="email" id="email" type="text" class="form-control" placeholder="Email address">
                    </div>
                    <div class="form-group">
                        <label for="pass">Password</label>
                        <input value="<?= isset($_POST['pass']) ? htmlspecialchars($_POST['pass']) : ''; ?>" name="pass" required class="form-control" type="password" placeholder="Password" id="pass">
                        <div class="invalid-feedback">Password is not valid.</div>
                    </div>
                    <div class="form-group">
                        <label for="pass2">Confirm Password</label>
                        <input value="<?= isset($_POST['pass-confirm']) ? htmlspecialchars($_POST['pass-confirm']) : ''; ?>" name="pass-confirm" required class="form-control" type="password" placeholder="Confirm Password" id="pass2">
                        <div class="invalid-feedback">Password is not valid.</div>
                    </div>
                    <div class="form-group">
                        <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php elseif (isset($_GET['password_reset']) && $_GET['password_reset'] === 'success') : ?>
                            <div class="alert alert-success">Password reset successfully. You can now <a href="login.php">login</a> with your new password.</div>
                        <?php endif; ?>
                        <button class="btn btn-success px-5">Change password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>