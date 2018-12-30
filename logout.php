
			<?php
			session_start();
			if(!isset($_SESSION['email']))
				header('location: index.php');
			if(isset($_SESSION['email']))
				unset($_SESSION['email']);
			header('location:index.php?message=<div class="alert alert-warning">Account has been logged out !</div>');
			?>