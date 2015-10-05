<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	session_start();
	// if(!isset($_SESSION['userId'])){
		// header("Location:../index.php");
	// }
	// else {
		// if($_SESSION['usertype'] != "urd") {
			// header("Location:../".$_SESSION['usertype']);
		// }
	// }

	include "../class/includes.class.php";
	$include = new includes();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="description" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/x-icon" href="../assets/images/icons/icon.png" />
		<title id="Description">CHANGE METER/NEW CONNECTION</title>
	
		<?PHP
			echo $include->includeCSS();
			echo $include->includeJS();
		?>

		<script>
			$(document).ready(function(){
				$("#jqxMenu").jqxMenu({width: window.innerwidth, theme:"main-theme"});

				$("#mainSplitter").jqxSplitter({
					width:window.innerWidth-7,
					height:window.innerHeight-40,
					resizable:true,
					orientation: "horizontal",
					panels: [{ size:"90%",collapsible:false  },
						{ size: "10%",collapsible: false }]
				});

				var consumers = {
					datatype: "json",
					datafields: [
						{name: "status"},
						{name: "acctNo"},
						{name: "consumerName"},
						{name: "address"},
						{name: "municipality"},
						{name: "area"},
						{name: "type"},
						{name: "car"},
						{name: "cid"},
						{name: "appId"},
						{name: "tid"}
					],
					url: "sources/accounts.php",
					async: false
				}
				
				// setInterval(function(){
					// var dataAdapter = new $.jqx.dataAdapter(consumers);
					// $('#consumerList').jqxGrid({source:dataAdapter});
				// },3000);
				
				var consumer_data = new $.jqx.dataAdapter(consumers);

				$("#consumerList").on("contextmenu", function(){
					return false;
				});
				
				$("#consumerList").on("rowselect", function(){
					$("#assign").jqxButton({disabled: false});
				});
				
				$("#consumerList").jqxGrid({
					source: consumer_data,
					height: "100%",
					width: "100%",
					theme: "main-theme",
					pageable: true,
					showtoolbar: true,
					rendertoolbar: function(toolbar){
						var me = this;
						var container = $("<div style='margin: 5px;'></div>");
						var span = $("<span style='float: left; margin-top: 5px; margin-right: 4px;'>Search : </span>");
						var input = $("<input class='jqx-input jqx-widget-content jqx-rc-all' id='searchField1' type='text' style='height: 23px; float: left; width: 223px;' />");
						var searchButton = $("<div style='float: left; margin-left: 5px;' id='search'><img style='position: relative; margin-top: 2px;' src='../assets/images/search_lg.png'/><span style='margin-left: 4px; position: relative; top: -3px;'></span></div>");
						
						toolbar.append(container);
						container.append(span);
						container.append(input);
						container.append(searchButton);
						
						$("#search").jqxButton({theme:"custom-abo-ao",height:18,width:24});

						if (theme != "") {
							input.addClass("jqx-widget-content-" + theme);
							input.addClass("jqx-rc-all-" + theme);
						}
						$("#search").click(function(){
							$("#consumerList").jqxGrid('clearfilters');
							var datafield = "consumerName";
							var searchText = $("#searchField1").val();
							var filtergroup = new $.jqx.filter();
							var filter_or_operator = 1;
							var filtervalue = searchText;
							var filtercondition = 'contains';
							var filter = filtergroup.createfilter('stringfilter', filtervalue, filtercondition);
							filtergroup.addfilter(filter_or_operator, filter);
							$("#consumerList").jqxGrid('addfilter', datafield, filtergroup);
							$("#consumerList").jqxGrid('applyfilters');
						});
						
						var oldVal = "";
						input.on('keydown', function (event) {
							var key = event.charCode ? event.charCode : event.keyCode ? event.keyCode : 0;
								
							if (key == 13 || key == 9) {
								$("#consumerList").jqxGrid('clearfilters');
								var datafield = "consumerName";
								var searchText = $("#searchField1").val();
								var filtergroup = new $.jqx.filter();
								var filter_or_operator = 1;
								var filtervalue = searchText;
								var filtercondition = 'contains';
								var filter = filtergroup.createfilter('stringfilter', filtervalue, filtercondition);
								filtergroup.addfilter(filter_or_operator, filter);
								$("#consumerList").jqxGrid('addfilter', datafield, filtergroup);
								$("#consumerList").jqxGrid('applyfilters');
							}
						   
							if(key == 27){
								$("#consumerList").jqxGrid('clearfilters');
								return true;
							}
						});
					},
					ready: function(){
						$('#consumerList').jqxGrid('hidecolumn', 'cid');
					},
					columns: [
						{text: "Primary Account No.", pinned: true, align: "center", cellsalign: "center", datafield: "acctNo", width: 150},
						{text: "Consumer Name", pinned: true, align: "center", datafield: "consumerName", width: 300},
						{text: "Address", align: "center", datafield: "address", width: 350},
						{text: "Municipality", align: "center", cellsalign: "center", datafield: "municipality", width: 150},
						{text: "Area", align: "center", cellsalign: "center", datafield: "area", width: 100},
						{text: "Type", align: "center", datafield: "type", width: 100},
						{text: "C.A.R", align: "center", datafield: "car"}
					]
				});
				
				$('#acctNo').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
				
				$("#logout").click(function(){
			        $.ajax({
			            url: "../logout.php",
			            success: function(data){
			                if(data == 1){
			                    // $("#processing").jqxWindow("open");
			                    // setTimeout(function(){
			                        window.location.href = "../index.php";
			                    // }, 1000);
			                }
			            }
			        });
			    });
			});
		</script>
	</head>
	<body class="default">
		<div class = "row push-right-m2">
			<div id="jqxMenu">
				<ul>
					<li><img  src="../assets/images/icons/icol16/src/house.png" alt=""/><a href = "index.php"> Home</a></li>
					<li><img  src="../assets/images/icons/icol16/src/user.png" alt=""/><a href = "accounts.php"> Accounts</a></li>
					<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock.png"> Logout</li>
				</ul>
			</div>
			<div id = "mainSplitter">
				<div class="splitter-panel">
					<div id = "consumerList"></div>
				</div>
				<div class="splitter-panel">
					<div id = "woList"></div>
				</div>
			</div>
        </div>
	</body>
</html>