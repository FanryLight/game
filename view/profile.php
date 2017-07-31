<html>
<head>
    <link rel="stylesheet" type="text/css" href="/web/css/style.css">
</head>
<body>
<div class="container">
    <h1>tic-tac-toe</h1>
    <br>
    Hi, <label class="login"><?php echo $data['login'] ?></label><br>
    <?php echo "Total games: ".$data['games']."  Victories: ".$data['wins']." Defeats: ".$data['defeats']?><br>
    Victory rate: <?php echo $data['rate'] ?>
    <br>
    <div class="center">
    <a class="play" href="/room">Play</a>
    <a class="logout" href="/authorization/logout">Logout</a><br>
    </div>
</div>
</body>
</html>
