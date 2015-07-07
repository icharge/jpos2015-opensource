<?php

@ob_start();

class AjaxController extends Controller {

  public function actionGetGroupProductInfo($group_product_code) {
    $attributes = array();
    $attributes["group_product_code"] = Util::input($group_product_code);

    $model = GroupProduct::model()->findByAttributes($attributes);
    echo CJSON::encode($model);
  }

  public function actionGenProductCode() {
    $varTime = microtime();
    $varTime = str_replace(" ", "", $varTime);
    $varTime = str_replace(".", "", $varTime);

    echo $varTime;
  }

  public function actionSaveProduct() {
    if (!empty($_POST)) {
      $model = new Product();
      $model->attributes = $_POST["Product"];

      if ($model->save()) {
        echo "success";
      }
    }
  }

  public function actionGetProductInfo($product_code) {
    $condition = array();
    $condition["product_code"] = Util::input($product_code);

    $model = Product::model()->findByAttributes($condition);

    if (empty($model)) {
      // find by barcode_price
      $barcodePrice = BarcodePrice::model()->findByAttributes(array(
        'barcode' => $product_code
      ));

      $model = $barcodePrice->getProduct();

      if (!empty($model)) {
        $model->product_name = $model->product_name.' : '.$barcodePrice->name.' จำนวน '.$barcodePrice->qty_sub_stock.' ชิ้น';
        $model->product_total_per_pack = (int) $barcodePrice->qty_sub_stock; 
      }
    }
    echo CJSON::encode($model);
  }

  public function actionPrintBarCode($barcode = null) {
    $barcodeObj = new Barcode39($barcode);
    $barcodeObj->draw();
  }

  public function actionSaleSaveOnGrid() {
    $billSaleDetail = Yii::app()->session['billSaleDetail'];

    $prices = $_POST['prices'];
    $qtys = $_POST['qtys'];
    $serials = $_POST['serials'];

    // remove product item from array
    for ($i = 0; $i < count($billSaleDetail); $i++) {
      $product_code = $billSaleDetail[$i]['product_code'];

      $product = Product::model()->findByAttributes(array(
          'product_code' => $product_code
      ));

      $billSaleDetail[$i]['product_serial_no'] = Util::input($serials[$i]);
      $billSaleDetail[$i]['product_price'] = (int) Util::input($prices[$i]);
      $billSaleDetail[$i]['product_qty'] = (int) Util::input($qtys[$i]);

      if ($prices[$i] < $product->product_price) {
        $billSaleDetail[$i]['has_bonus'] = 'yes';
      } else {
        $billSaleDetail[$i]['has_bonus'] = 'no';
      }
    }

    Yii::app()->session['billSaleDetail'] = $billSaleDetail;
  }

  public function actionConvertNumberToText($number) {
    echo Util::convertNumberToText($number);
  }

  public function actionBillSaleDetail($bill_sale_id) {
    $dataProvider = new CActiveDataProvider('BillSaleDetail', array(
        'criteria' => array(
            'condition' => "bill_id = :bill_sale_id",
            'order' => 'bill_sale_detail_id DESC'
        ),
        'params' => array(
          'bill_sale_id' => (int) Util::input($bill_sale_id)
        ),
        'pagination' => false
    ));

    $this->renderPartial('//Ajax/BillSaleDetail', array(
        'dataProvider' => $dataProvider,
        'bill_sale_id' => (int) Util::input($bill_sale_id)
    ));
  }

  public function actionSearchMemberByMemberCode($member_code) {
    $member = Member::model()->findByAttributes(array(
      "member_code" => Util::input($member_code)
    ));

    echo CJSON::encode($member);
  }

  public function actionGetUserInfo($user_id) {
    $user_id = (int) Util::input($user_id);

    if (!empty($user_id)) {
      $user = User::model()->findByPk($user_id);
      echo CJSON::encode($user);
    }
  }

  public function actionMinProductStock() {
    $configSoftware = ConfigSoftware::model()->find();

    if (!empty($configSoftware)) {
      $products = Product::model()->count(array(
        'condition' => 'product_quantity <= :qty',
        'order' => 'product_name',
        'params' => array(
          'qty' => $configSoftware->alert_min_stock
        )
      ));

      echo CJSON::encode($products);
    }
  }

  public function actionSale() {
    if (!empty($_POST)) {
      $product_name;
      $qty_sub_stock;
      $old_price;

      // find product
      $product = Product::model()->findByAttributes(array(
        'product_code' => Util::input($_POST['product_code'])
      ));

      // not found find from barcode_price
      if (empty($product)) {
        $barcodePrice = BarcodePrice::model()->findByAttributes(array(
          'barcode' => Util::input($_POST['product_code'])
        ));

        if (!empty($barcodePrice)) {
          $product = $barcodePrice->getProduct();
          $product_name = $product->product_name." ( {$barcodePrice->name} )";
          $qty_sub_stock = $barcodePrice->qty_sub_stock;
          $old_price = $barcodePrice->price_before;
        }
      } else {
        $product_name = $product->product_name;
        $qty_sub_stock = $product->product_total_per_pack;
      }

      // found product
      if (!empty($product)) {
        // find price
        $price = $product->product_price;
        $old_price = $product->product_price_buy;

        // find default price and send price
        $sale_condition = Util::input($_POST['sale_condition']);

        if ($sale_condition == 'one') {
          // ขายปลีก
          $price = $product->product_price;
        } else {
          // ขายส่ง
          $price = $product->product_price_send;
        }

        // find by barcode price
        $barcodePrice = BarcodePrice::model()->findByAttributes(array(
          'barcode' => Util::input($_POST['product_code'])
        ));
        
        if (!empty($barcodePrice)) {
          $price = $barcodePrice->price;
          $old_price = $barcodePrice->price_before;
          $product_name = $product->product_name." ( {$barcodePrice->name} )";
          $qty_sub_stock = $barcodePrice->qty_sub_stock;
        }

        // find between price
        $productPrice = ProductPrice::model()->find(array(
          'condition' => '
            product_barcode = :product_barcode
            AND 
            (qty <= :qty AND :qty <= qty_end)
          ',
          'params' => array(
            'qty' => (int) Util::input($_POST['qty']),
            'product_barcode' => Util::input($_POST['product_code'])
          )
        ));
        
        if (!empty($productPrice)) {
          if ($sale_condition == 'one') {
            $price = $productPrice->price;
          } else {
            $price = $productPrice->price_send;
          }
        }

        // find qty_sub_stock
        $qty_sub_stock = $product->product_total_per_pack;

        if (!empty($barcodePrice)) {
          $qty_sub_stock = $barcodePrice->qty_sub_stock;
        }

        // default qty_sub_stock
        if ($qty_sub_stock == 0) {
          $qty_sub_stock = 1;
        }

        // check condition
        $configSoftware = ConfigSoftware::model()->find();
        $is_sale = false;

        if ($configSoftware->sale_out_of_stock == 'yes') {
          $is_sale = true;
        } else {
          if ($product->product_quantity > 0) {
            $is_sale = true;
          }
        }
          
        if ($is_sale) {
          // save to temp
          $saleTemp = new SaleTemp();
          $saleTemp->barcode = Util::input($_POST['product_code']);
          $saleTemp->name = $product_name;
          $saleTemp->serial = Util::input($_POST['serial']);
          $saleTemp->price = str_replace(',', '', $price);
          $saleTemp->qty = str_replace(',', '', $_POST['qty']);
          $saleTemp->qty_per_pack = $qty_sub_stock;
          $saleTemp->user_id = Yii::app()->request->cookies['user_id']->value;
          $saleTemp->branch_id = (int) Util::input($_POST['branch_id']);
          $saleTemp->pk_temp = rand(1, 99999);
          $saleTemp->created_at = new CDbExpression('NOW()');
          $saleTemp->old_price = $old_price;

          // save and update stock
          if ($saleTemp->save()) {
            Yii::app()->session['branch_id'] = (int) Util::input($_POST['branch_id']);

            echo 'success';
          }
        } else {
          echo 'สินค้าหมดสต้อก ไม่สามารถจำหน่ายได้';
        }
      }
    }
  }

  function actionRemoveRowSale($pk_temp) {
    if (!empty($pk_temp)) {
      SaleTemp::model()->deleteAllByAttributes(array(
        'pk_temp' => (int) Util::input($pk_temp)
      ));

      echo 'success';
    }
  }

  function actionEndSale() {
    
  }

  function actionClearRowSale() {
    SaleTemp::model()->deleteAllByAttributes(array(
      'user_id' => (int) Yii::app()->request->cookies['user_id']->value
    ));

    echo 'success';
  }

  function actionRowSale() {
    $saleTemps = SaleTemp::model()->findAll(array(
      'condition' => '
        branch_id = :branch_id
        AND user_id = :user_id',
      'params' => array(
        'branch_id' => (int) Yii::app()->session['branch_id'],
        'user_id' => (int) Yii::app()->request->cookies['user_id']->value
      ),
      'order' => 'created_at DESC'
    ));

    $this->renderPartial('//Ajax/RowSale', array(
      'row' => 0,
      'n' => 1,
      'saleTemps' => $saleTemps
    ));
  }

  function actionSaveDataOnGrid() {
    $pk_temp = (int) Util::input($_POST['pk_temp']);
    $price = Util::input($_POST['price']);
    $qty = Util::input($_POST['qty']);

    $sql = "
      UPDATE sale_temp SET 
        price = $price, 
        qty = $qty 
      WHERE pk_temp = $pk_temp
    ";
    Yii::app()->db->createCommand($sql)->execute();
  }

  public function actionConfigSoftwareInfo() {
    $configSoftware = ConfigSoftware::model()->find();
    echo CJSON::encode($configSoftware);
  }

  public function actionSaleTempInfo() {
    $pk_temp = (int) Util::input($_POST['pk_temp']);

    $saleTemp = SaleTemp::model()->findByAttributes(array(
      'pk_temp' => $pk_temp
    ));

    if (!empty($saleTemp)) {
      echo CJSON::encode($saleTemp);
    }
  }

  public function actionSaleTempMobileDelete() {
    $pk_temp = (int) Util::input($_POST['pk_temp']);
    $user_id = (int) Yii::app()->request->cookies['user_id']->value;

    $condition = array(
      'pk_temp' => $pk_temp,
      'user_id' => $user_id,
      'sale_type' => 'mobile'
    );

    if (SaleTemp::model()->deleteAllByAttributes($condition)) {
      echo 'success';
    }
  }

  public function actionEndSaleMobile() {
    $user_id = (int) Yii::app()->request->cookies['user_id']->value;

    $user = User::model()->findByPk($user_id);

    $saleTemps = SaleTemp::model()->findAllByAttributes(array(
      'user_id' => $user_id,
      'sale_type' => 'mobile'
    ));

    // find total money
    $total_money = 0;
    foreach ($saleTemps as $saleTemp) {
      $total_money += $saleTemp->price;
    }

    // bill sale
    $billSale = new BillSale();
    $billSale->bill_sale_created_date = new CDbExpression('NOW()');
    $billSale->bill_sale_status = 'pay';
    $billSale->member_id = 0;
    $billSale->bill_sale_vat = 'no';
    $billSale->user_id = $user_id;
    $billSale->branch_id = $user->branch_id;
    $billSale->bill_sale_pay_date = new CDbExpression('NOW()');
    $billSale->vat_type = 'in';
    $billSale->bonus_price = 0;
    $billSale->out_vat = 0;
    $billSale->input_money = 0;
    $billSale->return_money = 0;
    $billSale->total_money = $total_money;
    $billSale->sale_type = 'mobile';

    if ($billSale->save()) {
      // bill sale detail
      foreach ($saleTemps as $saleTemp) {
        $info = Product::getInfoByBarcode($saleTemp->barcode);

        $billSaleDetail = new BillSaleDetail();
        $billSaleDetail->bill_id = $billSale->bill_sale_id;
        $billSaleDetail->bill_sale_detail_barcode = $saleTemp->barcode;
        $billSaleDetail->bill_sale_detail_price = $saleTemp->price;
        $billSaleDetail->bill_sale_detail_price_vat = 0;
        $billSaleDetail->bill_sale_detail_qty = 1;
        $billSaleDetail->bill_sale_detail_has_bonus = 'no';
        $billSaleDetail->bill_sale_detail_type = 'one';
        $billSaleDetail->old_price = $info['old_price'];
        $billSaleDetail->save();
      } 

      // clear sale temp
      SaleTemp::model()->deleteAllByAttributes(array(
        'user_id' => $user_id,
        'sale_type' => 'mobile'
      ));

      // success
      echo 'success';
    }
  }

  public function actionOpenDrawCash() {
    $serial = new Serial();
    $serial->deviceOpen();
  }

}




















