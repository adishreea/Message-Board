<html> 
    <head>
        <title> Login Page </title>
    </head>
    <body>
        <div align = center>
            <form  method="get" action="<?= $_SERVER['PHP_SELF']; ?>">
                <table>
                    <tr>
                        <td>Username</td>
                        <td><input type="text" name="name"/></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input type="password" name="password"/></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="Login"/></td>
                    </tr>
                </table>
            <?php
                try 
                {
                    if (isset($_GET['name']) && isset($_GET['password'])) 
                    {
                        $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board", "root", "");
                        $dbh->beginTransaction();
                        $stmt = $dbh->prepare("select count(*) as count from users where username='" . $_GET['name'] . "' and password='" . md5($_GET['password']) . "'");
                        $stmt->execute();	
                        $row=$stmt->fetch();
                        if($row['count']>0)
                        {  
                            session_start();
                            $_SESSION['user1'] = $_GET['name'];
                            header('location: board.php');
                        } 
                    }
                } 
                catch (PDOException $e) 
                {  
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();  
                }   
                ?>
            </form>
        </div>
    </body>
</html>