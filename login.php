<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  $txt="$header<br>$tblstart";
  if(!$action){
   $txt.="
	<body onload=window.document.REPLIER.username.focus()>
	<FORM ACTION=login.php NAME=REPLIER METHOD=POST>
	$tccellh width=150>&nbsp</td>$tccellh>&nbsp<tr>
	$tccell1><b>User name:</td>	$tccell2l>$inpt=username SIZE=25 MAXLENGTH=25><tr>
	$tccell1><b>Password:</td>	$tccell2l>$inpp=password SIZE=13 MAXLENGTH=32><tr>
	$tccell1>&nbsp</td>$tccell2l>
	$inph=action VALUE=login>
	$inps=submit VALUE=Login></td></FORM>
   ";
  }
  if($_POST[action]=='login'){
   $userid=checkuser($username,$password);
   if($userid!=-1){
     setcookie('loguserid',$userid,2147483647);
     setcookie('logpassword',shenc($password),2147483647);
     $msg="You are now logged in as $username.";
   }else $msg="Couldn't login. Either you didn't enter an existing username, or you haven't entered the right password for the username.";
   $txt.="$tccell1>$msg<br>".redirect('index.php','return to the board',0); 
  }
  if($_POST[action]=='logout'){
   setcookie('loguserid',0);
   setcookie('logpassword','');
   $txt.="$tccell1> You are now logged out.<br>".redirect('index.php','return to the board',0); 
 }
  print $txt.$tblend.$footer;
  printtimedif($startingtime);
?>