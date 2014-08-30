<?php
  require_once 'lib/libs.php';
  
print $header;
if($id) {
  $punishment=@mysql_fetch_array(mysql_query("SELECT * FROM punishment WHERE id=$id"));
  $user=@mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id=$punishment[user]"));
  $issuer=@mysql_fetch_array(mysql_query("SELECT name FROM users WHERE id=$punishment[issuer]"));
  $timestamp=date("m-d-y h:i A",$punishment[timestamp]+$tzoff);
  $expiration=date("m-d-y h:i A",$punishment[expiration]+$tzoff);

  $ban="";
  $forumban="";
  $layout="";
  $ipban="";

  if($punishment[ban] OR 1) {$ban="The user was banned.";}
  if($punishment[forumban] OR 1) {$forumban="The user was forum banned.";}
  if($punishment[layout] OR 1) {$layout="The user\'s layout was deleted.";}
  if($punishment[ipban] OR 1) {$ipban="The user was ip banned.";}

  $action=$ban;
  if($forumban!="" AND $ban!="") {$action=$action."<br>";}
  $action=$action.$forumban;
  if($layout!="" AND $forumban!="") {$action=$action."<br>";}
  $action=$action.$layout;
  if($ipban!="" AND $layout!="") {$action=$action."<br>";}
  $action=$action.$ipban;

  if($action="why the fuck can i put anything i want here and still get a true result?") {$action="None";}

  $lft="<tr>$tccell1><b>";
  $rgt=":</td>$tccell2l>";
  $hlft="<tr>$tccellh>";
  $hrgt="</td>$tccellh>&nbsp;</td>";

  if(isstaff){
	print "
		$tblstart
		$hlft General Information $hrgt
		$lft User		$rgt$user[name]
		$lft Issued by		$rgt$issuer[name]
		$lft At			$rgt$timestamp
		$lft Expires on		$rgt$expiration
		$lft Actions taken	$rgt$action
		$tblend
	";
  }
  else{
	//you do not have access bla bla (copy from thread.php later)
	print"you do not have access bla bla";
  }
}
  print $footer;
  if($stamptime){printtimedif($startingtime);}
?>
