<div class="pull-left">
      <?php
      $form = $this->beginWidget("CActiveForm", array(
          'htmlOptions' => array(
              'name' => 'formProduct',
              'class' => 'form-inline',
              'enctype' => 'multipart/form-data'
          ),
          'focus' => array($model, 'product_code')
      ));

      echo $form->errorSummary($model);
      ?>
      <div>
        <div class="form-search">
          <?php echo $form->labelEx($model, "product_code"); ?>
          <?php
          echo $form->textField($model, "product_code", array(
              'class' => 'form-control',
              'style' => 'width: 200px'
          ));
          ?>
          <a href="#" class="btn btn-success" title="สร้างรหัสอัตโนมัติ" onclick="genProductCode()">
            <i class="glyphicon glyphicon-export"></i>
            สร้างรหัสบาร์โค้ด
          </a>
          <a href="#" class="btn btn-success" title="พิมพ์บาโค้ด" onclick="printBarCode()">
            <span class="glyphicon glyphicon-barcode"></span>
            พิมพ์บาโค้ด
          </a>
        </div>
      </div>

      <div>
        <?php echo $form->labelEx($model, "product_name"); ?>
        <?php
        echo $form->textField($model, "product_name", array(
            'class' => 'form-control',
            'style' => 'width: 400px'
        ));
        ?>
      </div>

      <div>
        <div class="form-search">
          <?php echo $form->labelEx($model, "group_product_id"); ?>
          <?php
          echo $form->textField($model, "group_product_id", array(
              'class' => 'form-control',
              'style' => 'width: 100px',
              'onblur' => 'getGroupProductName()',
              'value' => @$model->group_product->group_product_code
          ));
          ?>
          <?php
          echo $form->textField($model, "group_product_id", array(
              'disabled' => 'disabled',
              'class' => 'form-control',
              'id' => 'Product_group_name',
              'value' => @$model->group_product->group_product_name,
              'style' => 'width: 400px'
          ));
          ?>
          <a href="#" class="btn btn-success" onclick="return dialogGroupProduct()" data-toggle="modal" data-target="#myModal">
            <i class="glyphicon glyphicon-search"></i>
            ...
          </a>
        </div>
      </div>

      <div>
        <?php echo $form->labelEx($model, 'product_price'); ?>
        <?php
        echo $form->textField($model, 'product_price', array(
            'class' => 'form-control',
            'style' => 'width: 100px'
        ));
        ?>

        <?php echo $form->labelEx($model, 'product_price_send'); ?>
        <?php
        echo $form->textField($model, 'product_price_send', array(
            'class' => 'form-control',
            'style' => 'width: 100px'
        ));
        ?>

      </div>

      <div>
        <?php echo $form->labelEx($model, 'product_price_buy'); ?>
        <?php
        echo $form->textField($model, 'product_price_buy', array(
            'class' => 'form-control',
            'style' => 'width: 100px'
        ));
        ?>

        <label>สินค้าขายบ่อย</label>
        <input type="checkbox" name="product_tag"
          <?php
          try {
            if (@$model->product_tag == 1) {
              echo "checked";
            }
          } catch (Exception $e) {

          }
          ?> value="1" />

        <label style="margin-left: 38px">น้ำหนักสินค้า (กรัม)</label>
        <input type="text" name="weight" value="<?php echo $model->weight; ?>" class="form-control" style="width: 100px" />
      </div>

      <div>
        <?php echo $form->labelEx($model, "product_detail"); ?>
        <?php
        echo $form->textField($model, "product_detail", array(
            'class' => 'form-control',
            'style' => 'width: 500px'
        ));
        ?>
      </div>

      <div>
        <label>ภาพสินค้า</label>
        <span>
          <input type="file" name="product_pic" class="form-control" style="width: 300px" />
          * รองรับไฟล์ .jpg .png เท่านั้น
        </span>
      </div>

      <div>
        <?php echo $form->labelEx($model, "product_quantity"); ?>
        <?php
        echo $form->textField($model, "product_quantity", array(
            'class' => 'form-control',
            'style' => 'width: 100px'
        ));
        ?>
      </div>

      <div style="margin-top: 7px; margin-bottom: 2px;">
        <?php echo $form->labelEx($model, "product_expire"); ?>
        <span class="alert alert-info" style="padding: 9px;">
          <?php
          echo $form->radioButton($model, "product_expire", array(
              'value' => 'expire',
              'checked' => ($default_product_expire == 'expire')
          ));
          ?> สินค้าไม่สด
          <?php
          echo $form->radioButton($model, "product_expire", array(
              'value' => 'fresh',
              'checked' => ($default_product_expire == 'fresh')
          ));
          ?> สินค้าสด
        </span>
      </div>
      <div>
        <?php echo $form->labelEx($model, "product_expire_date"); ?>
        <?php
        echo $form->textField($model, "product_expire_date", array(
            'class' => 'form-control calendar',
            'style' => 'width: 100px'
        ));
        ?>

        <?php echo $form->labelEx($model, "product_sale_condition"); ?>
        <span class="alert alert-info" style="padding: 9px">
          <?php
          echo $form->radioButton($model, "product_sale_condition", array(
              'value' => 'sale',
              'checked' => ($default_product_sale_condition == 'sale')
          ));
          ?> ขายได้ทันที
          <?php
          echo $form->radioButton($model, "product_sale_condition", array(
              'value' => 'prompt',
              'checked' => ($default_product_sale_condition == 'prompt')
          ));
          ?> กำหนดจำนวนก่อนทุกครั้ง
        </span>
      </div>
      <div style="margin-top: 8px; margin-bottom: 8px">
        <?php echo $form->labelEx($model, "product_return"); ?>
        <span class="alert alert-info" style="padding: 9px">
          <?php
          echo $form->radioButton($model, "product_return", array(
              'value' => 'in',
              'checked' => ($default_product_return == 'in')
          ));
          ?> สินค้าของร้าน
          <?php
          echo $form->radioButton($model, "product_return", array(
              'value' => 'out',
              'checked' => ($default_product_return == 'out')
          ));
          ?> สินค้าฝากขาย
        </span>
      </div>

      <div>
        <label></label>
        <a href="#" onclick="formProduct.submit()" class="btn btn-primary">
          <b class="glyphicon glyphicon-floppy-disk"></b>
          Save
        </a>
      </div>
      <?php echo $form->hiddenField($model, "product_id"); ?>
      <?php $this->endWidget(); ?>
    </div>
    <div class="pull-right">
      <?php if (!empty($model->product_pic)): ?>
        <img src="upload/<?php echo $model->product_pic; ?>" width="250px" />
      <?php endif; ?>
    </div>
    <div class="clearfix"></div>