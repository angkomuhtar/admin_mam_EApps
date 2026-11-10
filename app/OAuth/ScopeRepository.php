<?php

namespace App\OAuth;

use Laravel\Passport\Bridge\ScopeRepository as PassportScopeRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;

class ScopeRepository extends PassportScopeRepository
{
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        $client = $this->clients?->findActive($clientEntity->getIdentifier());

        if (! $client) {
            return parent::finalizeScopes($scopes, $grantType, $clientEntity, $userIdentifier);
        }

        if ($scopes === []) {
            $scopes = collect($client->scopes ?? [])
                ->map(fn ($scope) => $this->getScopeEntityByIdentifier($scope))
                ->filter()
                ->values()
                ->all();
        } else {
            foreach ($scopes as $scope) {
                if (! $client->hasScope($scope->getIdentifier())) {
                    throw OAuthServerException::invalidScope($scope->getIdentifier());
                }
            }
        }

        return parent::finalizeScopes($scopes, $grantType, $clientEntity, $userIdentifier);
    }
}
