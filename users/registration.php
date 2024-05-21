<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>KapeTann | Registration Form</title>
    <link rel="stylesheet" href="../assets/css/login.css" />
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico"><!-- Favicon / Icon -->
</head>

<body>
    <?php
    require_once('db.php');
    require_once('../functions/utilities.php');
    if (isset($_REQUEST['username'])) {
        $username = stripslashes($_REQUEST['username']);
        $username = mysqli_real_escape_string($conn, $username);
        $query = "SELECT * FROM `users` WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            echo "
            <div class='form'>
                <h3>Username is already taken.</h3><br/>
                <p class='link'>Click here to <a href='registration.php'>registration</a> again.</p>
            </div>";
            exit();
        }
        $name    = stripslashes($_REQUEST['name']);
        $name    = mysqli_real_escape_string($conn, $name);
        $email    = stripslashes($_REQUEST['email']);
        $email    = mysqli_real_escape_string($conn, $email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $query = "SELECT * FROM `users` WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            echo "
            <div class='form'>
                <h3>Email is already taken.</h3><br/>
                <p class='link'>Click here to <a href='registration.php'>registration</a> again.</p>
            </div>";
            exit();
        }
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($conn, $password);
        $create_datetime = date("Y-m-d H:i:s");
        $activation_token = generateToken();

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query    = "INSERT into `users` (name, username, password, email, create_datetime, activation_token)
                        VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $name, $username, $hashed_password, $email, $create_datetime, $activation_token);




        if ($stmt->execute()) {
            $activation_link = 'http://localhost/coffee-shop-website/users/activate.php'
                . '?email=' . urlencode($email)
                . '&activation_token=' . urlencode($activation_token);
            $body = "Hi, $name. Click here to activate your account: $activation_link";
            $header = "Account Activation for KG";
            send_mail($email, $header, $body);
            echo "
            <div class='form'>
                <h3>You are registered successfully.</h3><br/>
                <p class='link'>Click here to <a href='login.php'>Login</a></p>
            </div>";
        } else {

            echo "
            <div class='form'>
                <h3>Required fields are missing.</h3><br/>
                <p class='link'>Click here to <a href='registration.php'>registration</a> again.</p>
            </div>";
        }
        $stmt->close();
    } else {
    ?>
        <form class="form" method="POST">
            <center>
                <img src="../assets/images/logo.png" alt="" class="img img-fluid">
            </center>
            <hr />
            <h1 class="login-title">Registration</h1>
            <input type="text" class="login-input" name="name" placeholder="Name" required />
            <input type="text" class="login-input" name="username" placeholder="Username" required />
            <input type="text" class="login-input" name="email" placeholder="Email Adress" required>
            <input type="password" class="login-input" name="password" placeholder="Password" required>
            <input type="submit" name="submit" value="Register" class="login-button">
            <p class="link">Already have an account? <a href="login.php">Login here!</a></p>
        </form>
    <?php
    }
    ?>
</body>

</html>