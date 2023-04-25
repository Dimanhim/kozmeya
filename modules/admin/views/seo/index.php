<?php echo $this->render('/components/system/_index', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    'buttons' => [
        'metatemplates' => ['href' => 'metatemplates/index', 'name' => 'Шаблоны', 'class' => 'btn btn-info'],
        'export' => ['href' => 'export', 'name' => 'Экспорт', 'class' => 'btn btn-default'],
    ],
    'partials' => [
        '/seo/_import' => [],
    ],
]); ?>