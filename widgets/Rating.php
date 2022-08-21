<?php

namespace app\widgets;

use app\models\Rating as RatingModel;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Url;
use yii\db\Expression;

final class Rating extends Widget
{
    public string $tableName = '';
    public int $tableId = 0;
    public string $sessionId = '';
    public int $ratingValue = 0;

    public function run(): string
    {
        $data = $this->loadData();
        return $this->render('rating', [
            'url' => strval($this->getUrl()),
            'tableName' => strval($this->tableName),
            'tableId' => intval($this->tableId),
            'ratingCount' => intval($data['ratingCount']),
            'ratingAverage' => floatval($data['ratingAverage']),
            'yourRating' => floatval($data['yourRating'])
        ]);
    }

    protected function getUrl(): string
    {
        return Url::toRoute(['/api/rate']);
    }

    protected function isNotAllowed(): bool
    {
        if (in_array($this->tableName, RatingModel::$ALLOWED)) {
            return false;
        }
        return true;
    }

    /**
     * @return array
     * @phpstan-return array{"ratingCount": int, "ratingAverage": string, "yourRating": string}
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function saveRating(): array
    {
        Yii::$app->session->open();

        if (empty($this->tableName)) {
            throw new InvalidConfigException('Missing configuration "tableName".');
        }
        if (empty($this->tableId)) {
            #throw new InvalidConfigException('Missing configuration "tableId".');
        }
        if (!isset($this->ratingValue)) {
            throw new InvalidConfigException('Missing configuration "ratingValue".');
        }
        if (empty(Yii::$app->session->id)) {
            throw new InvalidConfigException('Missing configuration "sessionId".');
        }

        if ($this->isNotAllowed()) {
            throw new InvalidConfigException('Context is not allowed.');
        }

        $params = [
            'tableName' => $this->tableName,
            'tableId' => $this->tableId,
            'sessionId' => Yii::$app->session->id,
        ];
        $model = RatingModel::findOne($params);

        if (is_null($model)) {
            $model = new RatingModel($params);
            $model->created = new Expression('NOW()');
        }

        if ($this->ratingValue > 0) {
            $model->value = $this->ratingValue;
            $model->modified = new Expression('NOW()');
            $model->save(false);
        } else {
            $model->delete();
        }

        $data = $this->loadData();

        $sql = <<<SQL
            UPDATE {{{$this->tableName}}} 
            SET ratings = :ratings, ratingAvg = :ratingAvg
            WHERE id = :id
        SQL;

        Yii::$app->db->createCommand($sql, [
            'ratings' => $data['ratingCount'],
            'ratingAvg' => $data['ratingAverage'],
            'id' => $this->tableId
        ])->execute();

        return $data;
    }

    /**
     * @phpstan-return array{"ratingCount": int, "ratingAverage": string, "yourRating": string}
     * @throws \yii\db\Exception
     */
    private function loadData(): array
    {
        Yii::$app->session->open();

        $sql = '
            select "all" as type, count(id) as count, avg(value) as average
            from rating
            where tableName=:tableName
            and tableId=:tableId
            
            UNION ALL
            
            select "you" as type, "1" as count, value AS average
            from rating
            where tableName=:tableName
            and tableId=:tableId
            and sessionId=:sessionId
        ';

        $rows = Yii::$app->db->createCommand($sql)
            ->bindValue(':tableName', $this->tableName)
            ->bindValue(':tableId', $this->tableId)
            ->bindValue(':sessionId', Yii::$app->session->id)
            ->queryAll();

        $data = [
            'ratingCount' => (int)0,
            'ratingAverage' => '0.0',
            'yourRating' => '0.0',
        ];

        foreach ($rows as $row) {
            if ($row['type'] == 'all') {
                $data['ratingCount'] = (int)$row['count'];
                $data['ratingAverage'] = number_format(round($row['average'], 1), 1, '.', '');
            } else {
                $data['yourRating'] = number_format(round($row['average'], 1), 1, '.', '');
            }
        }

        return $data;
    }
}
