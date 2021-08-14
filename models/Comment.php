<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class Comment extends ActiveRecord
{
    public static $ALLOWED = [
        'advertisement',
        'album',
        'blog',
        'catalog',
        'fingering',
        'glossar',
        'lesson',
        'website',
        'video',
        'quote'
    ];

    /**
     * Einfacher Schutz vor automatisierten Registrierungen
     *
     * @var string
     */
    public $nspm;

    public $check;

    public $verifyCode;

    public function init()
    {
        #$this->on(self::EVENT_AFTER_INSERT, [$this, 'onAfterInsert']);
        #$this->on(self::EVENT_AFTER_UPDATE, [$this, 'onAfterInsert']);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created']
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            // name
            ['name', 'required'],
            ['name', 'filter', 'filter' => 'strip_tags'],
            ['name', 'string', 'max' => 50, 'encoding' => 'utf-8'],
            ['name', 'sanitizeField'],
            // email
//            ['email', 'required'],
//            ['email', 'filter', 'filter' => 'strip_tags'],
//            ['email', 'string', 'max' => 100, 'encoding' => 'utf-8'],
//            ['email', 'email'],
//            ['email', 'sanitizeField'],
            // website
//            ['website', 'filter', 'filter' => 'strip_tags'],
//            ['website', 'filter', 'filter' => 'trim'],
//            ['website', 'string', 'max' => 100, 'encoding' => 'utf-8'],
//            ['website', 'url'],
            // text
            ['text', 'required'],
            ['text', 'filter', 'filter' => 'strip_tags'],
            ['text', 'filter', 'filter' => 'trim'],
            ['text', 'string', 'min' => 10, 'max' => 1000, 'encoding' => 'utf-8'],
            ['text', 'sanitizeField'],
            /*['verifyCode', 'captcha'],*/
            ['check', 'required', 'requiredValue' => 1, 'message' => 'Du musst dich mit den Kommentarregeln einverstanden erklären.'],
            // nsmp
            ['nspm', 'filter', 'filter' => 'strip_tags'],
            ['nspm', 'filter', 'filter' => 'trim'],
            ['ipAddress', 'safe']
        ];
    }

    public function sanitizeField($attribute, $params, $validator)
    {
        if (preg_match('/ftp:|http:|https:|www\./i', $this->$attribute)) {
            $error = sprintf('Der %s darf keine Links enthalten.', $this->getAttributeLabel($attribute));
            $this->addError($attribute, $error);
        }
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Dein Nickname',
            'email' => 'E-Mail',
            'website' => 'Website',
            'text' => 'Dein Kommentar',
            'verifyCode' => ' Prüfcode',
            'nspm' => 'Zusatzinfo'
        ];
    }

    public static function findLatestComments(int $limit)
    {
        return static::find()
            ->with('search')
            ->select(['c1.tableName', 'c1.tableId', 'c1.name', 'c1.text', 'c1.created'])
            ->from(['c1' => 'comment'])
            ->leftJoin(['c2' => 'comment'], 'c1.tableName = c2.tableName AND  c1.tableId = c2.tableId AND c1.created < c2.created')
            ->where(['c2.id' => null, 'c1.deleted' => 0, 'c1.active' => 1])
            ->andWhere(['>', 'c1.tableId', 0])
            ->andWhere(['not in', 'c1.tableName', ['advertisement']])
            ->orderBy('c1.created DESC')
            ->limit($limit)
            ->all();
    }

    public function getSearch()
    {
        return $this->hasOne(Search::class, ['id' => 'tableId', 'tableName' => 'tableName']);
    }

    public static function synchronizeComments(): int
    {
        // reset entries
        foreach (self::$ALLOWED as $table) {
            Yii::$app->db->createCommand()->update($table, [
                'comments' => 0
            ])->execute();
        }

        // load data
        $sql = <<<SQL
            SELECT 
                tableName, 
                tableId, 
                COUNT(*) AS comments
            FROM comment
            WHERE deleted = 0
            AND active = 1
            GROUP BY tableName, tableId
            ORDER BY tableName
        SQL;
        $comments = Yii::$app->db->createCommand($sql)->queryAll();

        $count =  0;

        // update data
        foreach ($comments as $comment) {
            if (!in_array($comment['tableName'], self::$ALLOWED)) {
                continue;
            }
            if ($comment['tableId'] == 0) {
                continue;
            }
            $count += Yii::$app->db->createCommand()->update(
                $comment['tableName'],
                [
                    'comments' => $comment['comments'],
                ],
                [
                    'id' => $comment['tableId']
                ]
            )->execute();
        }

        return $count;
    }

}
