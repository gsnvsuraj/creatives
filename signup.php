<html>
	<head>
		<title>Sign Up</title>

		<link rel="stylesheet" type="text/css" href="styles/signup.css">
	</head>

	<body>
		
		<div>
		    <center>
		        <h1>Welcome to Uplabs</h1>
		    </center>
		</div>
		<?php
			use PHPMailer\PHPMailer\PHPMailer;
			use PHPMailer\PHPMailer\Exception;

			if(isset($_POST['submit']))
			{
				$servername = "localhost";
				$username = "root";
				$password = "";
				$dbname = "uplabs";

				// Create connection
                session_start();
				$conn = new mysqli($servername, $username, $password, $dbname);

				// Check connection
				if ($conn->connect_error) {
				    die("Connection failed: " . $conn->connect_error);
				}
				//echo "Connected successfully";

				$flag=0;

				$user = $_POST["user"];
				$pass = $_POST["pass"];
				$email=$_POST["email"];
				$copass=$_POST["conpass"];
				if($pass != $copass)
				{
                    echo "\n<center><h3>Both Passwords are not same!!</h3></center>";
                    $flag=1;
				}
				
				function valid_email($str)
				{
						return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
						$flag=1;
				}

				if(!valid_email($email))
				{
					echo "\n<center><h3>Invalid E-Mail Address!!</h3></center>";
					$flag=1;
				}
				else{

   					$q1="SELECT * FROM ulogin WHERE uname= '".$user."' ;";
   					$q2="SELECT * FROM ulogin WHERE email='".$email."';";
   					
   					$r1=$conn->query($q1);
   					$r2=$conn->query($q2);
   					
   					if($r1->num_rows!=0 )
   					{
                		echo "\n<center><h3>Username already exists. Use another Username.</h3></center>"; 
                		$flag=1;
                	}
   				    
   				    if($r2->num_rows!=0)
   				    {	
   				    	echo "\n<center><h3>E-Mail ID already exists. Use another E_Mail ID.</h3></center>";
   				      	$flag=1;
   				  	}

   				  	if($flag==0)
   				  	{
   				  		do{
    						$uni_id = rand(10,99);

						    $first = $uni_id;
						    $chars_to_do = 6 - strlen($uni_id);
						    for ($i = 1; $i <= $chars_to_do; $i++){ 
						        $first .= chr(rand(48,57)); 
						    }

						    $uid = $first;

						    $sql = "SELECT * FROM ulogin WHERE uid='".$uid."';";
						    $result = $conn->query($sql);

						}while($result->num_rows > 0);

   				  		$q3="INSERT INTO ulogin VALUES('".$email."','".$pass."','".$uid."','".$user."','no')";
   				  		
   				  		require 'PHPMailer/src/Exception.php';
						require 'PHPMailer/src/PHPMailer.php';
						require 'PHPMailer/src/SMTP.php';

						// Instantiation and passing `true` enables exceptions
						$mail = new PHPMailer(true); 

						try {
							$senderMail = "";			//add the sender mail
							$senderPass = "";			//add the sender password

							//$mail->SMTPDebug = 4;
						    //Server settings
						    $mail->isSMTP();                                            // Set mailer to use SMTP
						    $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
						    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
						    $mail->Username   = $senderMail;                     // SMTP username
						    $mail->Password   = $senderPass;                               // SMTP password
						    //$mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
						    $mail->Port       = 587;                                    // TCP port to connect to

						    //Recipients
						    $mail->setFrom($senderMail, 'Uplabs');
						    $mail->addAddress($email);     // Add a recipient
						    $mail->addReplyTo($senderMail);

						    // Content
						    $mail->isHTML(true);                                  // Set email format to HTML
						    $mail->Subject = 'Verify your Account';
						    $message = "This mail is regarding the account verification you created at the Uplabs.<br>Click the link below to verify your account.<br><br>Click <a href='localhost/uplabs/verify.php?uid=".$uid."'>here</a>.<br><br><br>If this request was not made by you click <a href='localhost/uplabs/unverify.php?uid=".$uid."'>here</a>.";
						    $mail->Body = wordwrap($message, 70);
						    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

						    $mail->send();
						    
						} catch (Exception $e) {
						    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
						}

						if($conn->query($q3))
   				  		{   
   				  			$_SESSION["user"] = $user;
   				  			header("location:login.php");
   				  			exit();
   				  		}

   				  	}	
				}

				
				$conn->close();
			}
		?>

		 
		<form class="log"  action="signup.php" method="POST">
			 <h2 class="login">SIGN UP</h2>
		<center> <table><tr>
			<td>Username :</td><td> <input type="text" name="user" placeholder="Enter Username" required></td></tr>
			<tr><td>Email : </td><td><input type="text" name="email" placeholder="Enter email" required></td></tr>
			<tr><td>Password :</td><td> <input type="password" name="pass" placeholder="Enter password" required></td></tr>
			<tr><td>Conform Password :</td><td> <input type="Password" name="conpass" placeholder="conform password" required></td></tr></table><br><br>
			<input type="submit" name="submit" value="SignUp" class="button">
        </center>
			<br><br>Already have an account? <a href="login.php">LogIn</a>
			
		</form>
          	
	</body>
</html>