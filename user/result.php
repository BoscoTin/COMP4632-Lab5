<?php
    session_start();
    error_reporting(0);
    
    isset($_SESSION["userId"]) or header("Location: ../index.php") and exit(0);
    
    include "../mysql.php";
    
    $conn = FALSE;
    $userId = $_SESSION["userId"];
    $userName = $_SESSION["userName"];
    $email = trim($_GET["login"]);

    $errmsg = "";

    $conn = dbOpen();
    if (!$conn) {
        $errmsg .= mysql_error()."<br />";
    }
    else {
        $rows = retrieveUserVoteByEmail($conn, $email);

        if (is_null($rows)){
            $errmsg .= mysql_error()."<br />";
        } 
    }

    dbClose($conn);
    $conn = FALSE;
?>

<html>
<head>
    <meta charset='UTF-8'>
    <title>User Result - Vulnerable Voting System</title>
</head>

<body>
    <form id='form_logout' name='form_logout' method='POST' action='../logout.php'>
        <table border='0' width='100%'>
            <tr>
                <td colspan='3'>
                    <h2>Vulnerable Voting System</h2>
                    <h3>User Result</h3>
                </td>
                <!-- userinfo -->
                <td align='RIGHT' valign='BOTTOM'>
                    <?=$userName?><br />
                </td>
            </tr>
            <!-- navigation -->
            <tr bgcolor='#8AC007' align='CENTER'>
                <td width='25%'><a href='profile.php?login=<?=$email?>'>Profile</a></td>
                <td width='25%'><a href='voting.php?login=<?=$email?>'>Voting</a></td>
                <td width='25%'>Result</td>
                <td align='RIGHT'><input type='SUBMIT' id='submit_logout' name='submit_logout' value='Logout' /></td>
            </tr>
        </table>
    </form>

    <font color='#FF0000'>
        <span id='err_retrieve'><?=$errmsg?></span>
    </font>

    <h3>Voting Record:</h3>
    <table border='0' cellpadding='5'>
        <tr bgcolor='#8AC007'>
            <th>Hot Topic</th>
            <th>My Choice</th>
        </tr>

        <?php 
            $alphabet = range('a', 'z');
            for($i = 0; $i < count($rows); ++$i) {
                $row = $rows[$i];
        ?> 
            <tr bgcolor='#C1E0EB'>
                <td><?=$row["topic"]?></td>
                <td><?=$row[ "option_".$alphabet[$row["choice"] - 1] ]?></td>
            </tr>
        <?php
            }
        ?>
        
    </table>
</body>
</html>