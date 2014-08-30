<?php


 $nmcol[0]=array('-1'=>'888888','97ACEF','D8E8FE','AFFABE','FFEA95');
 $nmcol[1]=array('-1'=>'888888','F185C9','FFB3F3','C762F2','C53A9E');
 $nmcol[2]=array('-1'=>'888888','7C60B0','EEB9BA','47B53C','F0C413');
 $nmcol[147]=array('-1'=>'888888','888888','888888','888888','888888');
 $nmcol[148]=array('-1'=>'FFEA95','FFEA95','FFEA95','FFEA95','FFEA95');
 $nmcol[149]=array('-1'=>'97ACEF','97ACEF','97ACEF','97ACEF','97ACEF');
// $nmcol[0]=array('-1'=>'888888','6AC061','90E090','AFFABE','FFEA95');
// $nmcol[1]=array('-1'=>'888888','FB6060','FFB3F3','C762F2','C53A9E');
// $nmcol[2]=array('-1'=>'888888','7C60B0','EEB9BA','47B53C','F0C413');
 $linkcolor='FFD040';
 $linkcolor2='F0A020';
 $linkcolor3='FFEA00';
 $linkcolor4='FFFFFF';
 $textcolor='E0E0E0';
 $boardtitle='<img src=images/title2.jpg>';
 $font='arial';
 $font2='verdana';
 $font3='tahoma';
 $newpollpic='New poll';
 $newreplypic='<img src=images/newreply.png border=0>';
 $newthreadpic='<img src=images/newthread.png border=0>';
 $newpic='<img src=images/new.gif>';
 $numdir='num1/';
 $numfil='numnes';
 if(!$scheme) $scheme=0;
 $filename=@mysql_result(mysql_query("SELECT file FROM schemes WHERE id=$scheme"),0,0);
 if(!$filename) $filename='dailycycle.php';
 require "schemes/$filename";
?>