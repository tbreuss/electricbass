<?php

namespace app\filters;

use Yii;
use yii\base\ActionFilter;

class RedirectFilter extends ActionFilter
{
    public function afterAction($action, $result)
    {
        $request = Yii::$app->getRequest();

        if ($request->getMethod() !== 'GET') {
            return parent::afterAction($action, $result);
        }

        // if we are in the error action already, stop here
        if (isset($action->actionMethod) && ($action->actionMethod === 'actionError')) { // @phpstan-ignore-line
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

        // redirect if something strange happened
        if ($isNotSecure || $isWithoutWww || $hasConsecutiveSlashes || $hasTrailingSlash) {
            $redirectUrl = 'https://www.'
                . str_replace(['https://', 'http://', 'www.'], '', (string)$request->getHostInfo())
                . '/'
                . trim($trimmedPathInfo, '/')
                . ($queryString ? '?' : '')
                . $queryString
            ;
            $this->owner->redirect($redirectUrl, 301); // @phpstan-ignore-line
            return false;
        }

        return parent::afterAction($action, $result);
    }
}
