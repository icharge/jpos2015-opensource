<script type="text/javascript">   
    $(function() {
        $("#endSaleInputMoney").focus();
    });

    function doEndSale() {
        var total = $("#totalMoney").val();
        var input = $("#endSaleInputMoney").val();
        var returnMoney = $("#returnMoney").val();
        var radioSlip = $("#radioSlip:checked").val();
        var radioBill = $("#radioBill:checked").val();
        
        // convert to number
        total = parseFloat(total.replace(",", "").replace(" ", ""));
        input = parseFloat(input.replace(",", "").replace(" ", ""));
        
        if (input < total) {
            alert("โปรดกรอกจำนวนเงินให้ถูกต้อง");
            return false;
        }

        $("#hidden_total").val(total);
        $("#hidden_input").val(input);
        $("#hidden_return_money").val(returnMoney);
        $("#hidden_print_bill").val('');

        if (radioSlip != undefined) {
            $("#hidden_print_bill").val('slip');
        } else if (radioBill != undefined) {
            $("#hidden_print_bill").val('bill');
        }

        $("#btnCloseModal").trigger("click");

        endSale();
    }
    
    function processReturnMoney(e) {
        var total = $("#totalMoney").val();
        var input = $("#endSaleInputMoney").val();
        var bonus = Number($('#bonusPrice').val());
        
        total = total.replace(",", "");
        var returnMoney = parseFloat(input) - parseFloat(total);
        returnMoney = (returnMoney + bonus);
        returnMoney = numeral(returnMoney).format('0,0.00');

        $("#returnMoney").val(returnMoney);
        $('input[name=bonus_price]').val($('input[name=end_bonus_price]').val());
        
        if (e.keyCode == 13) {
	        doEndSale();
        } 
    }

    function payEqual() {
        $("#endSaleInputMoney").val($("#totalMoney").val());
        $("#returnMoney").val(0);
    }
</script>

<style>
	.return-money {
		font-size: 30px; 
		text-align: right; 
		display: inline-block; 
		padding-top: 10px;
		padding-bottom: 10px;
		height: 50px;
		width: 300px;
	}
	
	.total-money {
		font-size: 20px; 
		text-align: right; 
		padding-top: 10px;
		padding-bottom: 10px;
		height: 50px;
		width: 300px;
		display: inline-block;
		font-weight: bold;
		color: black;
	}
	
	.input-money {
		font-size: 30px; 
		text-align: right; 
		padding-top: 10px;
		padding-bottom: 10px;
		height: 50px;
		width: 300px;
		display: inline-block;
	}
	
	.end-sale {
		width: 710px; 
		font-size: 35px; 
		padding: 20px;
	}
	
	.lbl-total {
		font-size: 40px; 
		width: 200px
	}
	
	.lbl-input {
		font-size: 40px; 
		width: 200px
	}
	
	.lbl-return {
		font-size: 40px; 
		width: 200px
	}
	
	form div {
		margin-top: 1px;
		margin-bottom: 1px;
	}
	form div label {
		text-align: right;
		padding-right: 5px;
		width: 200px;
	}
</style>

<div class="panel panel-primary">
    <div class="panel-body alert-info">
        <form class="form-inline">
            <div>
                <label class="lbl-total">จำนวนเงิน</label>
                <input 
	                type="text" 
	                id="totalMoney" 
	                readonly="readonly" 
	                class="form-control total-money" />
            </div>
            <div>
                <label class="lbl-input">รับเงิน</label>
                <input 
                	type="text" 
                	id="endSaleInputMoney" 
                	onkeyup="processReturnMoney(event)" 
                	class="input-money form-control" />
                <a href="#" class="btn btn-primary btn-lg" style="font-size: 20px" onclick="payEqual()">
                    จ่ายพอดี
                </a>
            </div>
            <div>
                <label class="lbl-input">ส่วนลด</label>
                <input type="text" name="end_bonus_price" id="bonusPrice" onkeyup="processReturnMoney(event)" class="input-money form-control" />
            </div>
            <div>
                <label class="lbl-return">เงินทอน</label>
                <input 
                	type="text" 
                	id="returnMoney" 
                	disabled="disabled" 
                	class="return-money disabled form-control" />
            </div>
            <br />

            <div>
                <label>พิมพ์</label>
                <input type="radio" id="radioSlip" name="printBill" value="slip" /> สลิป
                <input type="radio" id="radioBill" name="printBill" value="bill" /> ใบเสร็จ
            </div>

            <div>
				<label></label>
                <a href="javascript:void(0)" id="cmdEndSaleEnter" onclick="doEndSale()" class="btn btn-success" style="font-size: 30px">
                    จบการขาย
                    (ENTER)
                </a>
            </div>
        </form>
    </div>
</div>

