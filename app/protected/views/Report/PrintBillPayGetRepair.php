<?php

include_once '../mpdf60/mpdf.php';

$html = "
  <style>
    .label {
      text-align: right;
      font-weight: bold;
    }
  </style>

  <div style='text-align: center; font-weight: bold; font-size: 16px'>ใบเสร็จรับเงิน ค่าซ่อม {$org->org_name}</div>
  <div style='text-align: center'>
    <div>{$org->org_address_1} {$org->org_address_2} {$org->org_address_3} {$org->org_address_4} </div>
    <div>เบอร์โทร: {$org->org_tel} Fax: {$org->org_fax}</div>
    <div>เลขประจำตัวผู้เสียภาษี: {$org->org_tax_code}</div>
  </div>
  <table width='100%' style='margin-top: 20px'>
    <tr>
      <td width='120px' class='label'>เลขที่ใบซ่อม</td>
      <td>{$repair->repair_id}</td>
    </tr>
    <tr>
      <td width='140px' class='label'>รหัสสินค้า/บาร์โค้ด</td>
      <td>{$repair->product_code}</td>

      <td width='100px' class='label'>ชื่อสินค้า</td>
      <td>{$repair->repair_product_name}</td>
    </tr>
    <tr>
      <td width='120px' class='label'>ลูกค้า</td>
      <td>{$repair->repair_name}</td>

      <td width='100px' class='label'>เบอร์โทรติดต่อ</td>
      <td>{$repair->repair_tel}</td>
    </tr>
    <tr>
      <td width='120px' class='label'>ผู้รับเรื่อง</td>
      <td>รหัส {$repair->user_id} : {$repair->user->user_name}</td>

      <td width='100px' class='label'>วันที่ทำรายการ</td>
      <td>{$repair->repair_created_date}</td>
    </tr>
    <tr>
      <td width='120px' class='label'>วันที่เริ่มซ่อม</td>
      <td>{$repair->repair_date}</td>

      <td width='100px' class='label'>ปัญหา/อาการ</td>
      <td>{$repair->repair_problem}</td>
    </tr>
    <tr>
      <td width='120px' class='label'>ค่าบริการ</td>
      <td>{$repair->repair_price}</td>

      <td width='100px' class='label'>การดำเนินการ</td>
      <td>{$repair->repair_detail}</td>
    </tr>
    <tr>
      <td width='120px' class='label'>สาเหตุอาการเสีย</td>
      <td>{$repair->repair_original}</td>
    </tr>
    <tr>
      <td width='120px' class='label'>ประเภทการซ่อม</td>
      <td>{$repair->getRepairType()}</td>

      <td width='100px' class='label'>สถานะ</td>
      <td>{$repair->getStatus()}</td>
    </tr>
  </table>
";

$mpdf = new mPDF('th');
$mpdf->WriteHTML($html);
$mpdf->Output();

?>