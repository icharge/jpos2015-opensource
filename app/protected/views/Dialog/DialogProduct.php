<script type="text/javascript">
    function chooseProduct(product_code) {
        $("#product_code").val(product_code);
        try {
            showProductInfo(product_code);
        } catch (e) {
            
        }
        sale();
    }

    function searchProduct() {
        $.ajax({
            url: 'index.php?r=Dialog/GridProduct',
            data: {
                search: $("input[name=search]").val()
            },
            type: 'POST',
            success: function(data) {
                if (data != null) {
                    $("#gridProduct").html(data);
                }
            }
        });
    }

    function loadGridProduct() {
        $("#gridProduct").load('index.php?r=Dialog/GridProduct', function(data) {
            <?php if ($find_on_page_quotation): ?>
            $(".btnChooseProduct").click(function() {
              var product_code = $(this).attr("title");
              showProductInfo(product_code);
            });
            <?php endif; ?>
        });
    }

    $(function() {
        loadGridProduct();
    });
</script>

<div class="input-group" style="width: 270px">
    <input type="text" name="search" class="form-control" style="width: 200px" />
    <a href="#" style="color: white" class="btn btn-primary input-group-addon" onclick="return searchProduct()">
        <i class="glyphicon glyphicon-search"></i>
        ค้นหา
    </a>
</div>

<div id="gridProduct"></div>