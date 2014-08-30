<?php
  if($REMOTE_ADDR!="204.210.198.149" and substr($REMOTE_ADDR,0,6)!="24.203" and substr($REMOTE_ADDR,0,6)!="64.228") die("No.");
  require 'lib/function.php';
  require 'lib/layout.php';
  if($_POST[id] and ($loguserid==1 or $loguserid==2)){
    $user=mysql_query("SELECT name,posts,sex,powerlevel FROM users WHERE id=$id");
    $user=mysql_fetch_array($user);
    if($user[posts]==$p or $user[posts]==0){
	$name=$user[name];
	$namecolor=getnamecolor($user[sex],$user[powerlevel]);
	$line="<br><br>===================<br>[Posted by <font $namecolor><b>$name</b></font>]<br>";
	$ups=mysql_query("SELECT id FROM posts WHERE user=$id");
	while($up=mysql_fetch_array($ups)) mysql_query("UPDATE posts_text SET signtext=CONCAT_WS('','$line',signtext) WHERE pid=$up[id]") or print mysql_error();
	mysql_query("UPDATE threads SET user=4 WHERE user=$id");
	mysql_query("UPDATE threads SET lastposter=4 WHERE lastposter=$id");
	mysql_query("UPDATE privatemsg SET userfrom=4 WHERE userfrom=$id");
	mysql_query("UPDATE privatemsg SET userto=4 WHERE userto=$id");
	mysql_query("UPDATE posts SET user=4,headid=0,signid=0 WHERE user=$id");
	mysql_query("UPDATE users SET posts=posts WHERE user=4");
	mysql_query("DELETE FROM userratings WHERE userrated=$id OR userfrom=$id");
	mysql_query("DELETE FROM users WHERE id=$id");
	mysql_query("DELETE FROM users_rpg WHERE uid=$id");
    }
  }
  print "
    $header<br>
    <form action=del.php method=post>$tblstart
    <td class='tbl tdbg1 font center' width=50%>User id to delete: <input type=text name=id size=4 maxlength=4></td>
    <td class='tbl tdbg2 font' width=1><input type=submit value=Submit></td>
    <td class='tbl tdbg1 font center' width=50%>Select users with <input type=text name=p size=4 maxlength=9 value=$p> posts</td>
    $tblend</form>
  ";
  if(!$p) $p=0;
  $users=mysql_query("SELECT id,name,regdate,lastposttime,lastactivity,lasturl,lastip,sex,powerlevel FROM users WHERE posts=$p ORDER BY lastactivity DESC");
  print "
    $tblstart
	<tr>
	$tccellh>id</td>
	$tccellh>Name</td>
	$tccellh>Regdate</td>
	$tccellh>Last post</td>
	$tccellh>Last activity</td>
	$tccellh>Last URL</td>
	$tccellh>IP
  ";
  while($user=mysql_fetch_array($users)){
    $namecolor=getnamecolor($user[sex],$user[powerlevel]);
    $lastpost='-';
    if($user[lastposttime]) $lastpost=date($dateformat,$user[lastposttime]);
    print "
      <tr align=center>
      $tccell1>$user[id]
      $tccell2><b><a href=profile.php?id=$user[id]><font $namecolor>$user[name]
	$tccell1 width=120>".date($dateformat,$user[regdate])."
	$tccell1 width=120>$lastpost
	$tccell1 width=120>".date($dateformat,$user[lastactivity])."
      $tccell2>$user[lasturl]&nbsp;
      $tccell2>$user[lastip]
    ";
  }
  print $tblend.$footer;
  printtimedif($startingtime);
?>