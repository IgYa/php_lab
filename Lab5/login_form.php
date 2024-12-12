<form method="POST" action="login.php">
    <h3>Увійдіть за допомогою Telegram Username або номеру телефону. Ви можете ввести пароль або скористатися кодом, надісланим у Telegram.</h3>

    <label for="username">Telegram Username:</label>
    <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" placeholder="@username">
    <br>

    <label for="phone">Phone Number:</label>
    <input type="text" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" placeholder="+1234567890">
    <br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter your password">
    <br>

    <button type="submit" name="login">Login</button>
    <br><br>

    <h4>Або отримайте код для входу через Telegram:</h4>
    <button type="submit" name="send_code">Send Code</button>
    <br>

    <label for="auth_code">Authentication Code:</label>
    <input type="text" id="auth_code" name="auth_code" placeholder="Enter the code from Telegram">
    <br>

    <button type="submit" name="login">Login with Code</button>
</form>
