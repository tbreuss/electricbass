<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m210513_130000_cleanup_website_urls
 */
final class m210513_130000_cleanup_website_urls extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        foreach ((new Query())->from('website')->each() as $row) {
            $url = preg_replace('/(.*)(-[0-9]+)/', '${1}', $row['url']);
            if ($url === null) {
                continue;
            }
            if ($url === $row['url']) {
                continue;
            }
            if (in_array($row['url'], ['/websites/line-6', '/websites/level-42'])) {
                continue;
            }
            $this->insert('redirect', ['from' => $row['url'], 'to' => $url]);
            $this->update('website', ['url' => $url], ['id' => $row['id']]);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m210513_130000_cleanup_website_urls cannot be reverted.\n";

        return false;
    }
}
