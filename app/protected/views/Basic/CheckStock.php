<div class="panel panel-primary" style="margin: 10px">
    <div class="panel-heading">ตรวจสอบสต้อก</div>
    <div class="panel-body">
        <?php $form = $this->beginWidget('CActiveForm'); ?>
        <div>
            <?php echo $form->labelEx($model, 'product_code'); ?>
            <?php
            echo $form->textField($model, 'product_code', array(
                'value' => $product_code,
                'class' => 'form-control',
                'style' => 'width: 200px'
            ));
            ?>
            <a href="#" class="btn btn-primary" onclick="document.forms[0].submit()">
            		<b class="glyphicon glyphicon-search"></b>
            		แสดงรายการ
            </a>
        </div>
        <hr />
        <?php $this->endWidget(); ?>

        <?php if (!empty($product)): ?>
            <form>
                <div>
                    <?php echo $form->labelEx($product, 'product_code'); ?>
                    <?php
                    echo $form->textField($product, 'product_code', array(
                        'disabled' => 'disabled',
                        'class' => 'form-control',
                        'style' => 'width: 200px'
                    ));
                    ?>

                    <?php echo $form->labelEx($product, 'product_pack_barcode'); ?>
                    <?php echo $form->textField($product, 'product_pack_barcode', array(
                        'disabled' => 'disabled',
                        'class' => 'form-control',
                        'style' => 'width: 200px'
                    )); ?>
                </div>
                <div>
                    <?php echo $form->labelEx($product, 'product_name'); ?>
                    <?php
                    echo $form->textField($product, 'product_name', array(
                        'disabled' => 'disabled',
                        'class' => 'form-control',
                        'style' => 'width: 560px'
                    ));
                    ?>
                </div>
                <div>
                    <?php echo $form->labelEx($product, 'group_product_id'); ?>
                    <?php echo $form->textField($product, 'group_product_id', array(
                        'disabled' => 'disabled',
                        'class' => 'form-control',
                        'style' => 'width: 560px',
                        'value' => @$product->group_product->group_product_name
                    )); ?>
                </div>
                <div>
                    <?php echo $form->labelEx($product, 'product_detail'); ?>
                    <?php echo $form->textField($product, 'product_detail', array(
                        'disabled' => 'disabled',
                        'class' => 'form-control',
                        'style' => 'width: 560px'
                    )); ?>
                </div>
                <div>
                    <?php echo $form->labelEx($product, 'product_created_date'); ?>
                    <?php echo $form->textField($product, 'product_created_date', array(
                        'disabled' => 'disabled',
                        'class' => 'form-control',
                        'style' => 'width: 200px'
                    )); ?>
                </div>
                <div>
                    <?php echo $form->labelEx($product, 'product_quantity'); ?>
                    <?php echo $form->textField($product, 'product_quantity', array(
                        'disabled' => 'disabled',
                        'class' => 'form-control',
                        'style' => 'background-color: #ffffcc; color: black; width: 200px'
                    )); ?>

                    <?php echo $form->labelEx($product, 'product_total_per_pack'); ?>
                    <?php echo $form->textField($product, 'product_total_per_pack', array(
                        'disabled' => 'disabled',
                        'class' => 'form-control',
                        'style' => 'width: 200px'
                    )); ?>
                </div>
                <div>
                    <?php echo $form->labelEx($product, 'product_expire'); ?>
                    <?php echo $form->textField($product, 'product_expire', array(
                        'disabled' => 'disabled',
                        'class' => 'form-control',
                        'style' => 'width: 200px',
                        'value' => $product->getProductExpire()
                    )); ?>

                    <?php echo $form->labelEx($product, 'product_return'); ?>
                    <?php echo $form->textField($product, 'product_return', array(
                        'disabled' => 'disabled',
                        'class' => 'form-control',
                        'style' => 'width: 200px',
                        'value' => $product->getProductReturn()
                    )); ?>
                </div>
                <div>
                    <?php echo $form->labelEx($product, 'product_expire_date'); ?>
                    <?php echo $form->textField($product, 'product_expire_date', array(
                        'disabled' => 'disabled',
                        'class' => 'form-control',
                        'style' => 'width: 200px'
                    )); ?>
    
                    <?php echo $form->labelEx($product, 'product_sale_condition'); ?>
                    <?php echo $form->textField($product, 'product_sale_condition', array(
                        'disabled' => 'disabled',
                        'class' => 'form-control',
                        'style' => 'width: 200px'
                    )); ?>
                </div>
                <div>
                    <?php echo $form->labelEx($product, 'product_price'); ?>
                    <?php echo $form->textField($product, 'product_price', array(
                        'disabled' => 'disabled',
                        'class' => 'form-control',
                        'style' => 'width: 200px'
                    )); ?>

                    <?php echo $form->labelEx($product, 'product_price_send'); ?>
                    <?php echo $form->textField($product, 'product_price_send', array(
                        'disabled' => 'disabled',
                        'class' => 'form-control',
                        'style' => 'width: 200px'
                    )); ?>
                    
                    <?php echo $form->labelEx($product, 'product_price_per_pack'); ?>
                    <?php echo $form->textField($product, 'product_price_per_pack', array(
                        'disabled' => 'disabled',
                        'class' => 'form-control',
                        'style' => 'width: 200px'
                    )); ?>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>