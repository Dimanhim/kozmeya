<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brands".
 *
 * @property integer $id
 * @property string $name
 * @property string $images
 * @property integer $vis
 * @property integer $posled
 */
class Emailer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $statuses = [
        1 => 'В очереди',
        2 => 'В работе',
        3 => 'Завершена',
    ];

    public static function tableName()
    {
        return 'emailer';
    }



    public function updateRelations(){
        $fileName = $_FILES["Emailer"]['name']['files'];
        $fileTmp = $_FILES["Emailer"]['tmp_name']['files'];

        $issetFile = false;
        if($fileTmp != "") $issetFile = true;

        if ($issetFile) {
            $reader = \PHPExcel_IOFactory::createReaderForFile($fileTmp);
            $reader->setReadDataOnly(true);
            $xls = $reader->load($fileTmp);

            $xls->setActiveSheetIndex(0);
            $sheet = $xls->getActiveSheet();

            $emails = [];

            foreach($sheet->getRowIterator() as $line => $row)
            {
                $cellIterator = $row->getCellIterator();

                foreach ($cellIterator as $cell)
                {
                    $emails[] = trim($cell->getCalculatedValue());
                }
            }

            $this->emails = implode(",", $emails);
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject', 'text'], 'required'],
            [['subject', 'text', 'emails', 'files'], 'string'],
            [['status'], 'integer'],
            [['date', 'last_send', 'files'], 'safe'],
        ];
    }

    public function columnsData(){
        return [
            ['class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return ['class' => 'rowChecker', 'value' => $model->id];
                }],

            'id',
            'date:datetime',
            [
                    'attribute' => 'last_send',
                    'format' => 'raw',
                    'value'=> function($data) { return ($data->last_send != "0000-00-00 00:00:00" ? date("d.m.Y H:i:s", strtotime($data->last_send)) : "Еще не выполнялась" ); },
            ],
            'subject',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value'=> function($data) { return $this->statuses[$data->status]; },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject' => 'Тема письма',
            'emails' => 'E-mail (через запятую)',
            'files' => 'Файл с e-mail',
            'date' => 'Дата создания',
            'last_send' => 'Последняя рассылка',
            'text' => 'Текст письма',
            'status' => 'Статус',
        ];
    }
}
