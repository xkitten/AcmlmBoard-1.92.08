<?php
  require 'lib/function.php';

if ($pid) {
	if(!$ppp) $ppp=($log?$loguser[postsperpage]:20);
	$id = mysql_result(mysql_query("SELECT `thread` FROM `posts` WHERE `id` = '$pid'"), 0);
	$numposts = mysql_result(mysql_query("SELECT COUNT(*) FROM `posts` WHERE `thread` = '$id' AND `id` < '$pid'"), 0);
	$page = floor($numposts / $ppp);
	if (!$r) {
		header("Location: thread.php?pid=$pid&r=1#$pid");
		die();
	}		
}

  if($id){
    $thread=mysql_fetch_array(mysql_query("SELECT * FROM threads WHERE id=$id"));
    $forumid=$thread[forum];
    $forum=mysql_fetch_array(mysql_query("SELECT * FROM forums WHERE id=$forumid"));

    if($tnext=mysql_result(mysql_query("SELECT min(lastpostdate) FROM threads WHERE forum=$forumid AND lastpostdate>$thread[lastpostdate]"),0,0))
	$tnext=mysql_result(mysql_query("SELECT id FROM threads WHERE lastpostdate=$tnext"),0,0);
    if($tprev=mysql_result(mysql_query("SELECT max(lastpostdate) FROM threads WHERE forum=$forumid AND lastpostdate<$thread[lastpostdate]"),0,0))
	$tprev=mysql_result(mysql_query("SELECT id FROM threads WHERE lastpostdate=$tprev"),0,0);

    if($tnext) $nextnewer="<a href=thread.php?id=$tnext>Next newer thread</a>";
    if($tprev) $nextolder="<a href=thread.php?id=$tprev>Next older thread</a>";
    if($nextnewer && $nextolder) $nextnewer.=' | ';
    if(@mysql_fetch_array(mysql_query("SELECT * FROM favorites WHERE user=$loguserid AND thread=$id")))
	$favlink="<a href=forum.php?act=rem&thread=$id>Remove from favorites</a>";
    else $favlink="<a href=forum.php?act=add&thread=$id>Add to favorites</a>";
    if($nextnewer or $nextolder) $favlink.=' | ';

    mysql_query("UPDATE threads SET views=views+1 WHERE id=$id");
    $thread[title]=str_replace("<","&lt;",$thread[title]);
    if($forum[minpower]>$power and $forum[minpower]>0) $thread[title]="(restricted)";
    $forumtitle="$forum[title]: ";
  }elseif($user){
    $usr=$user;
    $tuser=mysql_fetch_array(mysql_query("SELECT name FROM users WHERE id=$usr"));
    $thread[title]="Posts by $tuser[name]";
  }elseif($search) $thread[title]="Search results";

  $windowtitle="$boardname -- $forumtitle$thread[title]";
  require 'lib/layout.php';
  if($id) $fonline=fonlineusers($forumid);
  if(@mysql_num_rows(mysql_query("SELECT user FROM forummods WHERE forum=$forumid and user=$loguserid"))) $ismod=1;
  if($id && $ismod){
    $trashid=27;
    if($qmod){
	$verb='editing';
	if($st!='') mysql_query("UPDATE threads SET sticky=$st WHERE id=$id");
	if($cl!='') mysql_query("UPDATE threads SET closed=$cl WHERE id=$id");
	if($trash){
	  mysql_query("UPDATE threads SET sticky=0,closed=1,forum=$trashid WHERE id=$id");
	  $numposts=$thread[replies]+1;
	  $t1=mysql_fetch_array(mysql_query("SELECT lastpostdate,lastposter FROM threads WHERE forum=$forumid ORDER BY lastpostdate DESC LIMIT 1"));
	  $t2=mysql_fetch_array(mysql_query("SELECT lastpostdate,lastposter FROM threads WHERE forum=$trashid ORDER BY lastpostdate DESC LIMIT 1"));
	  mysql_query("UPDATE forums SET numposts=numposts-$numposts,numthreads=numthreads-1,lastpostdate=$t1[lastpostdate],lastpostuser=$t1[lastposter] WHERE id=$forumid");
	  mysql_query("UPDATE forums SET numposts=numposts+$numposts,numthreads=numthreads+1,lastpostdate=$t2[lastpostdate],lastpostuser=$t2[lastposter] WHERE id=$trashid");
	  $verb='trashing';
	}
	print "
	  $header$tblstart
	  $tccell1>Thank you, $loguser[name], for $verb this thread.<br>".
	  redirect("forum.php?id=$forumid",'return to the forum',0)."
	  $tblend$footer
	";
	printtimedif($startingtime);
	exit;
    }else{
	$fulledit="<a href=editthread.php?id=$id>Edit thread<a>";
	$link="<a href=thread.php?id=$id&qmod=1";
	if(!$thread[sticky])	$stick="$link&st=1>Stick</a>";
	else				$stick="$link&st=0>Unstick</a>";
	if(!$thread[closed])	$close="$link&cl=1>Close</a>";
	else				$close="$link&cl=0>Open</a>";
	if($thread[forum]!=$trashid) $trash=" | $link&trash=1>Trash</a>";
	$delete="<a href=editthread.php?action=editthread&delete=1&id=$id onClick='if(!confirm(\"Are you sure you want to delete the thread?\")){return false;}'>Delete</a>";
	$modfeats="<tr>$tccellcls colspan=2>Moderating options: $stick | $close$trash -- $fulledit";
    }
  }
  if($thread[poll]){
    $poll=mysql_fetch_array(mysql_query("SELECT * FROM poll WHERE id=$thread[poll]"));
    $voted=@mysql_result(mysql_query("SELECT count(*) FROM pollvotes WHERE poll=$poll[id] AND user=$loguserid"),0,0);
    if($action=='vote' and $loguserid and (!$voted or $poll[doublevote]) and !$poll[closed]){
	mysql_query("INSERT INTO pollvotes (poll,choice,user) VALUES ($poll[id],$choice,$loguserid)");
	$voted++;
    }
    $tvotes=mysql_result(mysql_query("SELECT count(distinct `user`) FROM pollvotes WHERE poll=$poll[id]"),0,0);
    $pollcs=mysql_query("SELECT * FROM poll_choices WHERE poll=$poll[id]");
    while($pollc=mysql_fetch_array($pollcs)){
	$votes=mysql_result(mysql_query("SELECT count(*) FROM pollvotes WHERE choice=$pollc[id]"),0,0);
    if($tvotes != 0) $pct=sprintf('%02.1f',$votes/$tvotes*100);
		else $pct = "00.0";

	$barpart="<table cellpadding=0 cellspacing=0 width=$pct% bgcolor='".($pollc[color]?$pollc[color]:$tableborder)."'><td>&nbsp;</table>";
	if(!$votes) $barpart='&nbsp;';
	$s=($votes>1?'s':'');
	$link='';
	if($loguserid and (!$voted or $poll[doublevote]) and !$poll[closed]) $link="<a href=thread.php?id=$id&choice=$pollc[id]&action=vote>";
	$choices.="
	  $tccell1l width=20%>$link$pollc[choice]</a></td>
	  $tccell2l width=60%>$barpart</td>
	  $tccell1 width=20%>$pct%, $votes vote$s<tr>
	";
    }
    $mlt='disabled';
    if($poll[doublevote]) $mlt='enabled';
    if($tvotes != 1) {
		$ss = 's';
		$hv = 'have';
	} else {
		$ss = '';
		$hv = 'has';
	}
    if($ismod or $thread[user]==$loguserid) $polledit=" | <a href=editpoll.php?forumid=$forumid&id=$poll[id]>Edit</a>";
    $polltbl="
	$tccellc colspan=3><b>$poll[question]<tr>
	$tccell2ls colspan=3>$poll[briefing]<tr>
	$choices
	$tccell2l colspan=3>$smallfont Multi-voting is $mlt. $tvotes user$ss $hv voted. $polledit
	$tblend<br>$tblstart
    ";
  }

  loadtlayout();
  if($loguser[viewsig]==0) $sfields='';
  if($loguser[viewsig]==1) $sfields=',headtext,signtext';
  if($loguser[viewsig]==2) $sfields=',u.postheader headtext,u.signature signtext';
  $ufields=userfields();

  $activity=mysql_query("SELECT user, count(*) num FROM posts WHERE date>".(ctime()-86400)." GROUP BY user");
  while($n=mysql_fetch_array($activity)) $act[$n[user]]=$n[num];
  $postlist="
	$polltbl
	$modfeats<tr>
	$tccellh width=150>User</font></td>
	$tccellh>Post
  ";
  if(!$ppp) $ppp=($log?$loguser[postsperpage]:20);
  if($id && $power<$forum[minpower]){
    print "
	$header$tblstart
	$tccell1>Couldn't enter the forum. Either you don't have access to this restricted forum, or you are not logged in.
	<br>Click <a href=index.php>here</a> to return to the board, or wait to get redirected.
	<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php>
	$tblend
    ";
  }else{
    $min=$ppp*$page;
    if($id)
	$posts=mysql_query("SELECT p.*,text$sfields,edited,options,tagval,u.id uid,name,$ufields,regdate FROM posts p,posts_text LEFT JOIN users u ON p.user=u.id WHERE thread=$id AND p.id=pid ORDER BY p.id LIMIT $min,$ppp");
    elseif($usr){
	$thread[replies]=mysql_result(mysql_query("SELECT count(*) FROM posts WHERE user=$usr"),0,0)-1;
	$posts=mysql_query("SELECT p.*,text$sfields,edited,options,tagval,u.id uid,name,$ufields,regdate FROM posts p,posts_text LEFT JOIN users u ON p.user=u.id WHERE user=$usr AND p.id=pid ORDER BY p.id LIMIT $min,$ppp");
    } elseif($search){
	if($quser){
	  $user=mysql_fetch_array(mysql_query("SELECT id FROM users WHERE name='".addslashes($quser)."'"));
	  $u=$user[id];
	  $srch.=($srch?"AND ":"")."posts.user=$u";
	}
	if($qip) $srch.=($srch?" AND ":"")."ip LIKE '$qip'";
	if($qmsg) $srch.=($srch?" AND ":"")."text LIKE '%".addslashes($qmsg)."%'";
	if($dopt==1) $srch.=($srch?" AND ":"")."date>".(ctime()-86400*$datedays);
	if($dopt==2){
	  $date1=mktime(0,0,0,$d1m,$d1d,$d1y);
	  $date2=mktime(0,0,0,$d2m,$d2d,$d2y)+86400;
	  $srch.=($srch?" AND ":"")."date>$date1 AND date<$date2";
	}
	if($pord) $order=" ORDER BY id".($pord==2?" DESC":"");
	if(!$fsch){
	  $posts=mysql_query("SELECT id,user,date,thread,ip,text,num$signquery,edited,options FROM posts,posts_text WHERE $srch AND id=pid $order LIMIT $min,$ppp");
	  $thread[replies]=mysql_result(mysql_query("SELECT COUNT(*) FROM posts,posts_text WHERE $srch AND id=pid"),0,0);
	}else{
	  $posts=mysql_query("SELECT posts.id,posts.user,date,thread,ip,text,num$signquery,edited,options FROM posts,posts_text,threads WHERE $srch AND thread=threads.id AND forum=$fid AND id=pid $order LIMIT $min,$ppp");
	  $thread[replies]=mysql_result(mysql_query("SELECT COUNT(*) FROM posts,posts_text,threads WHERE $srch AND thread=threads.id AND forum=$fid AND id=pid"),0,0);
	}
	$quser=str_replace(" ","+",$quser);
	$qip=str_replace(" ","+",$qip);
	$qmsg=str_replace(" ","+",$qmsg);
    }
    for($i=0;$post=mysql_fetch_array($posts);$i++){
	$bg=$i%2+1;
	$postlist.='<tr>';
      $quote = "<a href=\"thread.php?pid=$post[id]\">Link</a>";
	$edit='';
	if($id and !$thread[closed]) $quote .= " | <a href=newreply.php?id=$id&postid=$post[id]>Quote</a>";
	$deletelink="<a href=editpost.php?id=$post[id]&action=delete onClick='if(!confirm(\"Are you sure you want to delete the post?\")){return false;}'>Delete</a>";
	if(($ismod or $post[user]==$loguserid) and !$thread[closed]) $edit=($quote?' | ':'')."<a href=editpost.php?id=$post[id]>Edit</a> | $deletelink";
	if($isadmin) $ip=($edit?' | ':'')."IP: <a href=ipsearch.php?ip=$post[ip]>$post[ip]</a>";
	if(!$id){
	  $pthread=mysql_fetch_array(mysql_query("SELECT id,title,forum FROM threads WHERE id=$post[thread]"));
	  $pforum=@mysql_fetch_array(mysql_query("SELECT minpower FROM forums WHERE id=$pthread[forum]"));
	}
      $post[act]=$act[$post[user]];
	if($pforum[minpower]<=$power or !$pforum[minpower]) $postlist.=threadpost($post,$bg,$pthread);
	else $postlist.="$tccellc colspan=2>$fonttag (restricted)";
    }
    $query=preg_replace("'page=([0-9].*)'si",'','?'.getenv("QUERY_STRING"));
    $query=preg_replace("'pid=(\d.*)'si","id=$id",$query);
    if($query AND substr($query,-1)!="&") $query.="&";
    if(!$page) $page=0;
    $pagelinks="Pages:";
    for($i=0;$i<(($thread[replies]+1)/$ppp);$i++){
	if($i==$page) $pagelinks.=" ".($i+1);
	else $pagelinks.=" <a href=thread.php$query"."page=$i>".($i+1)."</a>";
    }
    if($thread[replies]<$ppp) $pagelinks='';
    print
	$header.sizelimitjs()."
	$tblstart$tccell1s>$fonline$tblend
	<table width=100%><td align=left>$fonttag<a href=index.php>$boardname</a> - <a href=forum.php?id=$forumid>$forum[title]</a> - $thread[title]</td><td align=right>$smallfont
    ";
    if($forumid>-1){
	print "<a href=newthread.php?poll=1&id=$forumid>$newpollpic</a> | ";
	print "<a href=newthread.php?id=$forumid>$newthreadpic</a>";
	if(!$thread[closed]) print " | <a href=newreply.php?id=$id>$newreplypic</a>";
	else print " | Thread closed";
    } 
    print "</table><table width=100%><td align=left>$smallfont$pagelinks</td><td align=right>$smallfont$favlink$nextnewer$nextolder</table>
	$tblstart
    ";
    print "$postlist$modfeats$tblend
	<table width=100%><td align=left>$smallfont$pagelinks</td><td align=right>$smallfont$favlink$nextnewer$nextolder</table>
	<table width=100%><td align=left>$fonttag<a href=index.php>$boardname</a> - <a href=forum.php?id=$forumid>$forum[title]</a> - $thread[title]</td><td align=right>$smallfont
    ";
    if($forumid){
	print "<a href=newthread.php?id=$forumid>$newthreadpic</a>";
	if(!$thread[closed]) print " | <a href=newreply.php?id=$id>$newreplypic</a>";
	else print " | Thread closed";
    }
    print "</table>";
  }
  print $footer;
  printtimedif($startingtime);
?>