<?php
  function userfields(){return 'posts,sex,powerlevel,picture,title,useranks,location,lastposttime,lastactivity,imood';}

  function postcode($post,$set){
    global $tzoff,$smallfont,$ip,$quote,$edit,$dateshort,$dateformat,$tlayout,$textcolor,$numdir,$numfil;
    $exp=calcexp($post[posts],(ctime()-$post[regdate])/86400);
    $lvl=calclvl($exp);
    $expleft=calcexpleft($exp);
    if($tlayout==2){
	$level="Level: $lvl";
	$poststext="Posts: ";
	$postnum="$post[num]/";
	$posttotal=$post[posts];
	$experience="EXP: $exp<br>For next: $expleft";
    }else{
	$level="<img src=images/$numdir"."level.gif width=36 height=8><img src=numgfx.php?n=$lvl&l=3&f=$numfil height=8>";
	$experience="<img src=images/$numdir"."exp.gif width=20 height=8><img src=numgfx.php?n=$exp&l=5&f=$numfil height=8><br><img src=images/$numdir"."fornext.gif width=44 height=8><img src=numgfx.php?n=$expleft&l=2&f=$numfil height=8>";
	$poststext="<img src=images/_.gif height=2><br><img src=images/$numdir"."posts.gif width=28 height=8>";
	$postnum="<img src=numgfx.php?n=$post[num]/&l=5&f=$numfil height=8>";
	$posttotal="<img src=numgfx.php?n=$post[posts]&f=$numfil".($post[num]?'':'&l=4')." height=8>";
	$totalwidth=56;
	$barwidth=$totalwidth-round(@($expleft/totallvlexp($lvl))*$totalwidth);
	if($barwidth<1) $barwidth=0;
	if($barwidth>0) $baron="<img src=images/$numdir"."bar-on.gif width=$barwidth height=8>";
	if($barwidth<$totalwidth) $baroff="<img src=images/$numdir".'bar-off.gif width='.($totalwidth-$barwidth).' height=8>';
	$bar="<br><img src=images/$numdir"."barleft.gif width=2 height=8>$baron$baroff<img src=images/$numdir".'barright.gif width=2 height=8>';
    }
    if(!$post[num]){
	$postnum='';
	if($postlayout==1) $posttotal="<img src=numgfx.php?n=$post[posts]&f=$numfil&l=4 height=8>";
    }
    if($post[icq]) $icqicon="<a href=http://wwp.icq.com/$post[icq]#pager><img src=http://wwp.icq.com/scripts/online.dll?icq=$post[icq]&img=5 border=0 width=13 height=13 align=absbottom></a>";
    if($post[imood]) $imood="<img src=http://www.imood.com/query.cgi?email=$post[imood]&type=1&fg=$textcolor&trans=1 height=15 align=absbottom>";
    $reinf=syndrome($post[act]);
    $sincelastpost='Since last post: '.timeunits(ctime()-$post[lastposttime]);
    $lastactivity='Last activity: '.timeunits(ctime()-$post[lastactivity]);
    $since='Since: '.@date($dateshort,$post[regdate]+$tzoff);
    $postdate=date($dateformat,$post[date]+$tzoff);
    if($set[threadlink]) $threadlink=", in $set[threadlink]";
    return "
	$set[tdbg] rowspan=2>
	  $set[userlink]</a>$smallfont<br>
	  $set[userrank]$reinf<br>
        $level$bar<br>
	  $set[userpic]<br>
	  $poststext$postnum$posttotal<br>
	  $experience<br><br>
	  $since$set[location]<br><br>
	  $sincelastpost<br>$lastactivity<br>
	  $icqicon$imood</font>
	</td>
	$set[tdbg] height=1 width=80%>
	  <table cellspacing=0 cellpadding=2 width=100% class=fonts>
	    <td>Posted on $postdate$threadlink</td>
	    <td width=255><nobr>$quote$edit$ip
	  </table><tr>
	$set[tdbg] height=220>$post[headtext]$post[text]$post[signtext]$set[edited]</td>
    ";
  }
?>