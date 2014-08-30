<?php
  if(!$time) $time=300;
  require 'lib/function.php';
  require 'lib/layout.php';
  $posters=mysql_query("SELECT id,posts,name,sex,powerlevel,lastactivity,lastip,lastposttime,lasturl FROM users WHERE lastactivity>".(ctime()-$time).' ORDER BY '.($sort=='IP'&&$isadmin?'lastip':'lastactivity DESC'));
  $guests=mysql_query('SELECT ip,date,lasturl FROM guests WHERE date>'.(ctime()-$time).' ORDER BY '.($sort=='IP'&&$isadmin?'ip':'date').' DESC');
  $server=getenv('SERVER_NAME');
  $port=getenv('SERVER_PORT');
  $host=$server;
  $lnk='<a href=online.php?'.($sort?"sort=$sort&":'').'time';
  print "
	$header$smallfont
	$lnk=60>During last minute</a> |
	$lnk=300>During last 5 minutes</a> |
	$lnk=900>During last 15 minutes</a> |
	$lnk=3600>During last hour</a> | 
	$lnk=86400>During last day</a>
  ";
  if($isadmin)
    print ' | <a href=online.php?'.($sort=='IP'?'':'sort=IP&')."time=$time>Sort by ".($sort=='IP'?'date':'IP')."</a>";
  print "<br>
	$fonttag Online users during the last $time seconds:
	$tblstart
	 $tccellh width=20>&nbsp</td>
	 $tccellh>Username</td>
	 $tccellh width=80> Last activity</td>
	 $tccellh width=130> Last post</td>
	 $tccellh>URL</td>
  ";
  if($isadmin) print "$tccellh width=100>IP address</td>";
  print "$tccellh width=60> Posts<tr>";
  for($i=1;$user=mysql_fetch_array($posters);$i++){
    if($i>1) print '<tr>';
    $namecolor=getnamecolor($user[sex],$user[powerlevel]);
    $user[lastposttime]=date($dateformat,$user[lastposttime]+$tzoff);
    if(!$user[posts]) $user[lastposttime]='-------- --:-- --';
    $user[lasturl]=str_replace('<','&lt;',$user[lasturl]);
    $user[lasturl]=str_replace('>','&gt;',$user[lasturl]);
    $user[lasturl]=str_replace('%20',' ',$user[lasturl]);
    print "
	$tccell1>$i</td>
	$tccell2l><a href=profile.php?id=$user[id]><font $namecolor>$user[name]</td>
	$tccell1>".date('h:i A',$user[lastactivity]+$tzoff)."</td>
	$tccell1>$user[lastposttime]</td>
    ".($user[lasturl]=='IP banned'?"$tccell2l>IP banned</td>":"
	$tccell2l><a href=\"$user[lasturl]\">$user[lasturl]
    ");
    if($isadmin) print "$tccell1><a href=ipsearch.php?ip=$user[lastip]>$user[lastip]</a></td>";
    print "$tccell2>$user[posts]";
  }
  print "
	$tblend
	$fonttag<br>Guests:
	$tblstart
	 $tccellh width=20>&nbsp</td>
	 $tccellh width=150>&nbsp</td>
	 $tccellh width=80>Last activity</td>
	 $tccellh>URL
  ";
  if($isadmin) print "</td>$tccellh width=100> IP address";
  print '<tr>';
  for($i=1;$guest=mysql_fetch_array($guests);$i++){
    if($i>1) print "<tr>";
    $guest[lasturl]=str_replace('<','&lt;',$guest[lasturl]);
    $guest[lasturl]=str_replace('>','&gt;',$guest[lasturl]);
    print "
	$tccell1>$i</td>
	$tccell2>&nbsp</td>
	$tccell1>".date('h:i A',$guest[date]+$tzoff)."</td>
    ".($guest[lasturl]=='IP banned'?"$tccell2l>IP banned</td>":"
	$tccell2l><a href=\"$guest[lasturl]\">$guest[lasturl]
    ");
    if($isadmin) print "</td>$tccell1><a href=ipsearch.php?ip=$guest[ip]>$guest[ip]</a>";
  }
  print $tblend.$footer;
  printtimedif($startingtime);
?>