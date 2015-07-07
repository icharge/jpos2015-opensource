<div class="panel panel-info" style="margin: 10px">
	<div class="panel-heading">กำหนดขนาดบิล</div>
	<div class="panel-body">
		<form method="post" name="formBillConfig">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>ชื่อบิล</th>
						<th width="140px">ขนาดตัวหนังสือ (px)</th>
						<th width="120px">ความกว้าง</th>
						<th width="120px">ความสูง</th>
						<th width="80px">A4</th>
						<th width="80px">A5</th>
						<th width="80px">แนวตั้ง</th>
						<th width="80px">แนวนอน</th>
						<th width="80px">แสดงเส้น</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>สลิปการขาย</td>
						<td>
							<input type="text" name="BillConfig[slip_font_size]"
								class="form-control"
								style="width: 140px"
								value="<?php echo $billConfig->slip_font_size; ?>" />
						</td>
						<td>
							<input type="text" name="BillConfig[slip_width]" 
								class="form-control"
								style="width: 100px" 
								value="<?php echo $billConfig->slip_width; ?>" />
						</td>
						<td>
							<input type="text" name="BillConfig[slip_height]" 
								class="form-control"
								style="width: 100px" 
								value="<?php echo $billConfig->slip_height; ?>" />
						</td>
						<td>
							<?php
							$default_slip_paper_a4 = 'checked';
							$default_slip_paper_a5 = '';
							
							if ($billConfig->slip_paper == 'A5') {
								$default_slip_paper_a5 = 'checked';
								$default_slip_paper_a4 = '';
							}
							?>
							<input type="radio" name="BillConfig[slip_paper]" value="A4"
								<?php echo $default_slip_paper_a4; ?>
							/>
						</td>
						<td>
							<input type="radio" name="BillConfig[slip_paper]" value="A5"
								<?php echo $default_slip_paper_a5; ?>
							/>
						</td>
						<td>
							<?php
							$default_slip_position_h = '';
							$default_slip_position_v = 'checked';
							
							if ($billConfig->slip_position == 'horizontal') {
								$default_slip_position_h = 'checked';
								$default_slip_position_v = '';
							}
							?>
							<input type="radio" name="BillConfig[slip_position]" value="horizontal"
								<?php echo $default_slip_position_h; ?>
							/>
						</td>
						<td>
							<input type="radio" name="BillConfig[slip_position]" value="vertical"
								<?php echo $default_slip_position_v; ?> 
							/>
						</td>
						<td>
							
						</td>
					</tr>
					<tr>
						<td>ใบส่งสินค้า</td>
						<td></td>
						<td>
							<input type="text" 
								name="BillConfig[bill_send_product_width]" 
								class="form-control"
								style="width: 100px" 
								value="<?php echo $billConfig->bill_send_product_width; ?>" />
						</td>
						<td>
							<input type="text" 
								name="BillConfig[bill_send_product_height]" 
								class="form-control"
								style="width: 100px" 
								value="<?php echo $billConfig->bill_send_product_height; ?>" />
						</td>
						<td>
							<?php
							$default_bill_send_product_paper_a4 = 'checked';
							$default_bill_send_product_paper_a5 = '';
							
							if ($billConfig->bill_send_product_paper == 'A5') {
								$default_bill_send_product_paper_a4 = '';
								$default_bill_send_product_paper_a5 = 'checked';
							}
							?>
							<input type="radio" name="BillConfig[bill_send_product_paper]" value="A4"
								<?php echo $default_bill_send_product_paper_a4; ?>
							/>
						</td>
						<td>
							<input type="radio" name="BillConfig[bill_send_product_paper]" value="A5"
								<?php echo $default_bill_send_product_paper_a5; ?>
							/>
						</td>
						<td>
							<?php
							$default_bill_send_product_position_h = '';
							$default_bill_send_product_position_v = 'checked';
							
							if ($billConfig->bill_send_product_position == 'horizontal') {
								$default_bill_send_product_position_h = 'checked';
								$default_bill_send_product_position_v = '';
							}
							?>
							<input type="radio" name="BillConfig[bill_send_product_position]" value="horizontal"
								<?php echo $default_bill_send_product_position_h; ?>
							/>
						</td>
						<td>
							<input type="radio" name="BillConfig[bill_send_product_position]" 
								value="vertical"
								<?php echo $default_bill_send_product_position_v; ?>
							/>
						</td>
						<td align="center">
							<input type="checkbox" name="bill_send_show_line" value="yes"
								<?php if ($billConfig->bill_send_show_line == 'yes'): ?>
								checked
								<?php endif; ?>
							>
						</td>
					</tr>
					<tr>
						<td>ใบวางบิล</td>
						<td></td>
						<td>
							<input type="text" 
								name="BillConfig[bill_drop_width]" 
								class="form-control"
								style="width: 100px" 
								value="<?php echo $billConfig->bill_drop_width; ?>" />
						</td>
						<td>
							<input type="text" 
								name="BillConfig[bill_drop_height]" 
								class="form-control"
								style="width: 100px" 
								value="<?php echo $billConfig->bill_drop_height; ?>" />
						</td>
						<td>
							<?php
							$default_bill_drop_paper_a4 = 'checked';
							$default_bill_drop_paper_a5 = '';
							
							if ($billConfig->bill_drop_paper == 'A5') {
								$default_bill_drop_paper_a4 = '';
								$default_bill_drop_paper_a5 = 'checked';
							}
							?>
							<input type="radio" name="BillConfig[bill_drop_paper]" value="A4" 
								<?php echo $default_bill_drop_paper_a4; ?>
							/>
						</td>
						<td>
							<input type="radio" name="BillConfig[bill_drop_paper]" value="A5" 
								<?php echo $default_bill_drop_paper_a5; ?>
							/>
						</td>
						<td>
							<?php
							$default_bill_drop_position_h = '';
							$default_bill_drop_position_v = 'checked';
							
							if ($billConfig->bill_drop_position == 'horizontal') {
								$default_bill_drop_position_h = 'checked';
								$default_bill_drop_position_v = '';
							}
							?>
							<input type="radio" name="BillConfig[bill_drop_position]" value="horizontal" 
								<?php echo $default_bill_drop_position_h; ?>
							/>
						</td>
						<td>
							<input type="radio" name="BillConfig[bill_drop_position]" 
								value="vertical"
								<?php echo $default_bill_drop_position_v; ?>
							/>
						</td>
						<td align="center">
							<input type="checkbox" name="bill_drop_show_line" value="yes"
							<?php if ($billConfig->bill_drop_show_line == 'yes'): ?>
								checked
							<?php endif; ?>
							>
						</td>
					</tr>
					<tr>
						<td>ใบกำกับภาษี</td>
						<td></td>
						<td>
							<input type="text" 
								name="BillConfig[bill_add_tax_width]" 
								class="form-control"
								style="width: 100px" 
								value="<?php echo $billConfig->bill_add_tax_width; ?>" />
						</td>
						<td>
							<input type="text" 
								name="BillConfig[bill_add_tax_height]" 
								class="form-control"
								style="width: 100px" 
								value="<?php echo $billConfig->bill_add_tax_height; ?>" />
						</td>
						<td>
							<?php
							$default_bill_add_tax_paper_a4 = 'checked';
							$default_bill_add_tax_paper_a5 = '';
							
							if ($billConfig->bill_add_tax_paper == 'A5') {
								$default_bill_add_tax_paper_a4 = '';
								$default_bill_add_tax_paper_a5 = 'checked';
							}
							?>
							<input type="radio" name="BillConfig[bill_add_tax_paper]" value="A4"
								<?php echo $default_bill_add_tax_paper_a4; ?>
							/>
						</td>
						<td>
							<input type="radio" name="BillConfig[bill_add_tax_paper]" value="A5"
								<?php echo $default_bill_add_tax_paper_a5; ?>
							/>
						</td>
						<td>
							<?php
							$default_bill_add_tax_position_h = '';
							$default_bill_add_tax_position_v = 'checked';
							
							if ($billConfig->bill_add_tax_position == 'horizontal') {
								$default_bill_add_tax_position_h = 'checked';
								$default_bill_add_tax_position_v = '';
							}
							?>
							<input type="radio" name="BillConfig[bill_add_tax_position]" 
								value="horizontal"
								<?php echo $default_bill_add_tax_position_h; ?>
							/>
						</td>
						<td>
							<input type="radio" name="BillConfig[bill_add_tax_position]" 
								value="vertical"
								<?php echo $default_bill_add_tax_position_v; ?>
							/>
						</td>
						<td align="center">
							<input type="checkbox" name="bill_add_show_line" value="yes"
							<?php if ($billConfig->bill_add_show_line == 'yes'): ?>
								checked
							<?php endif; ?>
							>
						</td>
					</tr>
					<tr>
						<td>ใบเสร็จรับเงิน</td>
						<td></td>
						<td>
							<input type="text" 
								name="BillConfig[sale_width]" 
								class="form-control"
								style="width: 100px" 
								value="<?php echo $billConfig->sale_width; ?>" />
						</td>
						<td>
							<input type="text" 
								name="BillConfig[sale_height]" 
								class="form-control"
								style="width: 100px" 
								value="<?php echo $billConfig->sale_height; ?>" />
						</td>
						<td>
							<?php
							$default_sale_paper_a4 = 'checked';
							$default_sale_paper_a5 = '';
							
							if ($billConfig->sale_paper == 'A5') {
								$default_sale_paper_a4 = '';
								$default_sale_paper_a5 = 'checked';
							}
							?>
							<input type="radio" name="BillConfig[sale_paper]" value="A4"
								<?php echo $default_sale_paper_a4; ?>
							/>
						</td>
						<td>
							<input type="radio" name="BillConfig[sale_paper]" value="A5"
								<?php echo $default_sale_paper_a5; ?>
							/>
						</td>
						<td>
							<?php
							$default_sale_position_h = '';
							$default_sale_position_v = 'checked';
							
							if ($billConfig->sale_position == 'horizontal') {
								$default_sale_position_h = 'checked';
								$default_sale_position_v = '';
							}
							?>
							<input type="radio" name="BillConfig[sale_position]" 
								value="horizontal"
								<?php echo $default_sale_position_h; ?>
							/>
						</td>
						<td>
							<input type="radio" name="BillConfig[sale_position]" 
								value="vertical"
								<?php echo $default_sale_position_v; ?>
							/>
						</td>
						<td align="center">
							<input type="checkbox" name="sale_condition_show_line" value="yes"
							<?php if ($billConfig->sale_condition_show_line == 'yes'): ?>
								checked
							<?php endif; ?>
							>
						</td>
					</tr>
				</tbody>
			</table>
			<a href="#" class="btn btn-info" onclick="formBillConfig.submit()">
				<b class="glyphicon glyphicon-floppy-disk"></b>
				Save
			</a>
		</form>
	</div>
</div>


