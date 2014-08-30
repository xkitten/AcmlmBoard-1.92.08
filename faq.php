<?php
  require 'lib/function.php';
  require 'lib/layout.php';
  $smilies=readsmilies();
  for($i=0;$smilies[$i][0];$i++) $smilielist.="<br><img src='".$smilies[$i][1]."'> -- ".$smilies[$i][0];
  $tb='&nbsp &nbsp;';
  $tab="</a></b><br><br>$tb";
  $cell1="<br><br><tr>$tccell1l><br><b>- <a href=# name";
  $cell2="<br><br><tr>$tccell2l><br><b>- <a href=# name";

  $q01='General disclaimer';
  $q02='Why should I register an account on the board?';
  $q03="I'm new, where should I start?";
  $q04='What is the standard of acceptable behavior at the board?';
  $q05='What is considered "spam" at the board?';
  $q06='What is the strike system?';
  $q07='What can I do about my thread being closed/trashed/deleted?';
  $q08="Help! I've been banned! What do I do now?";
  $q09='Are cookies used for this board?';
  $q10='Can HTML be used?';
  $q11='Is there some sort of replacement code for HTML?';
  $q12='Are there any rules for post layouts?';
  $q13='What is Level and EXP, and how do I get more EXP?';
  $q14='How do I add my stats to my posts?';
  $q15='What are the "syndromes" and how do I get them?';
  $q16='What is the ACS?';
  $q17='What are the user rankings?';
  $q18='How can I get a custom rank?';
  $q19='How can I become a moderator or administrator?';
  $q20='What are announcements?';
  $q21='Can you add me to the Photo Album?';
  $q22='How can I change my username?';
  $q23='What do the username colors mean?';
  $q24='What are all the smilies?';
  $q25='How can I get a AcmlmBoard?';


  print "$header<br>
	 $tblstart
	 $tccellh>FAQ<tr>
	 $tccell1ls><br>
	 - <a href=#01>$q01</a><br><br>
	 - <a href=#02>$q02</a><br>
	 - <a href=#03>$q03</a><br>
	 - <a href=#04>$q04</a><br>
	 - <a href=#05>$q05</a><br>
	 - <a href=#06>$q06</a><br>
	 - <a href=#07>$q07</a><br>
	 - <a href=#08>$q08</a><br><br>
	 - <a href=#09>$q09</a><br>
	 - <a href=#10>$q10</a><br>
	 - <a href=#11>$q11</a><br>
	 - <a href=#12>$q12</a><br><br>
	 - <a href=#13>$q13</a><br>
	 - <a href=#14>$q14</a><br>
	 - <a href=#15>$q15</a><br>
	 - <a href=#16>$q16</a><br><br>
	 - <a href=#17>$q17</a><br>
	 - <a href=#18>$q18</a><br>
	 - <a href=#19>$q19</a><br><br>
	 - <a href=#20>$q20</a><br>
	 - <a href=#21>$q21</a><br>
	 - <a href=#22>$q22</a><br>
	 - <a href=#23>$q23</a><br>
	 - <a href=#24>$q24</a><br><br>
	 - <a href=#25>$q25</a><br><br>
	$tblend$tblstart
	 $tccellh>&nbsp;<tr>
	 $cell1=01>$q01
	 $tab The site does not own and cannot be held responsible for statements made by members on the forum. This site is offered as-is to the user. Any statements made on the board may be altered or removed at the discretion of the staff. Furthermore all users are expected to have read, understood, and agreed to this FAQ before posting.<br> <b>We do <u>not</u> sell or distribute member information to any third party.</b> If you have questions about any information contained in this FAQ, please send a private message with your question to a moderator or admin before posting. Questions on things not covered in this FAQ should be asked in the <a href=forum.php?id=3>Help / Suggestions Forum</a>.
	 $cell2=02>$q02
	 $tab By registering an user account, you will be able to post on the board as well as use several features only accessable through registering, such as the ability to mark forums as read and private messaging. Unregistered users have guest access to the board, meaning they can view threads but cannot reply to them.
	 $cell1=03>$q03
	 $tab The best place for newcomers to start is the <a href=forum.php?id=41>Newbie Forum</a> where you can introduce yourself to the board. If you have any questions not addressed in this FAQ, feel free to ask them in the <a href=forum.php?id=3>Help / Suggestions</a> forum.
	 $cell2=04>$q04
	 $tab First off, don't assume that our rules are the same as other boards. Just because spammy posts are OK on another board doesn't mean that they are here.<br><br>However, most of the rules aren't that hard to follow, and as long as you keep them in mind you should be all right.
	<br><br><b>DO:</b><ol>
	<li>Follow the rules the local mods put out for their forums.
	<li>LISTEN to the admins when they warn you.
	<li>Enjoy yourself, and post frequently while obeying the rules.
	<li>Use proper grammar and avoid l33t speak and AOL-speak (u, ur, y, in place of words such as you, you're, why). It's a good way to gain respect.
	<li>Exercise common sense when posting.
	<li>Exercise caution when posting threads about controversial topics such as religion.
	</ol><br><b>DON'T:</b><ol>
	<li>Spam
	<li>Insult/bash video game systems and games without a perfectly good reason. (i.e. \"DUD U SUCK IF U HAVE GAMCUB UR A GAY PKEMON KIDDIE\")
	<li>Be a bigot. Hateful, discriminating, or homophobic remarks are not welcome on this board. (DUDE THATS GAY STFU UR GAY)
	<li>Bump (post in) old or outdated threads.
	<li>Create multiple accounts unless authorized to by an admin (for testing reasons, etc.). Creating a second account when banned will result in a permanent ban from the board.
	<li>Ask any questions on the board that are answered in this FAQ.
	<li>Post threads regarding layout testing. You can preview your layout in your user profile.
	<li>Stick around if you hate this board. We don't want people going THIS BOARD SUX LOLOLOLOLOLOL DIE OF HIV.
	<li>Act like the board is a chat room (post messages like \"HEY ACMLM\", \"I AGREE\", \"Whats up?\", or similar messages directed to people). This is what private messages are for.
	<li>FLAME, or purposely try to be an ass to people.
	<li>Expect this board to be a utopia. People won't respect you if you act stupid or act as if you're better than everyone else.
	<li>Ask to be a mod, suck up, or act like a mod. It's the quickest way NOT to get what you want.
	<li>Threaten to hack the board. Chances are you can't. Besides, we have a few real hackers on staff who test the board constantly for any security problems.
	<li>Post porn. Permaban on the spot.
	<li>Post rom/warez links on the board. You're free to exchange links with others through private messages, but do not post them out in the open.
	<li>Join the board with the sole intent of advertising your site/service/etc.
	<li>Respond to a spammer attack/obvious flame. If it's obvious that the post/thread in question will be deleted, don't post and make fun of the person or ask for the post/thread to be taken care of.
	</ol><br>Keep in mind that you are fully responsible for your own account. 
	 $cell1=05>$q05
	 $tab 
	Spam is making:<br>
	<br>- A post that is off-topic, meaning that it has nothing to do with the original thread.
	<br>- A post that is only a few words long and doesn't contribute anything meaningful to the discussion.
	<br>- A thread that doesn't have any real meaning.
	<br>- A thread with a poll that is pointless.
	<br>- A thread that is an exact duplicate of a pre-existing, recent thread.
	<br><br>If you feel that a thread is spammy, then PM or IM an admin and ask them to take a look at it. Don't ask for someone to take a look at it in the thread itself or lecture the poster on the dangers of spamming.
	 $cell2=06>$q06
	 $tab In most cases, the staff at the board follows a three-strike system when it comes to punishments. For most minor offenses, an offending user will be warned about his/her conduct through a PM. If they continue to break the rules, they will be banned for 24 hours (possibly more if it was a serious offense, up to 72 hours). If the user continues to cause trouble, they will receive a second warning, possibly followed by another ban which could last anywhere from 72 hours to a week. Finally, if the user is still causing trouble after all these warnings, they will be permanently banned from the board.<br><br>Harsher bans may be issued if a user racks up multiple warnings in a short amount of time or does something idiotic such as flooding the board or posting questionable material.<br><br>Note that if you register another account to post with during a ban, you will be permanently banned on the spot. As well, if you think it's fun to constantly harass us on the board, keep in mind that we can and will contact your ISP and lodge a formal complaint. Many ISP's take harassment very seriously and may deactivate your account.
	 $cell1=07>$q07
	 $tab First off, don't complain on the board about a thread being closed or deleted, and don't single out a certain mod or admin as being responsible. This will result in a ban. Learn from your mistakes and move on.<br><br>If you feel you need clarification on why the thread was closed or if you feel a mistake was made, contact a local mod in charge of the forum or an admin and politely explain the problem.
	 $cell2=08>$q08
	 $tab First off, DO NOT create a new account to complain about your ban, as that will just result in a permanent ban from the board. If you feel that the ban was unfair or if you wish to know exactly why you were banned, PM an admin and calmly ask about it. Aside from that, the only thing you can do is wait for the ban to expire and make sure you know why it happened.
	 $cell1=09>$q09
	 $tab Yes, it uses cookies to store your login information. You can still use this board with cookies disabled, but you'll have to enter your username and password every time it's asked, and some features may not be available.
	 $cell2=10>$q10
	 $tab Yes, it can be used in posts, private messages, nearly everywhere except in things such as thread titles, usernames, etc.
	 $cell1=11>$q11
	 $tab Yes, but it's a bit limited. Here's what can be used: (most are case sensitive)<br>
	 [b]<b>Bold text</b>[/b]<br>
	 [i]<i>Italic text</i>[/i]<br>
	 [u]<u>Underlined text</u>[/u]<br>
	 [s]<s>Stroke text</s>[/s]<br>
	 [img]URL of image to display[/img]<br>
	 [url]URL of site or page to link to[/url]<br>
	 And several color codes such as ".doreplace2("
	 [red][<z>red][/color], [green][<z>green][/color], [blue][<z>blue][/color], [orange][<z>orange][/color], [yellow][<z>yellow][/color], [pink][<z>pink][/color], [white][<z>white][/color], and [black][<z>black][/color]
	 ",1,1,"")." (put [/color] to revert to normal color)
	 $cell2=12>$q12
	 $tab There are a few simple rules which all users should follow in their layouts:<br>
	<br>- No flashy backgrounds which make text difficult to read. This doesn't mean you can't have a nice background, but at least make sure that the area where the text is doesn't make a post hard to read.
	<br>- No huge pictures or excessive amounts of GIFs. Huge pictures just make threads longer to load and having too many GIFs can slow down some computers.
	<br>- No broken tables. If you're working with tables and feel that you might be messing up, check your post in your user profile. If the profile seems to be messed up, then the layout is as well. Broken tables can wreck havoc with threads.
	<br><br>If you have any questions about layouts in general or need help, visit the <a href=forum.php?id=22>Modern Art</a> forum.
	 $cell1=13>$q13
	 $tab EXP is calculated from your amount of posts and how long it's been since you registered. Level is calculated from EXP. You gain increasing amounts of EXP by posting, and by being registered longer.
	 $cell2=14>$q14
	 $tab In a way similar to HTML and the markup replacements (described above), just put those where you want the numbers to be:<br>
	<center><table><td>$tblstart
	  <tr>$tccellhs>Tag</td>$tccellhs>Value
	  <tr>$tccell2ls>&numposts&	</td>$tccell1ls>Current post count
	  <tr>$tccell2ls>&numdays&	</td>$tccell1ls>Number of days since registration
	  <tr>$tccell2ls>&level&	</td>$tccell1ls>Level
	  <tr>$tccell2ls>&exp&		</td>$tccell1ls>EXP
	  <tr>$tccell2ls>&expdone&	</td>$tccell1ls>EXP done in the current level
	  <tr>$tccell2ls>&expdone1k&	</td>$tccell1ls>EXP done / 1000
	  <tr>$tccell2ls>&expdone10k&	</td>$tccell1ls>EXP done / 10000
	  <tr>$tccell2ls>&expnext&	</td>$tccell1ls>Amount of EXP left for next level
	  <tr>$tccell2ls>&expnext1k&	</td>$tccell1ls>EXP needed / 1000
	  <tr>$tccell2ls>&expnext10k&	</td>$tccell1ls>EXP needed / 10000
	  <tr>$tccell2ls>&exppct&	</td>$tccell1ls>Percentage of EXP done in the level
	  <tr>$tccell2ls>&exppct2&	</td>$tccell1ls>Percentage of EXP left in the level
	  <tr>$tccell2ls>&expgain&	</td>$tccell1ls>EXP gain per post
	  <tr>$tccell2ls>&expgaintime&</td>$tccell1ls>Seconds for 1 EXP when idle
	  <tr>$tccell2ls>&lvlexp&	</td>$tccell1ls>Total EXP amount needed for next level
	  <tr>$tccell2ls>&lvllen&	</td>$tccell1ls>EXP needed to go through the current level
	  <tr>$tccell2ls>&5000&		</td>$tccell1ls>Posts left until you have 5000
	  <tr>$tccell2ls>&20000&	</td>$tccell1ls>Posts left until you have 20000
	  <tr>$tccell2ls>&rank&		</td>$tccell1ls>Current rank, according to your amount of posts
	  <tr>$tccell2ls>&postrank&	</td>$tccell1ls>Post ranking
	  <tr>$tccell2ls>&postrank10k&</td>$tccell1ls>Post ranking you'd have with 10000 less posts
	  <tr>$tccell2ls>&postrank20k&</td>$tccell1ls>Post ranking you'd have with 20000 less posts
	  <tr>$tccell2ls>&date&		</td>$tccell1ls>Current date
	 $tblend</table></center>
	 $cell1=15>$q15
	 $tab The syndromes are based on how many posts you have made in the last 24 hours. The amount of posts required for each syndrome is as follows:<br>
	  <center><table><td>$tblstart
	    <tr>$tccellhs>Posts</td>$tccellhs>Syndrome
	    <tr>$tccell2s> 75</td>$tccell1ls><x".syndrome( 75)."
	    <tr>$tccell2s>100</td>$tccell1ls><x".syndrome(100)."
	    <tr>$tccell2s>150</td>$tccell1ls><x".syndrome(150)."
	    <tr>$tccell2s>200</td>$tccell1ls><x".syndrome(200)."
	    <tr>$tccell2s>250</td>$tccell1ls><x".syndrome(250)."
	    <tr>$tccell2s>300</td>$tccell1ls><x".syndrome(300)."
	    <tr>$tccell2s>350</td>$tccell1ls><x".syndrome(350)."
	    <tr>$tccell2s>400</td>$tccell1ls><x".syndrome(400)."
	    <tr>$tccell2s>450</td>$tccell1ls><x".syndrome(450)."
	    <tr>$tccell2s>500</td>$tccell1ls><x".syndrome(500)."
	    <tr>$tccell2s>600</td>$tccell1ls><x".syndrome(600)."
	  $tblend</table></center>
	  Any other \"syndromes\" you may see such as \"Cute Kitten Syndrome ++\" are not syndromes; they are simply a custom title that someone else has decided to take.<br> Don't forget that spamming in an attempt to gain these syndromes will result in a warning or a ban. The only right way to gain a syndrome is by making clear, non-spammy posts.
	 $cell2=16>$q16
	 $tab The ACS stands for Acmlm Championship Series, and is a \"competition\" run by Colin. Each day the top ten posters (and ties) on the board are given points. The top poster each day receives 10 points and the 10th placed poster receives 1. At around midnight Eastern time each day, the rankings are compiled and posted in the Craziness Domain. \"Awards\" are given out at the end of each month. Note that the ACS is just for fun and people really shouldn't be posting just to rank in it. As well, the points mean absolutely nothing in the grand scheme of things. You can't exchange them for anything. That being said, the ACS has been a success on the board and thankfully we haven't had to ban too many people for spamming to get a top spot. Let's hope it stays that way.
	 $cell1=17>$q17
	 $tab It's all listed on the <a href=ranks.php>Ranks page</a>, which also lists all moderators and administrators on this board. Ranks nobody is on and which you didn't reach aren't listed (to keep some suspense), unless you're a full moderator (moderating all the forums) or administrator on this board.
	 $cell2=18>$q18
	 $tab You can get one under one of those conditions:<br>
	 - Be a moderator or administrator<br>
	 - Have at least 2000 posts<br>
	 - Have at least 1000 posts, and have been registered for at least 200 days<br>
	 There may be a few rare exceptions, but asking for a custom title before having the requirements for it won't get you one.
	 $cell1=19>$q19
	 $tab If us administrators trust you enough for this. Don't ask us, we may ask you if we ever feel you worthy of being promoted. Being a good and regular member helps, while asking for this doesn't. It also depends whether we feel a need to promote more people, which isn't so often the case.
	 $cell2=20>$q20
	 $tab Announcements are general messages posted by administrators only. Everybody can view them, but not reply to them.
	 $cell1=21>$q21
	 $tab Yes, just send or post a picture of yourself and I may add it. Only actual photographs are accepted.
	 $cell2=22>$q22
	 $tab You can't change it yourself, only administrators can, but you can ask one of them for a name change.
	 $cell1=23>$q23
	 $tab They vary depending on the gender and power level:<table>
	 <td width=200>$fonttag<b><font ".getnamecolor(0,'-1').">Banned<br><font ".getnamecolor(0,0).">Regular, male<br><font ".getnamecolor(0,1).">Local moderator, male<br><font ".getnamecolor(0,2).">Full moderator, male<br><font ".getnamecolor(0,3).">Administrator, male</td>
	 <td width=200>$fonttag<b><font ".getnamecolor(1,'-1').">Banned<br><font ".getnamecolor(1,0).">Regular, female<br><font ".getnamecolor(1,1).">Local moderator, female<br><font ".getnamecolor(1,2).">Full moderator, female<br><font ".getnamecolor(1,3).">Administrator, female</td>
	 <td width=200>$fonttag<b><font ".getnamecolor(2,'-1').">Banned<br><font ".getnamecolor(2,0).">Regular, unspecified<br><font ".getnamecolor(2,1).">Local moderator, unspec.<br><font ".getnamecolor(2,2).">Full moderator, unspec.<br><font ".getnamecolor(2,3).">Administrator, unspec.</td></table>
	 $cell2=24>$q24
	 $tab Here's the smilies that can be used, and what you must type to use them: $smilielist
	 $cell1=25>$q25
	 $tab Well ... there is no released copy of it that can be downloaded, so the only way you can get one is by getting a copy from someone who has it. Also, the latest distributed version is 1.8, and the current version isn't in a distributable state (some beta versions have been given but they're exceptional cases). You may have a better chance of getting a copy if you aren't some random newcomer who comes just to ask for the board; many requests stay ignored. Also, if you ever get a copy, keep in mind you need some place to run it on, and need support for PHP and MySQL. AcmlmBoard doesn't have an admin control panel either, so you may be screwed if you don't know how to mess around with the .php files to configure a few things, and MySQL database managing software (such as PHPMyAdmin) can be very useful.<br><br>
	$tblend
	$footer
  ";
  printtimedif($startingtime);
?>
