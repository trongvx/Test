<?php
  session_start();
  include 'connect.php';
  include 'functions.php';
  $userid = null;
  if(isset($_GET['userid']))
    $userid = $_GET['userid'];
  $user = getUserById($userid);
  if(isset($_SESSION['email']))
  {
    $query = $db->prepare("SELECT * FROM users WHERE email=?");
    $query->execute([$_SESSION['email']]);
    $current_user = $query->fetch(PDO::FETCH_ASSOC);
    if($current_user['id'] == $user['id'])
        header('location: index.php');
  }
  if(isset($_POST['addfriend']) || isset($_POST['unfriend']) || isset($_POST['cancel']) || isset($_POST['accept']))
    header("location: personal.php?userid=$userid");
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
      if($current_user['id'] != $user['id'])
        echo '<title>'.$user['fullname'].'</title>';
      else
        echo '<title>Home</title>';
    }
    else
    {
      echo '
      <title>Welcome</title>';
    }
    ?>
    <style>
          .card{
            margin-right:50%;
            margin-top:30px
          }
          li:hover { 
            background-color: blue;
          }
          tr {
            background-size: cover;
          }
    </style>
</head>
<body style="background:#084B8A">
<div class="container">     
<?php
      include 'welcome-header.php';

        echo '<table style="margin-bottom: 20px;" border="2px" cellpadding="10px" width="100%">';
          echo '<tbody>';
            echo '<tr colspan="2" background="img/' . $user['wallpaper'] . '">';
              echo '<td>';
                echo '<div class="info">';
                  echo '<div class="avatar" style="float:left; margin-left:10px">';
                    echo '<img style="width: 180px; height: 170px; margin-top:25px; border: solid 6px white" src="img/' . $user['avatar'] . '" />';
                  echo '</div>';
                  echo '<div class="name" style="float:left; margin-top: 100px; margin-left:20px">';
                    echo '<h1 style="color:white; text-shadow: 3px 3px black">' . $user['fullname'] . '</h1>';
                    echo '<h6 style="color:white">' . $user['company'] . '</h6>';
                  echo '</div>';
                  echo '<div class="button" style="float:right; margin-top: 30px;">';
                  if(findRelationship($current_user['id'],$user['id']) == 0 && $current_user['id'] <> $user['id'])
                  {
                      echo '<h4 style="color:white; text-shadow: 3px 3px black">Add friend to see more posts!</h4>';
                      echo '<form method="POST" style="float:right">';
                      echo '<button name="addfriend" type="submit" class="btn btn-success" width="150px"><img src="icon/addfriend.ico" width="24px" height="24px" style="border:0"/> Add friend</button>';
                      echo '</form>';
                      if(isset($_POST['addfriend']))
                      {
                        addFriend($current_user['id'],$user['id']);
                      }
                  }
                  else if(findRelationship($current_user['id'],$user['id']) == 1)
                  {
                      echo '<form method="POST">';
                      //Reciever's role =======================================================
                      if($current_user['id'] === findReciever($user['id'],$current_user['id']))
                      {
                        echo '<button name="accept" type="submit" class="btn btn-success" width="150px"><img src="icon/accept.ico" width="24px" height="24px" style="border:0"/> Accept invitation</button>';
                        if(isset($_POST['accept']))
                        {
                          addFriend($current_user['id'],$user['id']);
                        }
                      }
                      //=======================================================================
                      echo '<button name="cancel" type="submit" class="btn btn-danger" width="150px"><img src="icon/refuse.ico" width="24px" height="24px" style="border:0"/> Cancel invitation</button>';
                      echo '</form>';
                      if(isset($_POST['cancel']))
                      {
                        unfriend($current_user['id'],$user['id']);
                      }
                  }
                  else if(findRelationship($current_user['id'],$user['id']) == 2)
                  {
                      echo '<form method="POST">';
                      echo '<button name="unfriend" type="submit" class="btn btn-danger" width="150px"><img src="icon/unfriend.ico" width="24px" height="24px" style="border:0"/> Unfriend</button>';
                      echo '</form>';
                      if(isset($_POST['unfriend']))
                      {
                        unfriend($user['id'],$current_user['id']);
                      }
                  }
                  echo '</div>';
                echo '</td>';
            echo '</tr>';
          echo '</tbody>';
        echo '</table>';
        $stmt = $db->prepare("SELECT u.*, date, post FROM users u JOIN posts p ON u.email = p.email ORDER BY date DESC");
    $stmt->execute([$_SESSION['email']]);
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      if($row['id'] == $user['id']) {
      echo '<div class="card" style="width:100%; margin-bottom:-10px">';
          echo '<div class="card-header">';
          if(isAdmin($row['id']))
          {
              echo '<table cellpadding="5">';
              echo '<tbody>';
                echo '<tr>';     
                  echo '<td>';
                      echo '<a target="blank" href="personal.php?userid=' . $row['id'] . '" style="color:inherit; text-decoration:none"><img style="width: 90px; height: 80px" src="img/'. $row['avatar'] . '" /></a>';
                      echo '</td>';
                      echo '<td>';
                      echo '<h3 style="color: #084B8A"><a href="personal.php?userid=' . $row['id'] . '"  style="color:inherit; text-decoration:none">' . $row['fullname'] . '</a></h3>';
                      echo $row['company'];
                      echo '<br/><strong>Administrator</strong> ';
                      echo '<img src="icon/star.ico" style="border:0"/><img src="icon/star.ico" style="border:0"/><img src="icon/star.ico" style="border:0"/><img src="icon/star.ico" style="border:0"/><img src="icon/star.ico" style="border:0"/>';
                    echo '</td>';
                echo '</tr>';
              echo '</tbody>';
            echo '</table>';
          }
          else
          {
              echo '<table cellpadding="5">';
              echo '<tbody>';
                echo '<tr>';     
                  echo '<td>';
                    if($row['email'] != $_SESSION['email'])
                    {
                      echo '<a target="blank" href="personal.php?userid=' . $row['id'] . '" style="color:inherit; text-decoration:none"><img style="width: 90px; height: 80px" src="img/'. $row['avatar'] . '" /></a>';
                      echo '</td>';
                      echo '<td>';
                      echo '<h3 style="color: #084B8A"><a target="blank" href="personal.php?userid=' . $row['id'] . '" style="color:inherit; text-decoration:none">' . $row['fullname'] . '</a></h3>';
                    }
                    else
                    {
                      echo '<a href="index.php" style="color:inherit; text-decoration:none"><img style="width: 90px; height: 80px" src="img/'. $row['avatar'] . '" /></a>';
                      echo '</td>';
                      echo '<td>';
                      echo '<h3 style="color: #084B8A"><a href="index.php" style="color:inherit; text-decoration:none">' . $row['fullname'] . '</a></h3>';
                    }
                  echo $row['company'];
                  echo '</td>';
                echo '</tr>';
              echo '</tbody>';
            echo '</table>';
          }
          echo '</div>';
            echo '<div class="card-body"><p class="card-title">Posted at '. $row['date'] . '</p>';
              echo '<h5><p class="card-text">' . $row['post'] . '</p></h5>';
            echo '</div>';
     echo '</div>';
   }
 }
?>
</div>
<div class="footer" style="margin-top: 10%">
<?php include 'footer.php'; ?>
</div>
</body>
</html>
