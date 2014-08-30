<?php

	  global $debug;
	  $debug = 0;
	  function is_debug() {
	    global $debug;
	    if ($debug == 1) { return true; } else { return false; }
	  }
	  function debugprint($str) {
	    global $debug;
	    if (is_debug()) { print $str; }
	  }


  require_once 'lib/libs.php';
  $threads=mysql_query("SELECT forum,closed,title,icon,replies,lastpostdate,lastposter,sticky,locked,poll,user FROM threads WHERE id=$id");
  $thread=mysql_fetch_array($threads);
  $forumid=$thread[forum];
  $posticons=file('posticons.dat');
  $mods=mysql_query("SELECT user FROM forummods WHERE forum=$forumid and user=$loguserid");
  print "$header<br>$tblstart";
  if(@mysql_num_rows($mods)) $ismod=1;
  $pollexists = 0;
  $pollq=mysql_query("SELECT id,question,briefing,closed,doublevote FROM poll WHERE id=".$thread['poll']);
  if(@mysql_num_rows($pollq)) $pollexists=1;
  if(
     (
      (!$action && $ismod && 
       (!$thread[locked] || !$bconf[lockable])
      )
      ||
      (!$action && $isadmin)
      || 
      (!$action && $thread[user] == $loguserid && !$thread[closed])
     ) && $pollexists)
    {
    
    $poll=mysql_fetch_array($pollq);

    $pollc=mysql_query("SELECT id,choice,color FROM poll_choices WHERE poll=".$thread['poll']);
    $choices = ""; $votedfor = 0; 
    while($ch=mysql_fetch_array($pollc)) {
        $voted = 0; $numvotes = 0;
        $chvq = "SELECT user FROM pollvotes WHERE poll=".$thread['poll']." AND choice=".$ch['id'];
        print "<!-- $chvq -->";
        $chv=mysql_query($chvq);
        if(@mysql_num_rows($chv)) { $voted=1; $numvotes = mysql_num_rows($chv); }
        if ($choices!="") $choices .= "<br>";
        $choices.="$inpt=\"choice[".$ch['id']."]\" value=\"".htmlentities($ch['choice'])."\"> (Color: $inpt=\"choicecolor[".$ch['id']."]\" value=\"".htmlentities($ch['color'])."\" size=6 maxlength=6>)"; 
        if (!$voted) $choices.="&nbsp;&nbsp;&nbsp;<INPUT type=checkbox class=radio name=choiceremove[".$ch['id']."] value=1> Remove";
              else { $choices.="&nbsp;&nbsp;&nbsp;$numvotes vote".($numvotes!=1?"s":""); $votedfor = 1; }
    }
    if ($votedfor) { $choices.="<br><br>(Choices that have been voted for can't be removed.)"; }
    $newchoices = "<script><!--
    
    var usingBetterWay = 0;
    
    
    function deleteRow(i)
{
document.getElementById(idOfTable).deleteRow(i);
}

var idOfTable = 'choiceTable';
var sbuttons = '<input type=\"button\" onclick=\"insRow(this.parentNode.parentNode.rowIndex)\" value=\"+\"><input type=\"button\" value=\"-\" onclick=\"deleteRow(this.parentNode.parentNode.rowIndex)\">';
var sinputs = '<input type=\"text\" name=\"choiceaddjs!!!\" id=\"!!!\" value=\"\"> (Color: <input type=\"text\" name=\"choicecoloraddjs[!!!]\" value=\"\" size=6 maxlength=6>)';

function plog(f) {
".(is_debug()?"
  document.getElementById('debug').innerHTML += \"<br>\"+f;":"")."
}

function isWebKit() {
  if (navigator.userAgent.toLowerCase().indexOf(\"safari\") != -1 || navigator.userAgent.toLowerCase().indexOf(\"webkit\") != -1) { return true; }
}

function insRow(qq)
{
plog (\"q: \"+qq+\" rl: \"+document.getElementById(idOfTable).rows.length);
qq++;
if (isWebKit() && (qq + 1 != document.getElementById(idOfTable).rows.length) && (qq + 1 >= document.getElementById(idOfTable).rows.length)) { if (qq == 0) { qq++; } plog(\"safari corr: q = \"+qq+\" length: \"+document.getElementById(idOfTable).rows.length); if (document.getElementById(idOfTable).rows.length == qq) { qq++; plog(\"safari corr 2, equal: q = \"+qq); } if (document.getElementById(idOfTable).rows.length + 1 == qq && qq != 2) { qq = -1; plog(\"safari corr 3, equal: q = \"+qq); } } // Fix for Safari/WebKit's messed up handling of adding rows. I'm happy to announce that I have -absolutely no idea- what it does either, but it seems to work.
var i=Date.parse(new Date().toString());
var x=document.getElementById(idOfTable).insertRow(qq);
var y=x.insertCell(0);
var z=x.insertCell(1);
if (document.getElementById(qq+'-'+i)) {
  while(document.getElementById(qq+'-'+i)) {
    i++;
  }
}
z.innerHTML=sbuttons;
z.className='font';
inputsx = sinputs;
inputsy = inputsx.replace(/!!!/g, 'po'+qq+i);
y.innerHTML=inputsy;
y.className='font';
}
function writeDynamicAdding() {
    document.getElementById('js-adding').innerHTML = '<table id=\"'+idOfTable+'\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr><td width=300></td><td><input type=\"button\" onclick=\"insRow(this.parentNode.parentNode.rowIndex)\" value=\"+\"></td></tr></table>';
    document.getElementById('nojs-adding').style.display = 'none';
}

function startGather() {
  window.setTimeout('gatherAndPost()',1);
  return false;
}

function gatherAndPost() {
  if (document.getElementById('realform')) { document.getElementById('realform').id = 'oldform'; }
  var pf = document.createElement('form');
  pf.setAttribute('action','editpoll.php');
  pf.setAttribute('method','POST');
  pf.setAttribute('style','display: none;');
  pf.setAttribute('id','realform');
  plog('gatherAndPost got called');
  var thr; var passedSubmit = 0; var xx = 0;
  for(i=0; (tag = document.body.getElementsByTagName('input')[i]); i++) {
    if(tag.className == 'donotreadd' || tag.getAttribute('class') == 'donotreadd' || passedSubmit) {  } else {
      if (tag.getAttribute('name')) {
        thr = document.createElement('input');
        thr.setAttribute('class','donotreadd');
        if (tag.getAttribute('name').indexOf('choiceaddjs') == 0) {
          thr.setAttribute('name','choiceaddjs['+xx+']');      
        } else if (tag.getAttribute('name').indexOf('choicecoloraddjs') == 0) {
          thr.setAttribute('name','choicecoloraddjs['+xx+']');      
          xx++;
        } else {
          thr.setAttribute('name',tag.getAttribute('name'));
        }
        thr.setAttribute('value',tag.value); // nasty, nasty! but getAttribute only nets us the initial value in Mozilla. dig we must. //tag.getAttribute('value'));
        if (tag.getAttribute('type').indexOf('radio') != -1 || tag.getAttribute('type').indexOf('checkbox') != -1) {
          if (tag.checked == '1' || tag.checked == 'true' || tag.checked == 'checked') {
            thr.setAttribute('type','hidden');
            pf.appendChild(thr);
            plog('adding input item, checked: '+tag.checked+' name '+tag.getAttribute('name')+' value 1'+tag.value+' value 2'+tag.getAttribute('value')+' type '+tag.getAttribute('type'));
          }
        } else {
            thr.setAttribute('type',tag.getAttribute('type'));
            pf.appendChild(thr);        
            plog('adding input item, name '+tag.getAttribute('name')+' value 1'+tag.value+' value 2'+tag.getAttribute('value')+' type '+tag.getAttribute('type'));
        }
        if (tag.getAttribute('type').toLowerCase() == 'submit') { plog('just passed submit'); passedSubmit = 1; }
      }
    }
  }
  thr = document.createElement('input');
  thr.setAttribute('name','createdWithPassthru');
  thr.setAttribute('value','yes');
  thr.setAttribute('type','hidden');
  pf.appendChild(thr);  
  thr = document.createElement('input');
  thr.setAttribute('name','submittedWithPassthru');
  thr.setAttribute('value','foobar');
  thr.setAttribute('type','submit');
  pf.appendChild(thr);  
  document.body.appendChild(pf);
  window.setTimeout('doPosting()',20);
  
  return false;
}
function doPosting() {
  document.getElementById('realform').submit();
//  return false;
}
//--></script><div id=\"nojs-adding\" style=\"display:block;\">$inpt=\"choiceadd[]\"> (Color: $inpt=\"choicecoloradd[]\" size=6 maxlength=6>)&nbsp;&nbsp;&nbsp;<INPUT type=checkbox class=radio name=choiceadddoadd value=1> Add</div><div id=\"js-adding\"><script><!--\nif (document.getElementById('nojs-adding')) { writeDynamicAdding(); usingBetterWay = 1; } //--></script></div><div id=\"debug\"><b></b></div>";

    $thread[icon]=str_replace("","",$thread[icon]);
    $customicon=$thread[icon];
    for($i=0;$posticons[$i];){
      $posticons[$i]=str_replace($br,"",$posticons[$i]);
	if($thread[icon]==$posticons[$i]){
	  $checked='checked=1';
	  $customicon='';
	}
	$posticonlist.="<INPUT type=radio class=radio name=iconid value=$i $checked>&nbsp<IMG SRC=$posticons[$i] HEIGHT=15 WIDTH=15>&nbsp &nbsp";
	$i++;
	if($i%10==0) $posticonlist.='<br>';
	$checked='';
    }
    if(!$thread[icon]) $checked='checked=1';
    $posticonlist.="
	<br>$radio=iconid value=-1 $checked>&nbsp None &nbsp &nbsp
	Custom: $inpt=custposticon VALUE='$customicon' SIZE=40 MAXLENGTH=100>
    ";
    $check1[$poll[closed]]='checked=1';
    $check2[$poll[doublevote]]='checked=1';
    $forums=mysql_query("SELECT id,title FROM forums WHERE minpower<=$power ORDER BY forder");
    while($forum=mysql_fetch_array($forums)){
	$checked='';
	if($thread[forum]==$forum[id]) $checked='selected';
	$forummovelist.="<option value=$forum[id] $checked>$forum[title]</option>";
    }

    $onsubmithook = '="if(usingBetterWay){startGather();return false;}else{alert(\'was going to post\');return false;}"';
    print "
	 <FORM ACTION=editpoll.php NAME=REPLIER id=\"mainform\" METHOD=POST onsubmit$onsubmithook>
	 $tccellh colspan=2>Editing poll for thread <b>".($thread[icon]!=""?"<img src=\"$thread[icon]\" />&nbsp;":"").$thread['title']."</b><tr>
	 $tccell1><b>Poll question:</b></td>	$tccell2l>$inpt=pollqu VALUE=\"$poll[question]\" SIZE=40 MAXLENGTH=100><tr>
	 $tccell1><b>Poll briefing:</b></td>	$tccell2l>$inpt=pollbr VALUE=\"$poll[briefing]\" SIZE=40 MAXLENGTH=100><tr>
	 $tccell1><b>Poll status:</b></td>	$tccell2l>$radio=pollclosed value=0 $check1[0]> Open&nbsp &nbsp$radio=pollclosed value=1 $check1[1]>Closed<tr>
							$tccell1><b>Multiple voting:</b></td>		$tccell2l>$radio=polldbl value=0 $check2[0]> Disabled&nbsp &nbsp$radio=polldbl value=1 $check2[1]>Enabled<tr>
							$tccell1><b>Choices:</b></td>		$tccell2l>$choices<tr>
							$tccell1><b>New choices:</b></td>		$tccell2l>$newchoices<tr>
"./*	 $tccell1><b>Forum</b></td>		$tccell2l><select name=forummove>$forummovelist</select> <INPUT type=checkbox class=radio name=delete value=1>Delete thread<tr>*/"
	 $tccell1>&nbsp</td>$tccell2l>
	 $inph=action VALUE=editpoll>$inph=id VALUE=$id>
	 $inps=doedit onclick$onsubmithook VALUE=\"Edit poll\"></td></FORM>
	$tblend
   ";
  }
  if($_POST[action]=='editpoll'){
    if(($ismod && (!$thread[locked] || !$bconf[lockable])) || ($isadmin) || ($thread[user] == $loguserid && !$thread[closed])){
	print "
	  $tccell1>Thank you, $loguser[name], for editing the poll.<br>
	  ";
	  if (!is_debug()) { print redirect("thread.php?id=$id",'return to the thread',0); }
	  
	  debugprint("$tblend$tblstart$tccellh>Edit poll debug data<tr>$tccell1l><pre>");

      $divisor = "----------------------------------";

	  $ch = $co = "";
	  debugprint("WOULD HAVE INSERTED:<br><br>");
	  
	  if($createdWithPassthru == "yes") {
	    if (is_array($choiceaddjs)) {
      	  debugprint("JAVASCRIPT INSERTS:<br>------<br>");
      	  foreach($choiceaddjs as $i=>$ch) {
            $co = $choicecoloraddjs[$i];                   // cleaning poll color:
      	    $co = preg_replace("'^#(.*?)$'", "\\1", $co);  // remove prefix # if given
            $co = preg_replace("'[^a-zA-Z0-9]'", "", $co); // only alphanumerics, please
            $co = substr($co, 0, 6);                       // and cut at six chars of length
        	debugprint("INSERT INTO poll_choices (id,poll,choice,color) VALUES (NULL,".$thread['poll'].",'".addslashes(stripslashes($ch))."','".addslashes(stripslashes($co))."')<br>");
        	mysql_query("INSERT INTO poll_choices (id,poll,choice,color) VALUES (NULL,".$thread['poll'].",'".addslashes(stripslashes($ch))."','".addslashes(stripslashes($co))."')");
      	  }
      	}
	  } else {
	    if (is_array($choiceadd) && $choiceadddoadd) {
      	  debugprint("NON-JAVASCRIPT INSERTS:<br>------<br>");
      	  foreach($choiceadd as $i=>$ch) {
            $co = $choicecoloradd[$i];                   // cleaning poll color:
      	    $co = preg_replace("'^#(.*?)$'", "\\1", $co);  // remove prefix # if given
            $co = preg_replace("'[^a-zA-Z0-9]'", "", $co); // only alphanumerics, please
            $co = substr($co, 0, 6);                       // and cut at six chars of length
        	debugprint ("INSERT INTO poll_choices (id,poll,choice,color) VALUES (NULL,".$thread['poll'].",'".addslashes(stripslashes($ch))."','".addslashes(stripslashes($co))."')<br>");
        	mysql_query("INSERT INTO poll_choices (id,poll,choice,color) VALUES (NULL,".$thread['poll'].",'".addslashes(stripslashes($ch))."','".addslashes(stripslashes($co))."')");
      	  }
      	}
	  }
	  
      $pollc=mysql_query("SELECT id,choice,color FROM poll_choices WHERE poll=".$thread['poll']);
      while($pc = mysql_fetch_array($pollc)) { $pollchoices[$pc['id']] = $pc; }
	  if (is_array($choice)) {
        debugprint("<br>CHANGED ITEMS:<br>------<br>");
        foreach($choice as $i=>$ch) {
          $i = intval($i);
          $co = $choicecolor[$i];                   // cleaning poll color:
          $co = preg_replace("'^#(.*?)$'", "\\1", $co);  // remove prefix # if given
          $co = preg_replace("'[^a-zA-Z0-9]'", "", $co); // only alphanumerics, please
          $co = substr($co, 0, 6);                       // and cut at six chars of length
          if ($choiceremove[$i]) {
              if (@mysql_num_rows(mysql_query("SELECT * FROM pollvotes WHERE choice=$id"))>0) {
                debugprint("WON'T DELETE CHOICE \"$ch\" (COLOR \"$co\") - HAS VOTES<br>");
              } else {
                debugprint("DELETE FROM poll_choices WHERE id=$i<br>");
                mysql_query("DELETE FROM poll_choices WHERE id=$i");
                debugprint(" # CHOICE \"$ch\" (COLOR \"$co\")<br>");
              }
          } else if (($ch != $pollchoices[$i]['choice'] && trim($ch) != "") || ($co != $pollchoices[$i]['color'] && trim($co) != "")) {
            debugprint("UPDATE poll_choices SET choice='".addslashes(stripslashes($ch))."', color='".addslashes(stripslashes($co))."' WHERE id=$i<br>");
            mysql_query("UPDATE poll_choices SET choice='".addslashes(stripslashes($ch))."', color='".addslashes(stripslashes($co))."' WHERE id=$i");
            debugprint(" # CHOICE \"$ch\" (COLOR \"$co\")<br>");
          } else {
              debugprint("WON'T CHANGE CHOICE \"$ch\" (COLOR \"$co\") - NOT CHANGED OR EMPTY COLOR/CHOICE<br>");
          }
      	}
      }
      
      debugprint("<br>OTHER CHANGES:<br>-----<br>");
      
      $pollclosed = intval($pollclosed); $polldbl = intval($polldbl);
      debugprint("UPDATE poll SET question='".addslashes(stripslashes($pollqu))."',briefing='".addslashes(stripslashes($pollbr))."',closed=$pollclosed,doublevote=$polldbl WHERE id=".$thread['poll']."<br>");
      mysql_query("UPDATE poll SET question='".addslashes(stripslashes($pollqu))."',briefing='".addslashes(stripslashes($pollbr))."',closed=$pollclosed,doublevote=$polldbl WHERE id=".$thread['poll']);

      debugprint("<br>$divisor<br><br>_POST:");

	  if (is_debug()) { print_r($_POST); }
	  

	    
	  debugprint("</pre>");
	  
	  print $tblend;
	  
/*	$posticons[$iconid]=str_replace("\n",'',$posticons[$iconid]);
	if(!$delete){
	  $icon=$posticons[$iconid];
	  if($custposticon) $icon=$custposticon;
	  mysql_query("INSERT INTO actionlog (atime, adesc, aip) VALUES (".ctime().", \"User ".$loguserid." edited thread $id\", \"$userip\")");
	  mysql_query("UPDATE threads SET forum=$forummove,closed=$closed,title='".addslashes($subject)."',icon='".addslashes($icon)."',sticky=$sticky WHERE id=$id");
	  if($forummove!=$forumid){
	    mysql_query("INSERT INTO actionlog (atime, adesc, aip) VALUES (".ctime().", \"User ".$loguserid."moved thread $id from $forumid to $forummove\", \"$userip\")");
	    $numposts=$thread[replies]+1;
	    $t1=mysql_fetch_array(mysql_query("SELECT lastpostdate,lastposter FROM threads WHERE forum=$forumid ORDER BY lastpostdate DESC LIMIT 1"));
	    $t2=mysql_fetch_array(mysql_query("SELECT lastpostdate,lastposter FROM threads WHERE forum=$forummove ORDER BY lastpostdate DESC LIMIT 1"));
	    mysql_query("UPDATE forums SET numposts=numposts-$numposts,numthreads=numthreads-1,lastpostdate=$t1[lastpostdate],lastpostuser=$t1[lastposter] WHERE id=$forumid");
	    mysql_query("UPDATE forums SET numposts=numposts+$numposts,numthreads=numthreads+1,lastpostdate=$t2[lastpostdate],lastpostuser=$t2[lastposter] WHERE id=$forummove");
	  }
	}else{
	  mysql_query("INSERT INTO actionlog (atime, adesc, aip) VALUES (".ctime().", \"User ".$loguserid." deleted thread $id - ip $REMOTE_ADDR\", \"$userip\")");
	  mysql_query("DELETE FROM threads WHERE id=$id");
	  mysql_query("DELETE FROM posts WHERE thread=$id");
	  $numdeletedposts=$thread[replies]+1;
	  $t1=mysql_fetch_array(mysql_query("SELECT lastpostdate,lastposter FROM threads WHERE forum=$forumid ORDER BY lastpostdate DESC LIMIT 1"));
	  mysql_query("UPDATE forums SET numposts=numposts-$numdeletedposts,numthreads=numthreads-1,lastpostdate=$t1[lastpostdate],lastpostuser=$t1[lastposter] WHERE id=$forumid");
	}*/
    }else
      print "
	  $tccell1 Couldn't edit the poll. Either you didn't enter an existing username,
	  or you haven't entered the right password for the username, or you are not allowed to edit this poll.<br>
	  ".redirect("thread.php?id=$id",'return to the poll',0);
  }
  print $footer;
  if($stamptime){printtimedif($startingtime);}
?>
