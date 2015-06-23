<?php

class HelpController extends Controller {

  public function actionUpdateSoftware() {
    $this->render("//Help/UpdateSoftware");
  }

  public function actionAbout() {
    $this->render("//Help/About");
  }

  public function actionUpdateSoftwareNow() {
    $sql = "
      ALTER TABLE tb_bill_sale_detail 
      CHANGE bill_sale_detail_price bill_sale_detail_price DOUBLE
    ";
    Yii::app()->db->createCommand($sql)->execute();

    $sql = "
      CREATE TABLE IF NOT EXISTS product_prices(
        order_field INT(2) NOT NULL,
        product_barcode VARCHAR(20) NOT NULL,
        qty INT(5) NOT NULL DEFAULT 0,
        price DOUBLE NOT NULL DEFAULT 0,
        price_send DOUBLE NOT NULL DEFAULT 0
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ";
    Yii::app()->db->createCommand($sql)->execute();

    // add column
    try {
      $sql = "ALTER TABLE tb_product ADD product_tag INT(1) DEFAULT 0 NOT NULL";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_product ADD product_pic VARCHAR(255) NULL";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    // add column
    try {
      $sql = "ALTER TABLE tb_product ADD weight INT(11) DEFAULT 0 NOT NULL";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    // add column
    try {
      $sql = "ALTER TABLE product_prices ADD qty_end INT(5) DEFAULT 0 NOT NULL";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    $sql = "
      CREATE TABLE IF NOT EXISTS drawcash_logs(
        id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
        draw_date DATETIME,
        draw_price DOUBLE DEFAULT 0 NOT NULL
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ";
    Yii::app()->db->createCommand($sql)->execute();

     // CREATE TABLE
      $sql = "
          CREATE TABLE IF NOT EXISTS quotations (
            id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
            customer_name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            customer_address VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            customer_tel VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci,
            customer_fax VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci,
            customer_tax VARCHAR(13) CHARACTER SET utf8 COLLATE utf8_general_ci,
            quotation_day INT(3) NOT NULL,
            quotation_send_day INT(3) NOT NULL,
            quotation_pay ENUM('cash', 'credit') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            created_at DATETIME NOT NULL,
            user_id INT(11) NOT NULL,
            vat INT(2) NOT NULL
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8
        ";
        Yii::app()->db->createCommand($sql)->execute();

        $sql = "
          ALTER TABLE quotations 
          DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
        ";
        Yii::app()->db->createCommand($sql)->execute();

    // CREATE TABLE quotation_details
    $sql = "
      CREATE TABLE IF NOT EXISTS quotation_details (
        id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
        quotation_id INT(11) NOT NULL,
        barcode VARCHAR(255) NOT NULL,
        old_price FLOAT NOT NULL,
        qty INT(5) NOT NULL,
        sub INT(11) NOT NULL,
        sale_price FLOAT NOT NULL
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ";

    Yii::app()->db->createCommand($sql)->execute();

    $sql = "
      CREATE TABLE IF NOT EXISTS backgrounds(
        id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
        name VARCHAR(500) NOT NULL,
        background_default ENUM('yes', 'no') DEFAULT 'no' NOT NULL
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ";
    Yii::app()->db->createCommand($sql)->execute();

    // add column
    try {
      $sql = "ALTER TABLE tb_organization ADD logo_show_on_header ENUM('no', 'yes') DEFAULT 'no' NOT NULL";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    // add column
    try {
      $sql = "ALTER TABLE tb_organization ADD logo_show_on_header_bg ENUM('no', 'yes') DEFAULT 'no' NOT NULL";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    $sql = "
      CREATE TABLE IF NOT EXISTS barcode_prices(
        barcode VARCHAR(50) PRIMARY KEY NOT NULL,
        price DOUBLE NOT NULL DEFAULT 0,
        name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
        qty_sub_stock INT(5) DEFAULT 1 NOT NULL,
        barcode_fk VARCHAR(50) NOT NULL
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ";
    Yii::app()->db->createCommand($sql)->execute();

    try {
      $sql = "ALTER TABLE tb_repair ADD repair_group ENUM('internal', 'external') DEFAULT 'internal' COMMENT 'สินค้าซ่อมมาจากร้านหรือไม่ internal คือสินค้าในร้าน external คือสินค้านอกร้าน' NOT NULL";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_repair ADD repair_tel VARCHAR(50) COMMENT 'เบอร์โทรติดต่อ' NOT NULL";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_repair ADD repair_name VARCHAR(255) COMMENT 'ลูกค้า' NOT NULL";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_repair ADD repair_product_name VARCHAR(255) COMMENT 'สินค้า' NOT NULL";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_repair ADD repair_end_date DATETIME COMMENT 'วันที่ซ่อมเสร็จ' NULL";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_config ADD slip_font_size INT(2) COMMENT 'ขนาดตัวอักษร' NOT NULL DEFAULT 11";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_import_detail ADD import_bill_detail_code VARCHAR(50) COMMENT 'รหัสสินค้า ที่รับเข้า' NULL";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_import_detail ADD import_bill_detail_qty_per_pack INT(2) COMMENT 'จำนวนต่อแพค' NULL";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_config ADD sale_font_size INT(2) COMMENT 'ขนาดตัวอักษร ใบเสร็จ' NOT NULL DEFAULT 11";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_config ADD sale_width INT(2) COMMENT 'ความกว้างใบเสร็จ' NULL";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_config ADD sale_height INT(2) COMMENT 'ความสูงใบเสร็จ' NULL";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_config ADD sale_paper VARCHAR(10) COMMENT 'รูปแบบกระดาษ ใบเสร็จ' NOT NULL DEFAULT 'A4'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_config ADD sale_position VARCHAR(15) COMMENT 'แนวนอน แนวตั้ง ของใบเสร็จ' NOT NULL DEFAULT 'vertical'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_sale ADD vat_type ENUM('in', 'out') COMMENT 'รูปแบบ vat (in, out)'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    $sql = "
      CREATE TABLE IF NOT EXISTS config_software(
        id INT(5) PRIMARY KEY NOT NULL,
        alert_min_stock INT(3) NOT NULL DEFAULT 1 COMMENT 'จำนวนคงเหลือต่ำสุด',
        bill_slip_title VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'หัวบิล สสลิปการขาย',
        bill_send_title VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'หัวบิล ใบส่งสินค้า',
        bill_vat_title VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'หัวบิล ใบกำกับภาษี',
        bill_sale_title VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'หัวบิล ใบเสร็จรับเงิน',
        bill_drop_title VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'หัวบิล ใบวางบิล',
        items_per_page INT(3) NOT NULL DEFAULT 10
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ";
    Yii::app()->db->createCommand($sql)->execute();

    $sql = "
      INSERT INTO config_software(
        id,
        alert_min_stock,
        bill_slip_title,
        bill_send_title,
        bill_vat_title,
        bill_sale_title,
        bill_drop_title,
        items_per_page
      )
      VALUES (
        1,
        20,
        'ใบเสร็จรับเงิน',
        'ใบส่งสินค้า',
        'ใบกำกับภาษี',
        'ใบเสร็จรับเงิน',
        'ใบวางบิล',
        10
      )
    ";

    try {
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {
      
    }

    $sql = "
      CREATE TABLE IF NOT EXISTS sale_temp(
        barcode VARCHAR(50),
        name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
        serial VARCHAR(255),
        price DOUBLE,
        qty INT(5),
        qty_per_pack INT(5),
        user_id INT(11),
        branch_id INT(11),
        expire_date DATE
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ";
    Yii::app()->db->createCommand($sql)->execute();

    // add column
    try {
      $sql = "ALTER TABLE sale_temp ADD pk_temp VARCHAR(50)";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE sale_temp ADD created_at DATETIME";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    // add column
    try {
      $sql = "ALTER TABLE config_software ADD bill_slip_footer TEXT CHARACTER SET utf8 COLLATE utf8_general_ci";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE config_software ADD bill_send_footer TEXT CHARACTER SET utf8 COLLATE utf8_general_ci";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE config_software ADD bill_vat_footer TEXT CHARACTER SET utf8 COLLATE utf8_general_ci";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE config_software ADD bill_sale_footer TEXT CHARACTER SET utf8 COLLATE utf8_general_ci";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE config_software ADD bill_drop_footer TEXT CHARACTER SET utf8 COLLATE utf8_general_ci";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE config_software ADD score INT(3) COMMENT 'กี่บาทเป็น 1 แต้ม'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE barcode_prices ADD price_before INT(6) COMMENT 'ราคาทุน'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_sale_detail ADD old_price DOUBLE COMMENT 'ราคาทุน'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE sale_temp ADD old_price DOUBLE COMMENT 'ราคาทุน'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_sale ADD bonus_price DOUBLE COMMENT 'ส่วนลด'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_sale ADD out_vat DOUBLE COMMENT 'vat บวกเพิ่มกี่บาท'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_sale ADD input_money DOUBLE COMMENT 'รับเงิน'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_sale ADD return_money DOUBLE COMMENT 'เงินทอน'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_sale ADD total_money DOUBLE COMMENT 'ยอดเงินทั้งหมด'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE config_software ADD count_hour INTEGER COMMENT 'ค่าชั่วโมง'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_config ADD bill_send_show_line ENUM('no', 'yes') DEFAULT 'no' COMMENT 'แสดงเส้นใบส่งสินค้า'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_config ADD bill_drop_show_line ENUM('no', 'yes') DEFAULT 'no' COMMENT 'แสดงเส้นใบวางบิล'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_config ADD bill_add_show_line ENUM('no', 'yes') DEFAULT 'no' COMMENT 'แสดงเส้นใบกำกับภาษี'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE tb_bill_config ADD sale_condition_show_line ENUM('no', 'yes') DEFAULT 'no' COMMENT 'แสดงเส้นใบเสร็จรับเงิน'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    $sql = "
      CREATE TABLE IF NOT EXISTS temp_sale_per_day(
        no INT(4),
        sale_date VARCHAR(20),
        bill_id VARCHAR(8),
        barcode VARCHAR(20),
        name VARCHAR(200),
        bill_status VARCHAR(20),
        price FLOAT,
        sale_price FLOAT,
        price_old FLOAT,
        bonus_per_unit FLOAT,
        qty INT,
        total_bonus FLOAT,
        total_income FLOAT
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ";
    Yii::app()->db->createCommand($sql)->execute();

    try {
      $sql = "ALTER TABLE tb_member ADD remark VARCHAR(1000) COMMENT 'รายละเอียดเพิ่มเติม'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    $sql = "
      CREATE TABLE IF NOT EXISTS pay_types(
        id INT(3) PRIMARY KEY AUTO_INCREMENT NOT NULL,
        name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ชื่อประเภทรายจ่าย',
        remark VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'หมายเหตุ'
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ";
    Yii::app()->db->createCommand($sql)->execute();

    $sql = "
      CREATE TABLE IF NOT EXISTS pays(
        id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
        pay_type_id INT(5) NOT NULL COMMENT 'รหัสประเภท',
        name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'รายการ',
        remark VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'หมายเหตุ',
        created_at DATETIME NOT NULL COMMENT 'วันที่บันทึก',
        price FLOAT NOT NULL COMMENT 'จำนวนเงิน'
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ";
    Yii::app()->db->createCommand($sql)->execute();

    try {
      $sql = "ALTER TABLE config_software ADD sale_can_edit_price ENUM('no', 'yes') DEFAULT 'no' COMMENT 'ให้แคชเชียร์ แก้ไขราคาขายได้'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE config_software ADD sale_can_add_sub_price ENUM('no', 'yes') DEFAULT 'no' COMMENT 'ให้แคชเชียร์ คิดส่วนลดได้'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }

    try {
      $sql = "ALTER TABLE config_software ADD sale_out_of_stock ENUM('no', 'yes') DEFAULT 'no' COMMENT 'ขายสินค้าหมดสต็อคได้'";
      Yii::app()->db->createCommand($sql)->execute();
    } catch (Exception $e) {

    }
  }

  public function actionUpToNewVersion() {
    $file_name = 'jpos-update.zip';

    $input = file_get_contents("http://www.kudemy.com/downloads/jpos2014-for-update.zip");
    file_put_contents($file_name, $input);
    
    /* Open the Zip file */
    $zip = new ZipArchive;
    
    if($zip->open($file_name) != "true"){
     echo "Error :- Unable to open the Zip File";
    } 

    /* Extract Zip File */
    $zip->extractTo('protected');
    $zip->close();

    echo 'success';
  }

}