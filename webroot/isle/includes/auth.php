<?php
  session_start();
  require_once 'includes/error.php';
  require_once 'includes/DB.php';
  
  $DB = new DB();
  $csrfToken = base64_encode(hash("sha256", session_id()));
  
  spl_autoload_register(function($class) {
    $class = str_replace('ISLE\\', '', $class);
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
  });

  $svc = new ISLE\Service();
  if (isset($_GET["logout"]))
  {
    unset($_SESSION['user']);
	unset($_SESSION['username']);
	$_SESSION['valid'] = false;
  }
  if (!isset($_SESSION["user"]))
  {
    //Store as power/User object in session.
    try
    {
		$msg = '';
            
		if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
			$test_username = $DB->clean($_POST['username']);
			$hashAndSalt = $DB->getVal('SELECT password from myinstance_users where name = "'.$test_username.'"');
			$passwordPost = $_POST['password'];
//			if ($_POST['password'] == $DB->getVal('select password from myinstance_users where name = "'.$test_username.'";')) {
			if (password_verify($passwordPost, $hashAndSalt)) {
			  $_SESSION['valid'] = true;
			  $_SESSION['timeout'] = time();
			  $_SESSION['username'] = $test_username;
			  $_SESSION['user'] = $DB->getVal('select uid from myinstance_users where name = "'.$test_username.'";');
			  
			  echo 'You have entered valid use name and password';
			  header('Location: .\locations?m='.json_encode('Successful log in')); // Brute force to Locations instead of Assets. May want better way to do this
		   }
		   else {
			  $msg = 'Wrong username or password';
		   }
		}
	//	$test_username = $_GET["test"];
		// config-todo: replace 111111111 with whatever ID the auth mechanism you use returns when successful.
		
    } catch (Exception $e)
    {
      echo $e->getMessage();
    }
  }
  if (!isset($_SESSION["user"]) && isset($_POST['register'])){
		$post = $DB->clean($_POST);

		$userInfo = $DB->getRow("SELECT * from myinstance_users where name = '$post[username]'");
		$maxuid = $DB->getVal("SELECT MAX(uid) as uid from myinstance_users");
		$maxuidint = (int)$maxuid + 1;
// John Hudson's super cool registration code~~~~~~~
		if(!(intval($userInfo['uid']) > 0)){
			$password = password_hash($post['password'],PASSWORD_BCRYPT);
//			$passwordReset = password_hash('reset'.time(),PASSWORD_BCRYPT);
			$insertQuery = "INSERT INTO myinstance_users(uid, name, email, password, role) 
								VALUES ('$maxuidint','$post[username]','$post[username]@extron.com', '$password', '4');";
			$result = mysqli_query($DB->getLink(),$insertQuery);
			if($result){
			header('Location: .\assets?reg=y&m='.json_encode('Succesfully registered, please contact Joel to gain access.'));
			}
			else {
			header('Location: .\assets?login=n&m='.json_encode('Error, please try to register again'));
			}
		}
		else {
			header('Location: .\assets?login=n&m='.json_encode('Username is Already Taken'));
			$msg = 'Username is Already Taken';
		}
  }
  $user = $_SESSION["user"];
  $userClass = new ISLE\Models\User();
  $filter['cols'][0]['col'] = 'uid';
  $filter['cols'][0]['val'] = $user;
  //get user from db.
  $u = $svc->getAll($userClass, null, null, null, null, $filter);

  if(count($u) == 0 || $u[0]['role'] == ISLE\Models\Role::DISABLED) {
    echo 'User <i>'.$user.'</i> is not authorized.';
    exit;
  }
  else {
	$u = $u[0];
  }

?>
