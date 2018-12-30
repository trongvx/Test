<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require 'vendor/autoload.php';
    include 'connect.php';
    session_start();

    $email = $_POST['email'];
    $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute(array($email));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $link = null;
    if ($user)
    {
        $_SESSION['verify'] = generateString();
        $_SESSION['email_reset'] = $email;
        $link = 'tuevo.daonguyenvu.com/BTCN09/resetpage.php';
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'ltweb1.cd2018@gmail.com';                 // SMTP username
            $mail->Password = 'abc123XYZ~';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('ltweb1.cd2018@gmail.com', 'Tuebook');
            $mail->addAddress($email, $email);     // Add a recipient

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Reset password instructions';
            $mail->Body    = 'Hello <strong>'. $email .'</strong>' . '
            <br/>Someone has requested a link to change your password. You can do this through the link below.' . '
            <br/><a href="'. $link .'" target="blank">Change my password</a>' . "
            <br/>Your verify code is <b>" . $_SESSION['verify'] . "</b>
            <br/>If you didn't request this, please ignore this email." . "
            <br/>Your password won't change until you access the link above and create a new one.";

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
    
    header('Location:reset-password.php?message=<div class="alert alert-success">Please check your email to change a new password !</div>');
?>
<?php
    function generateString()
    {
        $arr = "a%^bcjopNOPQRSqrstuGHIJKM$#!@#!##@@@@vwyz0123&*()456defghi789ABCDEFklmnTUVWYZ!@#$";
        $str = null;    
        $len = strlen($arr);
        for($i = 0; $i < 9; $i++)
        {
            $rd = rand(0,$len);
            $str .= substr($arr, $rd, 1);
        }
        return $str;
    }
?>