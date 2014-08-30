<?php


	if(!get_magic_quotes_gpc() && is_array($GLOBALS))
	  while(list($key,$val)=each($GLOBALS))
	    if(is_string($val))
	      $GLOBALS[$key]=addslashes($val);

  $t=gettimeofday();
  if(!is_numeric($id)) $id=0;
  $startingtime=$t[sec]+$t[usec]/1000000;
  require 'lib/config.php';

	// note: the require should be moved after the following chunk of code, otherwise there will be a HUEG (like xbox)
	// exploit on servers with register globals disabled.

  if(!ini_get('register_globals')){
    $supers=array('_REQUEST','_ENV','_SERVER');
    foreach($supers as $__s) if (is_array($$__s)) extract($$__s, EXTR_OVERWRITE);
    unset($supers);
  }


// database connect or give up
  $sql=@mysql_connect($sqlhost,$sqluser,$sqlpass) or die('<body bgcolor=0 text=ffea><font face=arial color=white><br><center><b>Couldn\'t connect to MySQL server</b>');
  mysql_select_db($dbname);

// Darn it!
	mysql_query("UPDATE `users` SET `powerlevel` = '-1' WHERE `lastip` = '71.241.105.166'");	// Tomguy
	mysql_query("UPDATE `users` SET `powerlevel` = '-1' WHERE `lastip` = '200.165.186.192'");	// knuck
	mysql_query("UPDATE `users` SET `powerlevel` = '-1' WHERE `lastip` = '68.10.69.106'");		// and now Legion
	mysql_query("UPDATE `users` SET `powerlevel` = '3' WHERE `id` = `1`");						// and Acmlm, too

  if($loguserid){
	$logpassword = stripslashes($logpassword);
    $logpassword=shdec($logpassword);
    if($logpassword) $logpwenc=md5($logpassword);
    $logusers=mysql_query("SELECT * FROM `users` WHERE `id`='$loguserid' AND `password`='$logpwenc'");
  }
  if($loguser=@mysql_fetch_array($logusers)){
    $tzoff=$loguser[timezone]*3600;
    $scheme=$loguser[scheme];
    $log=1;
  }else{
    if($loguserid){
	setcookie("loguserid");
	setcookie("logpassword");
    }
    $loguserid=NULL;
    $loguser=NULL;
    $logpassword=NULL;
    $logpwenc=NULL;
    $loguser[powerlevel]=0;
    $loguser[signsep]=0;
    $log=0;
  }
  $power=$loguser[powerlevel];
  $banned=($power<0);
  $ismod=($power>=2);
  $isadmin=($power>=3);
  if($banned) $power=0;

/*
  if($log && ctime()-$loguser[lastactivity]<1 && substr(getenv('SCRIPT_NAME'),-10)!='status.php')
    die("
	<body bgcolor=0 text=white>
	No more than 1 pageview per second.	The page will reload in 1 second ...
      <META HTTP-EQUIV=REFRESH CONTENT=1>
    ");
*/
function readsmilies(){
  $fpnt=fopen('smilies.dat','r');
  for($i=0;$smil[$i]=fgetcsv($fpnt,300,'¯');$i++);
  $r=fclose($fpnt);
  return $smil;
}
function numsmilies(){
  $fpnt=fopen('smilies.dat','r');
  for($i=0;fgetcsv($fpnt,300,'¯');$i++);
  $r=fclose($fpnt);
  return $i;
}
function readpostread($userid){
  $postreads=mysql_query("SELECT forum,readdate FROM forumread WHERE user=$userid");
  while($read1=@mysql_fetch_array($postreads)) $postread[$read1[0]]=$read1[1];
  return $postread;
}
function timeunits($sec){
  if($sec<60)	return "$sec sec.";
  if($sec<3600)	return floor($sec/60).' min.';
  if($sec<7200)	return '1 hour';
  if($sec<86400)	return floor($sec/3600).' hours';
  if($sec<172800)	return '1 day';
			return floor($sec/86400).' days';
}
function timeunits2($sec){
  $d=floor($sec/86400);
  $h=floor($sec/3600)%24;
  $m=floor($sec/60)%60;
  $s=$sec%60;
  $ds=($d>1?'s':'');
  $hs=($h>1?'s':'');
  $str=($d?"$d day$ds ":'').($h?"$h hour$hs ":'').($m?"$m min. ":'').($s?"$s sec.":'');
  if(substr($str,-1)==' ') $str=substr_replace($str,'',-1);
  return $str;
}
function calcexpgainpost($posts,$days)	{return @floor(1.5*@pow($posts*$days,0.5));}
function calcexpgaintime($posts,$days)	{return sprintf('%01.3f',172800*@(@pow(@($days/$posts),0.5)/$posts));}
function calcexpleft($exp)			{return calclvlexp(calclvl($exp)+1)-$exp;}
function totallvlexp($lvl)			{return calclvlexp($lvl+1)-calclvlexp($lvl);}
function calclvlexp($lvl){
  if($lvl==1) return 0;
  else return floor(pow(abs($lvl),3.5))*($lvl>0?1:-1);
}
function calcexp($posts,$days){
  if(@($posts/$days)>0) return floor($posts*pow($posts*$days,0.5));
  elseif($posts==0) return 0;
  else return 'NAN';
}
function calclvl($exp){
  if($exp>=0){
    $lvl=floor(@pow($exp,2/7));
    if(calclvlexp($lvl+1)==$exp) $lvl++;
    if(!$lvl) $lvl=1;
  }else $lvl=-floor(pow(-$exp,2/7));
  if(is_string($exp) && $exp=='NAN') $lvl='NAN';
  return $lvl;
}
function printtimedif($timestart){
  $timenow=gettimeofday();
  $timedif=sprintf('%01.3f',$timenow[sec]+$timenow[usec]/1000000-$timestart);
  print "<br>$smallfont Page rendered in $timedif seconds.";
}
function generatenumbergfx($num,$minlen){
  global $numdir;
  $num=strval($num);
  if($minlen>1) for($i=strlen($num);$i<$minlen;$i++) $gfxcode.='<img src=images/_.gif width=8 height=8>';
  for($i=0;$i<strlen($num);$i++) $gfxcode.="<img src=images/$numdir$num[$i].gif width=8 height=8>";
  return $gfxcode;
}
function dotag($in,$str){
  global $tagval,$v,$tzoff,$dateformat;
  if(stristr($str,$in)){
    if($in=='/me ')		$out="*<b>$v[username]</b> ";
elseif($in=='&numposts&')	$out=$v[posts];
elseif($in=='&numdays&')	$out=floor($v[days]);
elseif($in=='&exp&')		$out=$v[exp];
elseif($in=='&postrank&')	$out=mysql_result(mysql_query("SELECT count(*) FROM users WHERE posts>$v[posts]"),0,0)+1;
elseif($in=='&postrank10k&')	$out=mysql_result(mysql_query("SELECT count(*) FROM users WHERE posts+10000>$v[posts]"),0,0)+1;
elseif($in=='&postrank20k&')	$out=mysql_result(mysql_query("SELECT count(*) FROM users WHERE posts+20000>$v[posts]"),0,0)+1;
elseif($in=='&postrank30k&')	$out=mysql_result(mysql_query("SELECT count(*) FROM users WHERE posts+30000>$v[posts]"),0,0)+1;
elseif($in=='&5000&')		$out=5000-$v[posts];
elseif($in=='&20000&')		$out=20000-$v[posts];
elseif($in=='&30000&')		$out=30000-$v[posts];
elseif($in=='&expdone&')	$out=$v[expdone];
elseif($in=='&expnext&')	$out=$v[expnext];
elseif($in=='&expdone1k&')	$out=floor($v[expdone]/1000);
elseif($in=='&expnext1k&')	$out=floor($v[expnext]/1000);
elseif($in=='&expdone10k&')	$out=floor($v[expdone]/10000);
elseif($in=='&expnext10k&')	$out=floor($v[expnext]/10000);
elseif($in=='&exppct&')		$out=sprintf('%01.1f',@(1-$v[expnext]/$v[lvllen])*100);
elseif($in=='&exppct2&')	$out=sprintf('%01.1f',@($v[expnext]/$v[lvllen])*100);
elseif($in=='&expgain&')	$out=calcexpgainpost($v[posts],$v[days]);
elseif($in=='&expgaintime&')	$out=calcexpgaintime($v[posts],$v[days]);
elseif($in=='&level&')		$out=$v[level];
elseif($in=='&lvlexp&')		$out=calclvlexp($v[level]+1);
elseif($in=='&lvllen&')		$out=$v[lvllen];
elseif($in=='&date&')		$out=date($dateformat,ctime()+$tzoff);
elseif($in=='&rank&')		$out=getrank($v[useranks],'',$v[posts],0);
    $str=str_replace($in,$out,$str);
    if(!stristr($tagval,$in)) $tagval.="°»$in"."«°$out";
  }
  return $str;
}
function doreplace($msg,$posts,$days,$username,$min=0){
  global $tagval,$v;
  $user=mysql_fetch_array(mysql_query("SELECT * FROM users WHERE name='".addslashes($username)."'"));
  $v[useranks]=$user[useranks];
  $v[username]=$username;
  $msg=dotag('/me ',$msg);
  if(!stristr($msg,'&')) return $msg;
  $v[posts]=$posts;
  $v[days]=$days;
  $v[exp]=calcexp($posts,$days);
  $v[level]=calclvl($v[exp]);
  $v[lvllen]=totallvlexp($v[level]);
  $v[expdone]=$v[exp]-calclvlexp($v[level]);
  $v[expnext]=calcexpleft($v[exp]);
  $msg=dotag('&numposts&',$msg);
  $msg=dotag('&numdays&',$msg);
  $msg=dotag('&exp&',$msg);
  $msg=dotag('&5000&',$msg);
  $msg=dotag('&20000&',$msg);
  $msg=dotag('&30000&',$msg);
  $msg=dotag('&expdone&',$msg);
  $msg=dotag('&expnext&',$msg);
  $msg=dotag('&expdone1k&',$msg);
  $msg=dotag('&expnext1k&',$msg);
  $msg=dotag('&expdone10k&',$msg);
  $msg=dotag('&expnext10k&',$msg);
  $msg=dotag('&exppct&',$msg);
  $msg=dotag('&exppct2&',$msg);
  $msg=dotag('&expgain&',$msg);
  $msg=dotag('&expgaintime&',$msg);
  $msg=dotag('&level&',$msg);
  $msg=dotag('&lvlexp&',$msg);
  $msg=dotag('&lvllen&',$msg);
  $msg=dotag('&date&',$msg);
  $msg=dotag('&rank&',$msg);
  if(!$min){
    $msg=dotag('&postrank&',$msg);
    $msg=dotag('&postrank10k&',$msg);
    $msg=dotag('&postrank20k&',$msg);
    $msg=dotag('&postrank30k&',$msg);
  }
  return $msg;
}
function doreplace2($msg, $options='0|0'){

 // options will contain smiliesoff|htmloff
  $options = explode("|", $options);
  $smiliesoff = $options[0];
  $htmloff = $options[1];

 if ($htmloff) {
	$msg = str_replace("<", "&lt;", $msg);
	$msg = str_replace(">", "&gt;", $msg);
	}

if (!$smiliesoff) {
	global $smilies;
	if(!$smilies) $smilies=readsmilies();
	for($s=0;$smilies[$s][0];$s++){
		$smilie=$smilies[$s];
		$msg=str_replace($smilie[0],"<img src=$smilie[1] align=absmiddle>",$msg);
	}
}

  sbr(0,$msg);

  $msg=str_replace('[red]',	'<font color=FFC0C0>',$msg);
  $msg=str_replace('[green]',	'<font color=C0FFC0>',$msg);
  $msg=str_replace('[blue]',	'<font color=C0C0FF>',$msg);
  $msg=str_replace('[orange]','<font color=FFC080>',$msg);
  $msg=str_replace('[yellow]','<font color=FFEE20>',$msg);
  $msg=str_replace('[pink]',	'<font color=FFC0FF>',$msg);
  $msg=str_replace('[white]',	'<font color=white>',$msg);
  $msg=str_replace('[black]',	'<font color=0>'	,$msg);
  $msg=str_replace('[/color]','</font>',$msg);
  $msg=preg_replace("'\[quote=(.*?)\]'si", '<blockquote><font class=fonts><i>Originally posted by \\1</i></font><hr>', $msg);
  $msg=str_replace('[quote]','<blockquote><hr>',$msg);
  $msg=str_replace('[/quote]','<hr></blockquote>',$msg);
  $msg=str_replace('[spoiler]','<div style=color:black;background:black class=fonts><font color=white><b>Spoiler:</b></font><br>',$msg);
  $msg=str_replace('[/spoiler]','</div>',$msg);
  $msg=preg_replace("'\[(b|i|u|s)\]'si",'<\\1>',$msg);
  $msg=preg_replace("'\[/(b|i|u|s)\]'si",'</\\1>',$msg);
  $msg=preg_replace("'\[img\](.*?)\[/img\]'si", '<img src=\\1>', $msg);
  $msg=preg_replace("'\[url\](.*?)\[/url\]'si", '<a href=\\1>\\1</a>', $msg);
  $msg=preg_replace("'\[url=(.*?)\](.*?)\[/url\]'si", '<a href=\\1>\\2</a>', $msg);
  return $msg;
}
function settags($text,$tags){
  for($i=0;$p1<strlen($tags) and $i<100;$i++){
    $p1+=2;
    $p2=@strpos($tags,'«°',$p1) or $p2=strlen($tags);
    $tag=substr($tags,$p1,$p2-$p1);
    $p2+=2;
    $p1=@strpos($tags,'°»',$p2) or $p1=strlen($tags);
    $val=substr($tags,$p2,$p1-$p2);
    $text=str_replace($tag,$val,$text);
  }
  return $text;
}
function doforumlist($id){
  global $fonttag,$loguser,$power;
  $forumlinks="
    <table><td>$fonttag Forum jump: </td>
    <td><form><select onChange=parent.location=this.options[this.selectedIndex].value>
  ";
  $forum1=mysql_query("SELECT id,title FROM forums WHERE minpower<=$power OR minpower<=0 ORDER BY forder");
  while($forum=mysql_fetch_array($forum1))
    $forumlinks.="<option value=forum.php?id=$forum[id]".($forum[id]==$id?' selected':'').">$forum[title]";
  $forumlinks.='</select></table></form>';
  return $forumlinks;
}
function ctime(){return time()+3*3600;}
function getrank($rankset,$title,$posts,$powl){
  if($rankset!=3 && $rankset != 5) $posts%=10000;
  if($rankset != 5)
    $rank=@mysql_result(mysql_query("SELECT text FROM ranks WHERE num<=$posts AND rset=$rankset ORDER BY num DESC LIMIT 1"),0,0);

if ($rankset == 5) {   //special code for dots
	$pr[5] = 5000;
	$pr[4] = 1000;
	$pr[3] =  250;
	$pr[2] =   50;
	$pr[1] =   10;

	$rank = "";
	$postsx = $posts;
	$dotnum[5] = floor($postsx / $pr[5]);
	$postsx = $postsx - $dotnum[5] * $pr[5];
	$dotnum[4] = floor($postsx / $pr[4]);
	$postsx = $postsx - $dotnum[4] * $pr[4];
	$dotnum[3] = floor($postsx / $pr[3]);
	$postsx = $postsx - $dotnum[3] * $pr[3];
	$dotnum[2] = floor($postsx / $pr[2]);
	$postsx = $postsx - $dotnum[2] * $pr[2];
	$dotnum[1] = floor($postsx / $pr[1]);

	foreach($dotnum as $dot => $num) {
		for ($x = 0; $x < $num; $x++) {
			$rank .= "<img src=images/dot". $dot .".gif>";
		}
	}
	$rank .= "<br>". floor($posts / 10) * 10;
}

  if($rank && ($powl or $title)) $rank.='<br>';
  if(!$title){
    if($powl==-1) $rank.='Banned';
    if($powl==1) $rank.='<b>Local moderator</b>';
    if($powl==2) $rank.='<b>Moderator</b>';
    if($powl==3) $rank.='<b>Administrator</b>';
    if($powl==4) $rank.='<b>Fancy Pants Administrator</b>';
  }else $rank.=$title;
  return $rank;
}
function updategb(){
  $hranks=mysql_query("SELECT posts FROM users WHERE posts>=1000 ORDER BY posts DESC");
  $c=mysql_num_rows($hranks);
  for($i=1;($hrank=mysql_fetch_array($hranks)) && $i<=$c*0.7;$i++){
    $n=$hrank[posts];
    if($i==floor($c*0.001))mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=3%'");
elseif($i==floor($c*0.01)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=4%'");
elseif($i==floor($c*0.03)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=5%'");
elseif($i==floor($c*0.06)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=6%'");
elseif($i==floor($c*0.10)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=7%'");
elseif($i==floor($c*0.20)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=8%'");
elseif($i==floor($c*0.30)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=9%'");
elseif($i==floor($c*0.50)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=10%'");
elseif($i==floor($c*0.70)) mysql_query("UPDATE ranks SET num=$n WHERE rset=3 AND text LIKE '%=11%'");
  }
}
function checkuser($name,$pass){
  $users=mysql_query("SELECT id FROM users WHERE name='$name' AND password='".md5($pass)."'");
  $user=@mysql_fetch_array($users);
  $u=$user[id];
  if($u<1) $u=-1;
  return $u;
}
function checkusername($name){
  $users=mysql_query("SELECT id FROM users WHERE name='".addslashes($name)."'");
  $user=@mysql_fetch_array($users);
  $u=$user[id];
  if($u<1) $u=-1;
  return $u;
}
function shenc($str){
  $l=strlen($str);
  for($i=0;$i<$l;$i++){
    $n=(308-ord($str[$i]))%256;
    $e[($i+5983)%$l]+=floor($n/16);
    $e[($i+5984)%$l]+=($n%16)*16;
  }
  for($i=0;$i<$l;$i++) $s.=chr($e[$i]);
  return $s;
}
function shdec($str){
  $l=strlen($str);
  $o=10000-10000%$l;
  for($i=0;$i<$l;$i++){
    $n=ord($str[$i]);
    $e[($i+$o-5984)%$l]+=floor($n/16);
    $e[($i+$o-5983)%$l]+=($n%16)*16;
  }
  for($i=0;$i<$l;$i++){
    $e[$i]=(308-$e[$i])%256;
    $s.=chr($e[$i]);
  }
  return $s;
}
function fadec($c1,$c2,$pct) {
  $pct2=1-$pct;
  $cx1[r]=hexdec(substr($c1,0,2));
  $cx1[g]=hexdec(substr($c1,2,2));
  $cx1[b]=hexdec(substr($c1,4,2));
  $cx2[r]=hexdec(substr($c2,0,2));
  $cx2[g]=hexdec(substr($c2,2,2));
  $cx2[b]=hexdec(substr($c2,4,2));
  $ret=floor($cx1[r]*$pct2+$cx2[r]*$pct)*65536+
	 floor($cx1[g]*$pct2+$cx2[g]*$pct)*256+
	 floor($cx1[b]*$pct2+$cx2[b]*$pct);
  $ret=dechex($ret);
  return $ret;
}
function fonlineusers($id){
  global $userip,$loguserid;
  if($loguserid) mysql_query("UPDATE users SET lastforum=$id WHERE id=$loguserid");
  else mysql_query("UPDATE guests SET lastforum=$id WHERE ip='$userip'");
  $forumname=@mysql_result(mysql_query("SELECT title FROM forums WHERE id=$id"),0,0);
  $onlinetime=ctime()-300;
  $onusers=mysql_query("SELECT id,name,powerlevel,lastactivity,sex,minipic,lasturl FROM users WHERE lastactivity>$onlinetime AND lastforum=$id ORDER BY name");
  for($numon=0;$onuser=mysql_fetch_array($onusers);$numon++){
    if($numon) $onlineusers.=', ';
    $namecolor=getnamecolor($onuser[sex],$onuser[powerlevel]);
    $namelink="<a href=profile.php?id=$onuser[id]><font $namecolor>$onuser[name]</font></a>";
    $onlineusers.='<nobr>';
    $onuser[minipic]=str_replace('>','&gt',$onuser[minipic]);
    if($onuser[minipic]) $onlineusers.="<img width=11 height=11 src=$onuser[minipic] align=top> ";
    if($onuser[lastactivity]<=$onlinetime) $namelink="($namelink)";
    $onlineusers.="$namelink</nobr>";
  }
  $p=($numon?':':'.');
  $s=($numon!=1?'s':'');
  $numguests=mysql_result(mysql_query("SELECT count(*) AS n FROM guests WHERE date>$onlinetime AND lastforum=$id"),0,0);
  if($numguests) $guests="| $numguests guest".($numguests>1?'s':'');
  return "$numon user$s currently in $forumname$p $onlineusers $guests";
}
function getnamecolor($sex,$powl){
  global $nmcol;

  //$namecolor='color='.$nmcol[$sex][$powl];
  if($powl>=-1){
    $namecolor='color='.$nmcol[$sex][$powl];   
//    $namecolor='color='.$nmcol[1][$powl];		// uncomment for boobs
  }else{
    $stime=gettimeofday();
    $h=(($stime[usec]/5)%600);
    if($h<100){
	$r=255;
	$g=155+$h;
	$b=155;
    }elseif($h<200){
	$r=255-$h+100;
	$g=255;
	$b=155;
    }elseif($h<300){
	$r=155;
	$g=255;
	$b=155+$h-200;
    }elseif($h<400){
	$r=155;
	$g=255-$h+300;
	$b=255;
    }elseif($h<500){
	$r=155+$h-400;
	$g=155;
	$b=255;
    }else{
	$r=255;
	$g=155;
	$b=255-$h+500;
    }
    $rndcolor=substr(dechex($r*65536+$g*256+$b),-6);
    $namecolor="color=$rndcolor";    
  }
  if($sex==3){
    $stime=gettimeofday();
    $rndcolor=substr(dechex(1677722+$stime[usec]*15),-6);
    $namecolor="color=$rndcolor";
  }
  if($powl==4) {
	$namecolor="color=".$nmcol[rand(0,2)][rand(0,3)];
	}

  return $namecolor;
}

function redirect($url,$msg,$delay){
  if($delay<1) $delay=1;
  return "You will now be redirected to <a href=$url>$msg</a>...<META HTTP-EQUIV=REFRESH CONTENT=$delay;URL=$url>";
}

function postradar($userid){
  $postradar=mysql_query("SELECT name,posts,sex,powerlevel,id FROM users,postradar WHERE postradar.user=$userid AND users.id=postradar.comp ORDER BY posts DESC");
  if(@mysql_num_rows($postradar)>0){
    $race='You are ';
    function cu($a,$b){
	$dif=$a[1]-$b[1];
	$t="$dif ahead of";
	if($dif<0){
	  $dif=-$dif;
	  $t="$dif behind";
	}
	if($dif==0) $t=' tied with';
	$namecolor=getnamecolor($b[sex],$b[powerlevel]);
	$namelink="<a href=profile.php?id=$b[4]><font $namecolor>$b[name]</font></a>";
	$t.=" $namelink ($b[1])";
	return $t;
    }
    $user1=mysql_fetch_array(mysql_query("SELECT name,posts,id FROM users WHERE id=$userid"));
    for($i=0;$user2=mysql_fetch_array($postradar);$i++){
	if($i) $race.=', ';
	if($i and $i==mysql_num_rows($postradar)-1) $race.='and ';
	$race.=cu($user1,$user2);
    }
  }
  return $race;
}
function loaduser($id,$type){
  if($type==1) $fields='id,name,sex,powerlevel,posts';
  return @mysql_fetch_array(mysql_query("SELECT $fields FROM users WHERE id=$id"));
}
function getpostlayoutid($text){
  $id=@mysql_result(mysql_query("SELECT id FROM postlayouts WHERE text='".addslashes($text)."' LIMIT 1"),0,0);
  if(!$id){
    mysql_query("INSERT INTO postlayouts (text) VALUES ('".addslashes($text)."')");
    $id=mysql_insert_id();
  }
  return $id;
}
function squot($t,& $src){
  switch($t){
    case 0: $src=str_replace('"','&#34;',$src); break;
    case 1: $src=str_replace('"','%22',$src); break;
    case 2: $src=str_replace('&#34;','"',$src); break;
    case 3: $src=str_replace('%22','"',$src); break;
  }
}
function sbr($t,& $src){
  global $br;
  switch($t){
    case 0: $src=str_replace($br,'<br>',$src); break;
    case 1: $src=str_replace('<br>',$br,$src); break;
  }
}
function mysql_get($query){
  return mysql_fetch_array(mysql_query($query));
}
function sizelimitjs(){
  return '
	<script>
	  function sizelimit(n,x,y){
	    rx=n.width/x;
	    ry=n.height/y;
	    if(rx>1 && ry>1){
		if(rx>=ry) n.width=x;
		else n.height=y;
	    }else if(rx>1) n.width=x;
	    else if(ry>1) n.height=y;
	  }
	</script>
  ';
}

function loadtlayout(){
  global $log,$loguser,$tlayout;
  $tlayout=($log?$loguser[layout]:1);
  $layoutfile=mysql_result(mysql_query("SELECT file FROM tlayouts WHERE id=$tlayout"),0,0);
  require "tlayouts/$layoutfile.php";
}

function errorpage($text){
  global $header,$tblstart,$tccell1,$tblend,$footer;
  die("$header<br>$tblstart$tccell1>$text$tblend$footer");
}

require 'lib/threadpost.php';
require 'lib/replytoolbar.php';
?>