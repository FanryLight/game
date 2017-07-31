<html>
<head>
    <link rel="stylesheet" type="text/css" href="/web/css/style.css">
</head>
<body>
<div class="container center">
    <h1>tic-tac-toe</h1>
    <h1>Login</h1>
    <form action="/authorization/login" method="post">
        <input type="text" placeholder="Login" name="login" required><br>
        <input type="password" placeholder="Password" name="password" required><br>
        <input class="button" type="submit" name="submit" value="Login">
    </form>

    <h1>Registration</h1>
    <form action="/authorization/register" method="post">
        <input type="text" placeholder="Login" name="login" required><br>
        <input type="password" placeholder="Password" name="password" required><br>
        <input type="password" placeholder="Password" name="repeatPassword" required><br>
        <input class="button" type="submit" name="submit" value="Register">
    </form>

    <label class="message red"><?php echo $message ?></label>
</div>
</body>
</html>


