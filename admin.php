<?php
	session_start();
?>

<?php
include("dbConfig.php");


$id = session_id();
$cookie_sql = "SELECT Droit FROM Session_cookie WHERE Cookie = '$id'";
$pass_sql = mysqli_query($link, $cookie_sql);

	if ($pass_sql === false) {
            echo "Could not successfully run query ($sql) from DB: " . mysqli_error();
            exit;
        }
	$id = mysqli_fetch_array($pass_sql); 
        if ($id['Droit'] == "adm")
	{			
		$target_dir = "uploads/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		if(isset($_POST["submit"])) {
		   	// Check if file already exists
			if (file_exists($target_file)) {
			    echo "Sorry, file already exists.";
			    $uploadOk = 0;
			}
			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 500000) {
			    echo "Sorry, your file is too large.";
			    $uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType == "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
			    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			    $uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
			    echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			        	echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
				} else {
			        	echo "Sorry, there was an error uploading your file.";
				}
			}
		}
	}
	else
		die("Access Denied");
?>

	<?php include("includes/a_config.php") ?>
<!DOCTYPE html>
<html>

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
<form action="<?= $_SERVER['PHP_SELF'] ?>"method="post" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload file" name="submit">
</form>
	<?php include("includes/footer.php");?>
</body>
</html>

