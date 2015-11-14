<?PHP
	include "../../class/connect.class.php";
	$conn = new getConnection();
	$db = $conn->PDO();
	
	if($_POST["form"] == "NC") {
		$trans = $_POST["trans"];
		$res = $db->query("SELECT *FROM tbl_transactions a 
							LEFT OUTER JOIN tbl_applications b ON a.appId = b.appId 
							LEFT OUTER JOIN tbl_app_type c ON b.appId = c.appId 
							LEFT OUTER JOIN tbl_temp_consumers d ON b.cid = d.cid 
							WHERE a.tid = $trans");
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
		
		$type = $row[0]["typeId"];
		$acct = $row[0]["AccountNumberT"];
		$cid = $row[0]["cid"];
		
	}
	else {
		$type = $_POST["con"];
		$acct = $_POST["acct"];

		
		$res = $db->query("SELECT *FROM tbl_temp_consumers WHERE AccountNumberT = $acct");
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
	}
	
	$name = $row[0]["AccountNameT"];
	$address = $row[0]["AddressT"];
	$accountNum = $row[0]['AccountNumberT'];
	
	$res = $db->query("SELECT *FROM tbl_type WHERE typeId = $type");
	$rowT = $res->fetchAll(PDO::FETCH_ASSOC);
	
	$res = $db->query("SELECT *FROM tbl_service WHERE typeId = $type");
	$rowS = $res->fetchAll(PDO::FETCH_ASSOC);
	
	$res = $db->query("SELECT *FROM tbl_type_undertake a LEFT OUTER JOIN
						tbl_undertake b ON a.undertakeId = b.undertakeId
						WHERE typeId = $type");
	$rowU = $res->fetchAll(PDO::FETCH_ASSOC);
	
	$res = $db->query("SELECT *FROM tbl_type_fee a LEFT OUTER JOIN
						tbl_fee b ON a.feeId = b.feeId
						WHERE typeId = $type");
	$rowF = $res->fetchAll(PDO::FETCH_ASSOC);
	
	$res = $db->query("SELECT *FROM tbl_type_reason a LEFT OUTER JOIN
						tbl_reasons b ON a.reasonId = b.reasonId
						WHERE typeId = $type");
	$rowR = $res->fetchAll(PDO::FETCH_ASSOC);
	
	// $res = $db->query("select c.conDesc, d.subDesc from consumers a
						// LEFT OUTER JOIN tbl_consumer_connection b ON a.cid = b.cid
						// LEFT OUTER JOIN tbl_connection_type c ON b.conId = c.conId
						// LEFT OUTER JOIN tbl_connection_sub d ON b.subId = d.subId
						// WHERE a.cid = $cid");
						// WHERE a.AccountNumber = $acct");
	// $rowCT = $res->fetchAll(PDO::FETCH_ASSOC);
?>

<form id="frmSO">
	<div style="height:490px; overflow-y: scroll; border: thin solid; padding: 10px;" id="divSO">
		<div style="">
			<table width="100%">
				<thead>
					<tr>
						<td colspan="4" align="center">
							<h4 style="font-weight:bold;">Service Order for <?PHP echo $rowT[0]["typeDesc"]; ?></h4>
							<?PHP echo $rowT[0]["typeDesc"]; ?>
						</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>S.O. No.:</td>
						<td width="300"><input id="txtControl" name="txtControl" class="jqx-input jqx-widget-content jqx-rc-all" type="text" placeholder="<?PHP echo $rowT[0]["typeCode"]; ?>"></input></td>
						<td>Date Issued:</td>
						<td width="200" align="right"><div style="width:100%; border-bottom:thin solid;"><strong><?PHP echo date("Y-m-d"); ?></strong></div></td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr>
						<td>Name:</td>
						<td><div style="width:100%; border-bottom:thin solid;"><strong><?PHP echo $name; ?></strong></div></td>
						<td>Account No.:</td>
						<td align="right"><div style="width:100%; border-bottom:thin solid;"><strong><?PHP echo $accountNum; ?></strong></div></td>
					</tr>
					<tr>
						<td>Address:</td>
						<td colspan="3"><div style="width:100%; border-bottom:thin solid;"><strong><?PHP echo $address; ?></strong></div></td>
					</tr>
					<tr>
						<td>Type:</td>
						<td colspan="3"><div style="width:100%; border-bottom:thin solid;"><strong><?PHP echo $row[0]["CustomerTypeT"]; ?></strong></div></td>
						<!--td colspan="3"><div style="width:100%; border-bottom:thin solid;"><strong><!?PHP echo $rowCT[0]["conDesc"].($rowCT[0]["subDesc"] ? " - ".$rowCT[0]["subDesc"] : ""); ?></strong></div></td-->
					</tr>
				</tbody>
			</table>
			<br>
			<table width="100%" <?PHP echo (count($rowS) > 1 ? "" : "hidden"); ?> >
				<tr>
					<td colspan="<?PHP echo count($rowS); ?>" width="100px;">Service Type:</td>
				</tr>
				<tr>
					<?PHP
						foreach($rowS as $service) {
							echo '<td><div id="s-'.$service["serviceId"].'" name="s-'.$service["serviceId"].'" class="service" style="color:#ffffff;">&nbsp;'.$service["serviceDesc"].'</div></td>';
						}
					?>
				</tr>
			</table>
			<br>
			<table width="100%">
				<tr>
					<td valign="top" style="width:50%;">
						<strong>Please undertake the following:</strong>
						<p>
						<?PHP
							foreach($rowU as $undertake) {
								echo '<div id="u-'.$undertake["suId"].'" name="u-'.$undertake["suId"].'" class="undertake" style="color:#ffffff;">&nbsp;'.$undertake["undertakeDesc"].'</div>';
							}
						?>
						</p>
					</td>
					<td valign="top">
						<div id="divReason">
						<?PHP
							if(count($rowR) > 0) {
								echo '<strong>Reason:</strong>';
								echo '<p>';
							}
						
							foreach($rowR as $reason) {
								echo '<div id="r-'.$reason["trId"].'" name="r-'.$reason["trId"].'" class="reason" style="color:#ffffff;">&nbsp;'.$reason["reasonDesc"].'</div>';
							}
							
							if(count($rowR) > 0) {
								echo '</p>';
							}
						?>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<br/>
		<div style="min-height:110px;">
			<table width="100%">
				<tr>
					<td width="100px">
						<strong>S.O.A. No:</strong>
					</td>
					<td>
						<input class="jqx-input jqx-widget-content jqx-rc-all" type="text"></input>
					</td>
					<td valign="top" id="tdFee" rowspan="4" width="50%">
						<p>
						<?PHP
							foreach($rowF as $fee) {
								echo '<strong>'.$fee["feeDesc"].'</strong>';
								echo '<br/>';
								echo '<input id="txtFee-'.$fee["tfId"].'" name="txtFee-'.$fee["tfId"].'" class="jqx-input jqx-widget-content jqx-rc-all" type="text"></input>';
								echo '<br/>';
							}
						?>
						</p>
					</td>
				</tr>
				<tr>
					<td>
						<strong>O.R. No:</strong>
					</td>
					<td>
						<input id="txtOR" name="txtOR" class="jqx-input jqx-widget-content jqx-rc-all" type="text"></input>
					</td>
				</tr>
				<tr>
					<td>
						<strong>Date Paid:</strong>
					</td>
					<td>
						<!--<div id="txtDatePaid" name="txtDatePaid" class=""></div>-->
						<div id="txtDatePaid" name="txtDatePaid" class="jqx-input jqx-widget-content jqx-rc-all"></div>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<strong>Remarks:</strong>
					</td>
					<td>
						<textarea style="resize:none;" id="taRemarks" name="taRemarks" class="jqx-input jqx-widget-content jqx-rc-all" style=""></textarea>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div align="center" style="padding: 10px 0 0 0;">
		<input type="button" value="ISSUE S.O." id="issue"></td>
	</div>
</form>