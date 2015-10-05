<?PHP
	class pageDesigner{
		
		public function pageMenu() {
			return '
					<script>
					$("#jqxMenu").jqxMenu({ width:window.innerWidth-5, height: "30px", theme:"custom-zandro", autoOpen:false});
					</script>
					<div id="jqxMenu" >
						<ul>
							<li><img  src="../assets/images/icons/icol16/src/house.png" alt=""/><a href = "index.php"> Home</a></li>
							<li><img  src="../assets/images/icons/icol16/src/zone_money.png" alt="" /><a href = "#">Daily Transactions</a></li>
							<li id = "newConsumer"><img  src="../assets/images/icons/icol16/src/group.png" alt=""/>New Consumer</li>
							<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock.png"> Logout</li>
						</ul>
					</div>';
		}
	}
?>