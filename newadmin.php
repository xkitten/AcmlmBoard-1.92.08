<?php

  require("lib/function.php");

  require("lib/layout.php");

  $users1=mysql_query("SELECT id, name, password, powerlevel FROM users");

  while ($user=mysql_fetch_array($users1)) {

    $users[$user[id]]=$user;

  }

  $tccellha="<td bgcolor=$tableheadbg";

  $tccellhb="><center>$fonthead";

  print $header;

  if ($action=="") {
  print $header;
  print "
   <table border=0 bgcolor=$tableborder width=$tablewidth align=center cellpadding=0 cellspacing=0>
    <td>
     <FORM ACTION=\"admin.php\" NAME=\"REPLIER\" METHOD=\"POST\">
     <table border=0 cellpadding=2 cellspacing=1 width=100% bgcolor=$tableborder>
  ";

  if ($logpassword!="" and $loguser[password]==$logpassword) {

    $username=$loguser[name];

    $password=$loguser[password];

  }

  $replytable="$tccellha width=150$tccellhb"."&nbsp;</font></td>";

  $replytable.="$tccellh"."&nbsp;</td><tr>";

  $replytable.="$tccell1"."<b>User name:</td>";

  $replytable.="$tccell2"."</center><INPUT TYPE=TEXT NAME=\"username\" VALUE=\"$username\" SIZE=25 MAXLENGTH=25></td><tr>";

  $replytable.="$tccell1"."<b>Password:</td>";

  $replytable.="$tccell2"."</center><INPUT TYPE=PASSWORD NAME=\"password\" VALUE=\"$password\" SIZE=13 MAXLENGTH=13></td><tr>";

  $replytable.="$tccell1"."&nbsp;</td>";

  $replytable.="$tccell2"."</center>

  <INPUT TYPE=HIDDEN NAME=\"action\" VALUE=\"enteradmin\">

  <INPUT TYPE=Submit NAME=\"submit\" VALUE=\"Enter admin control\"></td></FORM>";

  print "$replytable

      </td>

     </table>

  ";

  print "</td>
     </table>
     ";
      print $footer;
      printtimedif($startingtime);

  }

  if ($action=="enteradmin") {

   $u=1;

   $userid=-1;

   while ($users[$u][id]!="") {

     if ($users[$u][name]==$username and $users[$u][password]==$password and $users[$u][powerlevel]>1) {

       $userid=$u;

     }

     $u++;

   }
  if ($userid!=-1) {
  while ($users[$i][0]!="") {
    $usercount++;
    $lastusername=$users[$i][2];
    $lastuserid=$i;
    if ((time()-$users[$i][9])<300) {
      $numonline++;
      if ($numonline>1) {
        $onlineusers.="<BR>";
      }
      $onlineusers.="<a href=profile.php?id=$i>".$users[$i][2]."</a> ".$users[$i][19];
    }
    $i++;
  }
  print "<frameset rows=\"10%,1*\">
 <frame name=Frame2><BODY><P>heh</P></BODY>
 <frameset cols=\"18%,65%,1*\">
  <frame name=Frame3>
  <frameset rows=\"94%,1*\">
   <frame name=Frame1 src=index.php>
   <frame name=Frame5>heh
   </frameset>
  <frame name=Frame4>
 </frameset>
 <noframes>
  <body lang=EN-US style='tab-interval:.5in'>
  <div class=Section1>
  <pl>Oh well, you can't see this..</p>
  </div>
  </body>
 </noframes>
</frameset>";
    }
  else {
  print $header;
  print "
   <table border=0 bgcolor=$tableborder width=$tablewidth align=center cellpadding=0 cellspacing=0>
    <td>
     <FORM ACTION=\"admin.php\" NAME=\"REPLIER\" METHOD=\"POST\">
     <table border=0 cellpadding=2 cellspacing=1 width=100% bgcolor=$tableborder>
  ";
      $replytable.="$tccell1"."Couldn't enter in the admin control. Either you are not an ".
      "administratror, or you haven't entered the right username or password.".
      "<br>Click <a href=index.php>here</a> to return to the board.".
      "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=index.php\">";
      print "$replytable
      </td>
     </table>
     </td>
     </table>"
     ;
      print $footer;
      printtimedif($startingtime);
 }
}
 
 /*  print "

   <table border=0 bgcolor=$tableborder width=$tablewidth align=center cellpadding=0 cellspacing=0>

    <td>

     <FORM ACTION=\"admin.php\" NAME=\"REPLIER\" METHOD=\"POST\">

     <table border=0 cellpadding=2 cellspacing=1 width=100% bgcolor=$tableborder>

  ";

    if ($userid!=-1) {

      $tsmilies="";

      $fpnt=fopen("smilies.dat", "r");

      $i=0;

      while (!feof($fpnt)) {

        $smilie=fgets($fpnt, 1000);

        $tsmilies.=$smilie;

        $i++;

      }

      $r=fclose($fpnt);

      $tposticons="";

      $fpnt=fopen("posticons.dat", "r");

      $i=0;

      while (!feof($fpnt)) {

        $posticon=fgets($fpnt, 200);

        $tposticons.=$posticon;

        $i++;

      }

      $r=fclose($fpnt);

      $tcategories="";

      $fpnt=fopen("categories.dat", "r");

      $i=0;

      while (!feof($fpnt)) {

        $category=fgets($fpnt, 200);

        $tcategories.=$category;

        $i++;

      }

      $r=fclose($fpnt);

//      $tforums="";

//      $fpnt=fopen("forums.dat", "r");

//      $i=0;

//      while (!feof($fpnt)) {

//        $forum=fgets($fpnt, 10000);

//        $tforums.=$forum;

//        $i++;

//      }

//      $r=fclose($fpnt);

      $fpnt=fopen("forummods.dat", "r");

      $i=0;

      while (!feof($fpnt)) {

        $forummod=fgets($fpnt, 10000);

        $tforummods.=$forummod;

        $i++;

      }

      $r=fclose($fpnt);

      $tranks="";

      $fpnt=fopen("ranks.dat", "r");

      $i=0;

      while (!feof($fpnt)) {

        $rank=fgets($fpnt, 200);

        $tranks.=$rank;

        $i++;

      }

      $r=fclose($fpnt);

      $fpnt=fopen("ipban.dat", "r");

      $i=0;

      while (!feof($fpnt)) {

        $ipban=fgets($fpnt, 20);

        $tipbans.=$ipban;

        $i++;

      }

      $r=fclose($fpnt);

      $replytable="$tccellha width=150$tccellhb"."&nbsp;</font></td>";

      $replytable.="$tccellh"."&nbsp;</td><tr>";

      $replytable.="$tccell1"."<b>Smilies:</b> (smilies.dat)$smallfont<br></center>&nbsp;Each line is a smilie. Syntax:<br><i><b>smilie code_URL of image to use</b></i><br><br>Beware: some characters, such as \"(\" and \"|\", can't be used without causing errors.</td>";

      $replytable.="$tccell2"."</center><TEXTAREA NAME=\"lsmilies\" ROWS=10 COLS=60 WRAP=VIRTUAL>$tsmilies</TEXTAREA></td><tr>";

      $replytable.="$tccell1"."<b>Posticons:</b> (posticons.dat)$smallfont<br></center>&nbsp;Each line is a posticon. Syntax:<br><i><b>URL of posticon</b></i>.</td>";

      $replytable.="$tccell2"."</center><TEXTAREA NAME=\"lposticons\" ROWS=10 COLS=60 WRAP=VIRTUAL>$tposticons</TEXTAREA></td><tr>";

      $replytable.="$tccell1"."<b>Forum categories:</b> (categories.dat)$smallfont<br></center>&nbsp;Each line is a category. Syntax:<br><i><b>Name of category</b></i>.</td>";

      $replytable.="$tccell2"."</center><TEXTAREA NAME=\"lcategories\" ROWS=10 COLS=60 WRAP=VIRTUAL>$tcategories</TEXTAREA></td><tr>";

//      $replytable.="$tccell1"."<b>Forums:</b> (forums.dat)$smallfont<br></center>&nbsp;Each line is a forum. Syntax:<br><i><b>Name_Description_Category number_User power level restriction</b></i>.</td>";

//      $replytable.="$tccell2"."</center><TEXTAREA NAME=\"lforums\" ROWS=10 COLS=60 WRAP=VIRTUAL>$tforums</TEXTAREA></td><tr>";

      $replytable.="$tccell1"."<b>Forum moderators:</b> (forummods.dat)$smallfont<br></center>&nbsp;Each line is for a forum. Syntax:<br><i><b>Mod. user ID_2nd moderator_3rd_(and so on)</b></i>.</td>";

      $replytable.="$tccell2"."</center><TEXTAREA NAME=\"lforummods\" ROWS=10 COLS=60 WRAP=VIRTUAL>$tforummods</TEXTAREA></td><tr>";

      $replytable.="$tccell1"."<b>User ranks:</b> (ranks.dat)$smallfont<br></center>&nbsp;Each line is a rank. Syntax:<br><i><b>Number of posts required_Rank title</b></i>.</td>";

      $replytable.="$tccell2"."</center><TEXTAREA NAME=\"lranks\" ROWS=10 COLS=60 WRAP=VIRTUAL>$tranks</TEXTAREA></td><tr>";

      $replytable.="$tccell1"."<b>IP ban:</b> (ipban.dat)$smallfont<br></center>&nbsp;Each line is a banned IP address or range. If you want to ban a IP range (like 206.172.*.*), enter only the non-changing part (like \"206.172.\"). Syntax:<br><i><b>IP address or range to ban</b></i>.</td>";

      $replytable.="$tccell2"."</center><TEXTAREA NAME=\"lipbans\" ROWS=10 COLS=60 WRAP=VIRTUAL>$tipbans</TEXTAREA></td><tr>";

      $replytable.="$tccell1"."&nbsp;</td>";

      $replytable.="$tccell2"."</center>

      <INPUT TYPE=HIDDEN NAME=\"action\" VALUE=\"savesettings\">

      <INPUT TYPE=Submit NAME=\"submit\" VALUE=\"Save settings\"></td></FORM>";

    } else {

      $replytable.="$tccell1"."Couldn't enter in the admin control. Either you are not an ".

      "administratror, or you haven't entered the right username or password.".

      "<br>Click <a href=index.php>here</a> to return to the board.".

      "<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=index.php\">";

    }

    print "$replytable

      </td>

     </table>

  ";

  print "

    </td>

   </table>

  "; 

  }

  if ($action=="savesettings") {

   print "

   <table border=0 bgcolor=$tableborder width=$tablewidth align=center cellpadding=0 cellspacing=0>

    <td>

     <table border=0 cellpadding=2 cellspacing=1 width=100% bgcolor=$tableborder>";

      $fpnt=fopen("smilies.dat", "w");

      $r=fputs($fpnt, "$lsmilies");

      $r=fclose($fpnt);

      $fpnt=fopen("posticons.dat", "w");

      $r=fputs($fpnt, "$lposticons");

      $r=fclose($fpnt);

      $fpnt=fopen("categories.dat", "w");

      $r=fputs($fpnt, "$lcategories");

      $r=fclose($fpnt);

//      $fpnt=fopen("forums.dat", "w");

//      $r=fputs($fpnt, "$lforums");

//      $r=fclose($fpnt);

      $fpnt=fopen("forummods.dat", "w");

      $r=fputs($fpnt, "$lforummods");

      $r=fclose($fpnt);

      $fpnt=fopen("ranks.dat", "w");

      $r=fputs($fpnt, "$lranks");

      $r=fclose($fpnt);

      $fpnt=fopen("ipban.dat", "w");

      $r=fputs($fpnt, "$lipbans");

      $r=fclose($fpnt);

      print "$tccell1"."Settings saved.".

      "<br>Click <a href=index.php>here</a> to return to the board, or wait to get redirected.

       <META HTTP-EQUIV=\"REFRESH\" CONTENT=\"0; URL=index.php\">

      </td>

     </table>

  ";

  print "

    </td>

   </table>

  "; 

  }

  print $footer;

  printtimedif($startingtime);

  mysql_close($sql);*/

?>