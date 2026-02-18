<?php

namespace app\filters;

use app\helpers\Url;
use Yii;
use yii\base\ActionFilter;
use yii\helpers\Html;

class RedirectFilter extends ActionFilter
{
    public function afterAction($action, $result)
    {
        $request = Yii::$app->getRequest();

        if ($request->getMethod() !== 'GET') {
            return parent::afterAction($action, $result);
        }

        // if we are in the error action already, stop here
        if (isset($action->actionMethod) && ($action->actionMethod === 'actionError')) {
            return parent::afterAction($action, $result);
        }

        // get request infos
        $requestUrl = $request->getAbsoluteUrl();
        $pathInfo = $request->getPathInfo();
        $trimmedPathInfo = ltrim(strval(preg_replace('#/{2,}#', '/', $pathInfo)), '/');
        $queryString = $request->getQueryString();

        // detect strange things
        $isNotSecure = !$request->getIsSecureConnection();
        $isWithoutWww = !str_contains($requestUrl, '://www.');
        $hasConsecutiveSlashes = $pathInfo !== $trimmedPathInfo;
        $hasTrailingSlash = substr($pathInfo, -1) === '/';
        $hasCanonicalLink = str_contains($result, ' rel="canonical">');

        // redirect if something strange happened
        if ($isNotSecure || $isWithoutWww || $hasConsecutiveSlashes || $hasTrailingSlash) {
            if (!$hasCanonicalLink) {
                $redirectUrl = 'https://www.'
                    . str_replace(['https://', 'http://', 'www.'], '', (string)$request->getHostInfo())
                    . '/'
                    . trim($trimmedPathInfo, '/')
                    . ($queryString ? '?' : '')
                    . $queryString
                ;

                $canonicalLink = Html::tag('link', '', [
                    'rel' => 'canonical',
                    'href' => Url::to($redirectUrl, true),
                ]);

                $result = str_replace('</head>', $canonicalLink . '</head>', $result);
            }
        }

        return parent::afterAction($action, $result);
    }
}
