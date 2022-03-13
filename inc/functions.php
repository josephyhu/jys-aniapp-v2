<?php
//Require autoload.
require 'vendor/autoload.php';

//Get the access token.
function get_token($code) {
    $http = new GuzzleHttp\Client;

    $response = $http->post('https://anilist.co/api/v2/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => '7672',
            'client_secret' => '5WG5OMjuWTBisB6xDe7dzZT6F5uZ5wXhIT9CPVA4',
            'redirect_uri' => 'https://jys-aniapp-v2.herokuapp.com', // http://example.com/callback
            'code' => $code, // The Authorization code received previously
        ],
        'headers' => [
            'Accept' => 'application/json'
        ]
    ]);

    return json_decode($response->getBody()->getContents())->access_token;
}

//Get current user id.
function get_userId($accessToken) {
    $query = '
        query {
            Viewer {
                id
            }
        }';

    $http = new GuzzleHttp\Client;
    $response = $http->request('POST', 'https://graphql.anilist.co', [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'query' => $query,
        ]
    ]);

    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['Viewer']['id'];
}

function get_username($userId) {
    $query = '
        query ($id: Int) {
            User (id: $id) {
                name
            }
        }';
    $variables = [
        'id' => $userId,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);

    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['User']['name'];
}

function get_userMediaList($userId, $type, $status) {
    $query = '
    query ($userId: Int, $type: MediaType, $status: MediaListStatus) {
        MediaListCollection (userId: $userId, type: $type, status: $status, sort: SCORE_DESC) {
            lists {
                entries {
                    media {
                        title {
                            romaji,
                            english
                        },
                        coverImage {
                            large,
                        },
                        format,
                        siteUrl
                    },
                    startedAt {
                        year,
                        month,
                        day,
                    }
                    completedAt {
                        year,
                        month,
                        day,
                    }
                    progress,
                    repeat,
                    score,
                }
            }
        }
    }';
    $variables = [
        'userId' => $userId,
        'type' => $type,
        'status' => $status,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['MediaListCollection']['lists'][0]['entries'];
}

function search_media($type, $page, $search) {
    $query = 'query ($page: Int, $perPage: Int, $type: MediaType, $search: String) {
        Page (page: $page, perPage: $perPage) {
            pageInfo {
                currentPage,
            },
            media (type: $type, search: $search, sort: SCORE_DESC) {
                title {
                    romaji,
                    english,
                },
                coverImage {
                    large
                }
                startDate {
                    year,
                    month,
                    day,
                },
                endDate {
                    year,
                    month,
                    day,
                }
                averageScore,
                format,
                siteUrl,
            }
        }
    }';

    $variables = [
        'type' => $type,
        'page' => $page,
        'perPage' => 10,
        'search' => $search,
    ];

    $http = new GuzzleHttp\Client;
    $response = $http->post('https://graphql.anilist.co', [
        'json' => [
            'query' => $query,
            'variables' => $variables,
        ]
    ]);
    $arr = json_decode($response->getBody()->getContents(), true);
    return $arr['data']['Page'];
}