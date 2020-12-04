<?php

if (isset($_POST["user"])){
	
	header("Location:" . "http://localhost/BlackJack/CreateUser.php");
	exit();
}


if (isset($_POST["submit"])) {
    // form has been submitted

    if (isset($_POST["username"]) && isset($_POST["password"]) && ! empty($_POST["username"]) && ! empty($_POST["password"])) {
        //echo "username is " . $_POST["username"];
        //echo "<br>";
        //echo "password is " . $_POST["password"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        	if (checkLoginCredentials($username, $password)) {
            	echo "successfully logged in ";
            	
            	session_start();
            	$_SESSION["user"] = $username;
            	// make sure has the name of the project
            	//header("Location:" . "http://localhost/TTT_eclips_SecondPart/SuccessLogin.php");
            	header("Location:" . "http://localhost/BlackJack/blackJack_main.php");
            	updateUserBalance($username);
            	exit();
        	}
        	else
        	{
        		echo "Incorrect user or password.";
        		$_POST["username"] = $username;
        		$_POST["password"] = $password;
        	}
        	
        
        // now we got to do the pdo logic and load another page
    }
}

if (isset($_POST["changePassword"])) {
    header("Location:" . "http://localhost/BlackJack/changePassword.php"); 
    
	exit();
    
}

function createPdo()
{
    $host = 'localhost:81';
    $user = 'root';
    // for now the password is empty
    $password = '';
    $dbname = 'black jack';
    // Set DSN
    $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;
    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $pdo;
}

function checkLoginCredentials($username, $password)
{
    $pdo = createPdo();
    $stmt = $pdo->query('SELECT * FROM balance');
    $loginValid = false;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

       
        $usernameDb = $row['username'];
        $passwordDb = $row['password'];

        if ($username === $usernameDb && $password === $passwordDb) {
            $loginValid = true;
            break;
        }
    }

    $pdo = null;
    return $loginValid;
}
function updateUserBalance($username){
    	$pdo=createPdo();
    	//table must be created with autoincrement functionality
   
     	$sql = "UPDATE balance SET balance = balance+100 WHERE username = :user";
     	$stmt = $pdo->prepare($sql);
     	
     	$success=$stmt->execute(array(':user' => $username));
     	$pdo=null;
    	
    	return $success;
}

?>


<html>
<body>
	<!--  make sure to use post and not get -->
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"
		method="post">
		<input type="text" name="username" placeholder="username" required value=<?php if (!empty($_POST['username'])) print $_POST['username']; ?> > <input
			type="password" name="password" placeholder="password" required > <input
			type="submit" value="submit" name="submit"> <input
			type="submit" value="New User" name="user">
			 <input type="submit" value="Change Password" name="changePassword">

	</form>
</body>
</html>