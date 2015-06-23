<?php if (Yii::app()->request->cookies['user_id'] != null): ?>

<?php
$user_id = Yii::app()->request->cookies['user_id']->value;
$user = User::model()->findByPk((int) $user_id);
?>

<?php if (!empty($user)): ?>
<div class="row">
  <div class="col-md-12">
    <nav class="nav navbar-inverse" role="navigation" >
      <ul class="nav navbar-nav">
        <!-- menu -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="glyphicon glyphicon-home"></i>
            บันทึกประจำวัน
          </a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="index.php?r=Basic/Sale">ขายสินค้า</a></li>
            <li><a href="index.php?r=Basic/GetSale">รับคืนสินค้า</a></li>
            <li><a href="index.php?r=Basic/ManageBill">จัดการบิลขาย</a></li>
            <li><a href="index.php?r=Basic/Repair">ซ่อมแซมสินค้า</a></li>
            <li><a href="index.php?r=Basic/GetRepair">รับซ่อมสินค้าจากภายนอก</a></li>
            <li><a href="index.php?r=Basic/BillImport">รับเข้าสินค้า</a></li>
            <li><a href="index.php?r=Basic/BillDrop">ใบวางบิล</a></li>
            <li><a href="index.php?r=Basic/BillQuotation">ใบเสนอราคา</a></li>
            <li><a href="index.php?r=Basic/CheckStock">เช็คสต็อก</a></li>
            <li class="divider"></li>
            <li><a href="index.php?r=Basic/ChangeProfile">เปลี่ยนรหัสผ่าน</a></li>
            <li><a href="index.php?r=Site/Home">พักหน้าจอ</a></li>
          </ul>
        </li>

        <!-- admin menu -->
        <?php if ($user->user_level == 'admin'): ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="glyphicon glyphicon-list-alt"></i>
            รายงาน
          </a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="index.php?r=Report/Income">รายงาน กำไร - ขาดทุน</a></li>
            <li><a href="index.php?r=Report/SalePerDay">ยอดขายประจำวัน</a></li>
            <li><a href="index.php?r=Report/SaleSumPerDay">สรุปยอดขายตามวัน</a></li>
            <li><a href="index.php?r=Report/SaleSumPerMonth">สรุปยอดขายตามเดือน</a></li>
            <li><a href="index.php?r=Report/SaleSumPerType">สรุปยอดขายตามประเภท</a></li>
            <li><a href="index.php?r=Report/SaleSumPerMember">สรุปยอดขายตามสมาชิก</a></li>
            <li><a href="index.php?r=Report/SaleSumPerEmployee">สรุปยอดขายตามพนักงาน</a></li>
            <li class="divider"></li>
            <li><a href="index.php?r=Report/ProductStock">รายงานสินค้า ทั้งหมด</a></li>
            <li><a href="index.php?r=Report/ProductInStock">รายงานสินค้า คงเหลือในสต้อก</a></li>
            <li><a href="index.php?r=Report/ProductOutStock">รายงานสินค้า หมดสต้อก</a></li>
            <li class="divider"></li>
            <li><a href="index.php?r=Report/ReportAR">รายงานลูกหนี้</a></li>
            <li><a href="index.php?r=Report/ReportIR">รายงานเจ้าหนี้</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="glyphicon glyphicon-star"></i>
            ส่งเสริมการขาย
          </a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="index.php?r=Support/SubPrice">กำหนดส่วนลด</a></li>
            <li><a href="index.php?r=Support/Score">กำหนดคะแนนสะสม</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="glyphicon glyphicon-cog"></i>
            ตั้งค่าพื้นฐาน
          </a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="index.php?r=Config/Organization">ข้อมูลร้านค้า</a></li>
            <li><a href="index.php?r=Config/BranchIndex">คลังสินค้า/สาขา</a></li>
            <li><a href="index.php?r=Config/GroupProductIndex">ประเภทสินค้า</a></li>
            <li><a href="index.php?r=Config/ProductIndex">สินค้า</a></li>
            <li><a href="index.php?r=Config/FarmerIndex">ตัวแทนจำหน่าย</a></li>
            <li><a href="index.php?r=Config/MemberIndex">สมาชิกร้าน</a></li>
            <li><a href="index.php?r=Config/UserIndex">ผู้ใช้งานระบบ</a></li>
            <li><a href="index.php?r=Config/DrawcashSetup">เงินในลิ้นชักวันนี้</a></li>
            <li class="divider"></li>
            <li><a href="index.php?r=PayType/Index">ประเภทรายจ่าย</a></li>
            <li><a href="index.php?r=Pay/Index">บันทึกรายจ่าย</a></li>
            <li class="divider"></li>
            <li><a href="index.php?r=Config/BillConfigIndex">ตั้งค่าการพิมพ์บิล</a></li>
            <li><a href="index.php?r=Config/ConfigSoftware">ตั้งค่าบิล และสินค้าขั้นต่ำ</a></li>
            <li><a href="index.php?r=Config/ConfigTime">ตั้งค่าเวลาเครื่อง</a></li>
            <li><a href="index.php?r=Config/ConfigSale">ตั้งค่า เงื่อนไขการขาย</a></li>
          </ul>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>
<?php endif; ?>

<?php endif; ?>
