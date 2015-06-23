<?php 
if (empty($result)) {
	echo "<div><strong>ไม่มีข้อมูลในการแสดงรายงาน</strong></div>";
} else {
	include_once '../mpdf60/mpdf.php';
	
	// condition
	if ($sale_condition_cash == 'cash') {
		$sale_condition_cash = 'เงินสด';
	}
	if ($sale_condition_credit == 'credit') {
		$sale_condition_credit = ' เงินเชื่อ';
	}
	
	$branch_name = $branch->branch_name;
	
	// html text
	$html = "
		<style>
			* {
				font-size: 10px;
			}
			.cell-header {
				text-align: center;
				font-weight: bold;
				border-bottom: #808080 3px double;
			}
			.cell {
				padding: 5px;
				border-bottom: #cccccc 1px solid;
			}
			.footer {
				border-bottom: #cccccc 3px double;
				padding: 5px;
			}
		</style>
	
		<div>รายงานยอดขายประจำวัน : {$date_find}</div>
		<div>สาขา: {$branch_name}</div>
		<div>เงื่อนไขการขาย: {$sale_condition_cash} {$sale_condition_credit}</div>
		<br />
	
		<table border='1px'>
			<thead>
				<tr>
					<th width='50px' class='cell-header' style='text-align: right'>ลำดับ</th>
					<td width='100px' class='cell-header' style='text-align: center'>วันที่</th>
					<th width='80px' class='cell-header' style='text-align: center'>่บิล</th>
					<th width='100px' class='cell-header' style='text-align: left'>รหัสสินค้า</th>
					<th width='400px' class='cell-header' style='text-align: left'>รายการสินค้า</th>
					<th width='80px' class='cell-header' style='text-align: left'>สถานะบิล</th>
					<th width='50px' class='cell-header' style='text-align: right'>ราคา</th>
					<th width='100px' class='cell-header' style='text-align: right'>จำหน่ายจริง</th>
					<td width='80px' class='cell-header' style='text-align: right'>ทุน</th>
					<th width='100px' class='cell-header' style='text-align: right'>กำไรต่อชิ้น</th>
					<th width='50px' class='cell-header' style='text-align: right'>จำนวน</th>
					<th width='80px' class='cell-header' style='text-align: right'>กำไรรวม</th>
					<th width='100px' class='cell-header' style='text-align: right'>จำนวนเงิน</th>
				</tr>
			</thead>

			<tbody>
	";
	
	$html .= "
		</tbody>
	";
	
	$sum = 0;
	$sum_qty = 0;
	$sum_bonus = 0;
	
	// Data
	foreach ($result as $row) {
		$html .= "
			<tr>
				<td class='cell' style='text-align: right'>{$row->no}</td>
				<td class='cell'>{$row->sale_date}</td>
				<td class='cell' style='text-align: center'>{$row->bill_id}</td>
				<td class='cell' style='text-align: left'>{$row->barcode}</td>
        <td class='cell' style='text-align: left'>{$row->name}</td>
        <td class='cell'>{$row->bill_status}</td>
        <td class='cell' style='text-align: right'>{$row->price}</td>
        <td class='cell' style='text-align: right'>{$row->sale_price}</td>
        <td class='cell' style='text-align: right'>{$row->price_old}</td>
        <td class='cell' style='text-align: right'>{$row->bonus_per_unit}</td>
        <td class='cell' style='text-align: right'>{$row->qty}</td>
        <td class='cell' style='text-align: right;'>{$row->total_bonus}</td>
        <td class='cell' style='text-align: right'>{$row->total_income}</td>
			</tr>";

		$sum_qty += $row->qty;
		$sum_bonus += $row->total_bonus;
		$sum += $row->total_income;
	}
	
	$sum_qty = number_format($sum_qty);
	$sum_bonus = number_format($sum_bonus, 2);
	$sum = number_format($sum, 2);

	$html .= "
		<tfoot>
			<tr>
				<td colspan='9'>รวม</td>
				<td class='footer' style='text-align: right'></td>
				<td class='footer' style='text-align: right'>{$sum_qty}</td>
				<td class='footer' style='text-align: right'>{$sum_bonus}</td>
				<td class='footer' style='text-align: right'>{$sum}</td>
			</tr>	
		</tfoot>
	</table>
	";
	
	// Generate PDF
	$mpdf = new mPDF('th', 'A4-L', 0, 0, 5, 5, 5, 5);
	$mpdf->WriteHTML($html);
	$mpdf->Output();
}
?>
