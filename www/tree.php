<script type="text/javascript">
 window.addEvent('domready', function() {
    mkTree('content');
    //addTreeEvents('customtree');
    //addSpanEvents('customtree');
 });
 // Save treestate to php session
 window.addEvent('beforeunload', function() {
    saveTreeState('customtree');
 });
</script>
<style type="text/css" media="screen">
#C_0 {
	height: 800px;
	overflow: auto;
}
</style>
</head>
<div id=subMenu>
Browse by custom views (sql queries)
</div>
<div style='clear:both; height:20px'></div>
<div id="wrapper" style="border:1px solid #E0E0E0; width: 100%;">
    <div id="container">
        <div id="content">
	</div>
     </div>
	
     <div id="sidebar">
          <div id=right>
           <div id=view_head> View Pane </div>
           <div id=C_0>
             <div id=topContent>
		content goes here
             </div>
           </div>
          </div>
     </div>
     <div class="clearing">&nbsp;</div>
</div>
