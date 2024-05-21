<?php
$error = '';
$email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    if (empty($email)) {
        $error = 'Please enter your email';
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $error = 'This is not a valid email address';
    } else {
        require_once "db.php";

        $stmt = $conn->prepare("SELECT * FROM reset_password WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'A reset token already exists for this email';
        } else {
            require_once "../functions/utilities.php";
            $expire_time = time() + (30 * 60);
            $reset_token = generateToken(50);

            $stmt = $conn->prepare("INSERT INTO reset_password (email, token, expire_on) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $reset_token, $expire_time);
            if ($stmt->execute()) {
                $to = $email;
                $subject = 'Reset password';
                $message = 'Click the link below to reset your password: http://localhost/coffee-shop-website/users/reset_password.php'
                    . '?email=' . $email
                    . '&reset_token=' . $reset_token;
                $headers = 'From: admin@localhost';
                send_mail($to, $subject, $message, $headers);
                // echo $message;
            } else {
                $error = 'Error inserting record: ' . $conn->error;
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
                <form method="post" action="" class="border rounded w-100 mb-5 mx-auto px-3 pt-3 bg-light">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input name="email" id="email" type="text" class="form-control" placeholder="Email address" value="<?= $email ?>">
                    </div>
                    <div class="form-group">
                        <p>If your email exists in the database, you will receive an email containing the reset password instructions.</p>
                    </div>
                    <div class="form-group">
                        <?php
                        if (!empty($error)) {
                            echo "<div class='alert alert-danger'>$error</div>";
                        }
                        ?>
                        <button class="btn btn-success px-5">Reset password</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>

</html>