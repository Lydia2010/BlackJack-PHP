
<?php
if (isset($_POST["submit"])) {
    // form has been submitted
    
    if (isset($_POST["username"]) && isset($_POST["password"]) && ! empty($_POST["username"]) && ! empty($_POST["password"])) {
        //echo "username is " . $_POST["username"];
        //echo "<br>";
        //echo "password is " . $_POST["password"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        if (validateCredentials($username, $password)){
            $rownum = chekExistingUser($username);
            
            if(empty($rownum) || $rownum==0){
                                 
        		if (insertIntoTable($username, $password)) {
        		
            		echo "succesfully created account ";
            	// make sure it is the same project name
            		header("Location:" . "http://localhost/BlackJack/LoginForm.php");
            		exit();
        		}
            }else{
            	echo "<br>";
        		echo "user already exists";
                
            }
            
        }
        else 
        {
        	echo "<br>";
        	echo "invalid username or password";
        }
        
        //update
        
        // now we got to do the pdo logic and load another page
    }
}

function createPdo()
{
    $host = 'localhost:81';
    $user = 'root';
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

function insertIntoTable($username,$password){
    $pdo=createPdo();
    //table must be created with autoincrement functionality
    //ALTER TABLE credentials MODIFY COLUMN id INT auto_increment
     $sql = "INSERT INTO balance(id,username,password) VALUES(:id, :username, :password)";
     $stmt = $pdo->prepare($sql);
     //$success=$stmt->execute(['id' => NULL, 'username' => $username, 'password' => $password]);
     $success=$stmt->execute(array(':id' => NULL,  ':username' => $username,':password' => $password));
     $pdo=null;
    // INSERT INTO table_name (name, group) VALUES ('my name', 'my group')
    return $success;
}

function chekExistingUser($username){
    $pdo=createPdo();
    
     $sql = "SELECT count(*) FROM balance WHERE username = ?";
     $stmt = $pdo->prepare($sql);
     	
     $stmt->execute(array($username)); 
     //$stmt->execute(array(':user' => $username)); 
       
     $count = $stmt->fetchColumn(); 
 
     $pdo=null;
     
     return $count;
}

function validateCredentials($username, $password){
	if (preg_match("/^[a-zA-Z0-9]{5,10}$/",$username)){ 
		if (preg_match ('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/', $password) ) {

        	return true;

    	} else {
       
        	return false;
    	}
	}
	else {
		
	  return false;
	  
	}
}
//update
//if (preg_match()

?>




<html>
<body>
</body>
<h2>Contact Form</h2>

<p>
	<span class="error">* required field.</span>
</p>

<form method="POST"
	action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<table>
		<tr>
			<td>Username</td>
			<td><input type="text" name="username"
				placeholder="first name" required>  
			</td>
		</tr>

		<tr>
			<td>Password</td>
			<td><input type="password" name="password"
				placeholder="password" required> 
			</td>
		</tr>

		



		
		<tr>
			<td><input type="submit" name="submit" value="Submit"></td>
		</tr>

	</table>
</form>

</html>