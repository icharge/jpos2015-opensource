<?php

class Branch extends CActiveRecord {

  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function tableName() {
    return "tb_branch";
  }

  public function attributeLabels() {
    return array(
        'branch_id' => 'id',
        'branch_name' => 'ชื่อคลังสินค้า/สาขา',
        'branch_tel' => 'เบอร์โทร',
        'branch_email' => 'email',
        'branch_address' => 'ที่อยู่',
        'branch_created_date' => 'วันที่บันทึก'
    );
  }

  public function rules() {
    return array(
        array('branch_name, branch_tel, branch_address', 'required'),
        array('branch_email, branch_id', 'safe')
    );
  }

  public function beforeValidate() {
    if ($this->isNewRecord) {
      $this->branch_created_date = new CDbExpression("NOW()");
    }
    return parent::beforeValidate();
  }

  public function search() {
    $criteria = new CDbCriteria();
    $criteria->compare('branch_id', $this->branch_id);
    $criteria->compare('branch_name', $this->branch_name);
    $criteria->compare('branch_tel', $this->branch_tel);
    $criteria->compare('branch_email', $this->branch_email);
    $criteria->compare('branch_address', $this->branch_address);
    $criteria->compare('branch_creted_date', $this->branch_created_date);

    return new CActiveDataProvider($this, array('criteria' => $criteria));
  }

  public static function getOptions($no_select = null) {
    $model = Branch::model()->findAll();
    $arr = array();

		if ($no_select == true) {
			$arr[] = '--- ไม่เลือกรายการ ---';
		}

    foreach ($model as $r) {
      $arr[$r->branch_id] = $r->branch_name;
    }
    return $arr;
  }

}
