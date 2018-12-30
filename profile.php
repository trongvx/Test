<?php
session_start();
if (!isset($_SESSION['email']))
	 header('Location: login.php');
if(isset($_POST['submit']))
{
				error_reporting(E_ALL);
                ini_set('display_errors', 1);
                include 'connect.php';
			
                $fullname = $_POST['fullname']; 
                $phone = $_POST['phone'];
				$company = $_POST['company'];
                $img_name = $_FILES['user_img']['name'];
                $wall = $_FILES['wallpaper']['name'];
				$intro = $_POST['intro'];
			
				$fullname = htmlentities($fullname);
                $phone = htmlentities($phone);  
                $company = htmlentities($company);
                $wall = htmlentities($wall);

                if(!empty($img_name))
                {
                  $tmp = $_FILES['user_img']['tmp_name'];
                  $img_name = time() . $img_name;
                  $new_path = 'img/' . $img_name;

                  if(!move_uploaded_file($tmp, $new_path))
                  {
                    header('Location: profile.php?message=<div class="alert alert-success">Upload image failed !</div>');
                  }
                  else
                  {
                    move_uploaded_file($tmp, $new_path);
                    $query = $db->prepare("UPDATE users SET avatar=? WHERE email=?");
                    $query->execute([$img_name,$_SESSION['email']]);
                  }
                }
                if(!empty($wall))
                {
                  $tmp = $_FILES['wallpaper']['tmp_name'];
                  $wall = time() . $wall;
                  $new_path = 'img/' . $wall;

                  if(!move_uploaded_file($tmp, $new_path))
                  {
                    header('Location: profile.php?message=<div class="alert alert-success">Upload wallpaper failed !</div>');
                  }
                  else
                  {
                    move_uploaded_file($tmp, $new_path);
                    $query = $db->prepare("UPDATE users SET wallpaper=? WHERE email=?");
                    $query->execute([$wall,$_SESSION['email']]);
                  }
                }
                $query = $db->prepare("UPDATE users SET fullname=?, phone=?, company=?, introduction=? WHERE email=?");
                $query->execute([$fullname,$phone,$company,$intro,$_SESSION['email']]);
                header('Location: profile.php?message=<div class="alert alert-success">Update profile successfully !</div>');
}			
?>
<!DOCTYPE>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta httmp-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <link rel="shortcut icon" href="icon/tuebook.ico" />
	<title>Profile</title>
	<style>
		h4
		{
			text-shadow: 3px 3px black;
		}
	</style>
</head>
<body style="background:#084B8A">
<?php include 'welcome-header.php'; ?>
<div class="container" style="margin-left:none">
	<form method="POST" enctype="multipart/form-data">
	 <div class="profile">
	 	  <div class="avatar" style="float:left;">
	 	  	<img style="width: 200px; height: 200px; margin-top:25px; border: solid 6px white" src="img/<?php $query = $db->prepare("SELECT * FROM users WHERE email=?");$query->execute(array($_SESSION['email']));$user = $query->fetch(PDO::FETCH_ASSOC);echo $user['avatar'];?>"/>
			  <br /><h5 style="margin-top: 30px"><a href="change-password.php" style="color:white; text-shadow: 2px 2px black; text-decoration:none"><img src="icon/lock.ico" style="width:32px;height:32px;margin-top:-5px"/> Change password</a></h5>
	 	  </div>
	 	  <div class="profile" style="float:right; width: 80%; margin-top: 15px">
		  <h1 style="color:white; text-shadow: 3px 3px black">Profile</h1>
			<?php
			if(isset($_GET['message']))
			{
				$message = $_GET['message'];
				unset($_GET['message']);
				echo $message;
			}
			?>
		  <h4 style="color:white">Full name</h4>
		  <div class="form-group">
		    <input type="text" name="fullname" class="form-control" value="<?php include 'connect.php'; $stmt = $db->prepare("SELECT * FROM users WHERE email=?"); $stmt->execute(array($_SESSION['email'])); $user = $stmt->fetch(PDO::FETCH_ASSOC); echo $user['fullname'];?>">
		  </div>
		  <h4 style="color:white">Wallpaper</h4>
		  <div class="form-group">
		    <input type="file" name="wallpaper" accept="image/gif, image/jpeg, image/png" class="form-control">
		  </div>
		  <h4 style="color:white">Phone number</h4>
		  <div class="form-group">
		    <input type="text" name="phone" class="form-control" value="<?php include 'connect.php'; $stmt = $db->prepare("SELECT * FROM users WHERE email=?"); $stmt->execute(array($_SESSION['email'])); $user = $stmt->fetch(PDO::FETCH_ASSOC); echo $user['phone'];?>">
		  </div>
		  <h4 style="color:white">Company</h4>
		  <div class="form-group">
		    <input type="text" name="company" class="form-control" value="<?php include 'connect.php'; $stmt = $db->prepare("SELECT * FROM users WHERE email=?"); $stmt->execute(array($_SESSION['email'])); $user = $stmt->fetch(PDO::FETCH_ASSOC); echo $user['company'];?>">
		  </div>
		  <h4 style="color:white">Introduction</h4>
		  <div class="form-group">
		    <textarea name="intro" class="form-control"><?php include 'connect.php'; $stmt = $db->prepare("SELECT * FROM users WHERE email=?"); $stmt->execute(array($_SESSION['email'])); $user = $stmt->fetch(PDO::FETCH_ASSOC); echo $user['introduction'];?></textarea>
		</div>
		  <button type="submit" name="submit" class="btn btn-primary" style="width:100%">Update</button>
		  </div>
		  <div class="browse-avt" style="position: absolute;">
			<input type="button" style="margin-top:25px" value="Upload" class="btn btn-primary" onclick="document.getElementById('fileToUpload').click();" />
			<input type="file" style="display:none;" id="fileToUpload" name="user_img" accept="image/gif, image/jpeg, image/png"/>
		  </div>
	</div>
	</form>
</div>
<div class="footer" style="margin-top: 40%">
<?php include 'footer.php'; ?>
</div>
</body>
</html>
