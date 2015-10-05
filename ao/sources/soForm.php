<?PHP
	include "../../class/connect.class.php";
	$conn = new getConnection();
	$db = $conn->PDO();
	
	if($_POST["form"] == "NC") {
		$trans = $_POST["trans"];
		
		$res = $db->query("select * from tbl_transactions a left outer join
							tbl_applications b on a.appId = b.appId left outer join
							tbl_app_type c on b.appId = c.appId left outer join
							tbl_consumers d on b.cId = d.cId left outer join
							tbl_consumer_address e on d.cId = e.cId left outer join
							tbl_consumer_connection h on d.cId = h.cId left outer join
							tbl_municipality f on e.munId = f.munId left outer join
							tbl_barangay g on e.brgyId = g.brgyId
							where a.tid = $trans");
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
		
		$type = $row[0]["typeId"];
		$acct = $row[0]["sysPro"];
	}
	else {
		$type = $_POST["con"];
		$acct = $_POST["acct"];

		
		$res = $db->query("select * from tbl_consumers a
				left outer join tbl_consumer_address b on a.cid = b.cid
				left outer join tbl_barangay c on b.brgyId = c.brgyId
				left outer join tbl_municipality d on c.munId = d.munId
				where a.sysPro = $acct");
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
	}
	
	$name = $row[0]["fname"].($row[0]["mname"] ? " ".$row[0]["mname"]." " : " ").$row[0]["lname"];
	$address = ($row[0]["address"] ? $row[0]["address"]." " : "").$row[0]["purok"]." ".$row[0]["brgyName"]." ".$row[0]["munDesc"];
	$accountNum = $row[0]['sysPro'];
	
	$res = $db->query("select * from tbl_type where typeId = $type");
	$rowT = $res->fetchAll(PDO::FETCH_ASSOC);
	
	$res = $db->query("select * from tbl_service where typeId = $type");
	$rowS = $res->fetchAll(PDO::FETCH_ASSOC);
	
	$res = $db->query("select * from tbl_type_undertake a left outer join
						tbl_undertake b on a.undertakeId = b.undertakeId
						where typeId = $type");
	$rowU = $res->fetchAll(PDO::FETCH_ASSOC);
	
	$res = $db->query("select * from tbl_type_fee a left outer join
						tbl_fee b on a.feeId = b.feeId
						where typeId = $type");
	$rowF = $res->fetchAll(PDO::FETCH_ASSOC);
	
	$res = $db->query("select * from tbl_type_reason a left outer join
						tbl_reasons b on a.reasonId = b.reasonId
						where typeId = $type");
	$rowR = $res->fetchAll(PDO::FETCH_ASSOC);
	
	$res = $db->query("select c.conDesc, d.subDesc from tbl_consumers a
						left outer join tbl_consumer_connection b on a.cId = b.cId
						left outer join tbl_connection_type c on b.conId = c.conId
						left outer join tbl_connection_sub d on b.subId = d.subId
						where a.sysPro = $acct");
	$rowCT = $res->fetchAll(PDO::FETCH_ASSOC);
?>

<form id="frmSO">
	<div style="height:490px; overflow-y: scroll; border: thin solid; padding: 10px;" id="divSO">
		<div style="">
			<table width="100%">
				<thead>
					<tr>
						<td colspan="4" align="center">
							<h4 style="font-weight:bold;">Service Order for <?PHP echo $rowT[0]["typeDesc"]; ?></h4>
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
						<td colspan="3"><div style="width:100%; border-bottom:thin solid;"><strong><?PHP echo $rowCT[0]["conDesc"].($rowCT[0]["subDesc"] ? " - ".$rowCT[0]["subDesc"] : ""); ?></strong></div></td>
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
						<input id="txtDatePaid" name="txtDatePaid" class="jqx-input jqx-widget-content jqx-rc-all" type="text"></input>
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