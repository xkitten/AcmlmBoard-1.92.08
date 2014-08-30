<?php
	require 'lib/function.php';
	require 'lib/layout.php';
	$threads=mysql_query("SELECT forum, closed, sticky,title,lastposter FROM threads WHERE id=$id");
	$thread=mysql_fetch_array($threads);
	$thread[title]=str_replace('<','&lt',$thread[title]);
	$smilies=readsmilies();
	if(!$ppp) $ppp=(!$log?20:$loguser[postsperpage]);
	$forumid=$thread[forum];
	$fonline=fonlineusers($forumid);
	$forums=mysql_query("SELECT title,minpowerreply FROM forums WHERE id=$forumid");
	$forum=mysql_fetch_array($forums);

	if(@mysql_num_rows(mysql_query("SELECT user FROM forummods WHERE forum=$forumid and user=$loguserid"))) $ismod=1;

	if ($ismod) { 
		if ($thread[sticky] == 1) $sticky = "checked";
		$modoptions = "	<tr>$tccell1><b>Moderator Options:</b></td>$tccell2l>
		$inpc=\"close\" id=\"close\" value=\"1\"><label for=\"close\">Close</label> - 
		$inpc=\"stick\" id=\"stick\" value=\"1\" $sticky><label for=\"stick\">Sticky</label>";
	}

	if ($forum[minpowerreply] > $power && $forum[minpowerreply] > 0) {
			$thread[title]='(restricted)';
		}

	print "$header
		$tblstart$tccell1s>$fonline$tblend
		$fonttag<a href=index.php>$boardname</a> - <a href=forum.php?id=$forumid>$forum[title]</a> - $thread[title] $tblstart";
	replytoolbar(1);

	if($log) activitycheck($loguserid);

	if(!$_POST[action] && !$thread[closed] && !($banned && $log) && ($power>=$forum[minpowerreply] or $forum[minpowerreply]<1) && $id>0) {
			print '<FORM ACTION=newreply.php NAME=REPLIER METHOD=POST>';
	
			if($log){
					$username=$loguser[name];
					$password=$logpassword;
				}
	
			if($postid){
					$posts=mysql_query("SELECT user,text,thread FROM posts,posts_text WHERE id=$postid AND id=pid");
					$post=mysql_fetch_array($posts);
					$post[text]=str_replace('<br>',$br,$post[text]);
					$u=$post[user];
					$users[$u]=loaduser($u,1);
					if($post[thread]==$id) $quotemsg="[quote=".$users[$u][name]."]$post[text][/quote]
";
				}

	$postlist="$tccellh width=150>User</td>$tccellh>Post<tr>";
	$threadpostcount=0;
	$ppp++;
	$posts=mysql_query("SELECT name,posts,sex,powerlevel,user,text,options,num FROM users u,posts p,posts_text WHERE thread=$id AND p.id=pid AND user=u.id ORDER BY p.id DESC LIMIT $ppp");

  while($post=mysql_fetch_array($posts)){
    $bg='tdbg1';
    $threadpostcount++;
    if($threadpostcount<$ppp){
      if(round($threadpostcount/2)==$threadpostcount/2) $bg='tdbg2';
      $postnum=($post[num]?"$post[num]/":'');
      $tcellbg="<td class='tbl $bg font' valign=top>";
      $namecolor=getnamecolor($post[sex],$post[powerlevel]);
      $postlist.="
	  $tcellbg<a href=profile.php?id=$post[user]><font $namecolor>$post[name]</font></a>$smallfont<br>
	  Posts: $postnum$post[posts]</td>
	  $tcellbg".doreplace2($post[text], $post[options])."<tr>
      ";
    }else{
      $tcellbg="<td bgcolor=$tablebg1 valign=top colspan=2";
      $postlist.="<td colspan=2 class='tbl $bg font'>This is a long thread. Click <a href=thread.php?id=$id>here</a> to view it.</td>";
    }
  }
  print "
	 <body onload=window.document.REPLIER.message.focus()>
	  $tccellh width=150>&nbsp</td>$tccellh>&nbsp<tr>
	  $tccell1><b>User name:</td>	$tccell2l>$inpt=username VALUE=\"".htmlspecialchars($username)."\" SIZE=25 MAXLENGTH=25><tr>
	  $tccell1><b>Password:</td>	$tccell2l>$inpp=password VALUE=\"".htmlspecialchars($password)."\" SIZE=13 MAXLENGTH=32><tr>
	  $tccell1><b>Reply:</td>
	  $tccell2l>".replytoolbar(2)."
	  $txta=message ROWS=20 COLS=$numcols ".replytoolbar(3).">$quotemsg</TEXTAREA><tr>
	  $tccell1>&nbsp</td>$tccell2l>
	  $inph=action VALUE=postreply>
	  $inph=id VALUE=$id>
	  $inps=submit VALUE=\"Submit reply\">
	  $inps=preview VALUE=\"Preview reply\"></td>
	<tr>$tccell1><b>Options:</b></td>$tccell2l>
		$inpc=\"nosmilies\" id=\"nosmilies\" value=\"1\"><label for=\"nosmilies\">Disable Smilies</label> - 
		$inpc=\"nolayout\" id=\"nolayout\" value=\"1\"><label for=\"nolayout\">Disable Layout</label> - 
		$inpc=\"nohtml\" id=\"nohtml\" value=\"1\"><label for=\"nohtml\">Disable HTML</label></td></tr>
		$modoptions
		$tblend
		</FORM>
	 $tblstart$postlist$tblend
	</table>
	$fonttag<a href=index.php>$boardname</a> - <a href=forum.php?id=$forumid>$forum[title]</a> - $thread[title]
	".replytoolbar(4);
  }
  if($_POST[action]=='postreply' && !($banned && $log) && $id>0) {
    $userid=checkuser($username,$password);
    $error='';
    if($userid==-1) 
      $error="Either you didn't enter an existing username, or you haven't entered the right password for the username.";
    else{
	$user=@mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id=$userid"));
      if($thread[lastposter]==$userid && $user[powerlevel]<=2)
        $error='You already have the last reply in this thread.';
      if($thread[closed])
        $error='The thread is closed and no more replies can be posted.';
      if($user[powerlevel]<$forum[minpowerreply])
        $error='Replying in this forum is restricted, and you are not allowed to post in this forum.';
      if(!$message)
        $error="You didn't enter anything in the post.";
    }
    if(!$error){
	activitycheck($userid);
	$sign=$user[signature];
	$head=$user[postheader];
	if($user[postbg]) $head="<div style=background:url($user[postbg]);height=100%>$head";
	$numposts=$user[posts]+1;
	$numdays=(ctime()-$user[regdate])/86400;
	$message=doreplace($message,$numposts,$numdays,$username);
	$rsign=doreplace($sign,$numposts,$numdays,$username);
	$rhead=doreplace($head,$numposts,$numdays,$username);
	$currenttime=ctime();
	if($submit){

	  mysql_query("UPDATE `users` SET `posts` = `posts` + 1, `lastposttime` = '$currenttime' WHERE `id` = '$userid'");

		if ($nolayout) {
			$headid = 0;
			$signid = 0;
		} else {
			$headid=getpostlayoutid($head);
			$signid=getpostlayoutid($sign);
		}


		if ($ismod) {
			if ($close) $close = "`closed` = '1',";
				else $close = "`closed` = '0',";
			if ($stick) $stick = "`sticky` = '1',";
				else $stick = "`sticky` = '0',";
			}

		mysql_query("INSERT INTO posts (thread,user,date,ip,num,headid,signid) VALUES ($id,$userid,$currenttime,'$userip',$numposts,$headid,$signid)");
		$pid=mysql_insert_id();

		$options = intval($nosmilies) . "|" . intval($nohtml);

	  if($pid) mysql_query("INSERT INTO `posts_text` (`pid`,`text`,`tagval`, `options`) VALUES ('$pid','$message','$tagval', '$options')");

	  mysql_query("UPDATE `threads` SET $close $stick `replies` =  `replies` + 1, `lastpostdate` = '$currenttime', `lastposter` = '$userid' WHERE `id`='$id'");
	  mysql_query("UPDATE `forums` SET `numposts` = `numposts` + 1, `lastpostdate` = '$currenttime', `lastpostuser` ='$userid' WHERE `id`='$forumid'");
	  $t = mysql_fetch_array(mysql_query("SELECT `replies` FROM `threads` WHERE `id`='$id'"));
	  $threadpostcount = $t[replies];
	  $pagenum = floor($threadpostcount / $ppp);
	  if($pagenum < 0) $pagenum = 0;

	  print "
	   $tccell1>Reply posted successfully!
	   <br>".redirect("thread.php?id=$id&page=$pagenum#$pid", $thread[title], 0).$tblend;


	}else{
		loadtlayout();
		$message = stripslashes($message);
		$ppost=$user;
		$ppost[uid]=$userid;
		$ppost[num]=$numposts;
		$ppost[posts]++;
		$ppost[lastposttime]=$currenttime;
		$ppost[date]=$currenttime;
		if ($nolayout) {
			$ppost[headtext] = "";
			$ppost[signtext] = "";
		} else {
			$ppost[headtext]=$rhead;
			$ppost[signtext]=$rsign;
		}
		$ppost[text]=$message;
		$ppost[options] = $nosmilies . "|" . $nohtml;

	  if($isadmin) $ip=$userip;

	if ($nosmilies)	$nosmilieschk	= " checked";
	if ($nohtml)	$nohtmlchk	= " checked";
	if ($nolayout)	$nolayoutchk	= " checked";

	  print "
		<body onload=window.document.REPLIER.message.focus()>
		$tccellh>Post preview
		$tblend$tblstart
		".threadpost($ppost,1)."
		$tblend<br>$tblstart
		<FORM ACTION=newreply.php NAME=REPLIER METHOD=POST>
		$tccellh width=150>&nbsp</td>$tccellh>&nbsp<tr>
		$tccell1><b>Reply:</td>
		$tccell2l>$txta=message ROWS=10 COLS=$numcols>$message</TEXTAREA><tr>
		$tccell1>&nbsp</td>$tccell2l>
		$inps=submit VALUE=\"Submit reply\">
		$inps=preview VALUE=\"Preview reply\"></td>
		$inph=username VALUE=\"".htmlspecialchars($username)."\">
		$inph=password VALUE=\"".htmlspecialchars($password)."\">
		$inph=action VALUE=postreply>
		$inph=id VALUE=$id>
	<tr>$tccell1><b>Options:</b></td>$tccell2l>
		$inpc=\"nosmilies\" id=\"nosmilies\" value=\"1\"><label for=\"nosmilies\">Disable Smilies</label> - 
		$inpc=\"nolayout\" id=\"nolayout\" value=\"1\"><label for=\"nolayout\">Disable Layout</label> - 
		$inpc=\"nohtml\" id=\"nohtml\" value=\"1\"><label for=\"nohtml\">Disable HTML</label></td></tr>
		$modoptions
		$tblend
		</FORM>
	 $tblstart$postlist$tblend
		</td></FORM>
	  ";
      }
    }else
	print "$tccell1>Couldn't enter the post. $error<br>".redirect("thread.php?id=$id",'$thread[title]',0);
  }
  if($thread[closed])
    print "
	$tccell1>Sorry, but this thread is closed, and no more replies can be posted in it.
	<br>".redirect("thread.php?id=$id",$thread[title],0);
  if($banned and $log)
    print "
	$tccell1>Sorry, but you are banned from the board, and can not post.
	<br>".redirect("thread.php?id=$id",$thread[title],0);
  print $footer;
  printtimedif($startingtime);

function activitycheck($userid){
  global $id,$thread,$header,$tblstart,$tccell1,$tblend,$footer;
  $activity=mysql_result(mysql_query("SELECT count(*) FROM posts WHERE user=$userid AND thread=$id AND date>".(ctime()-86400)),0,0);
  if($activity>=(stristr($thread[title],'ACS ')?5:50))
    die("$tblstart$tccell1>You have posted enough in this thread today. Come back later!$tblend$footer");
}
?>