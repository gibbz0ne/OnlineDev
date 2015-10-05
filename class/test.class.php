<?PHP
	class includes{
		function __construct() {
			define("_BASE_", "http://".$_SERVER["HTTP_HOST"]."/projecty");
			define("_SELF_", "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER['PHP_SELF']));
		}
		
		public function includeCSS() {
			return '<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.base.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.highcontrast.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.highcontrast.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.orange.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.metro.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.metrodark.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.darkblue.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.web.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/custom-menu.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/custom-panel.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/custom-button.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/custom-zandro.css" type="text/css" />
					
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/custom-abo-ogm.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/custom-abo-ao.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/custom-abo-tsd.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/custom-abo-mmd.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/custom-abo-urd.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/custom-abo-inspector.css" type="text/css" />
					
					<link rel="stylesheet" href="'._BASE_.'/assets/css/custom-kiks.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/css/abo.css" type="text/css" />
					
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/custom-orange.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/custom-blue.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/styles/bootstrap.min.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.energyblue.css" type="text/css" />';
		}
		
		public function includeJS() {
			return '<script type="text/javascript" src="'._BASE_.'/assets/scripts/jquery-1.11.1.min.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxcore.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxtree.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxsplitter.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxbuttons.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxpanel.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxlistbox.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxexpander.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxscrollbar.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxdocking.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxdatetimeinput.js"></script> 
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxcalendar.js"></script> 
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxwindow.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxbuttons.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxpanel.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxmenu.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxgrid.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxgrid.selection.js"></script>	
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxgrid.columnsresize.js"></script> 
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxgrid.edit.js"></script> 
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxgrid.filter.js"></script>		
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxgrid.columnsresize.js"></script> 
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxdata.js"></script>	
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxlistbox.js"></script>	
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxdropdownlist.js"></script>	
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxgrid.pager.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxinput.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxnumberinput.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxFileUpload.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxgrid.selection.js"></script> 
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/localization.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxcombobox.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxdata.export.js"></script> 
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxgrid.export.js"></script> 
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxtabs.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxgrid.sort.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxgrid.grouping.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxswitchbutton.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxcheckbox.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/scripts/demos.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxchart.js"></script>	
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxdata.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxdragdrop.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxvalidator.js"></script> 
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqxnavigationbar.js"></script> 
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqx-all.js"></script>';
		}
		
		public function includeJSFn($page) {		
			return '<script type="text/javascript" src="'._SELF_.'/js/'.$page.'.js"></script>';
		}
		
		public function test() {
			return _SELF_;
		}
	}
?>