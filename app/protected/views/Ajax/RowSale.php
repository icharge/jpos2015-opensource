<?php

$sum_price = 0;
$sum_qty = 0;
$sum_qty_per_pack = 0;
$row = 1;
?>
<?php foreach ($saleTemps as $saleTemp): ?>
  <?php 
    $sum_row = ($saleTemp->price * $saleTemp->qty);
    $sum_price += $sum_row;
    $sum_qty += $saleTemp->qty;
    $sum_qty_per_pack += $saleTemp->qty_per_pack;
  ?>
  <tr style="background: #cccccc">
    <td style="text-align: right; padding: 2px"><?php echo $n++; ?></td>
    <td style="padding: 2px">
      <input 
        type="hidden" 
        name="hidden_product_codes[]"
        value="<?php echo $saleTemp->barcode; ?>" />
      <?php echo $saleTemp->barcode; ?>
      <input 
        type="hidden"
        name="hidden_product_name[]"
        value="<?php echo $saleTemp->name; ?>"
        />
    </td>
    <td style="padding: 2px">
      <input
        type="text"
        style="border: #808080 1px solid; width: 100%; padding-left: 5px"
        value="<?php echo $saleTemp->serial; ?>"
        id="txtSerialNo_<?php echo $row; ?>"
        name="serials[]"
      />
    </td>
    <td style="padding: 2px"><?php echo $saleTemp->name; ?></td>
    <td style="padding: 2px" align="right">
      <input
        type="text"
        class="price"
        style="border: #808080 1px solid; text-align: right; width: 70px; padding-right: 5px"
        value="<?php echo number_format($saleTemp->price, 2); ?>"
        id="txtPrice_<?php echo $row; ?>"
        name="prices[]"
        onkeyup="computePrice(
          'txtPrice_<?php echo $row; ?>',
          'txtQty_<?php echo $row; ?>',
          'lblTotalPricePerRow_<?php echo $row; ?>',
          <?php echo $saleTemp->pk_temp ?>
        )"
      />
    </td>
    <td style="padding: 2px" align="right">
      <input
        type="text"
        class="qty"
        style="border: #808080 1px solid; text-align: right; width: 50px; padding-right: 5px"
        value="<?php echo $saleTemp->qty; ?>"
        id="txtQty_<?php echo $row; ?>"
      name="qtys[]"
      onkeyup="computePrice(
        'txtPrice_<?php echo $row; ?>',
        'txtQty_<?php echo $row; ?>',
        'lblTotalPricePerRow_<?php echo $row; ?>',
        <?php echo $saleTemp->pk_temp ?>
        )"
      />
    </td>
    <td style="padding: 2px" align="right">
      <?php echo number_format($saleTemp->qty_per_pack); ?>
      <input type="hidden" name="hidden_qty_per_pack[]" value="<?php echo $saleTemp->qty_per_pack; ?>" />
    </td>
    <td style="padding: 2px" align="right">
      <span id="lblTotalPricePerRow_<?php echo $row; ?>" class="pricePerRow">
        <?php echo number_format($sum_row, 2); ?>
      </span>
    </td>
    <td style="padding: 2px">
      <a href="javascript:void(0)" onclick="removeRowSale(<?php echo $saleTemp->pk_temp; ?>)"
        class="btn btn-danger btn-xs">
        <b class="glyphicon glyphicon-remove"></b>
      </a>
    </td>
  </tr>
  <?php $row++; ?>
<?php endforeach; ?>

<tr style="background-color: #808080; color: white;">
  <td><strong>รวม</strong></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td style="padding: 2px">
    <div style="text-align: right" id="lblSumQty">
      <?php echo number_format($sum_qty); ?>
    </div>
  </td>
  <td style="padding: 2px">
    <div style="text-align: right">
      <?php echo number_format($sum_qty_per_pack); ?>
    </div>
  </td>
  <td style="padding: 2px">
    <div style="text-align: right" id="lblSumPrice">
      <?php echo number_format($sum_price, 2); ?>
    </div>
  </td>
  <td></td>
</tr>