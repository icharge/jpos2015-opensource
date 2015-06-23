<?php

include_once '../mpdf60/mpdf.php';

$pdf = new mPDF("th");

$created_at = Util::DateThai($quotation->created_at);

// style
$html = "
<style>
  .cell-header {
    border-top: #808080 1px solid;
    border-bottom: #808080 3px double;
  }
  .cell-body {
    border-bottom: #808080 1px solid;
  }
  .cell-footer {
    border-bottom: #808080 1px solid;
  }
  .cell {
    border: #808080 1px solid;
    padding: 10px;
  }
</style>
";

$html .= "<div style='text-align: center; font-weight: bold;'>ใบเสนอราคา</div>";
$html .= "<div style='margin-top: 10px; text-align: right'>เลขที่: {$quotation->id}</div>";
$html .= "<div style='text-align: right'>วันที่: {$created_at}</div>";
$html .= "<div style='margin-top: 2px;'>เรียนคุณ {$quotation->customer_name}</div>";
$html .= "
  <div style='margin-top: 10px;'>
  &nbsp;&nbsp;&nbsp;&nbsp;
  ข้าพเจ้า {$org->org_name} ที่อยู่ {$org->org_address_1} {$org->org_address_2} {$org_address_3} {$org_address_4} โทรศัพท์ {$org->org_tel} เลขประจำตัวผู้เสียภาษี {$org->org_tax_code} ขอเสนอราคาพัสดุดังรายการต่อไปนี้
  </div>";
$html .= "
  <table style='margin-top: 15px' width='100%' cellpadding='5px' cellspacing='0px'>
    <thead>
      <tr>
        <th class='cell-header'>ลำดับ</th>
        <th class='cell-header' style='text-align: left'>รายการ</th>
        <th class='cell-header'>จำนวน</th>
        <th class='cell-header'>หน่วยละ</th>
        <th class='cell-header'>ส่วนลด</th>
        <th class='cell-header'>คงเหลือ</th>
      </tr>
    </thead>
    <tbody>";
    $n = 1;
    $sum = 0;
    $sumSub = 0;
    $sumOldPrice = 0;
    $sumSalePrice = 0;

    foreach ($quotationDetails as $quotationDetail) {
      $qty = number_format($quotationDetail->qty);
      $oldPrice = number_format($quotationDetail->old_price);
      $sub = number_format($quotationDetail->sub);
      $salePrice = number_format($quotationDetail->sale_price);

      $sum += ($quotationDetail->qty * $quotationDetail->sale_price);
      $sumSub += $quotationDetail->sub;
      $sumOldPrice += $quotationDetail->old_price;
      $sumSalePrice += $quotationDetail->sale_price;

      $html .= "
      <tr>
        <td class='cell-body' style='width: 50px; text-align: right'>{$n}</td>
        <td class='cell-body'>{$quotationDetail->getProduct()->product_name}</td>
        <td class='cell-body' style='width: 50px; text-align: right'>{$qty}</td>
        <td class='cell-body' style='width: 70px; text-align: right'>{$oldPrice}</td>
        <td class='cell-body' style='width: 70px; text-align: right'>{$sub}</td>
        <td class='cell-body' style='width: 70px; text-align: right'>{$salePrice}</td>
      </tr>
      ";

      $n++;
    }

$sumSalePrice = ($sumSalePrice + $sumSub);
$sumOldPrice = ($sumSalePrice - $sumSub);

$sumSubText = number_format($sumSub, 2);
$sumSalePriceText = number_format($sumSalePrice, 2);


// compute vat
$sumPrice = ($sumSalePrice - $sumSub);
$sumPrice = ($sumPrice * $quotation->vat) / 100;
$sumPriceText = number_format($sumPrice, 2);

$sumSalePriceText = number_format($sumSalePrice, 2);
$sumOldPriceText = number_format($sumSalePrice - $sumSub, 2);

// add vat
$sumSalePriceTotal = ($sumOldPrice + $sumPrice);
$sumSalePriceTotalText = number_format($sumSalePriceTotal, 2);

// thai money
$thaiMoney = Util::convertNumberToText(number_format($sumSalePriceTotal, 2));

$html .= "
    </tbody>
    <tfoot>
      <tr>
        <td colspan='3'></td>
        <td colspan='2'>รวม</td>
        <td class='cell-footer' style='text-align: right'>{$sumSalePriceText}</td>
      </tr>
      <tr>
        <td colspan='3'></td>
        <td colspan='2'>ส่วนลด</td>
        <td class='cell-footer' style='text-align: right'>{$sumSubText}</td>
      </tr>
      <tr>
        <td colspan='3'></td>
        <td colspan='2'>มูลค่าสินค้า</td>
        <td class='cell-footer' style='text-align: right'>{$sumOldPriceText}</td>
      </tr>
      <tr>
        <td colspan='3'></td>
        <td colspan='2'>ภาษีมูลค่าเพิ่ม (%)</td>
        <td class='cell-footer' style='text-align: right'>{$quotation->vat}</td>
      </tr>
      <tr>
        <td colspan='3'></td>
        <td colspan='2'>ภาษี</td>
        <td class='cell-footer' style='text-align: right'>{$sumPriceText}</td>
      </tr>
      <tr>
        <td colspan='3'></td>
        <td colspan='2'>สุทธิ</td>
        <td class='cell-footer' style='text-align: right; border-bottom: #808080 3px double'>{$sumSalePriceTotalText}</td>
      </tr>
      <tr>
        <td colspan='6'>รวมเป็นเงินทั้งสิ้น {$thaiMoney}</td>
      </tr>
    </tfoot>
  </table>";

if (empty($quotation->quotation_pay)) {
  $quotation->quotation_pay = "เงินสด";
} else {
  if ($quotation->quotation_pay != "cash") {
    $quotation->quotation_pay = "เงินเชื่อ";
  } else {
    $quotation->quotation_pay = "เงินสด";
  }
}

// ชื่อผู้เสนอราคา
$user_id = Yii::app()->request->cookies['user_id']->value;
$user = User::model()->findByPk($user_id);
$user_name = $user->user_name;

$html .= "
  <table width='100%'>
    <tr>
      <td class='cell' style='width: 200px;'>ยืนราคา {$quotation->quotation_day} วัน</td>
      <td class='cell'>ลูกค้า</td>
      <td class='cell'>ผู้เสนอราคา</td>
    </tr>
    <tr valign='top'>
      <td class='cell'>
        <div>กำหนดส่งของภายใน {$quotation->quotation_send_day} วัน</div>
        <div>นับตั้งแต่วันที่ลงนามในสัญญา</div>
      </td>
      <td class='cell' rowspan='2'>
        <div>ลงชื่อ</div>
        <div style='text-align: center; display: inline-block;'>
          <br />
          <br />
          <br />
          <br />
          <div>(..............................................)</div>
          <br />
          <div>ผู้ตกลงราคา</div>
        </div>
      </td>
      <td class='cell' rowspan='2'>
        <div>ลงชื่อ</div>
        <divstyle='text-align: center; display: inline-block;'>
          <br />
          <br />
          <br />
          <br />
          <div>(..............................................)</div>
          <br />
          ผู้เสนอราคา
        </div>
      </td>
    </tr>
    <tr>
      <td class='cell'>เงื่อนไขการชำระเงิน {$quotation->quotation_pay}</td>
    </tr>
  </table>
";

$pdf->WriteHTML($html);
$pdf->Output();

?>
