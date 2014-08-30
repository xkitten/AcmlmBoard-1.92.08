<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  print "
$body
<title>Hexadecimal color chart</title>
$css
<script language=javascript>
 function hex(val){
  document.box.hexval.value=val;
  if(document.all){
    document.all('hexbg').style.background=val;
  }else{
    document.layers['hexbgs'].bgColor=val;
  }
 }
</script>
<form name=hexchart>
<map name=colmap>";
  for($g=0;$g<6;$g++)
    for($r=0;$r<6;$r++)
      for($b=0;$b<6;$b++){
	  $x1=$b*8+$r*48+1;
	  $y1=$g*11+1;
	  $x2=$x1+8;
	  $y2=$y1+11;
	  $c=substr(dechex(16777216+$r*51*65536+(5-$g)*51*256+$b*51),-6);
	  print "<area shape=rect coords=$x1,$y1,$x2,$y2 href=javascript:hex('$c')>";
	}
  print "
</map>
<center>
 <table height=100% valign=middle><td>
 $tblstart
  $tccell1
  <a><img usemap=#colmap src=images/hexchart.png border=0 width=289 height=67></a><br>
  Click the box of the color you want to use; the hexadecimal number will be shown below.
  <br>
  <table>
   <td><layer name=hexbgs><table id=hexbg border bordercolor=$tableborder width=60 height=20 cellpadding=0 cellspacing=0><td>&nbsp;</table></layer></td>
   </form><form name=box>
   <td>$inpt=hexval size=6 value=###### readonly>
  </table>
 $tblend
 </table>
";
?>