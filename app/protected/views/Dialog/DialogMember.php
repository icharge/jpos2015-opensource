<script>
    function searchMember() {
        var search_member = $("#search_member").val();
        
                $.ajax({
                    url: 'index.php?r=Dialog/DialogMemberGrid',
                    data: {
                        search: search_member
                    },
                    type: 'POST',
                    success: function(data) {
                        if (data != null) {
                            $("#gridMember").html(data);
                        }
                    }
                });
    }

    $(function() {
        searchMember();
    });
</script>

<div class="">
    <form name="formSearchMember" class="form-inline">
        <input type="text" id="search_member" name="search_member" class="form-control" style="width: 200px" />
        <a href="javascript:void(0)" onclick="searchMember()" class="btn btn-info">ค้นหา</a>
    </form>
</div>

<div id="gridMember"></div>
