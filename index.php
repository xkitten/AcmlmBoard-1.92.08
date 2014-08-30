<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  $postread=readpostread($loguserid);
  $users1=mysql_query("SELECT id,name,birthday,sex,powerlevel FROM users WHERE FROM_UNIXTIME(birthday,'%m-%d')='".date('m-d',ctime())."' AND birthday ORDER BY name");
  for($numbd=0;$user=mysql_fetch_array($users1);$numbd++){
    if(!$numbd) $blist="<tr>$tccell2s colspan=5>Birthdays for ".date('F j',ctime()).': ';
    else $blist.=', ';
    $users[$user[id]]=$user;
    $y=date('Y',ctime())-date('Y',$user[birthday]);
    $namecolor=getnamecolor($user[sex],$user[powerlevel]);
    $blist.="<a href=profile.php?id=$user[id]><font $namecolor>$user[name]</font></a> ($y)"; 
  }
  $onlinetime=ctime()-300;
  $onusers=mysql_query("SELECT id,name,powerlevel,lastactivity,sex,minipic FROM users WHERE lastactivity>$onlinetime OR lastposttime>$onlinetime ORDER BY name");
  $numonline=mysql_num_rows($onusers);
  $numguests=mysql_result(mysql_query("SELECT count(*) FROM guests WHERE date>$onlinetime"),0,0);
  if($numguests) $guestcount=" | <nobr>$numguests guest".($numguests>1?"s":"");
  for($numon=0;$onuser=mysql_fetch_array($onusers);$numon++){
    if($numon) $onlineusers.=', ';
    $namecolor=getnamecolor($onuser[sex],$onuser[powerlevel]);
    $namelink="<a href=profile.php?id=$onuser[id]><font $namecolor>$onuser[name]</font></a>";
    if($onuser[minipic]) $onuser[minipic]='<img width=11 height=11 src="'.str_replace('"','%22',$onuser[minipic]).'" align=absmiddle> ';
    if($onuser[lastactivity]<=$onlinetime) $namelink="($namelink)";
    $onlineusers.="<nobr>$onuser[minipic]$namelink</nobr>";
  }
  if($onlineusers) $onlineusers=': '.$onlineusers;
  if($log){
    $headlinks.=' | <a href=index.php?action=markallforumsread>Mark all forums read</a>';
    $header=makeheader($header1,$headlinks,$header2);
  }
  if($action=='markforumread' and $log){
    mysql_query("DELETE FROM forumread WHERE user=$loguserid AND forum=$forumid");
    mysql_query("INSERT INTO forumread (user,forum,readdate) VALUES ($loguserid,$forumid,".ctime().')');
    $postread=readpostread($loguserid);
  }
  if($action=='markallforumsread' and $log){
    mysql_query("DELETE FROM forumread WHERE user=$loguserid");
    mysql_query("INSERT INTO forumread (user,forum,readdate) SELECT $loguserid,id,".ctime().' FROM forums');
    $postread=readpostread($loguserid);
  }
  if($log) $logmsg="You are logged in as $loguser[name].";
  $posts[d]=mysql_result(mysql_query('SELECT COUNT(*) FROM posts WHERE date>'.(ctime()-86400)),0,0);
  $posts[h]=mysql_result(mysql_query('SELECT COUNT(*) FROM posts WHERE date>'.(ctime()-3600)),0,0);
  $lastuser=mysql_fetch_array(mysql_query('SELECT id,name,sex,powerlevel FROM users ORDER BY id DESC LIMIT 1'));
  $misc=mysql_fetch_array(mysql_query('SELECT * FROM misc'));
  if($posts[d]>$misc[maxpostsday]) mysql_query("UPDATE misc SET maxpostsday=$posts[d],maxpostsdaydate=".ctime());
  if($posts[h]>$misc[maxpostshour]) mysql_query("UPDATE misc SET maxpostshour=$posts[h],maxpostshourdate=".ctime());
  if($numonline>$misc[maxusers]) mysql_query("UPDATE misc SET maxusers=$numonline,maxusersdate=".ctime().",maxuserstext='".addslashes($onlineusers)."'");
  $namecolor=getnamecolor($lastuser[sex],$lastuser[powerlevel]);
  print "$header<br>
	$tblstart
	 $tccell1s><table width=100%><td class=fonts>$logmsg</td><td align=right class=fonts>$count[u] registered users<br>Latest registered user: <a href=profile.php?id=$lastuser[id]><font $namecolor>$lastuser[name]</font></a></table>
	 $blist<tr>
	 $tccell2s>$count[t] threads and $count[p] posts in the board | $posts[d] posts during the last day, $posts[h] posts during the last hour.<tr>
	 $tccell1s>$numonline user".($numonline>1?'s':'')." currently online$onlineusers$guestcount
  ";

  $new='&nbsp;';
  if($log){
    $pmsgnum=0;
    $pmsgnew=0;
    $maxid=mysql_result(mysql_query("SELECT max(id) FROM pmsgs WHERE userto=$loguserid"),0,0);
    $pmsgs=mysql_query("SELECT userfrom,date,u.id,name,sex,powerlevel FROM pmsgs p,pmsgs_text,users AS u WHERE p.id=0$maxid AND u.id=p.userfrom AND p.id=pid") or print mysql_error();
    if($pmsg=@mysql_fetch_array($pmsgs)){
	$pmsgnum=mysql_result(mysql_query("SELECT COUNT(*) FROM pmsgs WHERE userto=$loguserid"),0,0);
	$pmsgnew=mysql_result(mysql_query("SELECT COUNT(*) FROM pmsgs WHERE userto=$loguserid AND msgread=0"),0,0);
	if($pmsgnew) $new=$newpic;
	$namecolor=getnamecolor($pmsg[sex],$pmsg[powerlevel]);
	$lastmsg="Last message from <a href=profile.php?id=$pmsg[id]><font $namecolor>$pmsg[name]</font></a> on ".date($dateformat,$pmsg[date]+$tzoff);
    }
    $privatebox="
	$tblstart
	$tccellhs colspan=2>Private messages<tr>
	$tccell1>$new</td>
	$tccell2l><a href=private.php>Private messages</a> -- You have $pmsgnum private messages ($pmsgnew new). $lastmsg
	$tblend<br>
    ";
  }
  $forumlist="
	$tccellh>&nbsp;</td>
	$tccellh>Forum</td>
	$tccellh>Threads</td>
	$tccellh>Posts</td>
	$tccellh>Last post
  ";
  $categories=mysql_query("SELECT id,name FROM categories WHERE (!minpower OR minpower<=$loguser[powerlevel]) ORDER BY id");
  $forums=mysql_query("SELECT f.*,u.id AS uid,name,sex,powerlevel FROM forums f LEFT JOIN users u ON f.lastpostuser=u.id WHERE (!minpower OR minpower<=$loguser[powerlevel]) ORDER BY catid,forder");
  $mods=mysql_query("SELECT u.id,name,sex,powerlevel,forum FROM users u INNER JOIN forummods m ON u.id=m.user INNER JOIN forums f ON f.id=m.forum WHERE (!minpower OR minpower<=$power) ORDER BY catid,forder,name");
  $forum=mysql_fetch_array($forums);
  $mod=mysql_fetch_array($mods);
  while($category=mysql_fetch_array($categories)){
    $forumlist.="<tr><td class='tbl tdbgc center font' colspan=5><a href=index.php?cat=$category[id]>$category[name]";
    for(;$forum[catid]==$category[id];$modlist=''){
	for($m=0;$mod[forum]==$forum[id];$m++){
	  $namecolor=getnamecolor($mod[sex],$mod[powerlevel]);
	  $modlist.=($m?', ':'')."<a href=profile.php?id=$mod[id]><font $namecolor>$mod[name]</font></a>";
	  $mod=mysql_fetch_array($mods);
	}
	if($m) $modlist="$smallfont(moderated by: $modlist)</font>";
	$namecolor=getnamecolor($forum[sex],$forum[powerlevel]);
	if($forum[numposts]){
	  $forumlastpost=date($dateformat,$forum[lastpostdate]+$tzoff);
	  $threadi = mysql_fetch_array(mysql_query("SELECT `id`, `replies` FROM `threads` WHERE `forum` = '$forum[id]' AND `lastpostdate` = '$forum[lastpostdate]' LIMIT 1"));
	  $threadid = $threadi[id];
	  if(!$ppp) $ppp=($log?$loguser[postsperpage]:20);
	  $pg = floor($threadi[replies] / $ppp);
	  $by="$smallfont<br>by <a href=profile.php?id=$forum[uid]><font $namecolor>$forum[name]</font></a> <a href=thread.php?id=$threadid&page=$pg>»</a><br></font>";
	}else{
	  $forumlastpost='-------- --:-- --';
	  $by='';
	}
	$new='&nbsp;';
	if((($forum[lastpostdate]>$postread[$forum[id]] and $log) or (!$log and $forum[lastpostdate]>ctime()-3600)) and $forum[numposts]) $new=$newpic;
	if($forum[lastpostdate]>$category[lastpostdate]){
	  $category[lastpostdate]=$forum[lastpostdate];
	  $category[l]=$forumlastpost.$by;
	}
	if($cat=='' or $cat==$category[id])
	  $forumlist.="
		<tr>$tccell1>$new</td>
		$tccell2l><a href=forum.php?id=$forum[id]>$forum[title]</a><br>
		$smallfont$forum[description]<br>$modlist</td>
		$tccell1>$forum[numthreads]</td>
		$tccell1>$forum[numposts]</td>
		$tccell2><nobr>$forumlastpost$by$forumlastuser
	  ";
	$forum=mysql_fetch_array($forums);
    }
  }
  print "$tblend<br>$privatebox$tblstart$forumlist$tblend$footer";
  printtimedif($startingtime);
?>