<?php
  function userfields(){return 'posts,sex,powerlevel';}

  function postcode($post,$set){
    global $smallfont,$ip,$quote,$edit;

    $postnum=($post[num]?"$post[num]/":'').$post[posts];
    if($set[threadlink]) $threadlink=", in $set[threadlink]";
    return "
	$set[tdbg] rowspan=2>
	  $set[userlink]<br>
	  $smallfont Posts: $postnum</td>
	$set[tdbg] width=80%>
	  <table cellspacing=0 cellpadding=2 width=100% class=fonts>
	    <td>Posted on $set[date]$threadlink</td>
	    <td width=255><nobr>$quote$edit$ip
	  </table><tr>
	$set[tdbg] height=60>$post[headtext]$post[text]$post[signtext]$set[edited]</td>
    ";
  }
?>