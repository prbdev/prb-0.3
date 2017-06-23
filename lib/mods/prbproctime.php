<?php

 Class prbproctime extends Info {

    var $moduleVars;
    var $varVal;

    function setVars() {
        #
        # setup which vars are used by this module
        #
        $this->moduleVars = array_merge( 
            $this->reqVars,
            array(
            'description' => 'opt'
            ));
    }

    function CreateDB() {

        $this->createOpts = array_merge(
            array ( 
            "DS:proct:GAUGE:600:U:U"
            ),
            $this->stdrra);

        if( $this->debug ) {print "Creating $this->rrdFile...\n";}
        return rrd_create($this->rrdFile, $this->createOpts, count($this->createOpts));
    }

    function Update() {

        $proct = $this->varVal;

        $this->outtext = "N:$proct";
        $ret = rrd_update($this->rrdFile,"N:$proct");
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
        "-v Time (s)",
        "-s ".$time,
        "DEF:proct=".$this->rrdFile.":proct:AVERAGE",
        "AREA:proct#00CC00: PRB processing time\\t",
        "GPRINT:proct:LAST:Current\\: %8.3lf %ss\\t",
        "GPRINT:proct:MAX:Max\\: %8.3lf %ss\\t",
        "GPRINT:proct:AVERAGE:Average\\: %8.3lf %ss\\l",
        $this->lastUpdate())
        );

		$ret =  rrd_graph( $this->pngFilePre."-".$ext.".png", $graph, count($graph) );
        if (! is_array($ret) and $this->debug ) {
            $err = rrd_error();
            print "rrd_graph() ERROR: $err<br>";
            print_r($graph);
            print "<br>".$this->pngFilePre."-".$ext.".png<p>";
        }
	}
}
?>
