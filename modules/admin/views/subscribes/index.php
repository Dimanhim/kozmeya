<?php echo $this->render('/components/system/_index', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    'buttons' => [
        'export' => ['href' => 'export', 'name' => 'Экспорт', 'class' => 'btn btn-default'],
    ]
]); ?>