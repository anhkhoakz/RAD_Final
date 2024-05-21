<?php
require('db.php');
$error = '';

if (isset($_GET['email'], $_GET['activation_token'])) {
    $email = $_GET['email'];
    $token = $_GET['activation_token'];

    $sql = "SELECT * FROM users WHERE email = ? AND activation_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $update_sql = "UPDATE users SET activated = 1 WHERE email = ? AND activation_token = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('ss', $email, $token);
        if (!$update_stmt->execute()) {
            $error = 'Error updating record: ' . $conn->error;
        }
    } else {
        $error = 'Invalid activation link';
    }
} else {
    $error = 'Invalid activation link';
}

// echo $error;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Account Activation</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mt-5 mx-auto p-3 border rounded">
                <h4>Account Activation</h4>
                <?php if (empty($error)) { ?>
                    <p class="text-success">Congratulations! Your account has been activated.</p>
                    <a class="btn btn-success px-5" href="login.php">Login</a>
                <?php } else { ?>
                    <p class="text-danger">This is not a valid URL or it has expired.</p>
                    <a class="btn btn-success px-5" href="registration.php">Registration</a>
                <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>