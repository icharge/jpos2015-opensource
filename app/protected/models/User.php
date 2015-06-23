<?php

class User extends CActiveRecord {

  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function tableName() {
    return "tb_user";
  }

  public function attributeLabels() {
    return array(
        "user_id" => "id",
        "user_name" => "ชื่อ",
        "user_tel" => "เบอร์โทร",
        "user_level" => "ระดับ",
        "user_username" => "username",
        "user_password" => "password",
        "user_created_date" => "วันที่บันทึก",
				"branch_id" => 'สาขา'
    );
  }

  public function rules() {
    return array(
        array("user_username, user_password, user_name", "required"),
        array("user_tel, user_level, user_id, branch_id", "safe")
    );
  }

  public function beforeValidate() {
    if ($this->isNewRecord) {
      $this->user_created_date = new CDbExpression("NOW()");
    }
    return parent::beforeValidate();
  }

  public function search() {
    return new CActiveDataProvider($this, array(
        'sort' => array('defaultOrder' => 'user_id DESC')
    ));
  }

	public function relations() {
		return array(
			'Branch' => array(self::BELONGS_TO, 'Branch', 'branch_id')
		);
	}

}
