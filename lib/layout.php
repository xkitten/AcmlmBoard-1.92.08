<?php

  $userip=$REMOTE_ADDR;
  $clientip=(getenv("HTTP_CLIENT_IP") == "" ? "XXXXXXXXXXXXXXXXX" : getenv("HTTP_CLIENT_IP"));
  $forwardedip=(getenv("HTTP_X_FORWARDED_FOR") == "" ? "XXXXXXXXXXXXXXXXX" : getenv("HTTP_X_FORWARDED_FOR"));

  if(!$windowtitle) $windowtitle=$boardname;
  require 'colors.php';
  $dateformat='m-d-y h:i A';
  $dateshort='m-d-y';
  $race=postradar($loguserid);

  $tablewidth='100%';
  $fonttag='<font class=font>';
  $fonthead='<font class=fonth>';
  $smallfont='<font class=fonts>';
  $tinyfont='<font class=fontt>';
  foreach(array(1,2,c,h) as $celltype){
    $cell="<td class='tbl tdbg$celltype font";
    $celln="tccell$celltype";
    $$celln     =$cell." center'";
    ${$celln.s} =$cell."s center'";
    ${$celln.t} =$cell."t center'";
    ${$celln.l} =$cell."'";
    ${$celln.r} =$cell." right'";
    ${$celln.ls}=$cell."s'";
    ${$celln.lt}=$cell."t'";
    ${$celln.rs}=$cell."s right'";
    ${$celln.rt}=$cell."t right'";
  }

	$inpt='<INPUT TYPE=TEXT NAME';
	$inpp='<INPUT TYPE=PASSWORD NAME';
	$inph='<INPUT TYPE=HIDDEN NAME';
	$inps='<INPUT TYPE=SUBMIT CLASS=SUBMIT NAME';
	$inpc="<input type=checkbox name";
	$radio='<INPUT TYPE=radio CLASS=radio NAME';
	$txta='<TEXTAREA WRAP=VIRTUAL NAME';
	$tblstart='<table class=table cellspacing=0>';
	$tblend='</table>';
  $sepn=array('Dashes','Line','Full horizontal line','None');
  $sep=array('<br><br>--------------------<br>',
		 '<br><br>____________________<br>',
		 '<br><br><hr>',
		 '<br><br>');
  $br='
';
  $css="
	<STYLE>
	A:link,A:visited,A:active,A:hover{text-decoration:none;font-weight:bold}
	A:HOVER{color:$linkcolor4;}
	body{
	 scrollbar-face-color:		$scr3;
	 scrollbar-track-color:		$scr7;
	 scrollbar-arrow-color:		$scr6;
	 scrollbar-highlight-color:	$scr2;
	 scrollbar-3dlight-color:	$scr1;
	 scrollbar-shadow-color:	$scr4;
	 scrollbar-darkshadow-color:	$scr5;
      }
	.font 	{font:13px $font}
	.fonth	{font:13px $font;color:$tableheadtext}
	.fonts	{font:10px $font2}
	.fontt	{font:10px $font3}
	.tdbg1	{background:#$tablebg1}
	.tdbg2	{background:#$tablebg2}
	.tdbgc	{background:#$categorybg}
	.tdbgh	{background:#$tableheadbg}
	.center	{text-align:center}
	.right	{text-align:right}
	.table	{empty-cells:	show;
			 border-top:	#$tableborder 1px solid;width:$tablewidth;
			 border-left:	#$tableborder 1px solid;width:$tablewidth;}
	td.tbl	{border-right:	#$tableborder 1px solid;
			 border-bottom:	#$tableborder 1px solid}
  ";
  $numcols=60;
  if($formcss){
    $numcols=80;
    $css.="
	textarea,input,select{
	  border:	#$inputborder solid 1px;
	  background:#000000;
	  color:	$textcolor;
	  font:	10pt $font;}
	.radio{
	  border:	none;
	  background:none;
	  color:	$textcolor;
	  font:	10pt $font;}
	.submit{
	  border:	#$inputborder solid 2px;
	  font:	10pt $font;}
    ";
  }
  $css.='</style>';

  if($loguserid){
    $headlinks='
        <a href=javascript:document.logout.submit()>Logout</a>
	| <a href=editprofile.php>Edit profile</a>';
    if(@mysql_result(mysql_query('SELECT count(*) FROM userpic'),0,0)) $headlinks.='
	| <a href=userpic.php>Avatars</a>';
    $headlinks.='
	| <a href=postradar.php>Post radar</a>
	| <a href=forum.php?fav=1>Favorites</a>';
  }else
    $headlinks='
	  <a href=register.php>Register</a>
	| <a href=login.php>Login</a>';
  $headlinks2="
	<a href=index.php>Main</a>
	| <a href=memberlist.php>Memberlist</a>
	| <a href=activeusers.php>Active users</a>
	| <a href=calendar.php>Calendar</a>
	| <a href=irc.php>IRC Chat</a>
	| <a href=online.php>Online users</a><br>
	<a href=ranks.php>Ranks</a>
	| <a href=faq.php>FAQ</a>
	| <a href=acs.php>ACS</a>
	| <a href=stats.php>Stats</a>
	| <a href='#' onclick=javascript:newwin=window.open('hex.php','hexadecimalchart','toolbar=no,scrollbars=no,status=no,width=320,height=170')>Color Chart</a>
<!--	| <a href=search.php>Search</a> -->
	| <a href=photo.php>Photo album</a>
  ";
  $views=mysql_result(mysql_query('SELECT views FROM misc'),0,0)+1;
  mysql_query("UPDATE misc SET views=$views");
  if($views%1000000>999000 or $views%1000000<1000){
    $u=($loguserid?$loguserid:0);
    mysql_query("INSERT INTO hits VALUES ($views,$u,'$userip',".ctime().')');
  }
  $count[u]=mysql_result(mysql_query('SELECT COUNT(*) FROM users'),0,0);
  $count[t]=mysql_result(mysql_query('SELECT COUNT(*) FROM threads'),0,0);
  $count[p]=mysql_result(mysql_query('SELECT COUNT(*) FROM posts'),0,0);
  mysql_query("INSERT INTO dailystats (date) VALUES ('".date('m-d-y',ctime())."')");
  mysql_query("UPDATE dailystats SET users=$count[u],threads=$count[t],posts=$count[p],views=$views WHERE date='".date('m-d-y',ctime())."'");
  updategb();

  $new='&nbsp;';
  if($log && strpos($PHP_SELF, "private.php") == false && strpos($PHP_SELF, "index.php") == 0){
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
		if ($pmsgnew != 1) $ssss = "s";
		if ($pmsgnew > 0) $privatebox="
		<tr><td colspan=3 class='tbl tdbg2 center fonts'>$new <a href=private.php>You have $pmsgnew new private message$ssss</a> -- $lastmsg
		";
		else $privatebox = "";
  }


  $body="<body bgcolor=$bgcolor text=$textcolor link=$linkcolor vlink=$linkcolor2 alink=$linkcolor3 background=$bgimage>";
  $header1="<html><head><title>$windowtitle</title><LINK REL=SHORTCUTICON HREF=favicon.ico>
	$css
	</head>
	$body
	<center>
	 $tblstart
	  <form action=login.php method=post name=logout><input type=hidden name=action value=logout></form>
	  <td class='tbl tdbg1 center'>$boardtitle</td>
	 $tblend$tblstart
	  <td colspan=3 class='tbl tdbg1 center fonts'>";
  $header2="
	  <tr>
	  <td width=120 class='tbl tdbg2 center fonts'><nobr>Views: $views<br><img src=images/_.gif width=120 height=1></td>
	  <td width=100% class='tbl tdbg2 center fonts'>$headlinks2</td>
	  <td width=120 class='tbl tdbg2 center fonts'><nobr>".date($dateformat,ctime()+$tzoff)."<br><img src=images/_.gif width=120 height=1><tr>
	  <td colspan=3 class='tbl tdbg1 center fonts'>$race
	  $privatebox
	 $tblend
	</center>
  ";
  function makeheader($header1,$headlinks,$header2){return $header1.$headlinks.$header2;}
  $ref=$HTTP_REFERER;
  if($ref && substr($ref,7,7)!="acmlm.o") mysql_query("INSERT INTO referer (url,ref) VALUES ('".addslashes($url)."', '".addslashes($ref)."')");
  $url=getenv('SCRIPT_URL');
  if(!$url) $url=str_replace('/etc/board','',getenv('SCRIPT_NAME'));
  $q=getenv('QUERY_STRING');
  if($q) $url.="?$q";

  if(mysql_result(mysql_query("SELECT count(*) FROM ipbans WHERE INSTR('$forwardedip',ip)=1"),0,0)) $ipbanned=1;
  if(mysql_result(mysql_query("SELECT count(*) FROM ipbans WHERE INSTR('$clientip',ip)=1"),0,0)) $ipbanned=1;
  if(mysql_result(mysql_query("SELECT count(*) FROM ipbans WHERE INSTR('$userip',ip)=1"),0,0)) $ipbanned=1;

  if($ipbanned) {
		$url='IP banned';
		setcookie('ipbanned',1,2147483647);
	}
  mysql_query("DELETE FROM guests WHERE ip='$userip' OR date<".(ctime()-300));
  if($log){
/*
    $ulastip=mysql_result(mysql_query("SELECT lastip FROM users WHERE id=$loguserid"),0,0);
    $aol1=(substr($userip,0,7)=='152.163' or substr($userip,0,7)=='205.188' or substr($userip,0,6)=='64.12.' or substr($userip,0,6)=='195.93' or substr($userip,0,6)=='198.81');
    $aol2=(substr($ulastip,0,7)=='152.163' or substr($ulastip,0,7)=='205.188' or substr($ulastip,0,6)=='64.12.' or substr($ulastip,0,6)=='195.93' or substr($ulastip,0,6)=='198.81');
    if($userip!=$ulastip && !($aol1 && $aol2)){
	$fpnt=fopen('ipchanges.log', 'a');
	$r=fputs($fpnt, "User $loguserid IP changed from $ulastip to $userip, on ".date($dateformat,ctime())."
");
	$r=fclose($fpnt);
    }
*/
if ($loguserid != 3 && $loguserid != 2) mysql_query("UPDATE users SET lastactivity=".ctime().",lastip='$userip',lasturl='".addslashes($url)."',lastforum=0 WHERE id=$loguserid");
    if($isadmin) $headlinks="<s>Admin</s> | $headlinks";
  }else{
     mysql_query("INSERT INTO guests (ip,date,lasturl) VALUES ('$userip',".ctime().",'".addslashes($url)."')");
  }
  $header=makeheader($header1,$headlinks,$header2);
  $footer="
	</textarea></form></embed></noembed></noscript></noembed></embed></table></table>
	<center>$smallfont
	<br><br><a href=$siteurl>$sitename</a>
	<br>$affiliatelinks	
	<br><img src=images/poweredbyacmlm.gif>
	<br>AcmlmBoard v1.92.08 ~ 12-06-2005
	<br><small>©2000-2005 Acmlm, Emuz, Blades, Xkeeper</small>
		</body></html>
  ";
  if($ipbanned) die("$header<br>$tblstart$tccell1>Your IP address has been banned from this board.$tblend$footer");
?>