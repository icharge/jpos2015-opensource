<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model,
        'summaryText' => '',
        'columns' => array(
            array(
                'name' => 'member_code',
                'type' => 'raw',
                'value' => 'CHtml::link($data->member_code, "#", array(
                         "data-dismiss" => "modal",
                         "class" => "cmdChooseMember",
                         "member_code" => $data->member_code,
                         "member_name" => $data->member_name,
                         "onClick" => "chooseMember($data->member_code, \'$data->member_name\')"
        ))',
                'htmlOptions' => array(
                    'align' => 'center',
                    'width' => '100px'
                )
            ),
            'member_name',
            'member_tel',
            'member_address'
        )
    ));
    ?>