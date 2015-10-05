<?PHP
class includes{
    function __construct() {
        define("_BASE_", "http://".$_SERVER["HTTP_HOST"]."/test");
        define("_SELF_", "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER['PHP_SELF']));
    }

    public function includeCSS() {
        return '<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.base.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.highcontrast.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.orange.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.metro.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.metrodark.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.darkblue.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/jqx.web.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/custom-menu.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/custom-panel.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/custom-button.css" type="text/css" />
					<link rel="stylesheet" href="'._BASE_.'/assets/jqwidgets/styles/main-theme.css" type="text/css" />
					
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
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/localization.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/scripts/demos.js"></script>
					<script type="text/javascript" src="'._BASE_.'/assets/jqwidgets/jqx-all.js"></script>';
    }

    public function includeJSFn($page) {
        return '<script type="text/javascript" src="'._SELF_.'/js/'.$page.'.js"></script>';
    }
}
?>