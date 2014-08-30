<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  print "$header<br>$tblstart";
  if(!$action and $log){
    $rat="$radio=rating value";
    $ratelist="
	$rat=0> 0 &nbsp
	$rat=1> 1 &nbsp
	$rat=2> 2 &nbsp
	$rat=3> 3 &nbsp
	$rat=4> 4 &nbsp
	$rat=5> 5 &nbsp
	$rat=6> 6 &nbsp
	$rat=7> 7 &nbsp
	$rat=8> 8 &nbsp
	$rat=9> 9 &nbsp
	$rat=10> 10
    ";
    print "
	<FORM ACTION=rateuser.php NAME=REPLIER METHOD=POST>
	$tccellh width=150>&nbsp</td>
	$tccellh>&nbsp<tr>
	$tccell1><b>Rating:</b></td>	$tccell2l>$ratelist<tr>
	$tccell1>&nbsp</td>		$tccell2l>
	$inph=action VALUE=rateuser>
	$inph=userid VALUE=$id>
	$inps=submit VALUE='Give rating!'></td></FORM>
    "; 
  }
  if($_POST[action]=='rateuser' && $userid!=$loguserid){
    if($rating<0) $rating=0;
    if($rating>10) $rating=10;
    mysql_query("DELETE FROM userratings WHERE userfrom=$loguserid AND userrated=$userid");
    mysql_query("INSERT INTO userratings (userfrom,userrated,rating) VALUES ($loguserid,$userid,$rating)");
    print "$tccell1>Thank you, $loguser[name], for rating this user.<br>".redirect('index.php','return to the board',0);
  }
  if($action=='viewvotes' && $isadmin){
    $users=mysql_query('SELECT id,name FROM users');
    while($user=mysql_fetch_array($users)) $username[$user[id]]=$user[name];
    $ratings=mysql_query("SELECT userfrom,userrated,rating FROM userratings WHERE userrated=$id");
    if(@mysql_num_rows($ratings)) while($rating=mysql_fetch_array($ratings)) $fromlist.="<b>$rating[rating]</b> from ".$username[$rating[userfrom]].'<br>';
    else $fromlist="None.";
    $ratings=mysql_query("SELECT userfrom,userrated,rating FROM userratings WHERE userfrom=$id");
    if(@mysql_num_rows($ratings)) while($rating=mysql_fetch_array($ratings)) $votelist.="<b>$rating[rating]</b> for ".$username[$rating[userrated]].'<br>';
    else $votelist='None.';
    print "
	$tccellh><b>Votes for $username[$id]:</b></td>
	$tccellh><b>Votes from $username[$id]:</b>
	<tr valign=top>
	$tccell1l>$fromlist</td>
	$tccell1l>$votelist
    ";
  }
  print $tblend.$footer;
  printtimedif($startingtime);
?>