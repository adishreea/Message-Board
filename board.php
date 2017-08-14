<html>
    <head>
        <title>Message Board</title>
        <style>
            table 
            {
                border-collapse: collapse;  
            }
            table, th, td 
            {
                    border: 1px solid black; 
            }
        </style>
    </head>
    <body style=" padding-left: 2cm; padding-top: 2cm;">
        <?php session_start();  ?>
        <p style="text-align: right"> 
            <a href="board.php?logout=1">Logout</a> 
        </p>
        <?php if(isset($_GET['logout']))
            { 
                session_unset();
                session_destroy();
                header("location: login.php");
            }   
        ?>
        <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
            Enter Message <br/>
            <textarea rows="5" cols="50" name="msg" > </textarea>
            <br/><br/>
            <input type="submit" value="New Post" name="new"/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <br/> <br/>
            <table>
                <thead>
                    <th>Message ID</th>
                    <th>User Name</th>
                    <th>full name</th>
                    <th>Date Time</th>
                    <th>Replay to</th>
                    <th>Message</th>
                    <th></th>
                </thead>
                <tbody> 
                    <?php
                        $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board", "root", "");
                        $dbh->beginTransaction();
                        $st = $dbh->prepare("select p.id as id, u.username as uname, u.fullname as fname, p.datetime as dt, p.replyto as rid, p.message as msg from users u, posts p where p.postedby=u.username order by datetime desc");
                        $st->execute();
                        while($row = $st->fetch())
                        {
                    ?>
                    <tr>
                        <td><?=$row['id']?></td>
                        <td><?=$row['uname']?></td>
                        <td><?=$row['fname']?></td>
                        <td><?=$row['dt']?></td>
                        <td><?=$row['rid']?></td>
                        <td><?=$row['msg']?></td>
                        <td> <button type="submit" name="reply"  formaction="board.php?replyto=<?=$row['id']?>">Reply</button></td>
                    </tr> 
                    <?php  
                        }
                        if(isset($_GET['replyto'])&&isset($_GET['reply']))
                        {
                            echo $_POST['msg'];
                            echo $_GET['replyto'];
                        }  
                    ?> 
                </tbody>
            </table>
        </form>
        <?php  
            $uid= uniqid();
            if(isset($_GET['replyto'])&&isset($_POST['msg']))
            {
                $dbh->exec("update posts set replyto='".$uid."' where id='".$_GET['replyto']."'");
                $dbh->exec("insert into posts (id,postedby,datetime,message) values ('" . $uid . "','".$_SESSION['user1']."',now(),'" . $_POST['msg'] . "')");
                $dbh->commit();
                header('Refresh: 0; url=board.php');
            }
            if (isset($_POST['new'])) 
            { 
                $stmt = $dbh->exec("insert into posts (id,postedby,datetime,message) values ('" . $uid . "','".$_SESSION['user1']."',now(),'" . $_POST['msg'] . "')");
                if ($dbh->commit())
                {
                    echo "message inserted successfully";
                } 
                header('Refresh: 0; url=board.php');   
            }  
        ?> 
    </body>
</html>