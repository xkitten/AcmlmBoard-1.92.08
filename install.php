<?php

require "lib/config.php";

print "<html><head><title>$boardname -- Install</title></head>
<body bgcolor=#333333>
<table cellpadding=4 cellspacing=0 border=0 width=600 height=100% align=center>
<td width=0 align=center bgcolor=#222222>
<font color=#dddddd face=\"Courier New\" style=\"font-size: 13px\">
<b>Acmlmboard 1.92.08 Installer</b>
</font></td><tr><td valign=top bgcolor=#111111 height=100%>
<font color=#dddddd face=\"Courier New\" style=\"font-size: 13px\">\n";

if (!$step) {
	print "Welcome to the Acmlmboard 1.92.08 beta installer. As this version is still very far from being complete, please...
<br><font color=#ff8080><b>DO NOT DISTRIBUTE !!</b></font>
<br>Please report all bugs to Xkeeper or the <a href=http://amber.stormbirds.org/acmlmbugs/><font color=#80FF80>Bug Tracker</font></a></center>
<br><br>
<pre>Acmlmboard 1.92.08 will be installed using the following information:

MySQL host: <font color=#ff8080>$sqlhost</font>
MySQL user: <font color=#ff8080>$sqluser</font>
MySQL pass: <font color=#ff8080>$sqlpass</font>
Database:   <font color=#ff8080>$dbname</font>

If this is correct, please <a href=$PHP_SELF?step=1><font color=#80ff80>click here</font></a>.
If not, please edit the <font color=#ff8080>config.php</font> file in the <font color=#ff8080>lib/</font> directory.";

	} elseif ($step == 1) {
	$errors = 0;
	$total = 0;

	print "<pre>Attempting to install...\n\n";
	$sql = @mysql_connect($sqlhost,$sqluser,$sqlpass) or die("<font color=#ff2020>Couldn't connect to the MySQL database!</font></body></html>");
	mysql_select_db($dbname) or die("<font color=#ff2020>Couldn't select the MySQL database!</font></body></html>");

	$status = doquery("CREATE TABLE `actionlog` (  `id` mediumint(9) NOT NULL auto_increment,  `atime` varchar(15) NOT NULL default '',  `adesc` mediumtext NOT NULL,  `aip` text NOT NULL,  PRIMARY KEY  (`id`)) ENGINE=MyISAM");
	print "Creating table 'actionlog'...       $status\n";
	$status = doquery("CREATE TABLE `announcements` (  `id` smallint(5) unsigned NOT NULL auto_increment,  `user` smallint(5) unsigned NOT NULL default '0',  `date` int(10) NOT NULL default '0',  `ip` varchar(32) NOT NULL default '',  `title` varchar(250) NOT NULL default '',  `text` text,  `forum` tinyint(3) NOT NULL default '0',  `headtext` text,  `signtext` text,  `edited` text,  `headid` mediumint(6) NOT NULL default '0',  `signid` mediumint(6) NOT NULL default '0',  `tagval` text NOT NULL,  PRIMARY KEY  (`id`),  KEY `forum` (`forum`)) ENGINE=MyISAM");
	print "Creating table 'announcements'...   $status\n";
	$status = doquery("CREATE TABLE `blockedlayouts` (  `user` smallint(5) unsigned NOT NULL default '0',  `blockee` smallint(5) unsigned NOT NULL default '0',  KEY `user` (`user`)) ENGINE=MyISAM");
	print "Creating table 'blockedlayouts'...  $status\n";
	$status = doquery("CREATE TABLE `categories` (  `id` smallint(5) unsigned NOT NULL default '0',  `name` varchar(255) NOT NULL default '',  `minpower` tinyint(4) default '0',  PRIMARY KEY  (`id`)) ENGINE=MyISAM");
	print "Creating table 'categories'...      $status\n";
	$status = doquery("CREATE TABLE `dailystats` (  `date` varchar(8) NOT NULL default '',  `users` int(11) NOT NULL default '0',  `threads` int(11) NOT NULL default '0',  `posts` int(11) NOT NULL default '0',  `views` int(11) NOT NULL default '0',  PRIMARY KEY  (`date`)) ENGINE=MyISAM");
	print "Creating table 'dailystats'...      $status\n";
	$status = doquery("CREATE TABLE `events` (  `id` mediumint(8) unsigned NOT NULL auto_increment,  `d` tinyint(2) unsigned NOT NULL default '0',  `m` tinyint(2) unsigned NOT NULL default '0',  `y` smallint(4) unsigned NOT NULL default '0',  `user` mediumint(8) unsigned NOT NULL default '0',  `title` varchar(200) NOT NULL default '',  `text` text NOT NULL,  UNIQUE KEY `id` (`id`)) ENGINE=MyISAM");
	print "Creating table 'events'...          $status\n";
	$status = doquery("CREATE TABLE `favorites` (  `user` bigint(6) NOT NULL default '0',  `thread` bigint(9) NOT NULL default '0') ENGINE=MyISAM");
	print "Creating table 'favorites'...       $status\n";
	$status = doquery("CREATE TABLE `forummods` (  `forum` smallint(5) NOT NULL default '0',  `user` mediumint(8) NOT NULL default '0') ENGINE=MyISAM");
	print "Creating table 'forummods'...       $status\n";
	$status = doquery("CREATE TABLE `forumread` (  `user` smallint(5) unsigned NOT NULL default '0',  `forum` tinyint(3) unsigned NOT NULL default '0',  `readdate` int(11) NOT NULL default '0',  KEY `user` (`user`)) ENGINE=MyISAM");
	print "Creating table 'forumread'...       $status\n";
	$status = doquery("CREATE TABLE `forums` (  `id` smallint(5) unsigned NOT NULL auto_increment,  `title` varchar(250) default NULL,  `description` text,  `olddesc` text NOT NULL,  `catid` smallint(5) unsigned NOT NULL default '0',  `minpower` tinyint(2) NOT NULL default '0',  `minpowerthread` tinyint(2) NOT NULL default '0',  `minpowerreply` tinyint(2) NOT NULL default '0',  `numthreads` mediumint(8) unsigned NOT NULL default '0',  `numposts` mediumint(8) unsigned NOT NULL default '0',  `lastpostdate` int(11) NOT NULL default '0',  `lastpostuser` int(11) unsigned NOT NULL default '0',  `forder` smallint(5) NOT NULL default '0',  PRIMARY KEY  (`id`),  KEY `catid` (`catid`)) ENGINE=MyISAM");
	print "Creating table 'forums'...          $status\n";
	$status = doquery("CREATE TABLE `guests` (  `id` mediumint(8) unsigned NOT NULL auto_increment,  `ip` varchar(32) NOT NULL default '',  `date` int(11) NOT NULL default '0',  `lasturl` varchar(100) NOT NULL default '',  `lastforum` tinyint(3) unsigned NOT NULL default '0',  PRIMARY KEY  (`id`)) ENGINE=MyISAM");
	print "Creating table 'guests'...          $status\n";
	$status = doquery("CREATE TABLE `hits` (  `num` int(11) NOT NULL default '0',  `user` mediumint(8) NOT NULL default '0',  `ip` varchar(15) NOT NULL default '',  `date` int(11) NOT NULL default '0',  KEY `num` (`num`)) ENGINE=MyISAM");
	print "Creating table 'hits'...            $status\n";
	$status = doquery("CREATE TABLE `ipbans` (  `ip` varchar(15) NOT NULL default '',  `reason` varchar(100) NOT NULL default '',  `perm` tinyint(2) unsigned NOT NULL default '0',  `date` int(10) unsigned NOT NULL default '0',  `banner` smallint(5) unsigned NOT NULL default '1',  UNIQUE KEY `ip` (`ip`)) ENGINE=MyISAM");
	print "Creating table 'ipbans'...          $status\n";
	$status = doquery("CREATE TABLE `itemcateg` (  `id` tinyint(3) unsigned NOT NULL auto_increment,  `corder` tinyint(4) NOT NULL default '0',  `name` varchar(20) NOT NULL default '',  `description` varchar(255) NOT NULL default '',  PRIMARY KEY  (`id`)) ENGINE=MyISAM");
	print "Creating table 'itemcateg'...       $status\n";
	$status = doquery("CREATE TABLE `items` (  `id` tinyint(3) unsigned NOT NULL auto_increment,  `cat` tinyint(3) unsigned NOT NULL default '0',  `type` tinyint(4) unsigned NOT NULL default '0',  `name` varchar(30) NOT NULL default '',  `stype` varchar(9) NOT NULL default '',  `sHP` smallint(5) NOT NULL default '100',  `sMP` smallint(5) NOT NULL default '100',  `sAtk` smallint(5) NOT NULL default '100',  `sDef` smallint(5) NOT NULL default '100',  `sInt` smallint(5) NOT NULL default '100',  `sMDf` smallint(5) NOT NULL default '100',  `sDex` smallint(5) NOT NULL default '100',  `sLck` smallint(5) NOT NULL default '100',  `sSpd` smallint(5) NOT NULL default '100',  `coins` mediumint(8) NOT NULL default '100',  PRIMARY KEY  (`id`),  KEY `cat` (`cat`)) ENGINE=MyISAM");
	print "Creating table 'items'...           $status\n";
	$status = doquery("CREATE TABLE `misc` (  `views` int(11) unsigned NOT NULL default '0',  `hotcount` smallint(5) unsigned default '30',  `maxpostsday` mediumint(7) unsigned NOT NULL default '0',  `maxpostshour` mediumint(6) unsigned NOT NULL default '0',  `maxpostsdaydate` int(10) unsigned NOT NULL default '0',  `maxpostshourdate` int(10) unsigned NOT NULL default '0',  `maxusers` smallint(5) unsigned NOT NULL default '0',  `maxusersdate` int(10) unsigned NOT NULL default '0',  `maxuserstext` text) ENGINE=MyISAM");
	print "Creating table 'misc'...            $status\n";

	$status = doquery("CREATE TABLE `pmsgs` (  `id` mediumint(8) unsigned NOT NULL auto_increment,  `userto` smallint(5) unsigned NOT NULL default '0',  `userfrom` smallint(5) unsigned NOT NULL default '0',  `date` int(10) unsigned NOT NULL default '0',  `ip` char(15) NOT NULL default '',  `msgread` tinyint(3) unsigned NOT NULL default '0',  `headid` smallint(5) unsigned NOT NULL default '0',  `signid` smallint(5) unsigned NOT NULL default '0',  `folderto` tinyint(3) unsigned NOT NULL default '1',  `folderfrom` tinyint(3) unsigned NOT NULL default '2',  PRIMARY KEY  (`id`),  KEY `userto` (`userto`),  KEY `userfrom` (`userfrom`),  KEY `msgread` (`msgread`)) ENGINE=MyISAM");
	print "Creating table 'pmsgs'...           $status\n";
	$status = doquery("CREATE TABLE `pmsgs_text` (  `pid` mediumint(8) unsigned NOT NULL default '0',  `title` varchar(255) NOT NULL default '',  `headtext` text NOT NULL,  `text` mediumtext NOT NULL,  `signtext` text NOT NULL,  `tagval` text NOT NULL,  PRIMARY KEY  (`pid`)) ENGINE=MyISAM");
	print "Creating table 'pmsgs_text'...      $status\n";
	$status = doquery("CREATE TABLE `poll` (  `id` int(11) NOT NULL auto_increment,  `question` varchar(255) NOT NULL default '',  `briefing` text NOT NULL,  `closed` tinyint(1) NOT NULL default '0',  `doublevote` tinyint(1) NOT NULL default '0',  PRIMARY KEY  (`id`)) ENGINE=MyISAM");
	print "Creating table 'poll'...            $status\n";
	$status = doquery("CREATE TABLE `poll_choices` (  `id` int(11) NOT NULL auto_increment,  `poll` int(11) NOT NULL default '0',  `choice` varchar(255) NOT NULL default '',  `color` varchar(25) NOT NULL default '',  PRIMARY KEY  (`id`)) ENGINE=MyISAM");
	print "Creating table 'poll_choices'...    $status\n";
	$status = doquery("CREATE TABLE `pollvotes` (  `poll` int(11) NOT NULL default '0',  `choice` int(11) NOT NULL default '0',  `user` int(11) NOT NULL default '0',  UNIQUE KEY `choice` (`choice`,`user`)) ENGINE=MyISAM");
	print "Creating table 'pollvotes'...       $status\n";
	$status = doquery("CREATE TABLE `postlayouts` (  `id` mediumint(8) unsigned NOT NULL auto_increment,  `text` text NOT NULL,  UNIQUE KEY `id` (`id`)) ENGINE=MyISAM");
	print "Creating table 'postlayouts'...     $status\n";
	$status = doquery("CREATE TABLE `postradar` (  `user` smallint(5) unsigned NOT NULL default '0',  `comp` smallint(5) unsigned NOT NULL default '0',  UNIQUE KEY `user` (`user`,`comp`)) ENGINE=MyISAM");
	print "Creating table 'postradar'...       $status\n";
	$status = doquery("CREATE TABLE `posts` (  `id` mediumint(8) NOT NULL auto_increment,  `thread` int(10) unsigned NOT NULL default '0',  `user` smallint(5) unsigned NOT NULL default '0',  `date` int(10) unsigned NOT NULL default '0',  `ip` char(15) NOT NULL default '0.0.0.0',  `num` mediumint(8) NOT NULL default '0',  `headid` mediumint(8) unsigned NOT NULL default '0',  `signid` mediumint(8) unsigned NOT NULL default '0',  PRIMARY KEY  (`id`),  KEY `thread` (`thread`),  KEY `date` (`date`),  KEY `user` (`user`),  KEY `ip` (`ip`)) ENGINE=MyISAM");
	print "Creating table 'posts'...           $status\n";
	$status = doquery("CREATE TABLE `posts_text` (  `pid` mediumint(8) unsigned NOT NULL default '0',  `headtext` text,  `text` mediumtext,  `signtext` text,  `tagval` text,  `options` varchar(3) NOT NULL default '0|0',  `edited` text,  PRIMARY KEY  (`pid`)) ENGINE=MyISAM");
	print "Creating table 'posts_text'...      $status\n";
	$status = doquery("CREATE TABLE `ranks` (  `rset` tinyint(3) unsigned NOT NULL default '1',  `num` mediumint(8) NOT NULL default '0',  `text` varchar(255) NOT NULL default '',  KEY `count` (`num`)) ENGINE=MyISAM");
	print "Creating table 'ranks'...           $status\n";
	$status = doquery("CREATE TABLE `ranksets` (  `id` tinyint(3) unsigned NOT NULL default '0',  `name` varchar(50) NOT NULL default '',  PRIMARY KEY  (`id`)) ENGINE=MyISAM");
	print "Creating table 'ranksets'...        $status\n";
	$status = doquery("CREATE TABLE `schemes` (  `id` smallint(5) unsigned NOT NULL default '0',  `ord` smallint(5) NOT NULL default '0',  `name` varchar(50) default NULL,  `file` varchar(200) default NULL,  PRIMARY KEY  (`id`)) ENGINE=MyISAM");
	print "Creating table 'schemes'...         $status\n";
	$status = doquery("CREATE TABLE `threads` (  `id` int(10) unsigned NOT NULL auto_increment,  `forum` tinyint(3) unsigned NOT NULL default '0',  `user` smallint(5) unsigned NOT NULL default '0',  `views` smallint(5) unsigned NOT NULL default '0',  `closed` tinyint(1) unsigned NOT NULL default '0',  `title` varchar(100) NOT NULL default '',  `icon` varchar(200) NOT NULL default '',  `replies` smallint(5) unsigned NOT NULL default '0',  `lastpostdate` int(10) NOT NULL default '0',  `lastposter` smallint(5) unsigned NOT NULL default '0',  `sticky` tinyint(1) unsigned NOT NULL default '0',  `poll` smallint(5) unsigned NOT NULL default '0',  `locked` tinyint(1) unsigned NOT NULL default '0',  PRIMARY KEY  (`id`),  KEY `forum` (`forum`),  KEY `user` (`user`),  KEY `sticky` (`sticky`),  KEY `pollid` (`poll`),  KEY `lastpostdate` (`lastpostdate`)) ENGINE=MyISAM PACK_KEYS=0");
	print "Creating table 'threads'...         $status\n";
	$status = doquery("CREATE TABLE `tlayouts` (  `id` smallint(5) unsigned NOT NULL default '0',  `ord` smallint(5) NOT NULL default '0',  `name` varchar(50) default NULL,  `file` varchar(50) NOT NULL default '',  PRIMARY KEY  (`id`)) ENGINE=MyISAM");
	print "Creating table 'tlayouts'...        $status\n";
	$status = doquery("CREATE TABLE `userpic` (  `id` mediumint(8) unsigned NOT NULL auto_increment,  `categ` smallint(5) unsigned NOT NULL default '0',  `url` varchar(250) NOT NULL default '',  `name` varchar(100) NOT NULL default '',  PRIMARY KEY  (`id`),  KEY `categ` (`categ`)) ENGINE=MyISAM");
	print "Creating table 'userpic'...         $status\n";
	$status = doquery("CREATE TABLE `userpiccateg` (  `id` smallint(5) unsigned NOT NULL default '0',  `page` smallint(5) unsigned NOT NULL default '0',  `name` varchar(200) NOT NULL default '',  PRIMARY KEY  (`id`)) ENGINE=MyISAM");
	print "Creating table 'userpiccateg'...    $status\n";
	$status = doquery("CREATE TABLE `userratings` (  `userfrom` smallint(5) unsigned NOT NULL default '0',  `userrated` smallint(5) unsigned NOT NULL default '0',  `rating` smallint(5) NOT NULL default '0',  KEY `userrated` (`userrated`)) ENGINE=MyISAM");
	print "Creating table 'userratings'...     $status\n";
	$status = doquery("CREATE TABLE `users` (  `id` smallint(5) unsigned NOT NULL auto_increment,  `posts` mediumint(9) NOT NULL default '0',  `regdate` int(11) NOT NULL default '0',  `name` varchar(25) NOT NULL default '',  `password` varchar(32) NOT NULL default '',  `minipic` varchar(100) NOT NULL default '',  `picture` varchar(100) NOT NULL default '',  `postbg` varchar(250) NOT NULL default '',  `postheader` text,  `signature` text,  `bio` text,  `powerlevel` tinyint(2) NOT NULL default '0',  `sex` tinyint(1) unsigned NOT NULL default '2',  `title` varchar(255) NOT NULL default '',  `useranks` tinyint(1) unsigned NOT NULL default '1',  `titleoption` tinyint(1) NOT NULL default '1',  `realname` varchar(60) NOT NULL default '',  `location` varchar(200) NOT NULL default '',  `birthday` int(11) NOT NULL default '0',  `email` varchar(60) NOT NULL default '',  `aim` varchar(30) NOT NULL default '',  `icq` int(10) unsigned NOT NULL default '0',  `imood` varchar(60) NOT NULL default '',  `homepageurl` varchar(80) NOT NULL default '',  `homepagename` varchar(100) NOT NULL default '',  `lastposttime` int(10) unsigned NOT NULL default '0',  `lastactivity` int(10) unsigned NOT NULL default '0',  `lastip` varchar(15) NOT NULL default '',  `lasturl` varchar(100) NOT NULL default '',  `lastforum` tinyint(3) unsigned NOT NULL default '0',  `postsperpage` smallint(4) unsigned NOT NULL default '20',  `threadsperpage` smallint(4) unsigned NOT NULL default '50',  `timezone` float NOT NULL default '0',  `scheme` tinyint(2) unsigned NOT NULL default '0',  `layout` tinyint(2) unsigned NOT NULL default '1',  `viewsig` tinyint(1) unsigned NOT NULL default '1',  `posttool` tinyint(1) unsigned NOT NULL default '1',  `signsep` tinyint(3) unsigned NOT NULL default '0',  `publicemail` tinyint(1) unsigned NOT NULL default '1',  `oldstylemark` tinyint(1) NOT NULL default '0',  PRIMARY KEY  (`id`),  KEY `posts` (`posts`),  KEY `name` (`name`),  KEY `lastforum` (`lastforum`),  KEY `lastposttime` (`lastposttime`),  KEY `lastactivity` (`lastactivity`)) ENGINE=MyISAM");
	print "Creating table 'users'...           $status\n";
	$status = doquery("CREATE TABLE `users_rpg` (  `uid` smallint(5) unsigned NOT NULL default '0',  `spent` int(11) NOT NULL default '0',  `eq1` smallint(5) unsigned NOT NULL default '0',  `eq2` smallint(5) unsigned NOT NULL default '0',  `eq3` smallint(5) unsigned NOT NULL default '0',  `eq4` smallint(5) unsigned NOT NULL default '0',  `eq5` smallint(5) unsigned NOT NULL default '0',  `eq6` smallint(5) unsigned NOT NULL default '0',  PRIMARY KEY  (`uid`)) ENGINE=MyISAM");
	print "Creating table 'users_rpg'...       $status\n";

	$status = doquery("INSERT INTO `tlayouts` (`id`, `ord`, `name`, `file`) VALUES (1, 0, 'Regular', 'regular')");
	print "Adding thread layout 'Regular'...   $status\n";
	$status = doquery("INSERT INTO `tlayouts` (`id`, `ord`, `name`, `file`) VALUES (2, 1, 'Regular without number/bar graphics', 'regular')");
	print "Adding thread layout 'Regular 2'... $status\n";
	$status = doquery("INSERT INTO `tlayouts` (`id`, `ord`, `name`, `file`) VALUES (3, 2, 'Compact', 'compact')");
	print "Adding thread layout 'Compact'...   $status\n";

	$status = doquery("INSERT INTO `schemes` (`id`, `ord`, `name`, `file`) VALUES (0, 1, 'Daily Cycle', 'dailycycle.php')");
	print "Adding default scheme...            $status\n";
	$status = doquery("INSERT INTO `misc` (`views`, `hotcount`) VALUES (0, 30)");
	print "Adding misc. row...                 $status\n";

	$status = doquery("INSERT INTO `ranksets` (`id`, `name`) VALUES (1, 'Default')");
	print "Adding default rank set...          $status\n";
	$status = doquery("INSERT INTO `ranks` (`rset`, `num`, `text`) VALUES (1,  0, 'Non-Poster')");
	print "Adding default ranks (1 of 3)...    $status\n";
	$status = doquery("INSERT INTO `ranks` (`rset`, `num`, `text`) VALUES (1,  1, 'Newbie')");
	print "Adding default ranks (2 of 3)...    $status\n";
	$status = doquery("INSERT INTO `ranks` (`rset`, `num`, `text`) VALUES (1, 10, 'Member')");
	print "Adding default ranks (3 of 3)...    $status\n";

print "\nTotal Errors:  <font color=#ff4040>$errors</font>
Total Queries: <font color=#80ff80>$total</font></pre><br><br>";

	if ($errors == 0) {
			print "\nYour Acmlmboard was successfully installed. You must now register and create forums, catagories, and other things, using phpMyAdmin. To verify that the installation was a success, please click <a href=index.php><font color=#80ff80>here</font></a>.
					<br><br><font color=#ff4040>NOTE:</font> it is highly advised you delete this file after a successful installation!";
		} else {
			print "\nPlease fix the problems that have occured. This may require dropping the partially-created tables, and trying again.
					<br><br><font color=#e0e080>NOTE:</font> it is possible the installation was still successful, especially if you have only recieved '<font color=#FF8080>Table already exists</font>' errors. However, it is far more likely you will need to redo the installation.
					<br><br>If you would like to drop all tables and retry the installation process, <a href=$PHP_SELF?step=2><font color=#ff8080>click here</font></a>.";
		}


} elseif ($step == 2) {
		print "<font color=#FF4040>WARNING:</font> This will erase ALL DATA in ALL TABLES. <font color=#ff8080>ONLY CONTINUE IF YOU KNOW WHAT YOU ARE DOING.</font>

<br><br><a href=$PHP_SELF?step=3><font color=#ff4040>Continue</font></a> - <a href=$PHP_SELF?step=0><font color=#80ff80>Go Back</font></a>";
} elseif ($step == 3) {
	print "<pre>";
	$sql = @mysql_connect($sqlhost,$sqluser,$sqlpass) or die("<font color=#ff2020>Couldn't connect to the MySQL database!</font></body></html>");
	mysql_select_db($dbname) or die("<font color=#ff2020>Couldn't select the MySQL database!</font></body></html>");
	$status = doquery("DROP TABLE `actionlog`, `announcements`, `blockedlayouts`, `categories`, `dailystats`, `events`, `favorites`, `forummods`, `forumread`, `forums`, `guests`, `hits`, `ipbans`, `itemcateg`, `items`, `misc`, `pmsgs`, `pmsgs_text`, `poll`, `poll_choices`, `pollvotes`, `postlayouts`, `postradar`, `posts`, `posts_text`, `ranks`, `ranksets`, `schemes`, `threads`, `tlayouts`, `userpic`, `userpiccateg`, `userratings`, `users`, `users_rpg`");
	print "Dropping all tables...              $status\n";

	print "\n\n<a href=$PHP_SELF?step=0><font color=#80ff80>Go Back</font></a>";
}


print "</pre></font></td><tr><td width=0 align=center bgcolor=#222222>
<font color=#dddddd face=\"Courier New\" style=\"font-size: 13px\">
Acmlmboard Installer v1.0 (12-06-05)
</font></td></table></body></html>";


function doquery($x) {
	global $errors, $total;
	$sql = mysql_query($x) OR $error = 1;
	$total++;
	if ($error) {
		$retr = "<font color=#ff3030>ERROR</font> (<font color=#ff8080>". mysql_error() ."</font>)";
		$errors++;
	} else {
		$retr = "<font color=#60ff60>OK</font>";
	}
	return $retr;
}

?>