<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  $post=mysql_fetch_array(mysql_query("SELECT * FROM posts,posts_text WHERE id=$id AND id=pid"));
  $threadid=$post[thread];
  $threads=mysql_query("SELECT forum,closed,title FROM threads WHERE id=$threadid");
  $thread=mysql_fetch_array($threads);
  $thread[title]=str_replace('<','&lt;',$thread[title]);
  $smilies=readsmilies();
  print $header;
  $forum=mysql_fetch_array(mysql_query("SELECT * FROM forums WHERE id=$thread[forum]"));
  $mods=mysql_query("SELECT user FROM forummods WHERE forum=$forum[id] AND user=$loguserid");
  if(@mysql_num_rows($mods) and $logpwenc) $ismod=1;
  print "$fonttag<a href=index.php>$boardname</a> - <a href=forum.php?id=$forum[id]>".$forum[title]."</a> - $thread[title]
	$tblstart
	 <FORM ACTION=editpost.php NAME=REPLIER METHOD=POST>";
  if(!$action and $log and ($ismod or $loguserid==$post[user]) and (!$forum[minpower] or $power>=$forum[minpower])){
  if($logpwenc and $loguser[password]==$logpwenc){
    $username=$loguser[name];
    $password=$logpassword;
  }
  $message=$post[text];
  if(!$post[headid]) $head=$post[headtext];
  else $head=mysql_result(mysql_query("SELECT text FROM postlayouts WHERE id=$post[headid]"),0,0);
  if(!$post[signid]) $sign=$post[signtext];
  else $sign=mysql_result(mysql_query("SELECT text FROM postlayouts WHERE id=$post[signid]"),0,0);
  sbr(1,$message);
  sbr(1,$head);
  sbr(1,$sign);

  $users=mysql_query("SELECT name FROM users WHERE id=$post[user]");
  $user=mysql_fetch_array($users);
  print "
	 $tccellh width=150>&nbsp</td>$tccellh>&nbsp<tr>
	 $tccell1><b>Header:</td>	 $tccell2l>$txta=head ROWS=8 COLS=$numcols>$head</TEXTAREA><tr>
	 $tccell1><b>Post:</td>		 $tccell2l>$txta=message ROWS=12 COLS=$numcols>$message</TEXTAREA><tr>
	 $tccell1><b>Signature:</td>	 $tccell2l>$txta=sign ROWS=8 COLS=$numcols>$sign</TEXTAREA><tr>
	 $inph=edited VALUE=\"$post[edited]\">
	 $tccell1>&nbsp</td>$tccell2l>
	 $inph=action VALUE=editpost>
	 $inph=id VALUE=$id>
	 $inps=submit VALUE=\"Edit post\">
	 $inps=preview VALUE=\"Preview post\"></td></FORM>
	 $tblend$fonttag<a href=index.php>$boardname</a> - <a href=forum.php?id=$forum[id]>".$forum[title]."</a> - $thread[title]
  ";
  }
  if($_POST[action]=='editpost'){
    print $tblstart;
    if($ismod or $loguserid==$post[user]){
	$user=mysql_fetch_array(mysql_query("SELECT posts,regdate FROM users WHERE id=$loguserid"));
	$numposts=$user[posts];
	$numdays=(ctime()-$user[regdate])/86400;
	$message=doreplace($message,$numposts,$numdays,$loguser[name]);
	if($submit){
	  $edited.="(edited by $loguser[name] on ".date("m-d-y h:i A",ctime()).")<br>";
	  $headid=@mysql_result(mysql_query("SELECT `id` FROM `postlayouts` WHERE `text` = '$head' LIMIT 1"),0,0);
	  $signid=@mysql_result(mysql_query("SELECT `id` FROM `postlayouts` WHERE `text` = '$sign' LIMIT 1"),0,0);
	  if($headid) $head=''; else $headid=0;
	  if($signid) $sign=''; else $signid=0;
	  mysql_query("UPDATE `posts_text` SET `headtext` = '$head', `text` = '$message', `signtext` = '$sign', `edited` = '$edited' WHERE `pid` = '$id'");
	  mysql_query("UPDATE `posts` SET `headid` = '$headid', `signid` = '$signid' WHERE `id` = '$id'");
	  $ppp=($log?$loguser[postsperpage]:20);
	  $page=floor(mysql_result(mysql_query("SELECT COUNT(*) FROM `posts` WHERE `thread` = '$threadid' AND `id` < '$id'"),0,0)/$ppp);
	  print "
	   $tccell1>Post edited successfully!.<br>
	   ".redirect("thread.php?id=$threadid&page=$page#$id",'return to the thread',0).'</table></table>';
	}else{
	  loadtlayout();
	  $ppost=mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id=$post[user]"));
	  $head = stripslashes($head);
	  $sign = stripslashes($sign);
	  $message = stripslashes($message);
	  $ppost[uid]=$post[user];
	  $ppost[num]=$post[num];
	  $ppost[date]=$post[date];
	  $ppost[tagval]=$post[tagval];
	  $ppost[headtext]=$head;
	  $ppost[signtext]=$sign;
	  $ppost[text]=$message;
	  if($isadmin) $ip=$post[ip];
	  print "
		<body onload=window.document.REPLIER.message.focus()>
		$tccellh>Post preview
		$tblend$tblstart
		".threadpost($ppost,1)."
		$tblend<br>$tblstart
		$tccellh width=150>&nbsp</td>$tccellh>&nbsp<tr>
		$tccell1><b>Header:</td>	 $tccell2l>$txta=head ROWS=4 COLS=$numcols>$head</TEXTAREA><tr>
		$tccell1><b>Post:</td>		 $tccell2l>$txta=message ROWS=6 COLS=$numcols>$message</TEXTAREA><tr>
		$tccell1><b>Signature:</td>	 $tccell2l>$txta=sign ROWS=4 COLS=$numcols>$sign</TEXTAREA><tr>
		$tccell1>&nbsp</td>$tccell2l>
		$inps=submit VALUE=\"Edit post\">
		$inps=preview VALUE=\"Preview post\">
		$inph=action VALUE=editpost>
		$inph=id VALUE=$id>
		</td></FORM>
	  ";
      }
    }else
      print "
	  $tccell1>Couldn't edit the post. Either you didn't enter the correct username or password,
	  or you are not allowed to edit this post.<br>
	  ".redirect("thread.php?id=$threadid","return to the thread",0);
    print $tblend;
  }
  if($action=='delete'){
    if($ismod or $loguserid==$post[user]){
	mysql_query("DELETE FROM posts WHERE id=$id");
	mysql_query("DELETE FROM posts_text WHERE pid=$id");
	$p=mysql_fetch_array(mysql_query("SELECT id,user,date FROM posts WHERE thread=$threadid ORDER BY date DESC"));
	mysql_query("UPDATE threads SET replies=replies-1, lastposter=$p[user], lastpostdate=$p[date] WHERE id=$threadid");
	mysql_query("UPDATE forums SET numposts=numposts-1 WHERE id=$forum[id]");
	$txt="Thank you, $loguser[name], for deleting the post.";
    }else $txt="Couldn't delete the post. You are not allowed to delete this post.";
    print "$tblstart$tccell1>$txt<br>".redirect("thread.php?id=$threadid","return to the thread",0)."$tblend";
  }
  print $footer;
  printtimedif($startingtime);
?>