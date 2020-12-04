<?php 
include_once "myblackJack.php";
//session_start();

?>
<html>
	<head>
	

	</head>
<body >

<h2>Black Jack</h2>

<form method="POST" action="">
Add to Balance <input type="text" name="balance" value="">
<input type="submit" name="add" value="Add" />
Your balance now <input type="text" name="update_balance" value="<?php echo retrieveUserBalance($_SESSION["user"]);?>"/>
</form>

<form method="POST" action="blackJack_main.php">

<!-- Add to Balance <input type="text" name="balance" value=""> -->

 
 </br></br>
<table border=1>
   
  <tr>    
    <th>Number of hands</th>
    <th>Bet Hand 1</th>     
    <th>Bet Hand 2</th>
    <th>Bet Hand 3</th>
  </tr>  
  <tr>  
    <td><select name="hand"style="width:100%">
        <option value=1>One</option>
        <option value=2>Two</option>
        <option value=3>Three</option>               
    </select></td>    
    <td><select name="bet0" style="width:100%">
        <option value=5>5</option>
        <option value=10>10</option>
        <option value=25>25</option>
        <option value=50>50</option>
        <option value=100>100</option>  
    </select></td>
    <td><select name="bet1" style="width:100%">
        <option value=5>5</option>
        <option value=10>10</option>
        <option value=25>25</option>
        <option value=50>50</option>
        <option value=100>100</option>  
    </select></td>
    <td><select name="bet2" style="width:100%">
        <option value=5>5</option>
        <option value=10>10</option>
        <option value=25>25</option>
        <option value=50>50</option>
        <option value=100>100</option>  
    </select></td>
  </tr>  
</table>


  
   </br></br>	
   <input type="submit" name="hit" value="Hit" />
   <input type="submit" name="stand" value="Stand" />   
   <input type="submit" name="double" value="Double"/>
   <input type="submit" name="split" value="Split"/>
   
   <input type="submit" name="deal" value="Deal" />
	


</form>


	</body>
</html>

	</body>
</html>





