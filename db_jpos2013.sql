-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: 06 มี.ค. 2015  08:48น.
-- Server version: 5.5.34
-- PHP Version: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_jpos2013`
--


--
-- โครงสร้างตาราง `backgrounds`
--

CREATE TABLE `backgrounds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `background_default` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `barcode_prices`
--

CREATE TABLE `barcode_prices` (
  `barcode` varchar(50) NOT NULL,
  `price` double NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `qty_sub_stock` int(5) NOT NULL DEFAULT '1',
  `barcode_fk` varchar(50) NOT NULL,
  `price_before` int(6) DEFAULT NULL COMMENT 'ราคาทุน',
  PRIMARY KEY (`barcode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `config_software`
--

CREATE TABLE `config_software` (
  `id` int(5) NOT NULL,
  `alert_min_stock` int(3) NOT NULL DEFAULT '1' COMMENT 'จำนวนคงเหลือต่ำสุด',
  `bill_slip_title` varchar(250) NOT NULL COMMENT 'หัวบิล สสลิปการขาย',
  `bill_send_title` varchar(255) NOT NULL COMMENT 'หัวบิล ใบส่งสินค้า',
  `bill_vat_title` varchar(255) NOT NULL COMMENT 'หัวบิล ใบกำกับภาษี',
  `bill_sale_title` varchar(255) NOT NULL COMMENT 'หัวบิล ใบเสร็จรับเงิน',
  `bill_drop_title` varchar(255) NOT NULL COMMENT 'หัวบิล ใบวางบิล',
  `items_per_page` int(3) NOT NULL DEFAULT '10',
  `bill_slip_footer` text,
  `bill_send_footer` text,
  `bill_vat_footer` text,
  `bill_sale_footer` text,
  `bill_drop_footer` text,
  `score` int(3) DEFAULT NULL COMMENT 'กี่บาทเป็น 1 แต้ม',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `config_software` WRITE;
/*!40000 ALTER TABLE `config_software` DISABLE KEYS */;

INSERT INTO `config_software` (`id`, `alert_min_stock`, `bill_slip_title`, `bill_send_title`, `bill_vat_title`, `bill_sale_title`, `bill_drop_title`, `items_per_page`, `bill_slip_footer`, `bill_send_footer`, `bill_vat_footer`, `bill_sale_footer`, `bill_drop_footer`, `score`)
VALUES
  (1,20,'ใบเสร็จรับเงินจ้า','ใบส่งสินค้า','ใบกำกับภาษี','ใบเสร็จรับเงิน','ใบวางบิล',20,NULL,NULL,NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `config_software` ENABLE KEYS */;
UNLOCK TABLES;

--
-- โครงสร้างตาราง `drawcash_logs`
--

CREATE TABLE `drawcash_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `draw_date` datetime DEFAULT NULL,
  `draw_price` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `product_prices`
--

CREATE TABLE `product_prices` (
  `order_field` int(2) NOT NULL,
  `product_barcode` varchar(20) NOT NULL,
  `qty` int(5) NOT NULL DEFAULT '0',
  `price` double NOT NULL DEFAULT '0',
  `price_send` double NOT NULL DEFAULT '0',
  `qty_end` int(5) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `quotations`
--

CREATE TABLE `quotations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `customer_address` varchar(1000) NOT NULL,
  `customer_tel` varchar(50) DEFAULT NULL,
  `customer_fax` varchar(50) DEFAULT NULL,
  `customer_tax` varchar(13) DEFAULT NULL,
  `quotation_day` int(3) NOT NULL,
  `quotation_send_day` int(3) NOT NULL,
  `quotation_pay` enum('cash','credit') NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `vat` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `quotation_details`
--

CREATE TABLE `quotation_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quotation_id` int(11) NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `old_price` float NOT NULL,
  `qty` int(5) NOT NULL,
  `sub` int(11) NOT NULL,
  `sale_price` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `sale_temp`
--

CREATE TABLE `sale_temp` (
  `barcode` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `serial` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `qty` int(5) DEFAULT NULL,
  `qty_per_pack` int(5) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `pk_temp` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_alert`
--

CREATE TABLE `tb_alert` (
  `alert_id` int(11) NOT NULL,
  `alert_topic` varchar(255) DEFAULT NULL,
  `alert_day` date DEFAULT NULL,
  `alert_detail` text,
  `alert_created_date` datetime DEFAULT NULL,
  `alert_status` enum('wait','do','complete') DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`alert_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_ar`
--

CREATE TABLE `tb_ar` (
  `ar_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `bill_sale_id` int(11) DEFAULT NULL,
  `ar_will_pay_date` date DEFAULT NULL,
  `ar_pay_date` datetime DEFAULT NULL,
  `ar_status` enum('wait','pay','cancel') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ar_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_bill_config`
--

CREATE TABLE `tb_bill_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slip_width` int(4) NOT NULL,
  `slip_height` int(4) NOT NULL,
  `bill_send_product_width` int(4) NOT NULL,
  `bill_send_product_height` int(4) NOT NULL,
  `bill_add_tax_width` int(4) NOT NULL,
  `bill_add_tax_height` int(4) NOT NULL,
  `bill_drop_width` int(5) NOT NULL,
  `bill_drop_height` int(5) NOT NULL,
  `slip_paper` varchar(10) NOT NULL,
  `slip_position` varchar(10) NOT NULL,
  `bill_send_product_paper` varchar(10) NOT NULL,
  `bill_send_product_position` varchar(10) NOT NULL,
  `bill_drop_paper` varchar(10) NOT NULL,
  `bill_drop_position` varchar(10) NOT NULL,
  `bill_add_tax_paper` varchar(10) NOT NULL,
  `bill_add_tax_position` varchar(10) NOT NULL,
  `slip_font_size` int(2) NOT NULL DEFAULT '11' COMMENT 'ขนาดตัวอักษร',
  `sale_font_size` int(2) NOT NULL DEFAULT '11' COMMENT 'ขนาดตัวอักษร ใบเสร็จ',
  `sale_width` int(2) DEFAULT NULL COMMENT 'ความกว้างใบเสร็จ',
  `sale_height` int(2) DEFAULT NULL COMMENT 'ความสูงใบเสร็จ',
  `sale_paper` varchar(10) NOT NULL DEFAULT 'A4' COMMENT 'รูปแบบกระดาษ ใบเสร็จ',
  `sale_position` varchar(15) NOT NULL DEFAULT 'vertical' COMMENT 'แนวนอน แนวตั้ง ของใบเสร็จ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_bill_import`
--

CREATE TABLE `tb_bill_import` (
  `bill_import_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bill_import_created_date` datetime NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `bill_import_pay` enum('cash','credit') COLLATE utf8_unicode_ci NOT NULL,
  `bill_import_remark` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `bill_import_pay_status` enum('pay','wait') COLLATE utf8_unicode_ci NOT NULL,
  `bill_import_pay_date` datetime DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `from_branch_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`bill_import_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_bill_import_detail`
--

CREATE TABLE `tb_bill_import_detail` (
  `bill_import_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_import_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_id` int(11) NOT NULL,
  `box_id` int(11) NOT NULL,
  `import_bill_detail_product_qty` int(5) NOT NULL,
  `import_bill_detail_price` float NOT NULL,
  `import_bill_detail_qty` int(5) NOT NULL,
  `import_bill_detail_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'รหัสสินค้า ที่รับเข้า',
  `import_bill_detail_qty_per_pack` int(2) DEFAULT NULL COMMENT 'จำนวนต่อแพค',
  PRIMARY KEY (`bill_import_detail_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_bill_sale`
--

CREATE TABLE `tb_bill_sale` (
  `bill_sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_sale_created_date` datetime NOT NULL,
  `bill_sale_status` enum('wait','pay','cancel','credit') COLLATE utf8_unicode_ci NOT NULL,
  `member_id` int(11) NOT NULL,
  `bill_sale_vat` enum('no','vat') COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `bill_sale_pay_date` datetime DEFAULT NULL,
  `bill_sale_drop_bill_date` date DEFAULT NULL,
  `bill_sale_want_drop` enum('no','yes') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`bill_sale_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=398 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_bill_sale_detail`
--

CREATE TABLE `tb_bill_sale_detail` (
  `bill_sale_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_id` int(11) NOT NULL,
  `bill_sale_detail_barcode` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `bill_sale_detail_price` double DEFAULT NULL,
  `bill_sale_detail_price_vat` double(5,2) NOT NULL,
  `bill_sale_detail_qty` int(5) NOT NULL,
  `bill_sale_detail_has_bonus` enum('no','yes') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `bill_sale_detail_type` enum('one','many') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'one' COMMENT 'ขายปลีก ขายส่ง',
  PRIMARY KEY (`bill_sale_detail_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=854 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_bill_sale_temp`
--

CREATE TABLE `tb_bill_sale_temp` (
  `product_total` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_price` int(5) DEFAULT NULL,
  `bill_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_box`
--

CREATE TABLE `tb_box` (
  `box_id` int(11) NOT NULL AUTO_INCREMENT,
  `box_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `box_total_per_unit` int(5) NOT NULL,
  `box_detail` text COLLATE utf8_unicode_ci NOT NULL,
  `box_created_date` datetime NOT NULL,
  PRIMARY KEY (`box_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_branch`
--

CREATE TABLE `tb_branch` (
  `branch_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(255) DEFAULT NULL,
  `branch_tel` varchar(255) DEFAULT NULL,
  `branch_email` varchar(255) DEFAULT NULL,
  `branch_address` text,
  `branch_created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`branch_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_client`
--

CREATE TABLE `tb_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(255) DEFAULT NULL,
  `client_score` int(11) DEFAULT NULL,
  `client_regis_date` date DEFAULT NULL,
  `client_serial` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_customer`
--

CREATE TABLE `tb_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `customer_tel` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_code` varchar(13) NOT NULL,
  `customer_sex` enum('f','m') NOT NULL,
  `customer_created_date` datetime NOT NULL,
  `customer_birth_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_farmer`
--

CREATE TABLE `tb_farmer` (
  `farmer_id` int(11) NOT NULL AUTO_INCREMENT,
  `farmer_name` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `farmer_tel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `farmer_address` text COLLATE utf8_unicode_ci NOT NULL,
  `farmer_created_date` datetime NOT NULL,
  PRIMARY KEY (`farmer_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_group_product`
--

CREATE TABLE `tb_group_product` (
  `group_product_id` int(5) NOT NULL AUTO_INCREMENT,
  `group_product_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `group_product_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `group_product_detail` text COLLATE utf8_unicode_ci NOT NULL,
  `group_product_created_date` datetime NOT NULL,
  `group_product_last_update` datetime NOT NULL,
  PRIMARY KEY (`group_product_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_group_product_box`
--

CREATE TABLE `tb_group_product_box` (
  `group_product_id` int(11) NOT NULL,
  `box_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_import_product`
--

CREATE TABLE `tb_import_product` (
  `import_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `import_product_total` int(5) NOT NULL,
  `import_product_price` int(4) NOT NULL,
  `import_product_created_date` datetime NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `box_id` int(11) NOT NULL,
  PRIMARY KEY (`import_product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_member`
--

CREATE TABLE `tb_member` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `member_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `member_tel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `member_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `member_created_date` datetime NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=39 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_organization`
--

CREATE TABLE `tb_organization` (
  `org_id` int(11) NOT NULL AUTO_INCREMENT,
  `org_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `org_address_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `org_address_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `org_address_3` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `org_address_4` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `org_tel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `org_fax` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `org_tax_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `org_current_version` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `org_name_eng` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `org_logo_show_on_bill` enum('no','yes') COLLATE utf8_unicode_ci NOT NULL,
  `org_logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `logo_show_on_header` enum('no','yes') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `logo_show_on_header_bg` enum('no','yes') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`org_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;


LOCK TABLES `tb_organization` WRITE;
/*!40000 ALTER TABLE `tb_organization` DISABLE KEYS */;

INSERT INTO `tb_organization` (`org_id`, `org_name`, `org_address_1`, `org_address_2`, `org_address_3`, `org_address_4`, `org_tel`, `org_fax`, `org_tax_code`, `org_current_version`, `org_name_eng`, `org_logo_show_on_bill`, `org_logo`, `logo_show_on_header`, `logo_show_on_header_bg`)
VALUES
  (1,'อินดี้มินิมาร์ท','35/2 ถนนรัถการ  ต.คลองแห  อ.หาดใหญ่ จ.สงขลา 90110','','','','081-609-9911 / 074-214-139','074-214-139','-',NULL,'','yes','logo1.png','no','no');

/*!40000 ALTER TABLE `tb_organization` ENABLE KEYS */;
UNLOCK TABLES;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_percen_sale`
--

CREATE TABLE `tb_percen_sale` (
  `percen_sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `percen_sale_total` int(3) NOT NULL,
  `percen_sale_created_date` datetime NOT NULL,
  PRIMARY KEY (`percen_sale_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_product`
--

CREATE TABLE `tb_product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK สินค้า',
  `group_product_id` int(5) NOT NULL COMMENT 'รหัส หมวดสินค้า',
  `product_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'บาโค้ด',
  `product_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'ชื่อสินค้า',
  `product_detail` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'รายละเอียด',
  `product_created_date` datetime DEFAULT NULL COMMENT 'วันที่บันทึก',
  `product_last_update` datetime DEFAULT NULL COMMENT 'วันที่อัพเดตล่าสุด',
  `product_quantity` int(6) DEFAULT '0' COMMENT 'จำนวน',
  `product_pack_barcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'รหัสแพค',
  `product_total_per_pack` int(5) NOT NULL COMMENT 'จำนวนต่อแพค',
  `product_expire` enum('expire','fresh') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'expire' COMMENT 'สินค้าสด/ไม่สด',
  `product_return` enum('in','out') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'in' COMMENT 'สินค้าของร้าน',
  `product_expire_date` date DEFAULT NULL COMMENT 'วันหมดอายุ',
  `product_sale_condition` enum('sale','prompt') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'เงื่อนไขการขาย',
  `product_price` float NOT NULL COMMENT 'ราคาจำหน่าย',
  `product_price_send` float NOT NULL COMMENT 'ราคาขายส่ง',
  `product_price_per_pack` int(6) NOT NULL COMMENT 'ราคาต่อแพค',
  `product_quantity_of_pack` int(5) NOT NULL COMMENT 'จำนวนคงเหลือ แพค',
  `product_price_buy` float NOT NULL COMMENT 'ต้นทุนเฉลีย',
  `product_tag` int(1) NOT NULL DEFAULT '0',
  `product_pic` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `weight` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3793 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_product_box`
--

CREATE TABLE `tb_product_box` (
  `product_id` int(11) NOT NULL,
  `box_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_product_price`
--

CREATE TABLE `tb_product_price` (
  `product_price_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `box_id` int(11) NOT NULL,
  `product_price_price` int(8) NOT NULL,
  `product_price_barcode` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`product_price_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_product_price_per_unit`
--

CREATE TABLE `tb_product_price_per_unit` (
  `product_id` int(11) NOT NULL,
  `product_price_per_unit_barcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_price_per_unit_price` int(11) DEFAULT NULL,
  `product_price_per_unit_old_price` float NOT NULL,
  `product_price_per_unit_sent` int(11) DEFAULT NULL,
  `product_price_per_unit_idint` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_product_serial`
--

CREATE TABLE `tb_product_serial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` int(11) NOT NULL,
  `serial_no` varchar(255) NOT NULL,
  `product_start_date` date NOT NULL,
  `product_expire_date` date NOT NULL,
  `bill_sale_id` int(11) NOT NULL,
  `clame_date` datetime NOT NULL,
  `clame_status` enum('no','wait','complete') NOT NULL,
  `repair_date` datetime NOT NULL,
  `repair_status` enum('no','wait','complete') NOT NULL,
  `user_id` int(11) NOT NULL,
  `current_status` enum('no','repair','clame') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=854 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_repair`
--

CREATE TABLE `tb_repair` (
  `repair_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `product_code` varchar(255) DEFAULT NULL,
  `repair_date` date DEFAULT NULL,
  `repair_problem` varchar(1000) DEFAULT NULL,
  `repair_price` int(11) DEFAULT NULL,
  `repair_type` enum('internal','center','external') DEFAULT NULL,
  `repair_original` varchar(1000) DEFAULT NULL,
  `repair_detail` varchar(1000) DEFAULT NULL,
  `repair_created_date` datetime DEFAULT NULL,
  `repair_status` enum('wait','do','complete') DEFAULT NULL,
  `serial_no` varchar(50) NOT NULL,
  `repair_group` enum('internal','external') NOT NULL DEFAULT 'internal' COMMENT 'สินค้าซ่อมมาจากร้านหรือไม่ internal คือสินค้าในร้าน external คือสินค้านอกร้าน',
  `repair_tel` varchar(50) NOT NULL COMMENT 'เบอร์โทรติดต่อ',
  `repair_name` varchar(255) NOT NULL COMMENT 'ลูกค้า',
  `repair_product_name` varchar(255) NOT NULL COMMENT 'สินค้า',
  `repair_end_date` datetime DEFAULT NULL COMMENT 'วันที่ซ่อมเสร็จ',
  PRIMARY KEY (`repair_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_sale_per_day`
--

CREATE TABLE `tb_sale_per_day` (
  `sale_year` int(4) DEFAULT NULL,
  `sale_month` int(2) DEFAULT NULL,
  `sale_day` int(2) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `sale_total_price` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_stock`
--

CREATE TABLE `tb_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stock_qty` int(11) NOT NULL,
  `stock_created_date` datetime NOT NULL,
  `stock_pack_qty` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `stock_qty_from_manual` int(11) DEFAULT NULL,
  `stock_qty_real` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tb_user`
--

CREATE TABLE `tb_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_tel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_level` enum('cacheer','admin') COLLATE utf8_unicode_ci NOT NULL,
  `user_username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_created_date` datetime NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

LOCK TABLES `tb_user` WRITE;
/*!40000 ALTER TABLE `tb_user` DISABLE KEYS */;

INSERT INTO `tb_user` (`user_id`, `user_name`, `user_tel`, `user_level`, `user_username`, `user_password`, `user_created_date`, `branch_id`)
VALUES
  (1,'ถาวร ศรีเสนพิลา','','cacheer','tavon','1234','2011-08-21 19:00:00',1),
  (2,'พนักงานขาย A001','0868776053','admin','admin','','2011-08-22 01:56:40',1),
  (8,'niwat thongchumnum','0824448555','admin','niwat','6441','2012-04-09 13:18:43',1),
  (5,'ต้นอ้อ','','admin','tonor','2227','2011-12-22 15:30:32',1),
  (6,'พนักงาน','','cacheer','cashier','1234','2012-03-19 11:25:04',1);

/*!40000 ALTER TABLE `tb_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_group_permissions`
--