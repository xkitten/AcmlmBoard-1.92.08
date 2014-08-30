<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  if($posttime<1) $posttime=86400;
  $query='SELECT users.id,users.posts,regdate,name,picture,sex,powerlevel,COUNT(*) AS cnt FROM users';
  $endp=' GROUP BY users.id ORDER BY cnt DESC';
  if($type=='thread')  $posters=mysql_query("$query,threads WHERE threads.user=users.id$endp");
  elseif($type=='pm')  $posters=mysql_query("$query,pmsgs WHERE pmsgs.userto=$loguserid AND pmsgs.userfrom=users.id$endp");
  elseif($type=='pms') $posters=mysql_query("$query,pmsgs WHERE pmsgs.userfrom=$loguserid AND pmsgs.userto=users.id$endp");
  else                 $posters=mysql_query("$query,posts WHERE posts.user=users.id".($tid?" AND thread=$tid":'')." AND posts.date>".(ctime()-$posttime).$endp) or print mysql_error();
  $link='<a href=activeusers.php?posttime';
  print "
	$header$smallfont
	$link=3600>During last hour</a> |
	$link=86400>During last day</a> |
	$link=604800>During last week</a> |
	$link=2592000>During last 30 days</a><br>
	$fonttag Most active users during the last ".timeunits2($posttime).":
	$tblstart
	 $tccellh width=30>#</td>
	 $tccellh>Username</td>
	 $tccellh width=150>Registered on</td>
	 $tccellh width=50>Posts</td>
	 $tccellh width=50>Total<tr>
  ";
  for($i=1;$user=mysql_fetch_array($posters);$i++){
    if($i>1) print '<tr>';
    $namecolor=getnamecolor($user[5],$user[6]);
    print "
	$tccell1>$i</td>
	$tccell2l><a href=profile.php?id=$user[0]><font $namecolor>$user[3]</font></a></td>
	$tccell1>".date($dateformat,$user[2]+$tzoff)."</td>
	$tccell2><b>$user[7]</b></td>
	$tccell2>$user[1]
    ";
  }
  print $tblend.$footer;
  printtimedif($startingtime);
?>