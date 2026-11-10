<?php

namespace App\OpenApi;

class ClientCredentialsSpecification
{
    public function build(string $baseUrl): array
    {
        return [
            'openapi' => '3.0.3',
            'info' => [
                'title' => 'HRM Client Credentials API',
                'version' => '1.0.0',
                'description' => 'Kontrak OAuth2 Client Credentials untuk komunikasi antar-aplikasi internal.',
            ],
            'servers' => [
                ['url' => $baseUrl, 'description' => 'HRM OAuth2 Server'],
            ],
            'tags' => [
                ['name' => 'Client Credentials', 'description' => 'Penerbitan dan pengujian access token aplikasi.'],
            ],
            'paths' => [
                '/oauth/token' => [
                    'post' => [
                        'tags' => ['Client Credentials'],
                        'summary' => 'Mendapatkan access token aplikasi',
                        'description' => 'Scope boleh dikosongkan agar server menggunakan seluruh scope milik client. Jika dikirim, scope harus merupakan subset scope client.',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/x-www-form-urlencoded' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['grant_type', 'client_id', 'client_secret'],
                                        'properties' => [
                                            'grant_type' => [
                                                'type' => 'string',
                                                'enum' => ['client_credentials'],
                                                'example' => 'client_credentials',
                                            ],
                                            'client_id' => ['type' => 'string', 'example' => '12'],
                                            'client_secret' => ['type' => 'string', 'format' => 'password'],
                                            'scope' => [
                                                'type' => 'string',
                                                'example' => 'plant:read',
                                                'description' => 'Opsional. Pisahkan beberapa scope dengan spasi.',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Access token berhasil diterbitkan.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/TokenResponse'],
                                    ],
                                ],
                            ],
                            '400' => [
                                'description' => 'Grant atau scope tidak valid.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/OAuthError'],
                                    ],
                                ],
                            ],
                            '401' => [
                                'description' => 'Client ID atau client secret tidak valid.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => ['$ref' => '#/components/schemas/OAuthError'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/api/oauth/client/ping' => [
                    'get' => [
                        'tags' => ['Client Credentials'],
                        'summary' => 'Menguji access token plant:read',
                        'description' => 'Endpoint diagnostik HRM untuk memastikan token Client Credentials valid dan memiliki scope plant:read.',
                        'security' => [['clientCredentials' => ['plant:read']]],
                        'responses' => [
                            '200' => [
                                'description' => 'Token valid.',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'required' => ['authenticated', 'service'],
                                            'properties' => [
                                                'authenticated' => ['type' => 'boolean', 'example' => true],
                                                'service' => ['type' => 'string', 'example' => 'Employee Apps'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            '401' => ['description' => 'Token tidak ada, tidak valid, dicabut, atau kedaluwarsa.'],
                            '403' => ['description' => 'Token tidak memiliki scope plant:read.'],
                        ],
                    ],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'clientCredentials' => [
                        'type' => 'oauth2',
                        'description' => 'OAuth2 Client Credentials milik HRM.',
                        'flows' => [
                            'clientCredentials' => [
                                'tokenUrl' => $baseUrl.'/oauth/token',
                                'scopes' => [
                                    'plant:read' => 'Membaca data dari server Plant.',
                                    'plant:edit' => 'Mengubah data di server Plant.',
                                ],
                            ],
                        ],
                    ],
                ],
                'schemas' => [
                    'TokenResponse' => [
                        'type' => 'object',
                        'required' => ['token_type', 'expires_in', 'access_token'],
                        'properties' => [
                            'token_type' => ['type' => 'string', 'example' => 'Bearer'],
                            'expires_in' => ['type' => 'integer', 'example' => 600],
                            'access_token' => ['type' => 'string', 'format' => 'jwt'],
                        ],
                    ],
                    'OAuthError' => [
                        'type' => 'object',
                        'required' => ['error', 'message'],
                        'properties' => [
                            'error' => ['type' => 'string', 'example' => 'invalid_client'],
                            'message' => ['type' => 'string'],
                            'hint' => ['type' => 'string', 'nullable' => true],
                        ],
                    ],
                ],
            ],
        ];
    }
}
