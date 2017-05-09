<?php
require_once('dbconfig.php');

class USER
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function register($uname,$umail,$upass)
	{
		
		if($uname=="")	{
		echo "provide username !";	
	}
	else if($umail=="")	{
		echo "provide email id !";	
	}
	else if(!filter_var($umail, FILTER_VALIDATE_EMAIL))	{
	    echo 'Please enter a valid email address !';
	}
	else if($upass=="")	{
		echo "provide password !";
	}
	else if(strlen($upass) < 6){
		echo "Password must be atleast 6 characters";	
	}
	else{
		try
		{
			
			$stmt1 = $this->conn->prepare("SELECT user_name, user_email FROM users WHERE user_name=:uname OR user_email=:umail");
			$stmt1->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$row1=$stmt1->fetch(PDO::FETCH_ASSOC);
				
			if($row1['user_name']==$uname) {
				echo "sorry username already taken !";
				$error[] = "sorry username already taken !";
			}
			else if($row1['user_email']==$umail) {
				echo "sorry email id already taken !";
				$error[] = "sorry email id already taken !";
			} else{
			//die();
			$new_password = password_hash($upass, PASSWORD_DEFAULT);
			
			$stmt = $this->conn->prepare("INSERT INTO users(user_name,user_email,user_pass) 
		                                               VALUES(:uname, :umail, :upass)");
												  
			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":umail", $umail);
			$stmt->bindparam(":upass", $new_password);										  
				
			$stmt->execute();	
			
			return $stmt;
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}		
	}
	
	
	public function doLogin($uname,$umail,$upass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT user_id, user_name, user_email, user_pass FROM users WHERE user_name=:uname OR user_email=:umail ");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			//echo $stmt->rowCount();
			//die();
			if($stmt->rowCount() == 1)
			{
				if(password_verify($upass, $userRow['user_pass']))
				{
					$_SESSION['user_session'] = $userRow['user_id'];
					return true;
				}
				else
				{
					return false;
				}
			}
			else{return false;}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function doLogout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}
	public function __sleep()
{
     return array();
}

public function __wakeup()
{
    $this->conn = getInstanceOf('conn');
}
}
?>