<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arthasanjal</title>
    <link rel="stylesheet" href="userlogin.css">
</head>

<body>
    <div class='top'></div>

    <section class="forms-section">
        <div class="section-title">
            <h1>Artha-Sanjal</h1>
        </div>
        <div class="forms">
            <div class="form-wrapper is-active">
                <button type="button" class="switcher switcher-login">
                    Login
                    <span class="underline"></span>
                </button>
                <form class="form form-login" action="FormLogin.php" method="post">
                    <fieldset>
                        <legend>Please, enter your email and password for login.</legend>
                        <div class="input-block">
                            <label for="login-Phonenumber">Phone-Number</label>
                            <input id="login-Phonenumber" name="login-Phonenumber" type="text" required>
                        </div>
                        <div class="input-block">
                            <label for="login-password">Password</label>
                            <input id="login-password" name="login-password" type="password" required>
                        </div>


                        <?php
                        if (isset($_GET['error'])) {
                            $error = $_GET['error'];

                            if ($error == 'userNotFound') {
                                echo '<p style="color: red; font-size: 14px;">User not found. Please check your phone number.</p>';
                            } elseif ($error == 'incorrectPassword') {
                                echo '<p style="color: red; font-size: 14px;">Incorrect password. Please try again.</p>';
                            }
                        }
                        ?>

                    </fieldset>
                    <button type="submit" class="btn-login">Login</button>
                </form>
            </div>

            <div class="form-wrapper">
                <button type="button" class="switcher switcher-signup">
                    Sign Up
                    <span class="underline"></span>
                </button>
                <form class="form form-signup" action="FormSignup.php" method="post">
                    <fieldset>
                        <div class="input-block">
                            <label for="Group-name">Group Name</label>
                            <input id="Group-name" name="Group-name" type="text" required>
                        </div>
                        <div class="input-block">
                            <label for="signup-account_number">Account number</label>
                            <input id="signup-account_number" name="signup-account_number" type="text" required>
                        </div>
                        <div class="input-block">
                            <label for="signup-email">E-mail</label>
                            <input id="signup-email" name="signup-email" type="email" required>
                        </div>
                        <div class="input-block">
                            <label for="signup-Phonenumber">Phone-Number</label>
                            <input id="signup-Phonenumber" name="signup-Phonenumber" type="tel" pattern="\d{10}"
                                required>
                        </div>
                        <div class="input-block">
                            <label for="signup-password">Password</label>
                            <input id="signup-password" name="signup-password" type="password" required>
                        </div>
                        <div class="input-block">
                            <label for="signup-password-confirm">Confirm password</label>
                            <input id="signup-password-confirm" name="signup-password-confirm" type="password" required>
                        </div>
                    </fieldset>

                    <button type="submit" class="btn-signup">Submit</button>
                </form>
            </div>
        </div>
    </section>
    <script src="userlogin.js"></script>
</body>

</html>