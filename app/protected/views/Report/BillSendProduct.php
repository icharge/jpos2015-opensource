<?php
include_once '../mpdf60/mpdf.php';

$billSaleId = $billSale->bill_sale_id;
$date_time1 = date('d/m/Y');
$date_time2 = date(':i:s');

$configSoftware = ConfigSoftware::model()->find();
$h = date('h') + $configSoftware->count_hour;

$date_time = "{$date_time1} {$h}{$date_time2}";

// header
$td_logo = '';

if ($org->org_logo_show_on_bill == 'yes') {
	$td_logo = "
		<td width='80px' rowspan='6'>
			<img src='upload/{$org->org_logo}' width='60px' />
		</td>
	";
}

$configSoftware = ConfigSoftware::model()->find();
$bill_send_title = $configSoftware->bill_send_title;
$bill_sale_title = $configSoftware->bill_sale_title;

$headerText = $bill_send_title;

if ($billType == "sale") {
    $headerText = $bill_sale_title;
}

$header = "
	<table width='100%'>
		<tr valign='top'>
			{$td_logo}
        
			<td align='center'>
				<div class='header-text bold'>
					$headerText
				</div>
            </td>
        </tr>
        <tr>
            <td align='center'>
			    <div class='header-text'>
			    	{$org->org_name} {$org->org_name_eng}
			    </div>
            </td>
        <tr>
            <td align='center'>
			    <div class='header-text'>
			    	เลขประจำตัวผู้เสียภาษี: {$org->org_tax_code} เบอร์โทร; {$org->org_tel}
			    </div>
            </td>
        </tr>
        <tr>
            <td align='center'>
			    <div class='header-text'>
			    	{$org->org_address_1} {$org->org_address_2}
			    </div>
            </td>
        </tr>
        <tr>
            <td align='center'>
			    <div class='header-text'>
			    	{$org->org_address_3} {$org->org_address_4}
			    </div>
            </td>
        </tr>
        <tr>
            <td align='center'>
			    <div class='header-text'>
			    	วันที่: $date_time เลขที่บิล: $billSaleId
			    </div>
			</td>
		</tr>
	</table>	
    ";
    
// to member
if (!empty($member)) {
    $header .= "
    <div class='row'>
        <span><strong>ลูกค้า:</strong> {$member->member_name}</span>
        <span>&nbsp;&nbsp;&nbsp;</span>
        <span><strong>เบอร์โทร:</strong> {$member->member_tel}</span>
    </div>
    <div class='row'>
        <span><strong>เลขประจำตัวผู้เสียภาษี:</strong> </span>
        <span>{$member->tax_code}</span>
    </div>
    <div class='row'><strong>ที่อยู่:</strong> {$member->member_address}</div>
    <br />";
}

// content
$content = "
    <table style='margin-top: 30px' width='100%' cellspacing='0' cellpadding='0'>
        <thead>
            <tr>
                <td class='cell-header' style='text-align: center' width='25px'>#</td>
                <td class='cell-header'>รหัสสินค้า</td>
                <td class='cell-header'>รายการ</td>
                <td class='cell-header' style='text-align: right'>น้ำหนัก(กรัม)</td>
                <td class='cell-header' style='text-align: right' width='60px'>ราคา</td>
                <td class='cell-header' style='text-align: right' width='50px'>จำนวน</td>
                <td class='cell-header' style='text-align: right' width='70px'>รวม</td>
            </tr>
        </thead>
        <tbody>";

$sum = 0;
$sum_qty = 0;
$i = 1;
$sum_weight = 0;

// table body
foreach ($billSaleDetail as $r) {
    $product_code = $r->bill_sale_detail_barcode;
    $product_name = $r->product->product_name;
    $product_price = $r->bill_sale_detail_price;
    $product_qty = $r->bill_sale_detail_qty;
    $bill_sale_detail_price_vat = $r->bill_sale_detail_price_vat;
   
    // find name of product
    if (empty($product_name)) {
        $productPrice = BarcodePrice::model()->findByPk($product_code);
        $fk = $productPrice->barcode_fk;
        $productRelate = Product::model()->findByAttributes(array(
            'product_code' => $fk
        ));

        $product_name = $productRelate->product_name." ({$productPrice->name})";
    }

    $price_per_row = ($product_qty * $product_price);
    
    $sum += $price_per_row;
    $sum_qty += $product_qty;
    
    $price_per_row = number_format($price_per_row);
    $product_price = number_format($product_price);
    $product_qty = number_format($product_qty);
    $vat = number_format($bill_sale_detail_price_vat, 2);

    $product = Product::model()->findByAttributes(array('product_code' => $product_code));
    $weight = $product->weight;
    $sum_weight += $weight;

    if (empty($weight)) {
        $weight = 0;
    }
    
    $content .= "
        <tr>
            <td class='cell' style='text-align: center'>$i</td>
            <td class='cell' width='180px'>$product_code</td>
            <td class='cell'>$product_name</td>
            <td class='cell' style='text-align: right'>$weight</td>
            <td class='cell' style='text-align: right'>$product_price</td>
            <td class='cell' style='text-align: right'>$product_qty</td>
            <td class='cell' style='text-align: right'>$price_per_row</td>
        </tr>";
    $i++;
}

// table footer
$sum_qty = number_format($sum_qty);
$sum_price = number_format($sum);
$content .= "
        </tbody>
        <tfoot>
            <tr>
                <td class='text bold'>รวม</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class='cell cell-footer'>$sum_qty</td>
                <td class='cell cell-footer'>$sum_price</td>
            </tr>
        </tfoot>
    </table>
    <br />";

$billSale = BillSale::model()->findByPk($billSaleId);
$billConfig = BillConfig::model()->find();

$vat_type = $billSale->vat_type;
$out_vat = $billSale->out_vat * 1;
$total_price = $sum;

if ($vat_type == 'out') {
    $total_price = ($sum + $out_vat);
}

$money = number_format($out_vat, 2);
$money_add = number_format($total_price, 2);
$bonus_price = number_format($billSale->bonus_price, 2);
$total_pay = number_format($total_price - $billSale->bonus_price, 2);

$money_add_total = ($total_price - $out_vat);
$money_add_total = number_format($money_add_total, 2);

$content .= "<table width='200px'>";

if ($billSale->bill_sale_vat == 'vat') {
    $content .= "
            <tr>
                <td>vat ประเภท</td>
                <td style='text-align: right'>{$vat_type}</td>
            </tr>
            <tr>
                <td>จำนวนเงิน</td>
                <td style='text-align: right'>{$money}</td>
            </tr>
            ";
}

    $content .= "
            <tr>
                <td>รวมเป็นเงิน</td>
                <td style='text-align: right'>{$money_add_total}</td>
            </tr>
            <tr>
                <td>ส่วนลด</td>
                <td style='text-align: right'>{$bonus_price}</td>
            </tr>
            <tr>
                <td>ยอดเงินที่ต้องชำระ</td>
                <td style='text-align: right'>
                    <strong ";

                // line of bill_send_product
                if ($billType == 'send') {
                    if ($billConfig->bill_send_show_line == 'yes') {
                        $content .= "style='border-bottom: #000 3px double'";
                    }
                } else {
                    if ($billConfig->sale_condition_show_line == 'yes') {
                        $content .= "style='border-bottom: #000 3px double'";
                    }
                }

    $content .= ">
                        {$total_pay}
                    </strong>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>นำหนักรวม(กรัม)</td>
                <td style='text-align: right'>{$sum_weight}</td>
            </tr>
        </table>
    ";


// footer
$footer = "
    <table width='100%' style='margin-top: 30px'>
        <tr>
            ";

            if ($billType != "sale") {
                $footer .= "<td class='text' style='text-align: center; font-weight: bold'>ผู้ส่งสินค้า</td>";
            }

            $footer .= "
            <td class='text' style='text-align: center; font-weight: bold'>พนักงานขาย</td>
            <td class='text' style='text-align: center; font-weight: bold'>ผู้รับสินค้า</td>
            <td class='text' style='text-align: center; font-weight: bold'>ผู้รับเงิน</td>
            <td class='text' style='text-align: center; font-weight: bold'>ผู้ตรวจสอบ</td>
        </tr>
        <tr>
            ";

            if ($billType != "sale") {
                $footer .= "
                    <td align='center'>
                        <span class='blank-row'>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </span>
                    </td>";
            }

            $footer .= "
            <td align='center'>
                <span class='blank-row'>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </span>
            </td>
            <td align='center'>
                <span class='blank-row'>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </span>
            </td>
            <td align='center'>
                <span class='blank-row'>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </span>
            </td>
            <td align='center'>
                <span class='blank-row'>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </span>
            </td>
        </tr>
        <tr>
            <td style='text-align: center; padding-top: 20px'>วันที่............................</td>
            <td style='text-align: center; padding-top: 20px'>วันที่............................</td>
            <td style='text-align: center; padding-top: 20px'>วันที่............................</td>
            <td style='text-align: center; padding-top: 20px'>วันที่............................</td>
            ";
        if ($billType != "sale") {
            $footer .= "<td style='text-align: center; padding-top: 20px'>วันที่............................</td>";
        }

        $footer .= "
        </tr>
    </table>
    <br />";

$html = $header.$content.$footer;

$paper = 'A4';
$w = 0;
$h = 0;

// remark footer
if ($billType == "sale") {
    if (!empty($configSoftware->bill_sale_footer)) {
      $html .= "<div style='margin-top: 5px; font-size: 10px'>* {$configSoftware->bill_sale_footer}</div>";
    }

    if (!empty($billConfig->sale_paper)) {
        $paper_name = $billConfig->sale_paper;
    }

    if (!empty($billConfig->sale_position)) {
        if ($billConfig->sale_position == 'vertical') {
            $paper_position = '-L';
        } else {
            $paper_position = '';
        }
    }

    if (!empty($paper_name) && !empty($paper_position)) {
        $paper = "{$paper_name}{$paper_position}";
    }

    if (!empty($billConfig->sale_width)) {
        $w = $billConfig->sale_width;
    }

    if (!empty($billConfig->sale_height)) {
        $h = $billConfig->sale_height;
    }
} else {
    if (!empty($configSoftware->bill_send_footer)) {
      $html .= "<div style='margin-top: 5px; font-size: 10px'>* {$configSoftware->bill_send_footer}</div>";
    }

    if (!empty($billConfig->bill_send_product_paper)) {
        $paper_name = $billConfig->bill_send_product_paper;
    }

    if (!empty($billConfig->bill_send_product_position)) {
        if ($billConfig->bill_send_product_position == 'vertical') {
            $paper_position = '-L';
        } else {
            $paper_position = '';
        }
    }

    if (!empty($paper_name) && !empty($paper_position)) {
        $paper = "{$paper_name}{$paper_position}";
    }

    if (!empty($billConfig->bill_send_product_width)) {
        $w = $billConfig->bill_send_product_width;
    }

    if (!empty($billConfig->bill_send_product_height)) {
        $h = $billConfig->bill_send_product_height;
    }
}

// paper size
$size = array($w, $h);

if ($w == 0 || $h == 0) {
    $size = $paper;
}

$style = "
    <style>
        .cell {
            padding: 3px;
            font-size: 14px;
";

// line of bill_send_product
if ($billType != 'sale') {
    if ($billConfig->bill_send_show_line == 'yes') {
        $style .= "border-bottom: #808080 0.15pt solid;";
    }
} else {
    if ($billConfig->sale_condition_show_line == 'yes') {
        $style .= "border-bottom: #808080 0.15pt solid;";
    }
}
        
$style .= "
        }
        .cell-header {
            font-weight: bold;
            padding: 5px;
";

// line of bill_send_product
if ($billType != 'sale') {
    if ($billConfig->bill_send_show_line == 'yes') {
        $style .= "border-bottom: #808080 3px double;";
    }
} else {
    if ($billConfig->sale_condition_show_line == 'yes') {
        $style .= "border-bottom: #808080 3px double;";
    }
}

$style .= "
            font-size: 14px;
            font-weight: bold;
        }
        .header-text {
            font-size: 14px;
            text-align: center;
            margin-top: 3px;
            margin-bottom: 3px;
            display: inline-block;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        .row {
            font-size: 14px;
            padding-top: 1px;
            padding-bottom: 1px;
        }
        .blank-row {
            padding-top: 14px;
            padding-bottom: 5px;
";

// line of bill_send_product
if ($billType != 'sale') {
    if ($billConfig->bill_send_show_line == 'yes') {
        $style .= "border-bottom: #808080 1px solid;";
    }
} else {
    if ($billConfig->sale_condition_show_line == 'yes') {
        $style .= "border-bottom: #808080 1px solid;";
    }
}
  
$style .= "
        }
        .text {
            font-size: 14px;
        }
        .cell-footer {
            font-size: 14px;
            text-align: right;
";

// line of bill_send_product
if ($billType != 'sale') {
    if ($billConfig->bill_send_show_line == 'yes') {
        $style .= "border-bottom: #808080 3px double;";
    }
} else {
    if ($billConfig->sale_condition_show_line == 'yes') {
        $style .= "border-bottom: #808080 3px double;";
    }
}

$style .= "
            padding-top: 3px;
            padding-bottom: 3px;
        }
    </style>
";

$mpdf = new mPDF('th', $size, 0, 0, 5, 5, 5, 5);
$mpdf->WriteHTML($style.$html);
$mpdf->Output();
?>

