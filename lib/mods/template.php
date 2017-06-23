<?php

 // Change modname to you module name. It must be the same
 // as the filename without the .php extension.
 Class modname extends Info {

    var $moduleVars;

    function setVars() {
        #
        # setup which vars are used by this module
        #
        $this->moduleVars = array_merge( 
            $this->reqVars,                 // specify which variables are required
            array(                          // and which are optional.
            'name'        => 'req',         // name is always required
            'host'        => 'req',         // host and community most of the time too.
            'community'   => 'req',
            'ifIndex'     => 'req',
            'description' => 'opt',
            'sysDescr'    => 'opt',
            'in'          => 'opt',
            'out'         => 'opt',
            'limit'       => 'opt',
            ));
    }

    function CreateDB() {

        $this->createOpts = array_merge(
            array ( 
            "DS:input:COUNTER:600:U:U",     // This is where you define the rrds
            "DS:output:COUNTER:600:U:U"     // 
            ),
            $this->stdrra);

        if( $this->debug ) {print "Creating $this->rrdFile...\n";}
        return rrd_create($this->rrdFile, $this->createOpts, count($this->createOpts));
    }

    function Update() {

        $if  = $this->ifIndex;
        $in  = snmpget($this->host, $this->community, ".1.3.6.1.2.1.2.2.1.10.".$if);
        $out = snmpget($this->host, $this->community, ".1.3.6.1.2.1.2.2.1.16.".$if);

        $this->outtext = "N:$in:$out";
        $ret = rrd_update($this->rrdFile,"N:$in:$out");
        if ( $ret == 0 ) {
            $err = rrd_error();
            echo "ERROR occurred: $err\n";
        }
        return $ret;
    }

    function Graph($ext, $time, $w=W, $h=H) {

        $graph = array_merge(
        $this->graphDefOpts, array (
        "-w", $w, "-h", $h,
        "-t", $this->description,
        "-v Bandwidth",                                         // ........
        "-s ".$time,                                            // Replace
        "DEF:input=".$this->rrdFile.":input:AVERAGE",           //  these
        "DEF:output=".$this->rrdFile.":output:AVERAGE",         //  lines
        "CDEF:inbits=input,8,*",                                //  with
        "CDEF:outbits=output,8,*",                              //  your   
        "AREA:inbits#00CC00:  Incomming traffic\\t",            //  own
        "GPRINT:inbits:LAST:Current\\: %8.3lf %sbps\\t",        //  graph
        "GPRINT:inbits:MAX:Max\\: %8.3lf %sbps\\t",             //  definition.
        "GPRINT:inbits:AVERAGE:Average\\: %8.3lf %sbps\\l",     //  Use this 
        "LINE2:outbits#0000FF:  Outgoing traffic\\t",           //  as
        "GPRINT:outbits:LAST:Current\\: %8.3lf %sbps\\t",       //  an
        "GPRINT:outbits:MAX:Max\\: %8.3lf %sbps\\t",            //  example.
        "GPRINT:outbits:AVERAGE:Average\\: %8.3lf %sbps\\l",    // .........
        $this->lastUpdate())
        );

		$ret =  rrd_graph( $this->pngFilePre."-".$ext.".png", $graph, count($graph) );
	}
}
?>
