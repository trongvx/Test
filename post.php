<?php
  if (!isset($_SESSION['email']))
     header('Location: login.php');
  else
  {
    if(isset($_POST['submit']) && $_POST['post'] != '')
    {
      include 'connect.php';
      error_reporting(E_ALL);
      ini_set('display_errors', 1);
      $post = $_POST['post'];
      $post = htmlentities($post);
      $query = $db->prepare("INSERT INTO posts(email,post) VALUES(?,?)");
      $query->execute([$_SESSION['email'],$post]);
    }
  }
?>
<!DOCTYPE>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta httmp-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <link rel="shortcut icon" href="t.ico" />
	<title>Post</title>
	
</head>
<body>
<div class="container" style="margin-left: -15px; width: 103%;">
<form method="POST">
  <div class="form-group">
  <textarea name="post" style="width:100%;height:130px;" placeholder="What's on your mind, <?php
    $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute(array($_SESSION['email']));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $user['fullname'] . '?';
  ?>"></textarea>
  </div>
  <button type="submit" name="submit" style="float:right; margin-bottom: 20px; margin-top: -10px; width: 100px;" class="btn btn-primary">Share</button>
</form>
</div>
</body>
</html>
