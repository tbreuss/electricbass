<?php

namespace app\controllers;

use app\models\Glossar;
use yii\web\Controller;
use yii\web\GoneHttpException;
use yii\web\Response;

final class GlossarController extends Controller
{
    public function actionIndex(): string
    {
        $glossars = Glossar::find()
            ->where(['deleted' => 0, 'hidden' => 0])
            ->orderBy('autosort')
            ->all();

        foreach ($glossars as $glossar) {
            if (!isset($glossarsByCategory[$glossar->category])) {
                $glossarsByCategory[$glossar->category] = [];
            }
            $glossarsByCategory[$glossar->category][] = $glossar;
        }

        return $this->render('index', [
            'glossarsByCategory' => $glossarsByCategory,
        ]);
    }

    public function actionView(string $id, string $category): string
    {
        $glossar = Glossar::findOneOrNull('/glossar/' . $category . '/' . $id);

        if (is_null($glossar)) {
            throw new GoneHttpException();
        }

        #$glossar->increaseHits();

        return $this->render('view', [
            'glossar' => $glossar,
            'previous' => $glossar->findPreviousOneOrNull(),
            'next' => $glossar->findNextOneOrNull(),
            'selectedCategory' => $category
        ]);
    }

    public function actionReorder(): Response
    {
        $glossars = Glossar::findAll(['order' => 'category, title']);
        $i = 0;
        foreach ($glossars as $glossar) {
            $glossar->autosort = $i;
            $glossar->save(false, ['autosort']);
            $i++;
        }
        return $this->redirect(['/glossar/index']);
    }
}
