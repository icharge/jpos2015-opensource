<?php

class Organization extends CActiveRecord {

  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function tableName() {
    return "tb_organization";
  }

  public function attributeLabels() {
    return array(
        "org_id" => "id",
        "org_name" => "ชื่อร้าน/บริษัท",
        "org_address_1" => "ที่อยู่ บรรทัด 1",
        "org_address_2" => "ที่อยู่ บรรทัด 2",
        "org_address_3" => "ที่อยู่ บรรทัด 3",
        "org_address_4" => "ที่อยู่ บรรทัด 4",
        "org_tel" => "เบอร์โทร",
        "org_fax" => "fax",
        "org_tax_code" => "เลขประจำตัวผู้เสียภาษี",
        "org_name_eng" => "ชื่อร้าน/บริษัท (อังกฤษ)",
        'org_logo' => 'โลโก้ ของร้าน',
        'org_logo_show_on_bill' => 'แสดงโลโก้บนบิล'
    );
  }

  public function rules() {
    return array(
        array('org_name, org_address_1, org_tel', 'required'),
        array('org_address_2, org_address_3, org_address_4, org_fax, org_tax_code, org_name_eng', 'safe')
    );
  }

}
