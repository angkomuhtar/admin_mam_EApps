<?php

namespace App\Models;

use Laravel\Passport\Client;

class OAuthClient extends Client
{
    public function isServiceClient(): bool
    {
        return $this->redirect === '';
    }

    public function hasGrantType($grantType)
    {
        if ($this->personal_access_client || $this->password_client) {
            return parent::hasGrantType($grantType);
        }

        if ($this->isServiceClient()) {
            return $grantType === 'client_credentials';
        }

        return in_array($grantType, ['authorization_code', 'refresh_token'], true);
    }

    public function hasScope($scope)
    {
        if ($this->personal_access_client || $this->password_client) {
            return parent::hasScope($scope);
        }

        return in_array($scope, $this->scopes ?? [], true);
    }
}
