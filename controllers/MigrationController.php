<?php

namespace app\controllers;

use app\models\ArticleOld;
use app\models\Album;
use app\models\Blog;
use app\models\Catalog;
use app\models\Glossar;
use app\models\Website;
use app\models\Lesson;
use app\models\Content;
use app\models\Fingering;
use app\widgets\Parser;
use Yii;
use yii\web\Controller;
use yii\helpers\Html;

class MigrationController extends Controller
{
    const TEST = 0;
    protected $keys;

    protected function correctId($id)
    {
        return $id;
        /*
        if ($id <= 2327) {
            $id += 10000;
        }
        return $id;
        */
    }

    public function actionMedia()
    {

        $LIVE = false;

        // Remove empty dirs
        $dir = Yii::getAlias('@webroot/media/a/');
        $files = scandir($dir);
        foreach ($files as $file) {
            if (is_dir($dir . '/' . $file)) {
                #echo "$dir$file<br>";
                $files2 = scandir($dir . '/' . $file);
                if (count($files2) == 2) {
                    rmdir($dir . '/' . $file);
                    #echo "removed<br>";
                }
            }
        }

        // Remove .DS_Store files

        $notMoved = [];

        // Glossar
        foreach (Glossar::find()->all() as $model) {
            $dir = Yii::getAlias(sprintf('@webroot/media/a/%s', $model->id));
            if (!is_dir($dir)) continue;
            $files = array_values(array_diff(scandir($dir), ['.', '..', '.DS_Store']));
            $count = count($files);
            if ($count == 1) {
                $oldname = $dir . '/' . $files[$count-1];
                $newname = Yii::getAlias(sprintf('@webroot/media/glossar/%d-%s', $this->correctId($model->id), $model->fotos));
                $newname = str_replace('-' . $model->id . '.', '.', $newname);
                echo $newname . '<br>';
                if ($LIVE) {
                    rename($oldname, $newname);
                }
            } else {
                $notMoved[] = $dir;
            }
        }

        /*
        // Album
        foreach (Album::find()->all() as $model) {
            $dir = Yii::getAlias(sprintf('@webroot/media/a/%s', $model->id));
            if (!is_dir($dir)) continue;
            $files = array_values(array_diff(scandir($dir), ['.', '..', '.DS_Store']));
            $count = count($files);
            if ($count == 1) {
                $oldname = $dir . '/' . $files[$count-1];
                $newname = Yii::getAlias(sprintf('@webroot/media/album/%d-%s', $this->correctId($model->id), $model->fotos));
                $newname = str_replace('-' . $model->id . '.', '.', $newname);
                echo $newname . '<br>';
                if ($LIVE) {
                    rename($oldname, $newname);
                }
            } else {
                $notMoved[] = $dir;
            }
        }

        // Katalog
        foreach (Catalog::find()->all() as $model) {
            $dir = Yii::getAlias(sprintf('@webroot/media/a/%s', $model->id));
            if (!is_dir($dir)) continue;
            $files = array_values(array_diff(scandir($dir), ['.', '..', '.DS_Store']));
            $count = count($files);
            if ($count == 1) {
                $oldname = $dir . '/' . $files[$count-1];
                $newname = Yii::getAlias(sprintf('@webroot/media/katalog/%d-%s', $this->correctId($model->id), $model->fotos));
                $newname = str_replace('-' . $model->id . '.', '.', $newname);
                echo $newname . '<br>';
                if ($LIVE) {
                    rename($oldname, $newname);
                }
            } else {
                $notMoved[] = $dir;
            }
        }

        // Website
        foreach (Website::find()->all() as $model) {
            $dir = Yii::getAlias(sprintf('@webroot/media/a/%s', $model->id));
            if (!is_dir($dir)) continue;
            $files = array_values(array_diff(scandir($dir), ['.', '..', '.DS_Store']));
            $count = count($files);
            if ($count == 1) {
                $oldname = $dir . '/' . $files[$count-1];
                $initial = substr($model->fotos, 0, 1);
                $newname = Yii::getAlias(sprintf('@webroot/media/website/%s/%s', $initial, $model->fotos));
                $newname = str_replace('-' . $model->id . '.', '.', $newname);
                echo $newname . '<br>';
                if ($LIVE) {
                    if (!is_dir(dirname($newname))) {
                        mkdir(dirname($newname), 0755, true);
                    }
                    rename($oldname, $newname);
                }
            } else {
                $notMoved[] = $dir;
            }
        }

        // Blog
        foreach (Blog::find()->all() as $model) {
            $dir = Yii::getAlias(sprintf('@webroot/media/a/%s', $model->id));
            if (!is_dir($dir)) continue;
            $files = array_values(array_diff(scandir($dir), ['.', '..', '.DS_Store']));
            foreach ($files as $file) {
                $oldname = $dir . '/' . $file;
                $newname = Yii::getAlias(sprintf('@webroot/media/blog/%d/%s', $this->correctId($model->id), $file));
                $newname = str_replace('-' . $model->id . '.', '.', $newname);
                echo $newname . '<br>';
                if ($LIVE) {
                    if (!is_dir(dirname($newname))) {
                        mkdir(dirname($newname), 0755, true);
                    }
                    rename($oldname, $newname);
                }
            }

            #$count = count($files);
            #if ($count == 1) {
            #    $oldname = $dir . '/' . $files[$count-1];
            #    $newname = Yii::getAlias(sprintf('@webroot/media/blog/%s', $model->fotos));
            #    $newname = str_replace('-' . $model->id . '.', '.', $newname);
            #    echo $newname . '<br>';
            #    if ($LIVE) {
            #        rename($oldname, $newname);
            #    }
            #} else {
            #    $notMoved[] = $dir;
            #}
        }

        // Lesson
        foreach (Lesson::find()->all() as $model) {
            $dir = Yii::getAlias(sprintf('@webroot/media/a/%s', $model->id));
            if (!is_dir($dir)) continue;
            $files = array_values(array_diff(scandir($dir), ['.', '..', '.DS_Store']));
            foreach ($files as $file) {
                $oldname = $dir . '/' . $file;
                $newname = Yii::getAlias(sprintf('@webroot/media/lektion/%d/%s', $this->correctId($model->id), $file));
                $newname = str_replace('-' . $model->id . '.', '.', $newname);
                echo $newname . '<br>';
                if ($LIVE) {
                    if (!is_dir(dirname($newname))) {
                        mkdir(dirname($newname), 0755, true);
                    }
                    rename($oldname, $newname);
                }
            }
        }
        */

        if (count($notMoved) > 0) {
            echo "Nicht verschobene Verzeichnisse:<br>";
            foreach ($notMoved as $dir) {
                echo "{$dir}<br>";
            }
        }

        die ('Ende');

    }

    public function actionAlbum()
    {
        $models = Album::find()->all();
        $labels = [];
        foreach ($models as $model) {
            $fields = [];
            $info = explode("\n", $model->info);
            foreach ($info as $line) {
                $pos = strpos($line, ':');
                if ($pos !== false) {
                    $label = trim(substr($line, 0, $pos));
                    $value = trim(substr($line, $pos + 1));
                    if (isset($labels[$label])) {
                        $labels[$label]++;
                    } else {
                        $labels[$label] = 1;
                    }

                    $key = '';
                    switch ($label) {
                        case 'Erscheinungsdatum':
                        case 'Veröffentlicht':
                        case 'Erscheinungstermin':
                        case 'Ursprüngliches Erscheinungsdatum':
                        case 'Original Release Date':
                            #$key = 'publication';
                            break;
                        case 'Label':
                            $key = 'label';
                            break;
                        case 'Copyright':
                            $key = 'copyright';
                            $value = trim(str_replace(['(C)', '(c)', '(P)', '(p)'], '', $value));
                            break;
                        case 'Gesamtlänge':
                        case 'Dauer':
                            $key = 'totalLength';
                            break;
                        case 'Genres':
                        case 'Genre':
                            $key = 'genres';
                            break;
                        case 'Anzahl der Disks':
                        case 'Anzahl Disks/Tonträger':
                            $key = 'quantity';
                            break;
                        case 'ASIN':
                        case 'ASIN-Code':
                            if (empty($model->asin)) {
                                $key = 'asin';
                            }
                            break;
                    }

                    if ($key == 'publication') {
                        $date = date('Y-m-d', strtotime($value));
                        echo "<p>$date</p>";
                    }

                    if (!empty($key)) {
                        $fields[$key] = $value;
                    }

                }
            }
            if (!empty($fields)) {
                foreach ($fields as $key => $value) {
                    $model->$key = $value;
                }
                $model->save(false, array_keys($fields));
                #echo"<pre>";print_r($fields);echo"</pre>";
            }
        }

        echo"<pre>";print_r($labels);echo"</pre>";
    /*
    [Erscheinungsdatum] => 563
    [Label] => 587
    [Copyright] => 580
    [Gesamtlänge] => 514
    [Genres] => 578
    [Anzahl Disks/Tonträger] => 3
    [Genre] => 2
    [Anzahl der Disks] => 5
    [Format] => 2
    [Original Release Date] => 1
    [ASIN] => 12
    [Dauer] => 2
    [Ursprüngliches Erscheinungsdatum] => 15
    [Performers] => 1
    [Komponisten] => 1
    [Leitung] => 1
    [Erscheinungstermin] => 2
    [Veröffentlicht] => 2
    [ASIN-Code] => 1
    */
    }

    public function actionBlogvideos()
    {
        $models = Blog::find()->where('categoryId=7')->all();
        foreach ($models as $model) {
            Parser::widget(['model' => $model, 'attribute' => 'text']);
        }
    }

    public function actionBook()
    {
        $articles = ArticleOld::find()->where('categoryId=16')->all();

        // Codeschnippsel (json -> columns)
        $keys = [];
        foreach ($articles as $i => $model) {
            if (empty($model->content)) {
                continue;
            }
            $encoded = json_decode($model->content, true);
            $encoded['type'] = 3;
            Yii::$app->db->createCommand()->update('book', $encoded, 'id=:id AND type=2', [':id' => $model->id])->execute();
        }

        #echo count(Blog::find()->all());
        echo"<pre>";print_r($keys);echo"</pre>";exit;

    }

    public function actionFingering()
    {
        $fields = [];
        $lengths = [];
        $models = Fingering::find()->all();
        foreach ($models as $i => $model) {
            if (empty($model->content)) {
                continue;
            }
            $encoded = json_decode($model->content, true);
            $fields = array_merge($fields, $encoded);

            // copy json fields to table columns
            foreach ($encoded as $key => $value) {
                $model->$key = $value;
            }

            // tags
            $tags = exlode(',', $model->tags);
            if (($found = array_search('Bass-Griff', $tags)) !== false) {
                unset($tags[$found]);
            };
            $model->tags = implode(',', $tags);
            $encoded['tags'] = '';

            // save
            $model->save(false, array_keys($encoded));

            // determine string lengths
            foreach ($encoded as $key => $value) {
                if ($i == 0) {
                    $lengths[$key] = 0;
                }
                $strlen = strlen($value);
                if ($strlen > $lengths[$key]) {
                    $lengths[$key] = $strlen;
                }
            }
        }

        echo"<pre>";print_r($fields);echo"</pre>";
        echo"<pre>";print_r($lengths);echo"</pre>";

    }

    /**
     * @return string
     */
    public function actionContent()
    {
        $tables = ['album', 'blog', 'book', 'glossar', 'link'];

        foreach ($tables as $table) {
            $sql = sprintf('UPDATE {{%s}} SET transformed = ""', $table);
            Yii::$app->db->createCommand($sql)
                ->bindValue(':table', $table)
                ->execute();
        }

        $contents = Content::find()->where('contentElementId>0')->orderBy("articleId ASC, priority ASC")->all();

        foreach ($contents as $content) {
            $methodName = 'transformContent_' . $content->contentElementId;
            $transformed = $this->$methodName($content);

            foreach ($tables as $table) {

                $sql = sprintf('UPDATE {{%s}} SET transformed = CONCAT(transformed, :transformed) WHERE id=:id', $table);
                Yii::$app->db->createCommand($sql)
                    ->bindValue(':transformed', $transformed)
                    ->bindValue(':id', $content->articleId)
                    ->execute();
            }


        }

        echo"<pre>";
        print_r($this->keys);
        echo"</pre>";
    }

    /**
     * @return string
     */
    public function actionContentold()
    {
        $tables = ['article'];

        foreach ($tables as $table) {
            $sql = sprintf('UPDATE {{%s}} SET transformed = ""', $table);
            Yii::$app->db->createCommand($sql)
                ->bindValue(':table', $table)
                ->execute();
        }

        $contents = Content::find()->where('contentElementId>0')->orderBy("articleId ASC, priority ASC")->all();

        foreach ($contents as $content) {
            $methodName = 'transformContent_' . $content->contentElementId;
            $transformed = $this->$methodName($content);

            foreach ($tables as $table) {

                $sql = sprintf('UPDATE {{%s}} SET transformed = CONCAT(transformed, :transformed) WHERE id=:id', $table);
                Yii::$app->db->createCommand($sql)
                    ->bindValue(':transformed', $transformed)
                    ->bindValue(':id', $content->articleId)
                    ->execute();
            }


        }

        echo"<pre>";
        print_r($this->keys);
        echo"</pre>";
    }

    public function actionLesson()
    {
        // Videos
        $models = Lesson::find()->where('renderer="video"')->all();
        foreach ($models as $model) {
            #if (!empty($model->text)) continue;
            $encoded = json_decode($model->content, true);
            $shortcut = ($encoded['service'] == 'YT') ? 'youtube' : 'vimeo';
            unset($encoded['service']);
            if (empty($encoded)) continue;
            $model->text = '[' . $shortcut;
            foreach ($encoded as $key => $value) {
                $model->text .= sprintf(' %s="%s"', trim($key), trim($value));
            }
            $model->text .= ']';
            $model->text .= "\n\n";
            $model->text .= $model->abstract;
            $model->save(false, ['text']);
            echo "Saved " . $model->id . "<br>";
            echo $model->text . "<hr>";
        }

        // Riff
        $models = Lesson::find()->where('renderer="riff"')->all();
        foreach ($models as $model) {
            #if (!empty($model->text)) continue;
            $encoded = json_decode($model->content, true);
            if (empty($encoded['alphatab'])) continue;
            $model->text = '';
            if (!empty($model->abstract)) {
                $model->text .= $model->abstract;
                $model->text .= "\n\n";
            }
            $model->text .= '[alphatab]';
            $model->text .= "\n";
            $model->text .= trim($encoded['alphatab']);
            $model->text .= "\n";
            $model->text .= '[/alphatab]';
            $model->text .= "\n\n";
            $model->save(false, ['text']);
            echo "Saved " . $model->id . "<br>";
        }

        // Default
        $models = Lesson::find()->where('renderer="default"')->all();
        foreach ($models as $model) {
            #if (!empty($model->text)) continue;
            $encoded = json_decode($model->content, true);
            if (empty($encoded['text'])) continue;
            $model->text = trim($encoded['text']);
            $model->save(false, ['text']);
            echo "Saved " . $model->id . "<br>";
        }

        // None
        $models = Lesson::find()->where('renderer="none"')->all();
        foreach ($models as $model) {
            #if (!empty($model->text)) continue;
            $model->text = "";
            $model->save(false, ['text']);
            echo "Saved " . $model->id . "<br>";
        }

    }

    public function actionBlog()
    {
        // Videos
        $models = Blog::find()->where('renderer="video"')->all();
        foreach ($models as $model) {
            #if (!empty($model->text)) continue;
            $encoded = json_decode($model->content, true);
            $shortcut = ($encoded['service'] == 'YT') ? 'youtube' : 'vimeo';
            unset($encoded['service']);
            if (empty($encoded)) continue;
            $model->text = '[' . $shortcut;
            foreach ($encoded as $key => $value) {
                $model->text .= sprintf(' %s="%s"', trim($key), trim($value));
            }
            $model->text .= ']';
            $model->text .= "\n\n";
            $model->text .= $model->abstract;
            $model->save(false, ['text']);
            echo "Saved " . $model->id . "<br>";
            echo $model->text . "<hr>";
        }

        // Default
        $models = Blog2::find()->where('renderer="default"')->all();
        foreach ($models as $model) {
            if (!empty($model->text)) continue;
            $encoded = json_decode($model->content, true);
            if (empty($encoded)) continue;
            $model->text = '';
            foreach ($encoded as $key => $value) {
                $model->text .= trim($value);
                $model->text .= "\n\n";
            }
            $model->save(false, ['text']);
            echo "Saved " . $model->id . "<br>";
        }

    }

    protected function transformContent_1($content)
    {
        $transformed = '';
        if (!empty($content->heading)) {
            $transformed .= "## " . trim($content->heading) . "\n\n";
        }
        if (!empty($content->content)) {
            $transformed .= trim($content->content) . "\n\n";
        }

        echo nl2br($transformed);

        if (static::TEST) {
            $content->transformed = $transformed;
            $content->save(false, ['transformed']);
        }

        return $transformed;
    }

    protected function transformContent_2($content)
    {
        $fields = array_merge(
            ["title" => $content->heading],
            json_decode($content->content, true)
        );
        $this->addKeyLengths($fields, 2);

        $transformed = '[img';
        foreach ($fields as $key => $value) {
            if (in_array($key, ['copyrightLabel', 'copyrightUrl'])) continue;
            $transformed .= sprintf(' %s="%s"', $key, $value);
        }
        $transformed .= ']';
        $transformed .= "\n\n";

        echo nl2br($transformed);

        if (static::TEST) {
            $content->transformed = $transformed;
            $content->save(false, ['transformed']);
        }

        return $transformed;


        /*
        $fields = json_decode($content->content, true);
        $lengths = [];
        foreach ($fields as $key => $value) {
            if (!isset($lengths[$key]) || strlen($value) > strlen($lengths[$key])) {
                $lengths[$key] = strlen($value);
            }
        }
        echo"<pre>";
        print_r($lengths);
        echo"</pre>";
        */
    }

    /**
     * Links
     * @param $content
     */
    protected function transformContent_3($content)
    {
        $fields = array_merge(
            ["title" => $content->heading],
            json_decode($content->content, true)
        );
        $this->addKeyLengths($fields, 3);

        $transformed = '[jsonlinks';
        foreach ($fields as $key => $value) {
            if (in_array($key, ['title'])) {
                $transformed .= sprintf(' %s="%s"', $key, $value);
            }
        }
        $transformed .= ']';
        $transformed .= $content->content;
        $transformed .= "[/jsonlinks]";
        $transformed .= "\n\n";

        echo nl2br($transformed);

        if (static::TEST) {
            $content->transformed = $transformed;
            $content->save(false, ['transformed']);
        }

        return $transformed;
    }

    /**
     * YouTube
     * @param $content
     */
    protected function transformContent_4($content)
    {
        $fields = array_merge(
            ["title" => $content->heading],
            json_decode($content->content, true)
        );
        $fields = array_map('trim', $fields);
        $this->addKeyLengths($fields, 4);

        $transformed = '[youtube';
        foreach ($fields as $key => $value) {
            $transformed .= sprintf(' %s="%s"', $key, $value);
        }
        $transformed .= ']';
        $transformed .= "\n\n";

        $transformed = str_replace(' text="', ' caption="', $transformed);
        echo nl2br($transformed);

        if (static::TEST) {
            $content->transformed = $transformed;
            $content->save(false, ['transformed']);
        }

        return $transformed;
    }

    /**
     * Gallery
     * @param $content
     */
    protected function transformContent_5($content)
    {
        $transformed = "[jsongallery";
        if (!empty($content->heading)) {
            $transformed .= sprintf(' title="%s"', $content->heading);
        }
        $transformed .= ']'. trim($content->content) . '[/jsongallery]';
        $transformed .= "\n\n";

        echo nl2br($transformed);

        if (static::TEST) {
            $content->transformed = $transformed;
            $content->save(false, ['transformed']);
        }

        return $transformed;
    }

    /**
     * ImageWithText
     * @param $content
     */
    protected function transformContent_7($content)
    {
        $fields = array_merge(
            ["title" => $content->heading],
            json_decode($content->content, true)
        );
        $fields = array_map('trim', $fields);
        $this->addKeyLengths($fields, 7);

        $transformed = '[imgtext';
        foreach ($fields as $key => $value) {
            if (in_array($key, ['text', 'copyrightLabel', 'copyrightUrl'])) continue;
            $transformed .= sprintf(' %s="%s"', $key, $value);
        }
        $transformed .= ']';
        $transformed .= isset($fields['text']) ? $fields['text'] : '';
        $transformed .= '[/imgtext]';
        $transformed .= "\n\n";

        echo nl2br($transformed);

        if (static::TEST) {
            $content->transformed = $transformed;
            $content->save(false, ['transformed']);
        }

        return $transformed;
    }

    /**
     * ImageWithText
     * @param $content
     */
    protected function transformContent_9($content)
    {
        $fields = array_merge(
            ["title" => $content->heading],
            json_decode($content->content, true)
        );
        $fields = array_map('trim', $fields);
        $this->addKeyLengths($fields, 9);

        $transformed = '[amazon';
        foreach ($fields as $key => $value) {
            $transformed .= sprintf(' %s="%s"', $key, $value);
        }
        $transformed .= ']';
        $transformed .= "\n\n";

        echo nl2br($transformed);

        if (static::TEST) {
            $content->transformed = $transformed;
            $content->save(false, ['transformed']);
        }

        return $transformed;
    }

    /**
     * AlphaTab
     * @param $content
     */
    protected function transformContent_10($content)
    {
        $transformed = "[alphatab";
        if (!empty($content->heading)) {
            $transformed .= sprintf(' title="%s"', $content->heading);
        }
        $transformed .= ']'. trim($content->content) . '[/alphatab]' . "\n\n";
        echo nl2br($transformed);

        if (static::TEST) {
            $content->transformed = $transformed;
            $content->save(false, ['transformed']);
        }

        return $transformed;
    }

    /**
     * HtmlPhp
     * @param $content
     */
    protected function transformContent_13($content)
    {
        $transformed = "[htmlphp";
        $transformed .= ']'. trim($content->content) . '[/htmlphp]' . "\n\n";
        echo nl2br($transformed);

        if (static::TEST) {
            $content->transformed = $transformed;
            $content->save(false, ['transformed']);
        }

        return $transformed;
    }

    /**
     * RssFeed
     * @param $content
     */
    protected function transformContent_14($content)
    {
        $fields = array_merge(
            ["title" => $content->heading],
            json_decode($content->content, true)
        );
        $fields = array_map('trim', $fields);
        $this->addKeyLengths($fields, 14);

        $transformed = '[rssfeed';
        foreach ($fields as $key => $value) {
            $transformed .= sprintf(' %s="%s"', $key, $value);
        }
        $transformed .= ']';
        $transformed .= "\n\n";

        echo nl2br($transformed);

        if (static::TEST) {
            $content->transformed = $transformed;
            $content->save(false, ['transformed']);
        }

        return $transformed;
    }

    /**
     * Articles
     * @param $content
     */
    protected function transformContent_15($content)
    {
        $fields = array_merge(
            ["title" => $content->heading],
            json_decode($content->content, true)
        );
        $fields = array_map('trim', $fields);
        $this->addKeyLengths($fields, 15);

        $transformed = '[articles';
        foreach ($fields as $key => $value) {
            if (in_array($key, ['text'])) continue;
            $transformed .= sprintf(' %s="%s"', $key, $value);
        }
        $transformed .= ']';
        $transformed .= "\n\n";

        echo nl2br($transformed);

        if (static::TEST) {
            $content->transformed = $transformed;
            $content->save(false, ['transformed']);
        }

        return $transformed;
    }

    /**
     * FilterModel
     * @param $content
     */
    protected function transformContent_16($content)
    {
        $fields = json_decode($content->content, true);
        $fields = array_map('trim', $fields);
        $this->addKeyLengths($fields, 15);

        $transformed = '[websites';
        foreach ($fields as $key => $value) {
            $transformed .= sprintf(' %s="%s"', $key, $value);
        }
        $transformed .= ']';
        $transformed .= "\n\n";

        echo nl2br($transformed);

        if (static::TEST) {
            $content->transformed = $transformed;
            $content->save(false, ['transformed']);
        }

        return $transformed;
    }

    protected function addKeyLengths($fields, $id)
    {
        foreach ($fields as $key => $value) {
            if (!isset($this->keys[$id][$key])) {
                $this->keys[$id][$key] = 0;
            }
            if (strlen($value) > $this->keys[$id][$key]) {
                $this->keys[$id][$key] = strlen($value);
            }
        }
    }

}
