<?php
session_start();
//global $newpassword;

//$username=$_SESSION["username"];
?>
 <html>
<body>
</body>
<h2>Change Password Form</h2>

<p>
	<span class="error">* required field.</span>
</p>

<form method="POST"
	action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<table>
	     <tr>
			<td>User</td>
			<td><input type="user" name="user"
				placeholder="User Name" required>  
			</td>
		</tr>
        <tr>
			<td>Old Password</td>
			<td><input type="password" name="oldpassword"
				placeholder="Old Password" required>  
			</td>
		</tr>

		<tr>
			<td>New Password</td>
			<td><input type="password" name="newpassword"
				placeholder="New Password" required> 
			</td>
		</tr>
        <tr>
			<td>Confirm Password</td>
			<td><input type="password" name="confirmpassword"
				placeholder="Confirm Password" required> 
			</td>
		</tr>
		<tr>
			<td><input type="submit" name="submit" value="Change Password"></td>
		</tr>

	</table>
</form>

</html>
<?php
        

if (isset($_POST["submit"])) {
           if (isset($_POST["submit"]) && isset($_POST["user"]) && !empty($_POST["user"])) {
            $username=$_POST["user"];
            $oldpassword=$_POST["oldpassword"];
            $newpassword=$_POST["newpassword"];
            $confirmpassword=$_POST["confirmpassword"];
            print_r($_SESSION);
            print_r($_POST);
               
            if(validatePassword($newpassword)) { 
               
            if (updatePassword($newpassword,$username)) {
                echo "successfully changed password.";
                header("Location:" . "http://localhost/BlackJack/LoginForm.php");
            		exit();
            }
                 
        } 
     else {
                
            echo "<br>";
        	echo "Invalid username or password.<br> Password must be between 5 and 10 characters and must contain at least one letter, one number and one special character.";  
                 }
    }
    
}
   
        function createPdo()
            {
                //changed localhost and password info so it works with my computer
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
            function updatePassword($password,$user)
            {
                $newpassword=$_POST["newpassword"];
                $pdo = createPdo();
                $sql="UPDATE balance SET  password =:password WHERE username = :user";
                $stmt = $pdo->prepare($sql);
                $success=$stmt->execute(array(':password'=>$password, ':user' => $user));
            
                
                $pdo=null;
                return $success;
                                
            }

            function validatePassword($newpassword){
	        if (preg_match ("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/", $newpassword)) {
            
                
        	return true;

    	} else {
       
        	return false;
    	}
	}
              
           
?>