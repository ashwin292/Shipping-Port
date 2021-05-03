<?php
$name = null;
$email = null;
$password = null;

$servername = "us-cdbr-east-03.cleardb.com";
$username = "b474b95ea4f970";
$password = "46b36be7";
$db = "heroku_989d675bc42ca01";
$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error){
    die("Connection failed: ". $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(!empty($_POST["user_id"]) && !empty($_POST["password"]) && !empty($_POST["email"])) {
        $password = $_POST["password"];
        $user_id = $_POST["user_id"];
        $email = $_POST["email"];

        $sql = "SELECT * from 180b_users where user_id = '$user_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        if(password_verify($password, $row['password']) && $row['email'] == $email) {
            echo "Logged in";
        }
        else {
            header('Location: authenticate.php');
        }
        
    } else {
        header('Location: authenticate.php');
    }
} else {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    </head>
    <body>
        <div>
            <header>
                <a href="index.php">Home</a>
                <h1>Login</h1>        
            </header>
            <section>
                <form id="login" method="post">
                    <label for="user_id">User_ID:</label>
                    <input id="user_id" name="user_id" type="number" required>
                    <label for="email">Email:</label>
                    <input id="email" name="email" type="text" required>
                    <label for="password">Password:</label>
                    <input id="password" name="password" type="password" required>                    
                    <br />
                    <input type="submit" value="Login">
                </form>
            </section>
        </div>
    </body>
</html>
<?php } ?>