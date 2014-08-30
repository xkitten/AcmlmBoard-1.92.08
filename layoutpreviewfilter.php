<?php
  require_once 'lib/libs.php';
  //require_once 'lib/layout.php';
  
  header("Pragma: no-cache");
  
  $loguser[viewsig]=1;

  $post['signtext'] = stripslashes($_POST['signtext']);
  $post['headtext'] = stripslashes($_POST['headtext']);
  $post['text'] = stripslashes($_POST['text']);

  $user=@mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id=$loguserid"));
  $user[viewsig]=1;
  $post['num'] = $user['posts'];
  $post['name'] = $user['name'];
  $post['regdate'] = $user['regdate'];
  $post['date'] = ctime();

  $numdays=(ctime()-$user[regdate])/86400;
  $post['signtext']=doreplace($post['signtext'],$user[posts],$numdays,$user[name]);
  $post['signtext']=doreplace2($post['signtext'],$user[posts],$numdays,$user[name]);
  $post['headtext']=doreplace($post['headtext'],$user[posts],$numdays,$user[name]);
  $post['headtext']=doreplace2($post['headtext'],$user[posts],$numdays,$user[name]);

  function myaddslashes($s) {
    return preg_replace("(\r\n|\n|\r)", "", str_replace("'", "\'", $s));
  }
  
  $post['styles'] = "";
  $post['signtext'] = implode(explode("\n", $post['signtext']));
  $post['headtext'] = implode(explode("\n", $post['headtext']));
  $post['text'] = implode(explode("\n", $post['text']));
  while (preg_match("'<style(.*?)</style>'si", $post['headtext'],$m)) {
    preg_match("'^(?:.*?)>(.*?)</style>$'si", $m[0], $m1);
/*    if (preg_match_all("'@import\s+(\()?([\"'])(.*?)\2(\))?'si", $m1[1], $m2, PREG_SET_ORDER)) {
      foreach ($m2 as $list) {
        $post['styleimports'][] = $list[3];
      }
    }*/
    $post['styles'] .= $m1[1];
    $post['headtext'] = str_replace($m[0]."", "", $post['headtext']);
//    $post['text'] .= "<br>replaced ".htmlentities($m[0])."<br>";
    $m = array();
    $m1 = array();
  }
  
  print "<body><script>\n\n";
//  $post = setlayout($post, true);
  print "var headt, textt, signt, stylest; \n\n headt = '" . myaddslashes( $post['headtext']) . "';\n stylest = '" . myaddslashes( $post['styles']) . "';\n textt = '" . myaddslashes( $post['text']) . "';\n signt = '" . myaddslashes( $post['signtext']) . "';\n\n";
/*  print "var importst = new Array();\n";
  if (count($post['styleimports']) > 0) {
    $i = 0;
    foreach ($post['styleimports'] as $f) {
      print "importst[".$i."] = \"". myaddslashes($f) . "\";\n";
      $i++;
    }
  }*/
  print "parent.setPreview(headt+textt+signt,stylest);\n\n";
  print "</script></body>";

?>
