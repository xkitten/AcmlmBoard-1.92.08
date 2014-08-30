<?php

header("Content-type: text/plain; charset=US-ASCII");

$totalspace 	= disk_total_space("/");
$readabletotal	= readable_size($totalspace);
$freespace		= disk_free_space("/");
$readablefree	= readable_size($freespace);
$freepct = round(($freespace/$totalspace) * 100, 2);

print "Free space:  $readablefree  ($freepct %)
Total space: $readabletotal";


function readable_size($size) {
   if ($size < 1024) {
       return $size . ' B';
   }
   $units = array("kB", "MB", "GB", "TB");
   foreach ($units as $unit) {
       $size = $size / 1024;
       if ($size < 1024) {
           break;
       }
   }
   $size = round($size, 2);
   return $size . ' ' . $unit;
}
?>