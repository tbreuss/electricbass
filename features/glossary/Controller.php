<?php

namespace app\features\glossary;

use app\features\glossary\models\Glossar;
use yii\web\GoneHttpException;
use yii\web\Response;

final class Controller extends \yii\web\Controller
{
    public function actionIndex(?string $category = null): string
    {
        $glossars = Glossar::findAllByCategory($category);

        if (count($glossars) === 0) {
            throw new GoneHttpException();
        }

        return $this->render('@app/features/glossary/views/index', [
            'glossars' => $glossars,
            'selectedCategory' => $category
        ]);
    }

    public function actionView(string $id, string $category): string
    {
        $glossar = Glossar::findOneOrNull('/glossar/' . $category . '/' . $id);

        if (is_null($glossar)) {
            throw new GoneHttpException();
        }

        #$glossar->increaseHits();

        return $this->render('@app/features/glossary/views/view', [
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
        return $this->redirect(['/glossary/index']);
    }
}
