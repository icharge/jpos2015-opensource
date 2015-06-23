<?php
include_once '../mpdf60/mpdf.php';

$nowThai = Util::nowThai();
$configSoftware = ConfigSoftware::model()->find();
$billConfig = BillConfig::model()->find();

// style
$style = "
	<style>
		.text {
			font-size: 13px;
		}
		.bold {
			font-weight: bold;
		}
		.red {
			font-color: #FF0000;
		}
		.cell {
			padding: 5px;
			font-size: 13px;
";

if ($billConfig->bill_add_show_line == 'yes') {
	$style .= "border-bottom: #cccccc 1px solid;";
}

$style .= "
		}
		.cell-header {
			text-align: center;
			background: #cccccc;
		}
		.right {
			text-align: right;
		}
		.center {
			text-align: center;
		}
		.cell-footer {
			font-size: 13px;
		}
	</style>
";

// header text
$header_text = "
<table width='100%'>
	<tr>
		<td colspan='2' class='center bold'>
			ใบกำกับภาษี
		</td>
	</tr>
	<tr>
		<td colspan='2' class='text'>
			เลขที่: <span class='bold'>{$billSale->bill_sale_id}</span>
			วันที่: <span class='bold red'>{$nowThai}</span>
		</td>
	</tr>
	<tr>
		<td class='text'>
			ร้าน: 
			<span class='bold red'>{$org->org_name}</span>
		</td>
		<td class='text'>
			สาขา:
			<span class='bold red'>{$billSale->branch->branch_name}</span>
		</td>
	</tr>
	<tr>
		<td colspan='2' class='text'>
			<div>{$org->org_address_1}</div>
			<div>{$org->org_address_2}</div>
			<div>{$org->org_address_3}</div>
			<div>{$org->org_address_4}</div>
			<div>
				<span style='padding-right: 40px'>
					เลขประจำตัวผู้เสียภาษี: {$org->org_tax_code}
				</span>
				
				<span style='padding-right: 40px'>
					โทรศัพท์: {$org->org_tel}				
				</span>
				
				แฟกซ์: {$org->org_fax}
			</div>	
		</td>
	</tr>
	<tr>
		<td colspan='2' class='text'>
			<div class=''>
				<strong>ลูกค้า</strong>
				<span>
					{$billSale->member->member_code} 
					{$billSale->member->member_name}
				</span>
			</div>
			<div>{$billSale->member->member_tel}</div>
			<div>{$billSale->member->member_address}</div>
		</td>
	</tr>
</table>
";

// body
$body_text = "
	<table width='100%'>
		<thead>
			<tr>
				<td class='cell cell-header' width='40px'>ลำดับ</td>
				<td class='cell cell-header' width='100px'>รหัสสินค้า</td>
				<td class='cell cell-header'>ชื่อ</td>
				<td class='cell cell-header' width='50px'>จำนวน</td>
				<td class='cell cell-header' width='110px'>ราคาต่อหน่วย</td>
				<td class='cell cell-header' width='70px'>ราคารวม</td>
			</tr>
		</thead>
";

$n = 1;
$sumPrice = 0;

$criteria = new CDbCriteria();
$criteria->compare('bill_id', $billSale->bill_sale_id);

$billSaleDetails = BillSaleDetail::model()->findAll($criteria);

foreach ($billSaleDetails as $billSaleDetail) {
	$qty = $billSaleDetail->bill_sale_detail_qty;
	$price = $billSaleDetail->bill_sale_detail_price;
	$totalPricePerRow = ($qty * $price);
	
	$sumPrice += $totalPricePerRow;
	$totalPricePerRowOutput = number_format($totalPricePerRow);
	$product_name = $billSaleDetail->product->product_name;
	$product_code = $billSaleDetail->bill_sale_detail_barcode;

	// find name of product
  if (empty($product_name)) {
    $productPrice = BarcodePrice::model()->findByPk($product_code);
    $fk = $productPrice->barcode_fk;
    $productRelate = Product::model()->findByAttributes(array(
      'product_code' => $fk
    ));

    $product_name = $productRelate->product_name." ({$productPrice->name})";
  }

	$body_text .= "
		<tr>
			<td class='cell right'>{$n}</td>
			<td class='cell center'>{$product_code}</td>
			<td class='cell'>{$product_name}</td>
			<td class='cell right'>{$qty}</td>
			<td class='cell right'>{$price}</td>
			<td class='cell right'>{$totalPricePerRowOutput}</td>
		</tr>
	";
	$n++;
}
$body_text .= "</table>";

// Footer
$vat = 0.00;

if ($billSale->bill_sale_vat == 'vat') {
	$vat = (0.07 * $sumPrice);
}

$vatText = number_format($vat, 2);
$sumPriceText = number_format($sumPrice, 2);
$beforeSumPrice = number_format($sumPrice - $vat, 2);

$vat_type = $billSale->vat_type;

if ($vat_type == 'out') {
	$beforeSumPrice = $billSale->total_money - $billSale->out_vat;
	$sumPriceText = $billSale->total_money;
}

$style_border = "";

if ($billConfig->bill_add_show_line == 'yes') {
	$style_border = "style='border-bottom: 3px double #000000'";
}

$footer_text = "
<br />
<table width='100%'>
	<tr>
		<td class='cell-footer right'>ประเภท vat</td>
		<td class='cell-footer right' width='50px'>{$vat_type}</td>
	</tr>
	<tr>
		<td class='cell-footer right'>ยอดก่อนคิด Vat</td>
		<td class='cell-footer right'>{$beforeSumPrice}</td>
	</tr>
	<tr>
		<td class='cell-footer right'>ภาษีมูลค่าเพิ่ม (Vat) 7%</td>
		<td class='cell-footer right'>{$vatText}</td>
	</tr>
	<tr>
		<td class='cell-footer red right'>ยอดสุทธิ</td>
		<td class='cell-footer right'>
			<font $style_border>{$sumPriceText}</font>
		</td>
	</tr>
</table>

<br />
<br />
<table>
	<tr>
		<td style='text-align: center'>ผู้รับของ</td>
		<td style='text-align: center'>ผู้ส่งของ</td>
		<td style='text-align: center'>ผู้ตรวจสอบ</td>
	</tr>
	<tr>
		<td style='padding-top: 50px'>......................................................</td>
		<td style='padding-top: 50px'>......................................................</td>
		<td style='padding-top: 50px'>......................................................</td>
	</tr>
	<tr>
		<td>วันที่</td>
		<td>วันที่</td>
		<td>วันที่</td>
	</tr>
</table>
";

// HTML
$html = "
	$style
	$header_text
	$body_text
	$footer_text
";

if (!empty($configSoftware->bill_vat_footer)) {
  $html .= "<div style='margin-top: 5px; font-size: 10px'>* {$configSoftware->bill_vat_footer}</div>";
}

// paper size
$w = $billConfig->bill_add_tax_width;
$h = $billConfig->bill_add_tax_height;

if ($w == 0 && $h == 0) {
	// paper
	$paper = $billConfig->bill_add_tax_paper;
	$position = $billConfig->bill_add_tax_position;

	if ($position != 'horizontal') {
		$position = "-L";
	} else {
		$position = "";
	}

	$paper = "{$paper}{$position}";
} else {
	// custom size
	$paper = array($w, $h);
}

// MPDF Render
$mpdf = new mPDF('th', $paper, 0, 0, 5, 5, 5, 5);
$mpdf->WriteHTML($html);
$mpdf->Output();

?>


