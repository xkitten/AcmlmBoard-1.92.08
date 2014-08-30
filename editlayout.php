<?php
  require_once 'lib/libs.php';
  $nopriv = 1;
  $windowtitle = "$boardname - Edit Layout";
  if(!$log) errorpage('You must be logged in to edit your layout.');
  if($banned) errorpage('Sorry, but banned users aren’t allowed to edit their layout.');
  if(!$action||$preview){

    $descbr="</b>$smallfont<br></center>&nbsp;";

  $inpc='<INPUT TYPE=CHECKBOX NAME'; // no checkbox var in lib/layout.php??

    sbr(1,$loguser[postheader]);
    sbr(1,$loguser[signature]);

    $thehtmlpreview = "";

    if ($preview) {
      $loguser[postheader] = stripslashes($_POST[postheader]);
      $loguser[signature] = stripslashes($_POST[postsign]);
      $loguser[postbg] = stripslashes($_POST[postbg]);
    }
  $loguser[viewsig]=1;
      $postnum = $loguser[posts];
      $postname = $loguser[name];
      $postregdate = $loguser[regdate];

      $numdays=(ctime()-$postregdate)/86400;
      $signatureshowoff=doreplace($loguser[signature],$postnum,$numdays,$postname);
      $signatureshowoff=doreplace2($signatureshowoff,$postnum,$numdays,$postname);
      $headershowoff=doreplace($loguser[postheader],$postnum,$numdays,$postname);
      $headershowoff=doreplace2($headershowoff,$postnum,$numdays,$postname);
      $sampletext = "(sample text)";
      if ($_POST[showlink]) {
        $sampletext = "(<a href=\"#\" onclick=\"return false\">sample</a> text)";
      }
      if ($_POST[showblockquote]) {
        $sampletext = "<blockquote>$smallfont<i>Originally posted by Someone</i></font><hr>" . $sampletext . "<hr></blockquote>" . $sampletext;
      }
      if ($_POST[showstretchimage]) {
        $sampletext .= "<img src=\"".$bconf[boardurl]."/images/stretchtest.gif\">";
      }
      
      if ($_POST[showstretchtext]) {
        $sampletext .= "<br><br>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nam luctus augue blandit nisl. Etiam id lacus sed ante pulvinar euismod. Maecenas neque quam, scelerisque sed, semper id, vulputate a, eros. Suspendisse mauris erat, condimentum in, pellentesque nec, lobortis vitae, elit. Nunc nec elit quis augue viverra consequat. In eget augue. Aliquam erat volutpat. Nulla blandit massa sed velit. Quisque nonummy consectetuer lacus. Aliquam egestas augue sit amet nulla. In diam leo, lacinia eget, convallis sed, pellentesque eu, velit. Nullam sem. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.<br><br>

Morbi rhoncus lectus id leo lacinia blandit. Fusce felis dolor, ullamcorper id, venenatis at, rhoncus feugiat, odio. Suspendisse et nulla eget lectus iaculis elementum. Suspendisse at felis non lectus blandit commodo. Morbi volutpat. Sed eget elit nec libero lobortis consequat. Duis eget magna gravida odio pellentesque venenatis. Maecenas ligula lorem, pellentesque ut, consequat et, commodo et, est. In dictum purus ac lorem. Vestibulum vel felis. In erat. Mauris sit amet est elementum ligula adipiscing vulputate. Curabitur ultrices dolor sagittis neque. Aenean adipiscing odio non lorem. Integer non odio. Nam libero. Vivamus posuere, lorem rutrum iaculis aliquet, metus nibh elementum neque, lacinia eleifend wisi libero vitae tellus. Aenean varius mauris in ipsum.<br><br>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nam luctus augue blandit nisl. Etiam id lacus sed ante pulvinar euismod. Maecenas neque quam, scelerisque sed, semper id, vulputate a, eros. Suspendisse mauris erat, condimentum in, pellentesque nec, lobortis vitae, elit. Nunc nec elit quis augue viverra consequat. In eget augue. Aliquam erat volutpat. Nulla blandit massa sed velit. Quisque nonummy consectetuer lacus. Aliquam egestas augue sit amet nulla. In diam leo, lacinia eget, convallis sed, pellentesque eu, velit. Nullam sem. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.<br><br>

Morbi rhoncus lectus id leo lacinia blandit. Fusce felis dolor, ullamcorper id, venenatis at, rhoncus feugiat, odio. Suspendisse et nulla eget lectus iaculis elementum. Suspendisse at felis non lectus blandit commodo. Morbi volutpat. Sed eget elit nec libero lobortis consequat. Duis eget magna gravida odio pellentesque venenatis. Maecenas ligula lorem, pellentesque ut, consequat et, commodo et, est. In dictum purus ac lorem. Vestibulum vel felis. In erat. Mauris sit amet est elementum ligula adipiscing vulputate. Curabitur ultrices dolor sagittis neque. Aenean adipiscing odio non lorem. Integer non odio. Nam libero. Vivamus posuere, lorem rutrum iaculis aliquet, metus nibh elementum neque, lacinia eleifend wisi libero vitae tellus. Aenean varius mauris in ipsum.";
      }
      

      
      $thehtmlpreview = $headershowoff . $sampletext . ($signatureshowoff != "" ? $signatureshowoff : $signatureshowoff);
      $p = $loguser;
      $p[text] = $sampletext;
      $p[headtext] = "<div id=\"layout-preview\">".$headershowoff;
      $p[signtext] = ($signatureshowoff != "" ? $signatureshowoff : $signatureshowoff)."</div>";
      $p[user] = $loguserid;
      $p[id] = $loguserid;
      $p[uid] = $loguserid;
      $p[date] = time();
      $bg=$bg%2+1;
      loadtlayout();
      $thehtml = threadpost($p,$bg);
      if ($loguser[postbg]) {
        $thehtmlpreview = "<div style=\"background-image: url(".$loguser[postbg].");\">" . $thehtmlpreview . "</div>";
      }

    $showt = array('blockquote'      => 'Quotes',
                   'link'            => 'Links',
                   'stretchimage'    => 'Stretch with image',
                   'stretchtext'     => 'Stretch with text',
                   );
// connecting these via the js
    $showjshook = " onclick=\"refreshLayoutPreview(document); return false\"";
//    $typejshook = " onfocus=\"refreshLayoutPreview(document)\"";
    foreach($showt as $nam=>$des) {
       if ($showoptions != "") { $showoptions .= "&nbsp;&nbsp;&nbsp;"; }
       $showoptions .= "$inpc=\"show".$nam."\" id=\"show-".$nam."\"" . ($_POST["show".$nam] ? " checked" : ""). "><label for=\"show-".$nam."\"> ".$des."</label>";
    }
    $showoptions = "<div style=\"text-align: center; float: left;\">$showoptions</div>";

    $annclist="";
    print "
	$header<br>$tblstart<script>var loaded = 0;</script>
	 <FORM ACTION=editlayout.php NAME=REPLIER METHOD=POST>$inph=signsep id=\"signsep\" value=\"".$sep[$loguser[signsep]]."\">
	 $tccellh> Post layout</td>$tccellh>&nbsp<tr>
	 $tccell1 width=\"35%\"><b>Post background:$descbr The full URL of a picture showing up in the background of your posts. Leave it blank for no background. Please make sure your text is readable on the background!</td>
	 $tccell2l>$inpt=postbg VALUE=\"$loguser[postbg]\" id=\"postbg\" SIZE=60 MAXLENGTH=250$typejshook><tr>
	 $tccell1 width=\"35%\"><b>Post header:$descbr This will get added before the start of each post you make. This can be used to give a default font color and face to your posts (by putting a <<z>font> tag). This should preferably be kept small, and not contain too much text or images.</td>
	 $tccell2l>$txta=postheader id=\"postheader\" ROWS=5 COLS=60$typejshook>$loguser[postheader]</TEXTAREA><tr>
	 $tccell1 width=\"35%\"><b>Signature:$descbr This will get added at the end of each post you make, possibly below an horizontal line. This should preferably be kept to a small enough size.</td>
	 $tccell2l>$txta=postsign id=\"postsign\" ROWS=5 COLS=60$typejshook>$loguser[signature]</TEXTAREA><tr>
	 $tccellh width=\"35%\"> Layout preview</td>$tccell1>$smallfont$showoptions$inps=preview id=\"preview-button\" onclick=\"if (loaded==1) return false;\" VALUE=\"Preview\"><tr>
	 $tccell1 colspan=\"2\">$tblstart
	 ". // $tccellh width=150></td>$tccellh colspan=2><tr>
/*	 $tccell1 width=\"35%\" valign=\"top\"><b>Show:$descbr </td>
	 $tccell2l><div id=\"layout-preview\">$thehtmlpreview</div></td><tr>*/
	 $thehtml."$tblend<tr>
	 $tccellh width=\"35%\">&nbsp</td>$tccellh id=\"dataloaderhouse\">&nbsp<tr>
	 $tccell2l width=\"35%\">&nbsp</td>$tccell2l>
	 $inph=action VALUE=savelayout>
	 $inph=userid VALUE=$userid>
	 $inph=userpass VALUE=\"$loguser[password]\">
	 $inps=submit VALUE=\"Edit layout\"></td></FORM>
	$tblend<script src=\"js/layoutpreview.js\"><!-- \nalert(\"Could not load live layout preview capabilites! Live layout preview may not work.\");\n // --></script><script><!--\nif (init(document,'".$sep[$loguser[signsep]]."') == 1) { loaded = 1; }\n // --></script>
    ";
  }

  if($action=='savelayout' && $submit){
    sbr(0,$postheader);
    sbr(0,$postsign);
    mysql_query("UPDATE users SET signature='".addslashes($postsign)."',postbg='".addslashes($postbg)."',postheader='".addslashes($postheader)."' WHERE id=$loguserid AND password='".addslashes($userpass)."'");
    print "$header<br>$tblstart$tccell1>Thank you, $loguser[name], for editing your layout.<br>".redirect("profile.php?id=$loguserid",'view your profile',0).$tblend;
  }
  print $footer;
  if($stamptime){printtimedif($startingtime);}
?>
