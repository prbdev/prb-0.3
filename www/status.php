<?php
// $Id: status.php,v 1.5 2006/11/01 07:47:58 guizy Exp $ 
//
?>
<div id=subMenu>
Status Overview
</div>
<div style='clear:both; height:20px'></div>

<?php
#
# start processing
#
 $sql = "select count(*) from host,info 
            where host.name=info.host and 
                    host.status = 'polling' and 
                    info.status='polling'";

 $res = mysql_query($sql);
 $s = mysql_fetch_row($res);
 $inst = $s[0];

 $sql = "select count(*) from host where status = 'polling'";
 $res = mysql_query($sql);
 $s = mysql_fetch_row($res);
 $poll = $s[0];

 $sql = "select count(*) from host where status='maintenance'";
 $res = mysql_query($sql);
 $s = mysql_fetch_row($res);
 $maint = $s[0];

 $sql = "select count(*) from host where status='incident'";
 $res = mysql_query($sql);
 $s = mysql_fetch_row($res);
 $inc = $s[0];

 print "<div class=header>Host statistics</div>";
 print "<div id=stats>
        <table>
        <tr><td>Number of hosts being polled</td><td align=right>$poll</td></tr>
        <tr><td>Number of hosts in maintenance (not polled)</td><td align=right>$maint</td></tr>
        <tr><td>Number of hosts in incident status (not polled)</td><td align=right>$inc</td></tr>
        <tr><td>Total number of hosts</td><td align=right>".($poll + $maint + $inc)."</td></tr>
        <tr><td>Total number of instances being polled</td><td align=right>$inst</td></tr>
        </table>
        </div>";


 $id   = $_REQUEST['id'];
 if( $id!='' ) {
    $sql  = "select * from prbStats where id='$id' ";
    $res  = mysql_query($sql)       or $w->errHandler("Error: ".mysql_error()."<BR>", "die");
    $info = mysql_fetch_assoc($res) or $w->errHandler("Error: No rows returned by <div class=code>$sql</div><BR> ".mysql_error()."<BR>", "die");
    $mod  = $info['module'];

    if(! is_file($cfg->modPath."/$mod.php") ) {
        $w->errHandler("Error: Can't load $cfg->modPath/$mod.php... It's not a file.<P>", "die");
    }
    require_once $cfg->modPath."/$mod.php";

    $m = new $mod($info);
    $m->debug = true;
    $m->rrdChkPath();
    $m->pngChkPath();
    $m->Graph("8hours",  -28800);
    $m->Graph("1day",  -86400);
    $m->Graph("1week", -604800);
    $m->Graph("8weeks",-4838400);
    //$m->Graph("2years",-63072000);


print $w->errorMsg;
print $m->hostDescription ."<p>";
?>
<img src=<?php echo $m->pngURL.'-8hours.png'   ?>>
<img src=<?php echo $m->pngURL.'-1day.png'   ?>>
<img src=<?php echo $m->pngURL.'-1week.png'  ?>>
<img src=<?php echo $m->pngURL.'-8weeks.png' ?>>
<!--
<img src=<?php echo $m->pngURL.'-2years.png' ?>>
/-->
<?php 
    require "footer.php";
    exit();
 }

    $sql = "select * from prbStats order by id";
    $res = mysql_query($sql);
    while( $info = mysql_fetch_assoc($res) ) {
        $mod = $info['module'];
        if(! is_file($cfg->modPath."/$mod.php") ) {
            print "Can't load $cfg->modPath/$mod.php<P>";
        }
        require_once $cfg->modPath."/$mod.php";
        $m = new $mod($info);
        $m->rrdChkPath();
        $m->pngChkPath();
        $m->Graph("1day_TN", -86400, $cfg->TNWidth, $cfg->TNHeight);
        print "<p><b>$m->name $m->description</b><p>\n";
        print "<a href=$_SERVER[PHP_SELF]?p=status.php&id=$m->id>";
        print "<img src=$m->pngURL-1day_TN.png></a><br>\n";
        //flush();
    }
    require "footer.php";
    exit();

?>
