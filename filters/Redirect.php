<?php

namespace app\filters;

use app\models\LogRedirect;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\web\Application;

class Redirect extends BaseObject implements BootstrapInterface
{
    public bool $enable = false;
    public array $filters = [];

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        if (!$this->enable) {
            return;
        }

        /** @var Application $app */
        $request = $app->getRequest();
        $referrer = $request->getReferrer();
        $requestUrl = $request->getAbsoluteUrl();
        $hostInfo = $request->getHostInfo();
        $url = $request->getUrl();
        $userAgent = $request->getUserAgent();
        $userAgent = $userAgent ?? '';

        if ($request->getMethod() !== 'GET') {
            return;
        }

        $signals = [];
        $redirectUrl = $requestUrl;

        $redirect = \app\models\Redirect::findOneByRequestUrl($url);
        if ($redirect) {
            $signals[] = 'db';
            $redirectUrl = $hostInfo . $redirect->to;
            $redirect->updated = date('Y-m-d H:i:s');
            $redirect->count += 1;
            $redirect->save(false, ['count', 'updated']);
        }

        // Check for HTTPS
        $http = 'http://';
        if (str_starts_with($redirectUrl, $http)) {
            $redirectUrl = 'https://' . substr($redirectUrl, strlen($http));
            $signals[] = 'https';
        }

        // Check for WWW
        if (!str_contains($requestUrl, '://www.')) {
            $redirectUrl = str_replace('://', '://www.', $redirectUrl);
            $signals[] = 'www';
        }

        // Check for trailing slash
        #if (!in_array($redirectUrl, ['https://www.electricbass.ch', 'https://www.electricbass.ch/'])) {
        #    $redirectUrl = rtrim($redirectUrl, '/');
        #}

        if ($requestUrl === $redirectUrl) {
            return;
        }

        if (!$this->isFilteredByUserAgent($userAgent)) {
            $signal = join('+', $signals);
            LogRedirect::logRedirect($signal, 301, $requestUrl, $redirectUrl, $referrer, $userAgent, date('Y-m-d H:i:s'));
        }

        $response = $app->getResponse();
        $response->redirect($redirectUrl, 301);
        $response->send();
        exit;
    }

    private function isFilteredByUserAgent(string $userAgent): bool
    {
        foreach ($this->filters as $filter) {
            if (str_contains($userAgent, $filter)) {
                return true;
            }
        }
        return false;
    }

}
