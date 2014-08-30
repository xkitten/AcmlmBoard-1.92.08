<?php

function threadpost($post,$bg,$pthread=''){
  global $loguser,$quote,$edit,$ip,$smallfont,$tzoff,$sep,$dateformat,$dateshort,$postl,$tlayout,${"tablebg$bg"};
  $post=setlayout($post);
  $p=$post[id];
  $u=$post[uid];
  $namecolor=getnamecolor($post[sex],$post[powerlevel]);
  $set[bg]=${"tablebg$bg"};
  $set[tdbg]="<td class='tbl font tdbg$bg' valign=top";
  $set[userrank]=getrank($post[useranks],$post[title],$post[posts],$post[powerlevel]);
  $set[userlink]="<a name=$p><a href=profile.php?id=$u><font $namecolor>$post[name]</font></a></a>";
  $set[date]=date($dateformat,$post[date]+$tzoff);
  if($post[location]) $set[location]="<br>From: $post[location]";
  if($post[picture]){
    $post[picture]=str_replace('>','%3E',$post[picture]);
//   $userpicture="<img src=\"$user[picture]\" name=pic$p onload=sizelimit(pic$p,60,100)>";
    $set[userpic]="<img width=60 src=\"$post[picture]\">";
  }
  if($post[edited]){
    $set[edited]=($post[signtext])?'':'<br><br>';
    $set[edited].="<hr>$smallfont$post[edited]";
  }
  if($post[signtext]) $post[signtext]=$sep[$loguser[signsep]].$post[signtext];
  if($pthread) $set[threadlink]="<a href=thread.php?id=$pthread[id]>$pthread[title]</a>";
  $post[text]=doreplace2($post[text], $post[options]);
  $return=dofilters(postcode($post,$set));
  return $return;
}

function setlayout($post){
  global $loguser,$postl;
  if($loguser[viewsig]!=1) $post[headid]=$post[signid]=0;
  if(!$loguser[viewsig]){
	    $post[headtext]=$post[signtext]='';
	    return $post;
 	 }
  $post[tagval].='°»';
  if($loguser[viewsig]!=2){
	    if($headid=$post[headid]){
			if(!$postl[$headid]) $postl[$headid]=mysql_get("SELECT text FROM postlayouts WHERE id=$headid");
			$post[headtext]=$postl[$headid][text];
		    }
	    if($signid=$post[signid]){
			if(!$postl[$signid]) $postl[$signid]=mysql_get("SELECT text FROM postlayouts WHERE id=$signid");
			$post[signtext]=$postl[$signid][text];
		    }
	  }
	  $post[headtext]=settags($post[headtext],$post[tagval]);
	  $post[signtext]=settags($post[signtext],$post[tagval]);
	  if($loguser[viewsig]==2){
		    $post[headtext]=doreplace($post[headtext],$post[num],($post[date]-$post[regdate])/86400,$post[name],1);
		    $post[signtext]=doreplace($post[signtext],$post[num],($post[date]-$post[regdate])/86400,$post[name],1);
		  }
	  $post[headtext]=doreplace2($post[headtext]);
	  $post[signtext]=doreplace2($post[signtext]);
//	  $post[text]=doreplace2($post[text], $post[options]);
	  return $post;
}

function syndrome($num){
  $a='>Affected by';
  if($num>=75)  $syn="83F3A3$a 'Reinfors Syndrome'";
  if($num>=100) $syn="FFE323$a 'Reinfors Syndrome' +";
  if($num>=150) $syn="FF5353$a 'Reinfors Syndrome' ++";
  if($num>=200) $syn="CE53CE$a 'Reinfors Syndrome' +++";
  if($num>=250) $syn="8E83EE$a 'Reinfors Syndrome' ++++";
  if($num>=300) $syn="BBAAFF$a 'Wooster Syndrome'!!";
  if($num>=350) $syn="FFB0FF$a 'Wooster Syndrome' +!!";
  if($num>=400) $syn="FFB070$a 'Wooster Syndrome' ++!!";
  if($num>=450) $syn="C8C0B8$a 'Wooster Syndrome' +++!!";
  if($num>=500) $syn="A0A0A0$a 'Wooster Syndrome' ++++!!";
  if($num>=500) $syn="A0A0A0$a 'Wooster Syndrome' ++++!!";
  if($num>=600) $syn="C762F2$a 'Anya Syndrome'!!!";
  if($num>=800) $syn="D06030$a 'Something higher than Anya Syndrome' +++++!!";
  if($syn) $syn="<br><i><font color=$syn</font></i>";
  return $syn;
}

function dofilters($p){
  $p=preg_replace("'<script(.*?)</script>'si",'',$p);
  $p=preg_replace("'<script'si",'<<z>script',$p);
  $p=preg_replace("'onload='si",'onload',$p);
  $p=preg_replace("'onhover='si",'onhover',$p);
  $p=preg_replace("'onfail='si",'onfail',$p);
  $p=preg_replace("'<script'si",'<<z>script',$p);
  $p=preg_replace("'<iframe'si",'<<z>iframe',$p);
  $p=preg_replace("'filter:alpha'si",'f·alpha',$p);
  $p=preg_replace("'filter:'si",'x:',$p);
  $p=preg_replace("'f·alpha'si",'filter:alpha',$p);
  $p=preg_replace("'zeon'si",'shit',$p);
  return $p;
}

?>