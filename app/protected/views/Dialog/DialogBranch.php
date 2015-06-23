<script type="text/javascript">
    function chooseRecord(branch_id, branch_name) {
        var data = {
            branch_id: branch_id,
            branch_name: branch_name
        };
        window.returnValue = data;
        window.close();
    }
</script>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $model,
    'columns' => array(
        array(
            'name' => 'branch_id',
            'type' => 'raw',
            'value' => 'CHtml::link("เลือกรายการ", "#", array(
                "class" => "btn btn-primary btnChooseBranch", 
                "onclick" => "chooseRecord(\'$data->branch_id\', \'$data->branch_name\')",
                "branch_id" => $data->branch_id,
                "branch_name" => $data->branch_name
            ))',
            'htmlOptions' => array(
                'align' => 'center',
                'width' => '100px'
            )
        ),
        array(
            'name' => 'branch_name',
            'htmlOptions' => array(
                'width' => '200px'
            )
        ),
        'branch_tel',
        'branch_email',
        'branch_address'
    )
));
?>