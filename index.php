<?php
$loggedIn = false;

if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] === 'username' && $_POST['password'] === 'password') {
        $loggedIn = true;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        html {
            height: 100%;
        }

        body {
            height: 100%;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            display: grid;
            justify-items: center;
            align-items: center;
            background-color: #3a3a3a;
        }

        #main-holder {
            width: 50%;
            height: 70%;
            display: grid;
            justify-items: center;
            align-items: center;
            background-color: white;
            border-radius: 7px;
            box-shadow: 0px 0px 5px 2px black;
        }

        #login-error-msg-holder {
            width: 100%;
            height: 100%;
            display: grid;
            justify-items: center;
            align-items: center;
        }

        #login-error-msg {
            width: 23%;
            text-align: center;
            margin: 0;
            padding: 5px;
            font-size: 12px;
            font-weight: bold;
            color: #8a0000;
            border: 1px solid #8a0000;
            background-color: #e58f8f;
            opacity: 0;
        }

        #error-msg-second-line {
            display: block;
        }

        #login-form {
            align-self: flex-start;
            display: grid;
            justify-items: center;
            align-items: center;
        }

        .login-form-field::placeholder {
            color: #3a3a3a;
        }

        .login-form-field {
            border: none;
            border-bottom: 1px solid #3a3a3a;
            margin-bottom: 10px;
            border-radius: 3px;
            outline: none;
            padding: 0px 0px 5px 5px;
        }

        #login-form-submit {
            width: 100%;
            padding: 7px;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            background-color: #3a3a3a;
            cursor: pointer;
            outline: none;
        }
    </style>
    <script defer src="login-page.js"></script>
</head>

<body>
    <main id="main-holder">
        <?php if ($loggedIn === false) : ?>
            <h1 id="login-header">Login</h1>

            <div id="login-error-msg-holder">
                <p id="login-error-msg">Invalid username <span id="error-msg-second-line">and/or password</span></p>
            </div>

            <form id="login-form" action="#" method="post">
                <input type="text" name="username" id="username" class="login-form-field" placeholder="Username">
                <input type="password" name="password" id="password" class="login-form-field" placeholder="Password">
                <button type="submit" value="Logout" id="login">Login</button>
            </form>

        <?php else : ?>
            <h1 id="user-header">You are logged in.</h1>
            <nav>
                <button id="user_dropdown">User Drop Down</button>
                <button id="punch_in">Punch In</button>
                <button id="punch_out">Punch Out</button>
                <form id="logout-form" action="#" method="post">
                    <button type="submit" value="Logout" id="logout">Logout</button>
                </form>
            </nav>
        <?php endif; ?>
    </main>
</body>

</html>