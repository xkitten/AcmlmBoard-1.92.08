<?php
  require_once 'lib/libs.php';
  mysql_query("INSERT INTO threads (id,forum,user,title) VALUES (0,99,15,'Lost posts')");
  $pt=mysql_query("SELECT * FROM posts_text ORDER BY pid");
  while($t=mysql_fetch_array($pt)){
    $p=mysql_query("SELECT * FROM posts WHERE id=$t[pid]");
    if(!mysql_num_rows($p)){
      mysql_query("INSERT INTO posts (id,thread,user,date) VALUES ($t[pid],0,15,$t[pid])");
    }
  }
?>
