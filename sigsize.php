<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  print "
	$header<br>$tblstart
	$tccellh>&nbsp</td>
	$tccellh>User</td>
	$tccellh>Header</td>
	$tccellh>Signature</td>
	$tccellh>Total
  ";
  $users=mysql_query('SELECT id,name,LENGTH(postheader) AS hsize,LENGTH(signature) AS ssize,LENGTH(postheader)+LENGTH(signature) AS tsize FROM users ORDER BY tsize DESC');
  for($i=1;$u=mysql_fetch_array($users);$i++){
    print "<tr>
	$tccell2>$i</td>
	$tccell1><a href=profile.php?id=$u[id]>$u[name]</a></td>
	$tccell2>$u[hsize]</td>
	$tccell2>$u[ssize]</td>
	$tccell1>$u[tsize]
    ";
  }
  print $tblend.$footer;
  printtimedif($startingtime);
?>