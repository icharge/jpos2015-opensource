<?php
include_once '../mpdf60/mpdf.php';

$date_time = date('d/m/Y h:i');

if ($org->org_logo_show_on_bill == 'yes') {
    $td_logo = "<img src='upload/{$org->org_logo}' width='25px' /> </td>";  
}

$configSoftware = ConfigSoftware::model()->find();
$bill_drop_title = $configSoftware->bill_drop_title;

// header
$header = "
    <div class='header-text bold'>{$bill_drop_title}</div>
    <div class='header-text'>{$td_logo} {$org->org_name} {$org->org_name_eng}</div>
    <div class='header-text'>เลขประจำตัวผู้เสียภาษี: {$org->org_tax_code} เบอร์โทร; {$org->org_tel}</div>
    <div class='header-text'>{$org->org_address_1} {$org->org_address_2}</div>
    <div class='header-text'>{$org->org_address_3} {$org->org_address_4}</div>
    <div class='header-text'>วันที่: $date_time</div>
    <br />
    ";
    
// to member
$header .= "
    <div class='row'>
        <span>ลูกค้า: {$member->member_name}</span>
        <span>&nbsp;&nbsp;&nbsp;</span>
        <span>เบอร์โทร: {$member->member_tel}</span>
    </div>
    <div class='row'>ที่อยู่: {$member->member_address}</div>
    <br />";

// content
$content = "
    <table width='100%' cellspacing='0' cellpadding='0'>
        <thead>
            <tr>
                <td class='cell-header' style='text-align: center'>ลำดับ</td>
                <td class='cell-header' style='text-align: center'>วันที่ออกใบส่งสินค้า</td>
                <td class='cell-header' style='text-align: right'>เลขที่บิล</td>
                <td class='cell-header' style='text-align: right'>จำนวนเงิน</td>
            </tr>
        </thead>
        <tbody>";

$sum = 0;
$i = 1;

// table body
$bill_sale_ids = Yii::app()->session['bill_sale_ids'];

$criteria = new CDbCriteria();
$criteria->order = 'bill_sale_id DESC';
$criteria->addInCondition('bill_sale_id', $bill_sale_ids);

$billSales = BillSale::model()->findAll($criteria);

foreach ($billSales as $r) {
    $bill_sale_id = $r->bill_sale_id;
    $product_price = $r->getSum(false);
       
    $sum += $product_price;
    $label_price = number_format($product_price);
    
    $content .= "
        <tr>
            <td class='cell' style='text-align: center' width='30px'>$i</td>
            <td class='cell' style='text-aligh: center'>{$r->bill_sale_created_date}</td>
            <td class='cell' style='text-align: right' width='100px'>$bill_sale_id</td>
            <td class='cell' style='text-align: right' width='100px'>$label_price</td>
        </tr>";
    $i++;
}

// table footer
$sum = number_format($sum);
$content .= "
        </tbody>
        <tfoot>
            <tr>
                <td class='text bold'>รวม</td>
                <td></td>
                <td></td>
                <td class='cell-footer'>$sum</td>
            </tr>
        </tfoot>
    </table>
    <br />";

// footer
$footer = "
    <table width='100%'>
        <tr>
            <td class='text' style='text-align: center; font-weight: bold'>พนักงานขาย</td>
            <td class='text' style='text-align: center; font-weight: bold'>ผู้รับสินค้า</td>
        </tr>
        <tr>
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
    </table>
    <br />";

$html = $header.$content.$footer;

if (!empty($configSoftware->bill_drop_footer)) {
  $html .= "<div style='margin-top: 5px; font-size: 10px'>* {$configSoftware->bill_drop_footer}</div>";
}

$mpdf = new mPDF('th', 'A4', 0, 0, 5, 5, 5, 5);
$mpdf->WriteHTML(file_get_contents('css/report_big.css'), 1);
$mpdf->WriteHTML($html);
$mpdf->Output();
?>