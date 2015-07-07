<?php

class Member extends CActiveRecord {

  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function tableName() {
    return "tb_member";
  }

  public function attributeLabels() {
    return array(
        'member_id' => 'id',
        'member_name' => 'ชื่อสมาชิก/ร้าน',
        'member_code' => 'รหัส',
        'member_tel' => 'เบอร์โทร',
        'member_address' => 'ที่อยู่',
        'member_created_date' => 'วันที่บันทึก',
				'branch_id' => 'สาขาที่สมัคร',
        'tax_code' => 'เลขผู้เสียภาษี'
    );
  }

  public function rules() {
    return array(
        array('member_code, member_name', 'required'),
        array('member_id, member_tel, member_address, member_created_date', 'safe')
    );
  }
  
  public function relations() {
    return array(
        'Branch' => array(self::BELONGS_TO, 'Branch', 'branch_id')
    );
  }

  public function beforeValidate() {
    if ($this->isNewRecord) {
      $this->member_created_date = new CDbExpression("NOW()");
    }
    
    return parent::beforeValidate();
  }

  public function search() {
    $criteria = new CDbCriteria();
    $criteria->compare('member_code', $this->member_code);
    $criteria->compare('member_name', $this->member_name);
    $criteria->compare('member_tel', $this->member_tel);
    $criteria->compare('member_address', $this->member_address);

    return new CActiveDataProvider($this, array(
        'sort' => array(
            'defaultOrder' => 'member_id DESC'
        ),
        'criteria' => $criteria,
        'pagination' => array(
            'pageSize' => 20
        )
    ));
  }

}


