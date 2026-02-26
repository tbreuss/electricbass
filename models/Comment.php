<?php

namespace app\models;

use app\helpers\Div;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property string $name
 * @property string $text
 * @property string $created
 * @property string $tableName
 * @property int $tableId
 * @property string $email
 * @property string $website
 * @property string $ipAddress
 * @property int $active
 * @property Search $search
 */
final class Comment extends ActiveRecord
{
    /** @var array|string[] */
    public static array $ALLOWED = [
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

    /** @var string */
    public $nspm;
    /** @var bool */
    public $check;
    /** @var string */
    public $verifyCode;

    public function init(): void
    {
        #$this->on(self::EVENT_AFTER_INSERT, [$this, 'onAfterInsert']);
        #$this->on(self::EVENT_AFTER_UPDATE, [$this, 'onAfterInsert']);
    }

    /**
     * @phpstan-return array<array>
     */
    public function behaviors(): array
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
     * @phpstan-return array<int, array>
     */
    public function rules(): array
    {
        return [
            // name
            ['name', 'required'],
            ['name', 'filter', 'filter' => 'strip_tags'],
            ['name', 'string', 'max' => 50, 'encoding' => 'utf-8'],
            ['name', 'detectEmailsAndLinks'],
            // email
//            ['email', 'required'],
//            ['email', 'filter', 'filter' => 'strip_tags'],
//            ['email', 'string', 'max' => 100, 'encoding' => 'utf-8'],
//            ['email', 'email'],
//            ['email', 'detectEmailsAndLinks'],
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
            ['text', 'detectEmailsAndLinks'],
            /*['verifyCode', 'captcha'],*/
            ['check', 'required', 'requiredValue' => 1, 'message' => 'Du musst dich mit den Kommentarregeln einverstanden erklären.'],
            // nsmp
            ['nspm', 'filter', 'filter' => 'strip_tags'],
            ['nspm', 'filter', 'filter' => 'trim'],
            ['ipAddress', 'safe']
        ];
    }

    public function detectEmailsAndLinks(string $attribute): void
    {
        $label = $this->getAttributeLabel($attribute);
        if (Div::detectEmails($this->$attribute)) {
            $this->addError($attribute, sprintf('%s darf keine E-Mails enthalten.', $label));
        }
        if (Div::detectLinks($this->$attribute)) {
            $this->addError($attribute, sprintf('%s darf keine Links enthalten.', $label));
        }
    }

    /**
     * @return array<string, string>
     */
    public function attributeLabels(): array
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

    /**
     * @return Comment[]
     */
    public static function findLatestComments(int $limit): array
    {
        return self::find()
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

    public function getSearch(): ActiveQuery
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
