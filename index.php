<?php
  session_start();
  include 'connect.php';
  include 'functions.php';
  if(isset($_POST['submit']))
  {
        $email = $_POST['email'];
        $password = $_POST['password'];
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute(array($email));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$user)
        {
          header('location:index.php?message=<div class="alert alert-danger">Email or password is incorrect !</div>');
        }
        else
        {
          $result = password_verify($password,$user['password']);
          if($result)
          {
            $_SESSION['email'] = $email;
            header('Location: index.php');
          }
          else
          {
            header('location:index.php?message=<div class="alert alert-danger">Email or password is incorrect !</div>');
          } 
        }
  }
?>
<!DOCTYPE>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta httmp-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<link rel="shortcut icon" href="icon/tuebook.ico" />
    <?php
    if (isset($_SESSION['email']))
    {
      $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
      $stmt->execute(array($_SESSION['email']));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      echo '<title>'.$user['fullname'].'</title>';
    }
    else
    {
      echo '<title>Tuebook</title>';
    }
    ?>
    <style>
          .card{
            margin-top:30px
          }
          li:hover { 
            background-color: blue;
          }
          tr {
            background-size: cover;
          }
		  body
		  {
			  z-index: 2;
			  background-color: rgba(4, 95, 180, 0.3);
		  }
		  .containter
		  {
			  z-index: 1;
		  }
    </style>
</head>
<body style="background:<?php if(isset($_SESSION['email'])) echo '#E6E6E6'; else echo 'white';?>">
<?php include 'welcome-header.php'; ?>
<div class="container-fluid">     
<?php
      if(isset($_GET['find_result']))
        echo $_GET['find_result'];
      if(!isset($_SESSION['email']))
      {
		    echo '<marquee direction="left"><h1 style="color:#084B8A;margin-top:10px;text-shadow: 1px 1px black">Welcome to Tuebook !</h1></marquee>';
        echo '
        <h4 style="color:#084B8A;text-shadow: 1px 1px black;margin-top:30px;margin-left:15%">Sign In Account</h4>
        <center><form method="POST" style="width: 70%">';
        if(isset($_GET['message']))
          {
            $message = $_GET['message'];
            unset($_GET['message']);
            echo $message;
          }
        echo '
          <div class="form-group">
            <input type="email" name="email" class="form-control" aria-describedby="emailHelp" placeholder="Enter email">
          </div>
          <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Enter password">
          </div>
          <div class="register">
            <h6><a href="register.php" style="color: #084B8A; float:left">Register</a></h6>
          </div>
          <button type="submit" name="submit" class="btn btn-primary" style="width:100px; float:right; margin-top:5px">Log in</button>
          <div class="forgot-pasword">
            <br/><h6><a href="reset-password.php" style="color: #084B8A; float:left">Forgot password</a></h6>
          </div>
        </form></center>';
      }
      else
      {
		    $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute(array($_SESSION['email']));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
		  	/*echo '<img src="img/'. $user['wallpaper'] .'" class="img-fluid" alt="Responsive image">';*/
    		echo '<table class="table" style="margin-bottom: 20px;" border="2px" cellpadding="10px" width="100%">';
    			echo '<tbody>';
    				echo '<tr colspan="2" background="img/' . $user['wallpaper'] . '">';
    					echo '<td>';
                echo '<div class="info">';
                  echo '<div class="avatar" style="float:left; margin-left:10px">';
    						    echo '<img style="width: 180px; height: 170px; margin-top:25px; border: solid 6px white;" src="img/' . $user['avatar'] . '" />';
                  echo '</div>';
                  echo '<div class="name" style="float:left; margin-top: 120px; margin-left:20px">';
                    echo '<h1 style="color:white; text-shadow: 3px 3px black">' . $user['fullname'] . '</h1>';
                  echo '</div>';
                echo '</div';
    		  			echo '</td>';
    		 		echo '</tr>';
    		  echo '</tbody>';
    		echo '</table>';
		  	echo '<div class="card" style="float:left; width:25%; margin:0px; height:65%">';
         		 echo '<div class="card-header">';
		  		 	echo '<center><h5 style="margin:0"><img src="icon/earth.ico" style="border:0"/> Introduction</h5></center>';
		  		 echo '</div>';
		  		 echo '<div class="card-body">';
				 	echo '<p class="card-text" style="text-align: justify">' . $user['introduction'] . '</p>';
		  			echo '<hr/>';
		 			echo '<strong><p class="card-text" style="text-align: justify;"><img src="icon/bag.ico" style="border:0"/> ' . $user['company'] . '</p></strong>';
				echo '</div>';
		  	echo '</div>';
		  	echo '<div style="float:right; width:73.5%;">';
		  		include 'post.php';
        		include 'display-posts.php';
		  	echo '</div>';
    		
      }
?>
</div>
<div class="footer" style="margin-top: 50px">
<?php include 'footer.php'; ?>
</div>
</body>
</html>
