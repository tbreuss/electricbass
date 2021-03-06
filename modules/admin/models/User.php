<?php

namespace app\modules\admin\models;

final class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    /** @var int */
    public $id;
    /** @var string */
    public $username;
    /** @var string */
    public $password;
    /** @var string */
    public $authKey;
    /** @var string */
    public $accessToken;

    /**
     * @phpstan-return array<int, array<string, mixed>>
     */
    private static function users(): array
    {
        return [
            '100' => [
                'id' => '100',
                'username' => ($_ENV['ADMIN_USER'] ?? ''),
                'password' => ($_ENV['ADMIN_PASS'] ?? ''),
                'authKey' => 'test100key',
                'accessToken' => '100-token',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): ?User
    {
        $users = self::users();
        return isset($users[$id]) ? new static($users[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): ?User
    {
        $users = self::users();
        foreach ($users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username): ?User
    {
        $users = self::users();
        foreach ($users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
