<?php

namespace app\controllers;

use app\models\Glossar;
use Yii;
use yii\web\Controller;

class GlossarController extends Controller
{

    public function actionIndex($category = null)
    {
        $condition = ['deleted' => 0, 'hidden' => 0];
        if (!empty($category)) {
            $condition['category'] = $category;
        }
        $glossars = Glossar::find()->where($condition)->orderBy('autosort')->all();
        return $this->render('index', array(
            'glossars' => $glossars,
            'selectedCategory' => $category
        ));
    }

    public function actionView($id, $category)
    {
        $glossar = Glossar::findOneOrThrowException('/glossar/' . $category . '/' . $id);

        #$glossar->increaseHits();

        return $this->render('view', array(
            'glossar' => $glossar,
            'previous' => $glossar->findPreviousOneOrNull(),
            'next' => $glossar->findNextOneOrNull(),
            'selectedCategory' => $category
        ));
    }

    public function actionReorder()
    {
        $glossars = Glossar::findAll(array('order' => 'category, title'));
        $i = 0;
        foreach ($glossars AS $glossar) {
            $glossar->autosort = $i;
            $glossar->save(false, array('autosort'));
            $i++;
        }
        return $this->redirect(array('/glossar/index'));
    }

}
