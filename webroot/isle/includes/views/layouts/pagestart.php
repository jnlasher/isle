<!DOCTYPE html>
<html lang="en" data-user="<?php echo $u['uid']; ?>" data-role="<?php echo $u['role']; ?>">
<head>
<?php
/*Grab all the styles for this page and include them in link tags here. If production grab the minified versions*/
echo $tmpl_headcontent;
echo shell_exec('pwd');
?>
</head>
<?php flush();/*Allows the browser to start getting content while the server is still loading the rest of the page. http://developer.yahoo.com/performance/rules.html#page-nav*/ ?>
<body>
    <span class="hide" id="csrfToken"><?php echo $csrfToken ?></span>
    <div id="container">
    <div id="top">
      <div id="header">
        <span class="logout floatRight"><?php if ($_SESSION['valid'] == True){ ?> Welcome, <?php echo $u['name']; ?> &nbsp;&nbsp;<?php } ?><a id="logout" href="javascript:void(0);"><?php if ($_SESSION['valid'] == True){ ?>Sign Out<?php } else{ ?>Sign In<?php } ?></a><br /><a href="<?php echo $rootdir; ?>assets?item=user&value=<?php echo $u['uid']; ?>"><?php if ($_SESSION['valid'] == True){ ?>My Assets<?php } ?></a></span>
		  <!--tabindex on anchor tags is needed for submenus to function in safari and chrome.-->
        <h1>ISLE: Extron</h1><?php if ((!isset($hidenav) or !$hidenav) and ($_SESSION['valid'] == True)) { ?><div id="nav"><ul><li><a href="<?php echo $rootdir; 
?>assets">Assets</a></li><li><a href="<?php 
echo $rootdir; ?>manufacturers">Manufacturers</a></li><li><a href="<?php echo $rootdir; ?>locations">Locations</a></li><li><a href="<?php echo $rootdir; ?>categories">Categories</a></li><?php if($u['role'] >= ISLE\Models\Role::CONTRIBUTOR) { ?><li><a href="<?php echo $rootdir; ?>attributes">Attributes</a></li><li><a href="<?php echo $rootdir; ?>relations">Relations</a></li><?php }//endif ?><?php if($u['role'] >= ISLE\Models\Role::ADMIN) { ?><li><a href="<?php echo $rootdir; ?>users">Users</a></li><li><a href="<?php echo $rootdir; ?>logs">Logs</a></li><?php } ?></ul></div><?php }//endif ?><!--end nav--></div><!--end header-->
    </div><!--end top-->
    <div id="middle">
      <div id="body">
        <div id="bodyContent">
          <!-- Application messages. -->
          <?php if(!isset($dontShowMsg) && isset($_SESSION['message'])) { ?>
          <div id="userMessage" role="alert" aria-label="<?php echo htmlspecialchars($_SESSION['message']['text']); ?>" class="fade in alert<?php echo ' ' . $_SESSION['message']['type']; ?>"><a class="close" href="#" role="button" aria-label="Close" data-dismiss="alert">&times;</a><?php echo htmlspecialchars($_SESSION['message']['text']); ?></div>
          <?php unset($_SESSION['message']); } ?>

<?php if (!isset($_SESSION['valid']) && !isset($_GET["Register"])){ ?>
		<form class = "form-signin" role = "form" 
					action = "<?php echo $_SERVER['SELF']; ?>"  method = "post">
					<h4 class = "form-signin-heading"><?php echo $msg; ?></h4>
					<input type = "text" class = "form-control" 
					   name = "username" placeholder = "First Initial/Lastname" 
					   required autofocus></br>
					<input type = "password" class = "form-control"
					   name = "password" placeholder = "Password" required>
					<button class = "btn btn-lg btn-primary btn-block" type = "submit" 
					   name = "login">Login</button>
		</form>
		<form method="get" class = "form-signin" role = "form"
				action = "<?php echo $_SERVER['SELF']; ?>" >
				<button class = "btn btn-lg btn-primary btn-block" type = "submit" 
					   name = "Register" value="1" >Register</button>
		</form>
<?php	require_once $layoutsPath . 'pageend.php';
die();	} 
	elseif(!isset($_SESSION['valid']) && isset($_GET["Register"])) { ?>
		<form class = "form-signin" role = "form" 
				action = "<?php echo $_SERVER['SELF']; ?>"  method = "post">
				<h4 class = "form-signin-heading"><?php echo $msg; ?></h4>
				<input type = "text" class = "form-control" 
				   name = "username" placeholder = "First Initial/Lastname" 
				   required autofocus></br>
				<input type = "password" class = "form-control"
				   name = "password" placeholder = "New Password" required>
				<button class = "btn btn-lg btn-primary btn-block" type = "submit" 
				   name = "register" value="1">Register</button>
		</form>
<?php	require_once $layoutsPath . 'pageend.php';
die();	} ?>
