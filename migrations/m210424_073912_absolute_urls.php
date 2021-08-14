<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m210424_073912_absolute_urls
 */
class m210424_073912_absolute_urls extends Migration
{
    private array $tables = ['advertisement', 'album', 'blog', 'catalog', 'fingering', 'glossar', 'lesson', 'video', 'website'];

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        // add column with index
        foreach ($this->tables as $table) {
            $this->addColumn($table, 'url', $this->string(255)->append('CHARACTER SET latin1')->after('title'));
            $this->createIndex('idx_url', $table, 'url', false);
            $this->dropIndex('urlSegment', $table);
        }

        // set data for column url
        foreach((new Query)->from('advertisement')->each() as $row) {
            $this->update('advertisement', ['url' => '/kleinanzeigen/' . $row['urlSegment']], ['id' => $row['id']]);
        }

        foreach((new Query)->from('album')->each() as $row) {
            $this->update('album', ['url' => '/katalog/alben/' . $row['urlSegment']], ['id' => $row['id']]);
        }

        foreach((new Query)->from('blog')->each() as $row) {
            $this->update('blog', ['url' => '/blog/' . $row['urlSegment']], ['id' => $row['id']]);
        }

        foreach((new Query)->from('catalog')->where(['category' => 'lehrbuecher'])->each() as $row) {
            $this->update('catalog', ['url' => '/katalog/lehrbuecher/' . $row['urlSegment']], ['id' => $row['id']]);
        }

        foreach((new Query)->from('catalog')->where(['category' => 'dvds'])->each() as $row) {
            $this->update('catalog', ['url' => '/katalog/dvds/' . $row['urlSegment']], ['id' => $row['id']]);
        }

        foreach((new Query)->from('catalog')->where(['category' => 'buecher'])->each() as $row) {
            $this->update('catalog', ['url' => '/katalog/buecher/' . $row['urlSegment']], ['id' => $row['id']]);
        }

        foreach((new Query)->from('fingering')->each() as $row) {
            $this->update('fingering', ['url' => '/tools/fingersaetze/' . $row['urlSegment']], ['id' => $row['id']]);
        }

        foreach((new Query)->from('glossar')->each() as $row) {
            $this->update('glossar', ['url' => '/glossar/' . strtolower($row['category']) . '/' . $row['urlSegment']], ['id' => $row['id']]);
        }

        foreach((new Query)->from('lesson')->each() as $row) {
            $this->update('lesson', ['url' => '/lektionen/' . $row['path']], ['id' => $row['id']]);
        }

        foreach((new Query)->from('video')->each() as $row) {
            $this->update('video', ['url' => '/videos/' . $row['urlSegment']], ['id' => $row['id']]);
        }

        foreach((new Query)->from('website')->each() as $row) {
            $this->update('website', ['url' => '/websites/' . $row['urlSegment']], ['id' => $row['id']]);
        }

        // drop column
        foreach ($this->tables as $table) {
            $this->dropColumn($table, 'urlSegment');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m210424_073912_absolute_urls cannot be reverted.\n";

        return false;
    }

}
