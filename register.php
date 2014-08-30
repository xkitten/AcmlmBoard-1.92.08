<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  $ipstart=substr($userip,0,6);
  print $header;
  if(!$_POST[action]){
    $descbr="</b>$smallfont<br></center>&nbsp";
    $sexlist="
	$radio=sex value=0> Male&nbsp &nbsp
	$radio=sex value=1> Female&nbsp &nbsp
	$radio=sex value=2 checked> N/A";
    $vsig="
	$radio=viewsig value=0> Disabled&nbsp &nbsp
	$radio=viewsig value=1 checked> Enabled";
    $vtool="
	$radio=posttool value=0> Disabled&nbsp &nbsp
	$radio=posttool value=1 checked> Enabled";
    $schemes=mysql_query('SELECT id,name FROM schemes ORDER BY ord');
    $tlayouts=mysql_query('SELECT id,name FROM tlayouts ORDER BY ord');
    while($sch=mysql_fetch_array($schemes)) $schlist.="<option value=$sch[id]>$sch[name]";
    while($lay=mysql_fetch_array($tlayouts)) $laylist.="<option value=$lay[id]>$lay[name]";
    $laylist="<select name=tlayout>$laylist</select>";
    $schlist="<select name=sscheme>$schlist</select>";
    print "
	<body onload=window.document.REPLIER.username.focus()>
	<FORM ACTION=register.php NAME=REPLIER METHOD=POST>
	$tblstart

	$tccellh>Login information</td>$tccellh>&nbsp<tr>
	$tccell1><b>User name:</b>$descbr The name you want to use on the board.</td>
	$tccell2l>$inpt=username SIZE=25 MAXLENGTH=25><tr>
	$tccell1><b>Password:</b>$descbr Enter any password up to 32 characters in length. It can later be changed by editing your profile.</td>
	$tccell2l>$inpp=password SIZE=13 MAXLENGTH=32><tr>

	$tccellh>Appearance</td>$tccellh>&nbsp<tr>
	$tccell1><b>User picture:$descbr The full URL of the image showing up below your username in posts. Leave it blank if you don't want to use a picture. The picture is resized to 60 in width. You can also select from a <a href=userpic.php>limited set</a> if you want to.</td>
	$tccell2l>$inpt=picture SIZE=60 MAXLENGTH=100><tr>
	$tccell1><b>Minipic:$descbr The full URL of a small picture showing up next to your username on some pages. Leave it blank if you don't want to use a picture. The picture is resized to 11x11.</td>
	$tccell2l>$inpt=minipic SIZE=60 MAXLENGTH=100><tr>
	$tccell1><b>Post header:$descbr This will get added before the start of each post you make. This can be used to give a default font color and face to your posts (by putting a <<z>font> tag). This should preferably be kept small, and not contain too much text or images.</td>
	$tccell2l>$txta=postheader ROWS=5 COLS=60></TEXTAREA><tr>
	$tccell1><b>Signature:$descbr This will get added at the end of each post you make, below an horizontal line. This should preferably be kept to a small enough size.</td>
	$tccell2l>$txta=signature ROWS=5 COLS=60></TEXTAREA><tr>

	$tccellh>Personal information</td>$tccellh>&nbsp<tr>
	$tccell1><b>Sex:$descbr Male or female. (or N/A if you don't want to tell it)</td>
	$tccell2l>$sexlist<tr>
	$tccell1><b>Real name:$descbr Your real name (you can leave this blank).</td>
	$tccell2l>$inpt=realname SIZE=40 MAXLENGTH=60><tr>
	$tccell1><b>Location:$descbr Where you live (city, country, etc.).</td>
	$tccell2l>$inpt=location SIZE=40 MAXLENGTH=60><tr>
	$tccell1><b>Birthday:$descbr Your date of birth.</td>
	$tccell2l>Month: $inpt=bmonth SIZE=2 MAXLENGTH=2> Day: $inpt=bday SIZE=2 MAXLENGTH=2> Year: $inpt=byear SIZE=4 MAXLENGTH=4><tr>
	$tccell1><b>Bio:$descbr Some information about yourself, showing up in your profile.</td>
	$tccell2l>$txta=bio ROWS=5 COLS=60></TEXTAREA><tr>

	$tccellh>Online services</td>$tccellh>&nbsp<tr>
	$tccell1><b>Email address:$descbr This is only shown in your profile; you don't have to enter it if you don't want to.</td>
	$tccell2l>$inpt=email SIZE=60 MAXLENGTH=60><tr>
	$tccell1><b>AIM screen name:$descbr Your AIM screen name, if you have one.</td>
	$tccell2l>$inpt=aim SIZE=30 MAXLENGTH=30><tr>
	$tccell1><b>ICQ number:$descbr Your ICQ number, if you have one.</td>
	$tccell2l>$inpt=icq SIZE=10 MAXLENGTH=10><tr>
	$tccell1><b>Homepage URL:$descbr Your homepage URL (must start with the \"http://\"), if you have one.</td>
	$tccell2l>$inpt=homepage SIZE=60 MAXLENGTH=80><tr>
	$tccell1><b>Homepage name:$descbr Your homepage name, if you have a homepage.</td>
	$tccell2l>$inpt=pagename SIZE=60 MAXLENGTH=100><tr>

	$tccellh>Options</td>$tccellh>&nbsp<tr>
	$tccell1><b>Timezone offset:$descbr How many hours you're offset from the time on the board (".date("m-d-y h:i A",ctime()).").</td>
	$tccell2l>$inpt=timezone SIZE=5 MAXLENGTH=5 VALUE=0><tr>
	$tccell1><b>Posts per page:$descbr The maximum number of posts you want to be shown in a page in threads.</td>
	$tccell2l>$inpt=postsperpage SIZE=4 MAXLENGTH=4 VALUE=20><tr>
	$tccell1><b>Threads per page:$descbr The maximum number of threads you want to be shown in a page in forums.</td>
	$tccell2l>$inpt=threadsperpage SIZE=4 MAXLENGTH=4 VALUE=50><tr>
	$tccell1><b>Use textbox toolbar when posting:$descbr You can disable it here, preventing potential slowdowns or other minor problems when posting.</td>
	$tccell2l>$vtool<tr>
	$tccell1><b>View signatures and post headers:$descbr You can disable them here, which can make thread pages smaller and load faster.</td>
	$tccell2l>$vsig<tr>
	$tccell1><b>Thread layout:$descbr You can choose from a few thread layouts here.</td>
	$tccell2l>$laylist<tr>
	$tccell1><b>Color scheme / layout:$descbr You can select from a few color schemes here.</td>
	$tccell2l>$schlist<tr>

	$tccellh>&nbsp</td>$tccellh>&nbsp<tr>
	$tccell1>&nbsp</td>$tccell2l>
	$inph=action VALUE=register>
	$inps=submit VALUE=\"Register account\"></td></FORM>
	</table>
    ";
  }
  if($_POST[action]=='register'){
	$users = mysql_query('SELECT name FROM users');
	$username = substr($username,0,25);
	$username2 = str_replace(' ','',$username);
	$username2 = str_replace(' ','',$username2);
	$username2 = preg_replace("'&nbsp;'si",'&nbsp',$username2);
	$username2 = preg_replace("'&nbsp'si",'',$username2);
	$username2 = stripslashes($username2);
    print $tblstart;
    $userid=-1;
    while ($user=mysql_fetch_array($users)) {
	$user[name]=str_replace(' ','',$user[name]);
	$user[name]=str_replace(' ','',$user[name]);
	if (strcasecmp($user[name],$username2)==0) $userid=$u;
    }
	$nomultis = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `lastip` = '$REMOTE_ADDR'"),0);
    if ($userid==-1 and $password and $username && $nomultis == 0) {
	$signature=str_replace($br,"<br>",$signature);
	$bio=str_replace($br,"<br>",$bio);
	$postheader=str_replace($br,"<br>",$postheader);
	$username=str_replace("<","&lt",$username);
	$birthday=mktime(0,0,0,$bmonth,$bday,$byear);
	if($birthday==-1) $birthday=0;
	$userlevel=0;
	if(!mysql_num_rows($users)) $userlevel=3;
	if($icq<1) $icq=0;
	$currenttime=ctime();
	$ipaddr=getenv("REMOTE_ADDR");
	$dagagdsf = mysql_query("INSERT INTO `users` (`posts`, `regdate`, `name`, `password`, `picture`, `signature`, `bio`, `powerlevel`, `title`, `lastactivity`, `email`, `icq`, `aim`, `sex`, `homepageurl`, `timezone`, `postsperpage`, `realname`, `location`, `lastip`, `lastposttime`, `postheader`, `useranks`, `birthday`, `scheme`, `minipic`, `homepagename`, `threadsperpage`, `posttool`, `viewsig`, `layout`) VALUES ('0', '$currenttime', '$username', '".md5($password)."', '$picture', '$signature', '$bio', '$userlevel', '', '$currenttime', '$email', '$icq', '$aim', '$sex', '$homepage', '$timezone', '$postsperpage', '$realname', '$location', '$ipaddr', '0', '$postheader', '1', '$birthday', '$sscheme', '$minipic', '$pagename', '$threadsperpage', '$posttool', '$viewsig', '$tlayout')") or print mysql_error();
	mysql_query("INSERT INTO `users_rpg` (`uid`) VALUES ('". mysql_insert_id() ."')") or print mysql_error();
	print "$tccell1>Thank you, $username, for registering your account.<br>".redirect('faq.php','the FAQ',0);
    }else{
	
	if ($userid != -1) {
		$reason = "That username is already in use.";
	} elseif ($nomultis) {
		$who = mysql_fetch_array(mysql_query("SELECT id, lastip FROM `users` WHERE `lastip` = '$ipaddr'"));
		$reason = "You have already registered! (<a href=profile.php?id=$who[id]>here</a>)";
	} else {
		$reason = "You haven't entered a username or password.";
	}
	
	print "
	 $tccell1>Couldn't register the account. $reason
	 <br>".redirect("index.php","the board",0);
    }
    print $tblend;
  }
  print $footer;
  printtimedif($startingtime);
?>