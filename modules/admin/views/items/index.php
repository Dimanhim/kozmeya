<?php echo $this->render('/components/system/_index', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    'buttons' => [
        'export' => ['href' => 'export', 'name' => 'Экспорт', 'class' => 'btn btn-default'],
        'import' => ['href' => 'import', 'name' => 'Импорт', 'class' => 'btn btn-default'],
        'prices' => ['href' => 'prices', 'name' => 'Изменение цен', 'class' => 'btn btn-default'],
    ],
    'viewGrid' => [
        1 => ['class' => 'fa fa-folder'],
        2 => ['class' => 'fa fa-bars'],
    ],
    'view' => $view,
]); ?>