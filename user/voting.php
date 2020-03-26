<?php
    session_start();
    error_reporting(0);
    
    isset($_SESSION["userId"]) or header("Location: ../index.php") and exit(0);
    
    include "../mysql.php";
    
    $conn = FALSE;
    $userId = $_SESSION["userId"];
    $userName = $_SESSION["userName"];
    $email = trim($_GET["login"]);
    $search = trim($_POST["txt_search"]);
    $voteId = trim($_POST["radio_topic"]);
    $choice = trim($_POST["radio_option"]);
    $errmsg = "";
    
    if (isset($_POST["submit_search"]) || isset($_POST["submit_vote"])) {
        $conn = dbOpen();
        if (!$conn) {
            $errmsg .= mysql_error()."<br />";
        }
        else {
            $rows = retrieveVoteByTopicUseridVoteid($conn, $search, $userId);
            if (is_null($rows)) {
                $errmsg .= mysql_error()."<br />";
            }
            else if (count($rows)==0) {
                $errmsg .= "No topic matches \"".$search."\"!<br />";
            }
            else {
                if (isset($_POST["submit_vote"])) {
                    $res = createUserVote($conn, $userId, $voteId, $choice);
                    if (!$res) {
                    //$errmsg .= mysql_error()."<br />";
                    }
                    else {
                        $errmsg .= "Voting success!";
                        $search = "";
                        $voteId = "";
                        $choice = "";
                        $rows = retrieveVoteByTopicUseridVoteid($conn, $search, $userId);
                    }
                }
            }       
        }
        dbClose($conn);
        $conn = FALSE;
    }
?>
<html>
<head>
    <meta charset='UTF-8'>
    <title>User Voting - Vulnerable Voting System</title>

    <script type='text/javascript'>
        function validateVote(){
            var errmsg = ""

            var tops = document.getElementsByName('radio_topic')
            var tops_flag = false
            for (var i = 0; i < tops.length; ++i){
                if (tops[i].checked == true){
                    tops_flag = true
                }
            }

            var opts = document.getElementsByName('radio_option')
            var opts_flag = false
            for (var i = 0; i < opts.length; ++i){
                if (opts[i].checked == true){
                    opts_flag = true
                }
            }

            if (!tops_flag){
                errmsg += "Topic is blank! <br />"
            }
            if (!opts_flag){
                errmsg += "Option is blank! <br />"
            }

            document.getElementById('err_vote').innerHTML = errmsg

            return errmsg == ""
        }
    </script>
</head>

<body>
    <form id='form_logout' name='form_logout' method='POST' action='../logout.php'>
        <table border='0' width='100%'>
            <tr>
                <td colspan='3'>
                    <h2>Vulnerable Voting System</h2>
                    <h3>User Voting</h3>
                </td>
                <!-- userinfo -->
                <td align='RIGHT' valign='BOTTOM'>
                    <?=$userName ?><br />
                </td>
            </tr>
            <!-- navigation -->
            <tr bgcolor='#8AC007' align='CENTER'>
                <td width='25%'><a href='profile.php?login=<?=$email?>'>Profile</a></td>
                <td width='25%'>Voting</td>
                <td width='25%'><a href='result.php?login=<?=$email?>'>Result</a></td>
                <td align='RIGHT'><input type='SUBMIT' id='submit_logout' name='submit_logout' value='Logout' /></td>
            </tr>
        </table>
    </form>

    <h3>Vote Now:</h3>
    <font color='#FF0000'>
        <span id='err_vote'></span>
    </font>
    <form id='form_topic_search' name='form_topic_search' method='POST' action='voting.php?login=<?=$email?>'>
        Topic: <input type='TEXT' id='txt_search' name='txt_search' value='' />
        <input type='SUBMIT' id='submit_search' name='submit_search' value='Search' />
    </form>

    <form id='form_vote' name='form_vote' method='POST' action='voting.php?login=<?=$email?>'>
        <table border='0'>
            <tr bgcolor='#8AC007'>
                <th></th>
                <th>Hot Topic</th>
                <th>Option A</th>
                <th>Option B</th>
                <th>Option C</th>
                <th>Option D</th>
            </tr>
            <?php
                for ($i=0; $i<count($rows); $i++) {
                $row = $rows[$i];
            ?>
                <tr bgcolor='#C1E0EB'>
                    <td><input type='RADIO' id='radio_topic' name='radio_topic' value='<?=$row["_id"];?>'> </td>
                    <td><?=$row["topic"];?></td>
                    <td><?=$row["option_a"];?></td>
                    <td><?=$row["option_b"];?></td>
                    <td><?=$row["option_c"];?></td>
                    <td><?=$row["option_d"];?></td>
                </tr>
            <?php
                }
            ?>
        </table>
        <br />
        My Option:
        <input type='RADIO' id='radio_option' name='radio_option' value='1'>A / 
        <input type='RADIO' id='radio_option' name='radio_option' value='2'>B / 
        <input type='RADIO' id='radio_option' name='radio_option' value='3'>C /
        <input type='RADIO' id='radio_option' name='radio_option' value='4'>D
        <br />
        <input type='HIDDEN' id='txt_search' name='txt_search' value='COMP4632' />
        <input type='SUBMIT' id='submit_vote' name='submit_vote' value='Vote' onclick='javascript: return validateVote();' />
    </form>
</body>
</html>