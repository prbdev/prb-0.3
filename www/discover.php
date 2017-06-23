<?php
// $Id: discover.php,v 1.11 2006/08/29 10:23:39 guizy Exp $
//
 $startTime = preg_replace('/^0?(\S+) (\S+)$/X', '$2$1', microtime());

 $mode = $_REQUEST[mode];
 if( $mode == '' ) $mode = 'snmp';

 function setSel($m) {
    global $mode;
    if( $mode == $m ) return "sel";
    return "nosel";
 }
?>
<div id=subMenu>
Add new host to poll: 
<a class=<?php echo setSel('snmp');?> href=?p=discover.php&mode=snmp> discover by snmp </a> | 
<a class=<?php echo setSel('manual');?> href=?p=discover.php&mode=manual> add by hand </a>
</div>
<div style='clear:both; height:20px'></div>
<div id=infoBox>
<?php

if( $mode == 'manual' ) {
    print $w->manualDiscoveryForm();
} elseif( $mode == 'snmp' ) {
    print $w->snmpDiscoveryForm();
} 

?>
