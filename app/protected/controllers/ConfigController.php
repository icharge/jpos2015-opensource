<?php

class ConfigController extends Controller {

  function checkLogin() {
    if (Yii::app()->request->cookies['user_id'] == null) {
      $this->redirect(array("Site/Index"));
    }
  }

  function actionOrganization() {   
    $this->checkLogin(); 

    $model = Organization::model()->find();

    if (!empty($_POST)) {
      $model->attributes = $_POST["Organization"];

      // show logo on bill
      $org_logo_show_on_bill = "no";
      $logo_show_on_header = "no";
      $logo_show_on_header_bg = "no";

      if (!empty($_POST['logo_show_on_header'])) {
        $logo_show_on_header = Util::input($_POST['logo_show_on_header']);
      }
      if (!empty($_POST['org_logo_show_on_bill'])) {
        $org_logo_show_on_bill = Util::input($_POST['org_logo_show_on_bill']);
      }
      if (!empty($_POST['logo_show_on_header_bg'])) {
        $logo_show_on_header_bg = Util::input($_POST['logo_show_on_header_bg']);
      }

      $on_bill = 'no';

      if ($org_logo_show_on_bill == 1) {
        $on_bill = 'yes';
      }

      $model->org_logo_show_on_bill = $on_bill;
      $model->logo_show_on_header = $logo_show_on_header;
      $model->logo_show_on_header_bg = $logo_show_on_header_bg;

      // logo
      if (!empty($_FILES['Organization'])) {
        $org_logo = $_FILES['Organization'];

        $name = $org_logo['name']['org_logo'];
        $tmp = $org_logo['tmp_name']['org_logo'];
        $size = $org_logo['size']['org_logo'];

        if ($size > 0) {
          $ext = explode(".", $name);
          $ext = $ext[count($ext) - 1];
          $ext = strtolower($ext);

          $name = microtime();
          $name = str_replace(" ", "", $name);
          $name = str_replace(".", "", $name);

          if ($ext == "jpg" || $ext == "png") {
            $newName = "{$name}.{$ext}";

            if (move_uploaded_file($tmp, "upload/$newName")) {
              // remove old file
              $oldName = $model->org_logo;

              if (file_exists('upload/' . $oldName)) {
                @unlink('upload/' . $oldName);
              }

              $model->org_logo = $name.".".$ext;
            }
          }
        }
      }

      $model->save();
    }
    $this->render("//Config/Organization", array(
      'model' => $model
    ));
  }

  function actionBranchIndex() {
    $this->checkLogin();

    $model = new Branch();
    $this->render("//Config/BranchIndex", array('model' => $model));
  }

  function actionBranchForm($id = null) {
    $this->checkLogin();

    $model = new Branch();

    if (!empty($_POST)) {
      $pk = Util::input($_POST["Branch"]["branch_id"]);

      if (!empty($pk)) {
        $model = Branch::model()->findByPk((int) $pk);
      }
      $model->attributes = Util::input($_POST["Branch"]);

      if ($model->save()) {
        $this->redirect(array('BranchIndex'));
      }
    }

    if ($id != null) {
      $model = Branch::model()->findByPk((int) $id);
    }
    $this->render('//Config/BranchForm', array('model' => $model));
  }

  function actionBranchDelete($id) {
    $this->checkLogin();

    Branch::model()->deleteByPk((int) $id);
    $this->redirect(array('BranchIndex'));
  }

  function actionGroupProductIndex() {
    $this->checkLogin();

    $configSoftware = ConfigSoftware::model()->find();

    $model = new CActiveDataProvider('GroupProduct', array(
      'criteria' => array(
        'order' => 'group_product_id DESC'
      ),
      'pagination' => array(
        'pageSize' => $configSoftware->items_per_page
      )
    ));

    $this->render('//Config/GroupProductIndex', array(
      'model' => $model
    ));
  }

  function actionGroupProductForm($id = null) {
    $this->checkLogin();

    $model = new GroupProduct();

    if (!empty($_POST)) {
      $pk = Util::input($_POST["GroupProduct"]["group_product_id"]);

      if (!empty($pk)) {
        $model = GroupProduct::model()->findByPk((int) $pk);
      }
      $model->attributes = Util::input($_POST["GroupProduct"]);

      if ($model->save()) {
        $this->redirect(array('GroupProductIndex'));
      }
    }

    if (!empty($id)) {
      $model = GroupProduct::model()->findByPk((int) $id);
    }

    $this->render('//Config/GroupProductForm', array(
      'model' => $model
    ));
  }

  function actionGroupProductDelete($id) {
    $this->checkLogin();

    GroupProduct::model()->deleteByPk((int) $id);
    $this->redirect(array('GroupProductIndex'));
  }

  function actionProductIndex($productTag = false, $search = null, $group_product_id = null) {
    $this->checkLogin();

    $configSoftware = ConfigSoftware::model()->find();

    $condition = "product_id > 0";

    if ($productTag) {
      $condition .= " AND product_tag = 1";
    }

    $params = array();

    if (!empty($search)) {
      $condition .= " 
        AND 
        (
          product_name LIKE(:search)
          OR product_code LIKE(:search)
        )
      ";
      $params['search'] = '%'.$search.'%';
    }

    if (!empty($_GET['Product_sort'])) {
      $order = Util::input($_GET['Product_sort']);
      $order = str_replace(".desc", " DESC", $order);
    } else {
      $order = "product_id DESC";
    }

    if (!empty($group_product_id)) {
      $condition .= " AND group_product_id = :group_product_id";
      $params['group_product_id'] = $group_product_id;
    }

    $model = new CActiveDataProvider("Product", array(
      "criteria" => array(
        "condition" => $condition,
        "order" => $order,
        "params" => $params
      ),
      'pagination' => array(
        'pageSize' => $configSoftware->items_per_page
      )
    ));
    
    $this->render('//Config/ProductIndex', array(
      'model' => $model,
      'search' => $search,
      'group_product_id' => $group_product_id
    ));
  }

  function actionProductForm($id = null) {
    $this->checkLogin();

    $model = new Product();
    $default_product_expire = 'expire';
    $default_product_return = 'in';
    $default_product_sale_condition = 'sale';

    if (!empty($_POST)) {
      $pk = Util::input($_POST["Product"]["product_id"]);

      if (!empty($pk)) {
        $model = Product::model()->findByPk((int) $pk);
      }

      // group_product_code to group_product_id
      $pk = Util::input($_POST['Product']['group_product_id']);
      $groupProduct = GroupProduct::model()->findByAttributes(array(
        "group_product_code" => $pk
      ));

      if (empty($groupProduct)) {
        $groupProduct = GroupProduct::model()->findByPk((int) $pk);
      }

      $model->attributes = Util::input($_POST["Product"]);
      $model->group_product_id = $groupProduct->group_product_id;
      $model->weight = Util::input($_POST['weight']);

      if (!empty($_POST['product_tag'])) {
        $model->product_tag = Util::input($_POST['product_tag']);
      } else {
        $model->product_tag = 0;
      }

      // process total small unit
      if (!empty($_POST['Product']['product_quantity_of_pack'])) {
        $total = ($model->product_total_per_pack * $model->product_quantity_of_pack);
        $model->product_quantity = $total;
      }

      // product_expire_date
      if (!empty($_POST['Product']['product_expire_date'])) {
        $product_expire_date = Util::input($_POST['Product']['product_expire_date']);
        $model->product_expire_date = Util::thaiToMySQLDate($product_expire_date);
      }

      // save image of product and upload
      if ($_FILES['product_pic']['name'] != "") {
        $name = $_FILES['product_pic']['name'];
        $tmp = $_FILES['product_pic']['tmp_name'];

        $ext = explode(".", $name);
        $ext = $ext[count($ext) - 1];

        $ext = strtolower($ext);

        if ($ext == "png" || $ext == "jpg") {
          $name = microtime();
          $name = str_replace(" ", "", $name);
          $name = str_replace("0.", "", $name);
          $name = $name.".".$ext;

          if (move_uploaded_file($tmp, "upload/$name")) {
            // remove old image
            if (!empty($model->product_pic)) {
              $oldImg = $model->product_pic;

              if (file_exists("upload/$oldImg")) {
                unlink("upload/$oldImg");
              }
            }

            // set new image
            $model->product_pic = $name;
          }
        }
      }

      // save
      if ($model->save()) {
        $this->redirect(array('ProductIndex'));
      }
    }

    $barcodePrices = null;

    if (!empty($id)) {
      $model = Product::model()->findByPk((int) $id);
      $model->product_expire_date = Util::mysqlToThaiDate($model->product_expire_date);

      $barcodePrices = BarcodePrice::model()->findAllByAttributes(array(
        'barcode_fk' => $model->product_code
      ));
    } else {
      $model->product_total_per_pack = 1;
    }

    $params['model'] = $model;
    $params['default_product_expire'] = $default_product_expire;
    $params['default_product_return'] = $default_product_return;
    $params['default_product_sale_condition'] = $default_product_sale_condition;
    $params['barcodePrices'] = $barcodePrices;

    $this->render('//Config/ProductForm', $params);
  }

  function actionProductDelete($id) {
    $this->checkLogin();

    Product::model()->deleteByPk((int) $id);
    $this->redirect(array('ProductIndex'));
  }

  function actionFarmerIndex() {
    $this->checkLogin();

    $configSoftware = ConfigSoftware::model()->find();

    $model = new CActiveDataProvider('Farmer', array(
      'criteria' => array(
        'order' => 'farmer_id DESC'
      ),
      'pagination' => array(
        'pageSize' => $configSoftware->items_per_page
      )
    ));

    $this->render('//Config/FarmerIndex', array(
      'model' => $model
    ));
  }

  function actionFarmerForm($id = null) {
    $this->checkLogin();

    $model = new Farmer();

    if (!empty($_POST)) {
      $pk = (int) Util::input($_POST['Farmer']['farmer_id']);

      if (!empty($pk)) {
        $model = Farmer::model()->findByPk((int) $pk);
      }
      $model->attributes = Util::input($_POST['Farmer']);

      if ($model->save()) {
        $this->redirect(array('FarmerIndex'));
      }
    }

    if (!empty($id)) {
      $model = Farmer::model()->findByPk((int) $id);
    }
    $this->render('//Config/FarmerForm', array(
      'model' => $model
    ));
  }

  function actionFarmerDelete($id) {
    $this->checkLogin();

    Farmer::model()->deleteByPk((int) $id);
    $this->redirect(array('FarmerIndex'));
  }

  function actionMemberIndex() {
    $this->checkLogin();

    $configSoftware = ConfigSoftware::model()->find();

    $model = new CActiveDataProvider('Member', array(
      'criteria' => array(
        'order' => 'member_id DESC'
      ),
      'pagination' => array(
        'pageSize' => $configSoftware->items_per_page
      )
    ));

    $this->render('//Config/MemberIndex', array(
      'model' => $model
    ));
  }

  function actionMemberForm($id = null) {
    $this->checkLogin();

    $model = new Member();

    if (!empty($_POST)) {
      $pk = Util::input($_POST['Member']['member_id']);

      if (!empty($pk)) {
        $model = Member::model()->findByPk((int) $pk);
      }

      $model->attributes = Util::input($_POST["Member"]);
      $model->remark = Util::input($_POST['Member']['remark']);
      $model->tax_code = Util::input($_POST['Member']['tax_code']);

      // save branch_id
      if (empty($_POST['Member']['branch_id'])) {
        $user_id = Yii::app()->request->cookies['user_id']->value;
        $user = User::model()->findByPk((int) $user_id);

        $model->branch_id = $user->branch_id;
      }

      if ($model->save()) {
        $this->redirect(array('MemberIndex'));
      }
    }

    if (!empty($id)) {
      $model = Member::model()->findByPk((int) $id);
    }

    $this->render('//Config/MemberForm', array(
      'model' => $model
    ));
  }

  function actionMemberDelete($id = null) {
    $this->checkLogin();

    Member::model()->deleteByPk((int) $id);
    $this->redirect(array('MemberIndex'));
  }

  function actionUserIndex() {
    $this->checkLogin();

    $configSoftware = ConfigSoftware::model()->find();

    $model = new CActiveDataProvider('User', array(
      'criteria' => array(
        'order' => 'user_id DESC'
      ),
      'pagination' => array(
        'pageSize' => $configSoftware->items_per_page
      )
    ));

    $this->render('//Config/UserIndex', array(
      'model' => $model
    ));
  }

  function actionUserForm($id = null) {
    $this->checkLogin();

    $model = new User();

    if (!empty($_POST)) {
      $pk = Util::input($_POST["User"]["user_id"]);

      if (!empty($pk)) {
        $model = User::model()->findByPk((int) $id);
      }

      $model->attributes = Util::input($_POST["User"]);

      if ($model->save()) {
        $this->redirect(array('UserIndex'));
      }
    }

    if (!empty($id)) {
      $model = User::model()->findByPk((int) $id);
    }
    $this->render('//Config/UserForm', array(
      'model' => $model
    ));
  }

  function actionUserDelete($id) {
    $this->checkLogin();

    User::model()->deleteByPk((int) $id);
    $this->redirect(array('UserIndex'));
  }

  function actionBillConfigIndex() {
    $this->checkLogin();

    $billConfig = BillConfig::model()->find();

    if (empty($billConfig)) {
      $billConfig = new BillConfig();
    }

    // Save
    if (!empty($_POST)) {
      $billConfig->slip_font_size = Util::input($_POST['BillConfig']['slip_font_size']);
      $billConfig->slip_width = Util::input($_POST['BillConfig']['slip_width']);
      $billConfig->slip_height = Util::input($_POST['BillConfig']['slip_height']);
      $billConfig->slip_paper = Util::input($_POST['BillConfig']['slip_paper']);
      $billConfig->slip_position = Util::input($_POST['BillConfig']['slip_position']);
      $billConfig->bill_send_product_width = Util::input($_POST['BillConfig']['bill_send_product_width']);
      $billConfig->bill_send_product_height = Util::input($_POST['BillConfig']['bill_send_product_height']);
      $billConfig->bill_send_product_paper = Util::input($_POST['BillConfig']['bill_send_product_paper']);
      $billConfig->bill_send_product_position = Util::input($_POST['BillConfig']['bill_send_product_position']);
      $billConfig->bill_send_show_line = @Util::input($_POST['bill_send_show_line']);
      $billConfig->bill_drop_width = Util::input($_POST['BillConfig']['bill_drop_width']);
      $billConfig->bill_drop_height = Util::input($_POST['BillConfig']['bill_drop_height']);
      $billConfig->bill_drop_paper = Util::input($_POST['BillConfig']['bill_drop_paper']);
      $billConfig->bill_drop_position = Util::input($_POST['BillConfig']['bill_drop_position']);
      $billConfig->bill_drop_show_line = @Util::input($_POST['bill_drop_show_line']);
      $billConfig->bill_add_tax_width = Util::input($_POST['BillConfig']['bill_add_tax_width']);
      $billConfig->bill_add_tax_height = Util::input($_POST['BillConfig']['bill_add_tax_height']);
      $billConfig->bill_add_tax_paper = Util::input($_POST['BillConfig']['bill_add_tax_paper']);
      $billConfig->bill_add_tax_position = Util::input($_POST['BillConfig']['bill_add_tax_position']);
      $billConfig->bill_add_show_line = @Util::input($_POST['bill_add_show_line']);
      $billConfig->sale_width = Util::input($_POST['BillConfig']['sale_width']);
      $billConfig->sale_height = Util::input($_POST['BillConfig']['sale_height']);
      $billConfig->sale_paper = Util::input($_POST['BillConfig']['sale_paper']);
      $billConfig->sale_position = Util::input($_POST['BillConfig']['sale_position']);
      $billConfig->sale_condition_show_line = @Util::input($_POST['sale_condition_show_line']);

      if ($billConfig->save()) {
        $this->redirect(array('BillConfigIndex'));
      }
    }

    $this->render('//Config/BillConfigIndex', array(
      'billConfig' => $billConfig
    ));
  }

  function actionAbout() {
    $this->checkLogin();
    $this->render('//Config/About');
  }

  function actionProductClear() {
    $this->checkLogin();

    Product::model()->deleteAll();
    $this->redirect(array("ProductIndex"));
  }

  function actionProductPriceSave() {
    $this->checkLogin();

    if (!empty($_POST)) {
      $product_code = Util::input($_POST['product_code']);
      $product_prices = str_replace(",", "", Util::input($_POST['product_price']));
      $product_price_sends = str_replace(",", "", Util::input($_POST['product_price_send']));
      $qtys = Util::input($_POST['qty']);
      $qty_ends = Util::input($_POST['qty_end']);
      $i = 0;

      ProductPrice::model()->deleteAllByAttributes(array(
        "product_barcode" => $product_code
      ));

      foreach ($product_prices as $product_price) {
        $price = $product_price;
        $price_send = $product_price_sends[$i];
        $qty = $qtys[$i];
        $qty_end = $qty_ends[$i];

        if ($qty > 0 && $price > 0) {
          $productPrice = new ProductPrice();
          $productPrice->product_barcode = $product_code;
          $productPrice->price = $price;
          $productPrice->price_send = $price_send;
          $productPrice->qty = $qty;
          $productPrice->qty_end = $qty_end;
          $productPrice->order_field = ($i + 1);
          $productPrice->save();
        }

        $i++;
      }

      echo 'success';
    }
  }

  public function actionDrawcashSetup() {
    $this->checkLogin();

    if (!empty($_POST)) {
      if (!empty($_POST['draw_price'])) {
        $drawCash = new DrawCash();
        $drawCash->draw_price = Util::input($_POST['draw_price']);
        $drawCash->draw_date = new CDbExpression("NOW()");
        
        if ($drawCash->save()) {
          $this->redirect(array("DrawcashSetup"));
        }
      }
    }

    $drawCashs = new CActiveDataProvider('DrawCash', array(
      'criteria' => array(
        "order" => "id DESC",
        "limit" => 30
      )
    ));

    $this->render("//Config/Drawcash", array(
      'drawCashs' => $drawCashs
    ));
  }

  public function actionDrawcashDelete($id) {
    $this->checkLogin();
    
    Drawcash::model()->deleteByPk((int) $id);
    $this->redirect(array("DrawcashSetup"));
  }

  public function actionSaveProductPriceBarCode() {
    $this->checkLogin();

    if (!empty($_POST)) {
      $barcodes = Util::input($_POST['barcode']);
      $price_befores = Util::input($_POST['price_before']);
      $prices = Util::input($_POST['price']);
      $qtys = Util::input($_POST['qty']);
      $names = Util::input($_POST['name']);
      $barcode_fk = Util::input($_POST['product_code']);

      if (!empty($barcodes)) {
        $size = count($barcodes);

        // delete
        BarcodePrice::model()->deleteAllByAttributes(array(
          'barcode_fk' => $barcode_fk
        ));

        // insert
        for ($i = 0; $i < $size; $i++) {
          if ($prices[$i] != 0) {
            $barcodePrice = new BarcodePrice();
            $barcodePrice->barcode = $barcodes[$i];
            $barcodePrice->price_before = $price_befores[$i];
            $barcodePrice->price = $prices[$i];
            $barcodePrice->qty_sub_stock = $qtys[$i];
            $barcodePrice->name = $names[$i];
            $barcodePrice->barcode_fk = $barcode_fk;
            $barcodePrice->save();
          }
        }

        echo 'success';
      }
    }
  }

  public function actionConfigSoftware() {
    $this->checkLogin(); 

    $configSoftware = ConfigSoftware::model()->find();

    if (!empty($_POST)) {
      $configSoftware->alert_min_stock = Util::input($_POST['alert_min_stock']);
      $configSoftware->bill_slip_title = Util::input($_POST['bill_slip_title']);
      $configSoftware->bill_send_title = Util::input($_POST['bill_send_title']);
      $configSoftware->bill_vat_title = Util::input($_POST['bill_vat_title']);
      $configSoftware->bill_sale_title = Util::input($_POST['bill_sale_title']);
      $configSoftware->bill_drop_title = Util::input($_POST['bill_drop_title']);
      $configSoftware->items_per_page = Util::input($_POST['items_per_page']);
      $configSoftware->bill_slip_footer = Util::input($_POST['bill_slip_footer']);
      $configSoftware->bill_send_footer = Util::input($_POST['bill_send_footer']);
      $configSoftware->bill_vat_footer = Util::input($_POST['bill_vat_footer']);
      $configSoftware->bill_sale_footer = Util::input($_POST['bill_sale_footer']);
      $configSoftware->bill_drop_footer = Util::input($_POST['bill_drop_footer']);
      
      if ($configSoftware->save()) {
        Yii::app()->user->setFlash('message', 'บันทึกรายการแล้ว');
        $this->redirect(array('ConfigSoftware'));
      }
    }

    $this->render('//Config/ConfigSoftware', array(
      'configSoftware' => $configSoftware
    ));
  }

  public function actionProductImportFromExcelFile() {
    $this->checkLogin(); 

      if (!empty($_FILES['excel_file'])) {
        $f = $_FILES['excel_file'];

        // extension
        $ext = explode(".", $f['name']);
        $ext = $ext[count($ext) - 1];

        if ($ext == "csv") {
          $newName = microtime();
          $newName = str_replace(".", "", $newName);
          $newName = str_replace(" ", "", $newName);
          $newName = $newName.'.'.$ext;

          if (move_uploaded_file($f['tmp_name'], 'upload/'.$newName)) {
            $data = file_get_contents('upload/'.$newName);

            if ($data != null) {
              $rows = explode("\r\n", $data);

              if (count($rows) === 0) {
                $rows = explode("\n", $data);
              }

              foreach ($rows as $row) {
                $cells = explode(",", $row);
                $no = $cells[0];
                $barcode = trim($cells[1]);
                $name = trim($cells[2]);
                $price = trim($cells[3]);
                $price_sale = trim($cells[4]);
                $qty = trim($cells[5]);

                $product = Product::model()->findByAttributes(array(
                  'product_code' => $barcode
                ));

                if (empty($product)) {
                  $product = new Product();
                  $product->product_code = $barcode;
                  $product->group_product_id = 1;
                  $product->product_name = $name;
                  $product->product_created_date = new CDbExpression("NOW()");
                  $product->product_last_update = new CDbExpression("NOW()");
                  $product->product_quantity = $qty;
                  $product->product_price = $price_sale;           // ราคาขาย
                  $product->product_price_buy = $price; // ทุต
                  $product->save();
                }
              }
            }
          }

          $this->redirect(array('ProductIndex'));
        } else {
          echo "โปรดเลือกไฟล์แบบ .csv";
        }
      }

    $this->render('//Config/ProductImportFromExcelFile');
  }

  public function actionConfigTime() {
    $this->checkLogin(); 

    $configSoftware = ConfigSoftware::model()->find();

    if (!empty($_POST)) {
      $configSoftware->count_hour = Util::input($_POST['hour']);
      $configSoftware->save();
    }

    $this->render('//Config/ConfigTime', array(
      'configSoftware' => $configSoftware
    ));
  }

  public function actionConfigSale() {
    $this->checkLogin();

    $configSoftware = ConfigSoftware::model()->find();

    if (!empty($_POST)) {
      $sale_can_edit_price = 'no';
      $sale_can_add_sub_price = 'no';
      $sale_out_of_stock = 'no';

      $sale_can_edit_price = @Util::input($_POST['ConfigSoftware']['sale_can_edit_price']);
      $sale_can_add_sub_price = @Util::input($_POST['ConfigSoftware']['sale_can_add_sub_price']);
      $sale_out_of_stock = @Util::input($_POST['ConfigSoftware']['sale_out_of_stock']);

      $configSoftware->sale_can_edit_price = $sale_can_edit_price;
      $configSoftware->sale_can_add_sub_price = $sale_can_add_sub_price;
      $configSoftware->sale_out_of_stock = $sale_out_of_stock;

      if ($configSoftware->save()) {
        Yii::app()->user->setFlash('message', 'บันทึกรายการแล้ว');
        $this->redirect(array('ConfigSale'));
      }
    }

    $this->render('//Config/ConfigSale', array(
      'configSoftware' => $configSoftware
    ));
  }

}
