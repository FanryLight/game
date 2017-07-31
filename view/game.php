<html>
    <head>
        <link rel="stylesheet" type="text/css" href="/web/css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="web/js/game.js"></script>
    </head>
    <body>
    <div class="container center">
        <h1>tic-tac-toe</h1>
        <div class="board">
            <label class="sign <?php echo $data['sign']?>"><?php echo $data['sign']?></label>
            <label class="login"><?php echo $data['userLogin']?></label> vs
            <label class="login"><?php echo $data['opponentLogin']?></label>
            <label class="sign <?php echo $data['sign'] === 'x' ? 'o' : 'x';?>">
                <?php echo $data['sign'] === 'x' ? 'o' : 'x';?>
            </label>
        </div>
        <label id="message" class="message"><?php echo $data['turn'] ? "Your turn" : "Opponent's turn"; ?></label>
        <input type="hidden" id="turn" value="<?php echo $data['turn']; ?>">
        <table class="field">
            <tr>
                <td height="100" width="100"><span class="field-cell sign-big <?php echo $data['field'][0][0]?>" id="0_0"><?php echo $data['field'][0][0]?></span></td>
                <td height="100" width="100"><span class="field-cell sign-big <?php echo $data['field'][0][1]?>" id="0_1"><?php echo $data['field'][0][1]?></span></td>
                <td height="100" width="100"><span class="field-cell sign-big <?php echo $data['field'][0][2]?>" id="0_2"><?php echo $data['field'][0][2]?></span></td>
            </tr>
            <tr>
                <td height="100" width="100"><span class="field-cell sign-big <?php echo $data['field'][1][0]?>" id="1_0"><?php echo $data['field'][1][0]?></span></td>
                <td height="100" width="100"><span class="field-cell sign-big <?php echo $data['field'][1][1]?>" id="1_1"><?php echo $data['field'][1][1]?></span></td>
                <td height="100" width="100"><span class="field-cell sign-big <?php echo $data['field'][1][2]?>" id="1_2"><?php echo $data['field'][1][2]?></span></td>
            </tr>
            <tr>
                <td height="100" width="100"><span class="field-cell sign-big <?php echo $data['field'][2][0]?>" id="2_0"><?php echo $data['field'][2][0]?></span></td>
                <td height="100" width="100"><span class="field-cell sign-big <?php echo $data['field'][2][1]?>" id="2_1"><?php echo $data['field'][2][1]?></span></td>
                <td height="100" width="100"><span class="field-cell sign-big <?php echo $data['field'][2][2]?>" id="2_2"><?php echo $data['field'][2][2]?></span></td>
            </tr>
        </table>
    </div>
    </body>
</html>

