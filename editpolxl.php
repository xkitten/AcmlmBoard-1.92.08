<?php
  require "function.php";
  require "layout.php";

function aslash($text) {
 $text2=str_replace("\"","&#34",$text);
 $text2=stripslashes($text2);
 return $text2;
}

  $forums=readforums();
  $users1=mysql_query("SELECT id,posts,regdate,name,password,powerlevel,signature,postheader FROM users");
  while($user=mysql_fetch_array($users1)) $users[$user[id]]=$user;
  $ranks=readranks();
  $smilies=readsmilies();
  $inumsmilies=numsmilies();
  $tccellha="<td bgcolor=$tableheadbg";
  $tccellhb="><center>$fonthead";
  print $header;
  replytoolbar(1);
    print "
    <table bgcolor=$tableborder width=$tablewidth cellpadding=0 cellspacing=0>
     <td>
      <table cellpadding=2 cellspacing=1 width=100%>
    ";
    $userid=checkuser($username,$password);
    $user=$users[$userid];
    $username=$users[$loguserid][name];
/*    if($user[powerlevel]<0) $userid=-1;
    if($userid!=-1){   */
     $caneditpoll=0;
     if($ismod or $loguserid==$thread[user]) $caneditpoll=1;
     $mods=mysql_query("SELECT user FROM forummods WHERE forum=$forumid and user=$loguserid");
     if(@mysql_num_rows($mods)>0 and $logpwenc) $caneditpoll=1;
     if ($caneditpoll) {
    if($submit){
        $currenttime=ctime();
        $postnum=$user[posts]+1;
        if ($doublevote == "yes") { $doublevote = 1; } else { $doublevote=0; }
        if ($closed == "yes") { $closed = 1; } else { $closed=0; }
        mysql_query("UPDATE poll SET question='".addslashes($pollquestion)."',briefing='".addslashes($pollbriefing)."',closed=$closed,doublevote=$doublevote WHERE id=$id");
        mysql_query("DELETE FROM poll_choices WHERE poll=$id");
        foreach ($pollchoices as $chid => $data) {
          $ccolor = $data[color];
          print "<!-- $chid» color: $ccolor | name: $data[choice] | poll: $id -->";
          $cname = $data[choice];
          mysql_query("INSERT INTO poll_choices (choice,color,poll) VALUES ('".addslashes($cname)."','".addslashes($ccolor)."',$id)");
          $moo = mysql_affected_rows();
          print "<!-- $chid» affected rows: $moo -->";
        }
        $getid = mysql_fetch_array(mysql_query("SELECT id FROM threads WHERE pollid=$id LIMIT 1"));
        $getid = $getid[id];
        print "
		$tccell1 Thank you, $username, for editing the poll.
		<br>".redirect("thread.php?id=$getid","go back to the poll",0)."</table></table>";
    } else {
      
        if (!$firsttimepassed) {
          $getpolldata = mysql_query("SELECT question,briefing,closed,doublevote FROM poll WHERE id=$id");
          while($data=mysql_fetch_array($getpolldata)){
            $pollquestion = $data['question'];
            $pollbriefing = $data['briefing'];
            $pollclosed = $data['closed'];
            $polldbl = $data['doublevote'];
            if ($polldbl == 1) { $polldbl = "yes"; } else { $polldbl = "no"; }
            if ($pollclosed == 1) { $closed = "yes"; }
          }

          $getchoices = mysql_query("SELECT id,poll,choice,color FROM poll_choices WHERE poll=$id");
          while($data=mysql_fetch_array($getchoices)){
          $choiceno = count($pollchoices)+1;
          if (count($pollchoices) == 1 && !is_array($pollchoices)) { $choiceno = 1; }
            $choicebyid[$data[id]] = $data[choice];
            $pollchoices[$choiceno] = array('color' => $data[color],
                                   'choice' => $data[choice],
                                   'id' => $data[id]);
          }
          
          $getvotes = mysql_query("SELECT id,choice,user FROM poll_votes WHERE poll=$id");
          while($data=mysql_fetch_array($getvotes)){
            $votes[$data[$id]] = array('user' => $data[user],
                                       'choice' => $data[choice]);
            $votesname[$data[$id]] = $data[user];
            print "<!-- $data[id]: user $data[user] | choice $data[choice] -->";
          }
          
        }

        $getusr = mysql_query("SELECT id,name FROM users");
        while($data=mysql_fetch_array($getusr)){
          $voteusers[$data[id]] = $data[name];
        }
      
        if ($addchoice) {
          $choiceno = count($pollchoices)+1;
          if (count($pollchoices) == 1 && !is_array($pollchoices)) { $choiceno = 1; }
          $pollchoices[$choiceno] = array('color' => $polladdcolor,
                                          'choice' => $polladdchoice);
        }
        
        if ($removechoice) {
          $pc2 = $pollchoices;
          foreach($pc2 as $ccid=>$data) {
            if ($ccid != $torem) {
              $pc3[$ccid] = $data;
            }
          }
          $pollchoices = $pc3; 
/*          $key_index = array_keys(array_keys($pollchoices), array_pop($removechoice));
          array_splice($pollchoices, $key_index[0], 1); */
          mysql_query("DELETE FROM poll_choices WHERE id=$torem");
        }
        
/*        if ($removevote) {
          $torem = array_pop($removevote);
          $key_index = array_keys(array_keys($votes), $torem);
          array_splice($votes, $key_index[0], 1);
          foreach($votes as $vid => $val) {
            $newvotes[$vid] = $val;
          }
          $votes = $newvotes;
          mysql_query("DELETE FROM poll_votes WHERE id=$torem");
          if (count($votes)==0 && isset($votes)) { unset($votes); }
        } */
        
	print "<body onload=window.document.REPLIER.pollquestion.focus()>
	$tccellha width=250$tccellhb&nbsp</td>$tccellh&nbsp
		<FORM ACTION=editpoll.php NAME=REPLIER METHOD=POST>
		$inph=firsttimepassed VALUE=1>
		$inph=torem VALUE=\"\">
		$inph=id VALUE=\"$id\">";
        
        if ($polldbl == "yes") { 
          $doublevote = "$radio=polldbl VALUE=\"yes\" CHECKED> Allow double voting
                         $radio=polldbl VALUE=\"no\"> Don't allow double voting";
        } else {
          $doublevote = "$radio=polldbl VALUE=\"yes\"> Allow double voting
                         $radio=polldbl VALUE=\"no\" CHECKED> Don't allow double voting";
        }
        if ($pollclosed == "yes") { 
          $closed = "$radio=pollclosed VALUE=\"yes\" CHECKED> Voting is closed
                         $radio=pollclosed VALUE=\"no\"> Voting is not closed";
        } else {
          $closed = "$radio=pollclosed VALUE=\"yes\"> Voting is closed
                         $radio=pollclosed VALUE=\"no\" CHECKED> Voting is not closed";
        }
        $tcheader = "$tccellha colspan=2 $tccellhb";
        $halfcols = $numcols/2;
        print "<tr>$tcheader Poll setup<tr>
               $tccell1<b>Poll question: $tccell2l$inpt=pollquestion VALUE=\"".aslash($pollquestion)."\"><tr>
               $tccell1<b>Poll briefing: $tccell2l$txta=pollbriefing ROWS=10 COLS=".$halfcols.">$pollbriefing</textarea><tr>
               $tccell1<b>Poll options: $tccell2l$doublevote<br>$closed<tr>
               $tcheader Choices<tr>";

        if (is_array($pollchoices)) {
          $m=0;
          foreach($pollchoices as $cid => $data) {
            $m++;
            $choicescode .= "$tccell1<b>Choice ".$m."$tccell2l$inpt=\"pollchoices[$cid][choice] \" VALUE=\"".aslash($data['choice'])."\"> Color: $inpt=\"pollchoices[$cid][color]\" VALUE=\"".aslash($data['color'])."\"> $inps=\"removechoice\" onClick=\"window.document.REPLIER.torem.value='$cid';\" VALUE=\"Remove\"><tr>";
          }
        }
          print "$choicescode
                 $tccell1<b>Add choice$tccell2l$inpt=\"polladdchoice\" VALUE=\"\"> Color: $inpt=\"polladdcolor\" VALUE=\"\"> $inps=\"addchoice\" VALUE=\"Add choice\">";
        
/*        if (is_array($votes) && !empty($votes)) {
          $votescode .= "<tr>$tcheader Votes";
          foreach($votes as $vid => $data) {
            $votescode .= "<tr>$tccell1&nbsp$tccell2l".$choicebyid[$data[choice]]." -- ".$voteusers[$votesname[$vid]]."$inph=votes[$vid][choice] VALUE=\"".$data['choice']."\">$inph=votes[$cid][id] VALUE=\"".$data['id']."\"> $inps=removevote[$cid] VALUE=\"Remove\">";
          }
        }
                 
                 
                print "$votescode";

                print "<tr>$tccell1&nbsp$tccell2l<pre>";
                print_r ($votesname);
                print "<hr>";
                print_r ($voteusers);
*/
                print "<tr>$tccell1&nbsp$tccell2l$inps=submit VALUE=\"Edit poll\"><tr></td></form></table></table>";
      }
  }else{
      print "
	  $tccell1 Couldn't enter the post. Either you didn't enter an existing username,
	  or you haven't entered the right password for the username, or you haven't entered a subject.
	  <br>".redirect("forum.php?id=$id","return to the forum",0)."</table></table>";
  }
  print $footer;
  printtimedif($startingtime);
?>