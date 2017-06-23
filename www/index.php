<?php
// $Id: index.php,v 1.10 2006/08/29 05:51:31 guizy Exp $
//
 $startTime = preg_replace('/^0?(\S+) (\S+)$/X', '$2$1', microtime());
// require_once 'HTML/TreeMenu.php';
 require "../etc/prbconfig.php";
 require $cfg->libPath."/Info.php";
 require $cfg->libPath."/Web.php";

 session_start();

#
# Setup database connection
#
 $cnn_id = mysql_connect($cfg->dbHost, $cfg->dbUser, $cfg->dbPass);
 mysql_select_db($cfg->dbName, $cnn_id);

 $w = new Web();

 $p = $_REQUEST['p'];
 if( !$p ) $p="$cfg->defaultPage";

 $pValid = false;
 function setClass($href) {
    global $p, $pValid;
    if( $p == $href ) {
        $class = 'current';
        $pValid = true;
    } else {
        $class = 'noSel';
    }
    return $class;
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="author" content="Guillaume Fontaine" />
	<meta name="copyright" content="copyright 2006-2007 prb.sourceforge.net" />
	<meta name="description" content="prd, php rrdtool browser" />
	<meta name="keywords" content="rrdtool, snmp, graphing, php, mysql" />
	<meta name="robots" content="all" />
<link href='<?=$cfg->CSS;?>'   type='text/css' rel='stylesheet' media='screen'>
<!--[if IE]>
  <link rel="stylesheet" type="text/css" href="css/ie_hacks.css" media="screen" />
<![endif]-->
<script type="text/javascript" src="js/mootools.js"></script>
<script type="text/javascript" src="js/prb.js"></script>
</head>
<body id=body>
<div id=topMenu>
  <a href=http://prb.sourceforge.net/><img style='float:left;' src=images/prbLogo.png></a>
  <img id=logo style='float:right;' src='images/YOURLOGO.gif' alt='YOUR LOGO'>
  <div id=stats>
   <?php // status indicators
     echo $w->statusTable();
   ?>
  </div>

  <div style='clear:both;height:12px;'></div>
  <div id="horiz-menubar">
     <ul id=menu class=solidblockmenu>
      <li><a class='<?php echo setClass('status.php');?>'       href='?p=status.php'>Status </a> </li>
      <li><a class='<?php echo setClass('uptimeReport.php');?>' href='?p=uptimeReport.php'>Uptime report </a> </li>
      <li><a class='<?php echo setClass('browse.php');?>'       href='?p=browse.php'> Browse </a> </li>
      <li><a class='<?php echo setClass('tree.php');?>'         href='?p=tree.php'> Custom views </a> </li>
      <li><a class='<?php echo setClass('discover.php');?>'     href='?p=discover.php'>Host discovery </a> </li>
      <!-- li><a class='<?php echo setClass('moduleUpdate.php');?>' href='?p=moduleUpdate.php'>Update module </a> </li //-->
     </ul>
  </div>
</div>

<div id=main>
  <?php
    if( $pValid ) {
        include "$p";
    } else {
        print "<div class=warning>$p is not a valid page...</div>";
    }
  ?>

</div>

<?php
 require "footer.php";
?>
