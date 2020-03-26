<?php
    session_start();

    include "../mysql.php";

    $conn = FALSE;

    $loginId = trim($_POST["txt_login"]);
    $password = $_POST["pwd_login"];

    $errmsg = "";

    if (isset($_POST["submit_login"])){
        $conn = dbOpen();

        if (!$conn) {
            $errmsg .= mysql_error()."<br />";
        }
        else {
            $rows = retrieveUserByEmailPassword($conn, $loginId, $password);
            if (is_null($rows)) {
                $errmsg .= mysql_error()."<br />";
            }
            else if (count($rows) != 1) {
                $errmsg .= "Login failed!<br />";
            }
            else if ($rows[0]["status"] != 'A') {
                $errmsg .= "Login failed!<br />";
            }
        }

        dbClose($conn);
        $conn = FALSE;
    }

    if (isset($_POST["submit_login"]) && strcmp($errmsg, "") == 0){
        $row = $rows[0];
        $_SESSION["userId"] = $row["_id"];
        $_SESSION["userName"] = $row["name"];

        header("Location: client.php");
    }
    else {
?>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Admin Login - Vulnerable Voting System</title>

        <script type='text/javascript'>
            function validateLogin() {
                var txtLogin = document.getElementById("txt_login").value;
                var pwdLogin = document.getElementById("pwd_login").value;
                var errmsg = "";

                if (txtLogin=="") {
                    errmsg += "Login email is missing!<br />";
                }
                else if (!validateEmail(txtLogin)) {
                    errmsg += "Login incorrect!<br />";
                }
                if (pwdLogin=="") {
                    errmsg += "Password is missing!<br />";
                }

                document.getElementById("err_login").innerHTML = errmsg;

                return(errmsg=="");
            }
        </script>
    </head>

    <body>
        <h2>Vulnerable Voting System</h2>
        <h3>Management Portal</h3>
        <font color='#FF0000'>
            <span id='err_login'><?=$errmsg; ?></span>
        </font>
        <form id='form_login' name='form_login' method='POST' action='login.php'>
            LOGIN ID: <input type='TEXT' id='txt_login' name='txt_login' value='<?=$loginId ?>' size='16' /><br />
            PASSWORD: <input type='PASSWORD' id='pwd_login' name='pwd_login' value='' size='16' /><br />
            <input type='SUBMIT' id='submit_login' name='submit_login' value='Login' onclick='javascript: return validateLogin();'/>
        </form>
    </body>
    </html>

<?php
    }
?>