<?php

namespace app\controllers;

use app\helpers\Html;
use app\helpers\Url;
use app\models\Glossar;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class GlossarController extends Controller
{
    /**
     * @phpstan-return array<array>
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => 'app\filters\RedirectFilter'
            ]
        ];
    }

    public function actionIndex(?string $category = null): string
    {
        $glossars = Glossar::findAllByCategory($category);

        if (count($glossars) === 0) {
            throw new NotFoundHttpException();
        }

        return $this->render('index', [
            'glossars' => $glossars,
            'selectedCategory' => $category
        ]);
    }

    public function actionView(string $id, string $category): string
    {
        $glossar = Glossar::findOneOrThrowException('/glossar/' . $category . '/' . $id);

        #$glossar->increaseHits();
        $glossar->content = $this->linkifyEntries($glossar->content);

        return $this->render('view', [
            'glossar' => $glossar,
            'previous' => $glossar->findPreviousOneOrNull(),
            'next' => $glossar->findNextOneOrNull(),
            'selectedCategory' => $category,
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

    private function linkifyEntries(string $content): string
    {
        $currentUrl = Url::to();
        $replacements = [];
        foreach (Glossar::queryTest() as $entry) {
            if ($currentUrl === $entry['url']) {
                continue;
            }
            $replacements[$entry['title']] = Html::a($entry['title'], $entry['url']);
        }

        $tokens = preg_split("/([\.\?\!\:\s\W])/u", $content, -1, PREG_SPLIT_DELIM_CAPTURE);

        foreach ($tokens as $i => $token) {
            foreach ($replacements as $from => $to) {
                if ($token === $from) {
                    $tokens[$i] = $to;
                    break;
                }
            }
        }

        return join('', $tokens);

        return strtr($content, $replacements);
    }
}
