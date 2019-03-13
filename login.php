<?php
	session_start();

if (!is_writable(session_save_path())) {
    echo 'Session path "'.session_save_path().'" is not writable for PHP!'; 
}
?>
<?php

include("dbConfig.php");
$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST["name"];
    $password = $_POST["password"];
	 if ($name == '' || $password == '') {
        $msg = "You must enter all fields";
	 } 
	 else {
        	$sql = "SELECT * FROM Account WHERE Nom = '$name' AND password = '$password'";
        	$query = mysqli_query($link, $sql);

       		if ($query === false) {
            		echo "Could not successfully run query ($sql) from DB: " . mysqli_error($link);
            		exit;
		}

		if (mysqli_num_rows($query) > 0) {
			echo "ok password";
			$cookie = session_id();
			$check = "SELECT Droit, Delay FROM Session_cookie WHERE Cookie = '$cookie'";
			$check_droit = mysqli_query($link, $check);
			if ($check_droit === false )
			{
				echo "Could not successfully run query ('$check') from DB: " . mysqli_error($link);
				exit;
			}
			$droit = mysqli_fetch_assoc($check_droit);

			if (isset($droit['Droit']) && isset($droit['Delay']))
			{
			if (strtotime($droit['Delay'])+(24*3600) < strtotime(date("Y-M-d H:i:s")))
			{
				session_destroy();
				$del_cookie = "DELETE FROM Session_cookie WHERE Cookie = '$cookie'";
				$delete = mysqli_query($link, $del_cookie); 
				header('Location: login.php');
				exit;
			}
			if ($droit['Droit'] === "adm")
			{
				header('Location: admin.php');
				exit;	
			}
			}
			$date= date("Y-m-d H:i:s");
			$cookie_sql = "INSERT INTO Session_cookie (ID, Droit, Cookie, Delay) VALUES ('0', 'adm', '$cookie', '$date') ON DUPLICATE KEY UPDATE Cookie = '$cookie'";
			$put_cookie = mysqli_query($link, $cookie_sql);
			if ($put_cookie === false) {
            			echo "Could not successfully run query ('$cookie_sql') from DB: " . mysqli_error($link);
				exit;
			}
			header('Location: admin.php');
            		exit;
        	}
        	$msg = "Username and password do not match";
	 }
}
?>

<?php include("includes/a_config.php");?>
<!DOCTYPE html">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php include("includes/head-tag-contents.php");?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AxaDev.com</title>
<meta name="description" content=""/>
<meta name="keywords" content=""/>

</head>
<body>
	<?php include("includes/design-top.php");?>
	<?php include("includes/navigation.php");?>
<div>
	<form name="frmregister"action="<?= $_SERVER['PHP_SELF'] ?>" method="post" >
		<table class="form" border="0">

			<tr>
			<td></td>
				<td style="color:red;">
				<?php echo $msg; ?></td>
			</tr> 
			
			<tr>
				<th><label for="name"><strong>Name:</strong></label></th>
				<td><input class="inp-text" name="name" id="name" type="text" size="30" /></td>
			</tr>
			<tr>
				<th><label for="name"><strong>Password:</strong></label></th>
				<td><input class="inp-text" name="password" id="password" type="password" size="30" /></td>
			</tr>
			<tr>
			<td></td>
				<td class="submit-button-right">
				<input class="send_btn" type="submit" value="Submit" alt="Submit" title="Submit" />
				
				<input class="send_btn" type="reset" value="Reset" alt="Reset" title="Reset" /></td>
				
			</tr>
		</table>
	</form>
</div>
	<?php include("includes/footer.php");?>
</body>
</html>




















































