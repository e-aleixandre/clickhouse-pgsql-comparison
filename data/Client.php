<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

class Client
{
    private \Manticoresearch\Client $client;
    private \Manticoresearch\Index $index;

    public function __construct()
    {
        $config = [
            'host' => '127.0.0.1',
            'port' => '9308'
        ];

        $this->client = new \Manticoresearch\Client($config);
        $this->index = $this->client->index('content');
    }

    public function updateContentById(int $contentId, string $value): void
    {
        $time = microtime(true);
        $result = $this->client->sql([
            'body' => [
                'query' => "SELECT * FROM content WHERE id = $contentId"
            ]
        ]);

        $previousContentState = $result['hits']['hits'][0];

        $doc = [
            'body' => [
                [
                    'replace' => [
                        'index' => 'content',
                        'id' => $contentId,
                        'doc' => [
                            'project_id' => $previousContentState['_source']['project_id'],
                            'pair_id' => $previousContentState['_source']['pair_id'],
                            'type' => $previousContentState['_source']['type'],
                            'key' => $previousContentState['_source']['key'],
                            'value' => $previousContentState['_source']['value'],
                            'latest' => false,
                            'translated' => $previousContentState['_source']['translated']
                        ]
                    ],
                ],
                [
                    'insert' => [
                        'index' => 'content',
                        'doc' => [
                            'project_id' => $previousContentState['_source']['project_id'],
                            'pair_id' => $previousContentState['_source']['pair_id'],
                            'type' => $previousContentState['_source']['type'],
                            'key' => $previousContentState['_source']['key'],
                            'value' => $value,
                            'latest' => true,
                            'translated' => $previousContentState['_source']['translated']
                        ]
                    ]
                ]
            ]
        ];

        $r = $this->client->bulk($doc);

        $result = $this->client->sql([
            'body' => [
                'query' => "SELECT * FROM content WHERE id = {$r['items'][0]['bulk']['_id']}"
            ]
        ]);

        $endTime = microtime(true);

        $response = [
            'time' => $endTime - $time,
            'results' => $result['hits']['hits']
        ];

        echo json_encode($response);

        die;
    }

    public function updateContent(int $projectId, string $type, string $key, string $value): void
    {
        $time = microtime(true);
        $result = $this->client->sql([
            'body' => [
                'query' => "SELECT * FROM content WHERE project_id = $projectId AND type = '$type' AND key = '$key' AND latest = true"
            ]
        ]);

        $previousContentState = $result['hits']['hits'][0];

        $doc = [
            'body' => [
                [
                    'replace' => [
                        'index' => 'content',
                        'id' => (int)$previousContentState['_id'],
                        'doc' => [
                            'project_id' => $projectId,
                            'pair_id' => $previousContentState['_source']['pair_id'],
                            'type' => $type,
                            'key' => $key,
                            'value' => $previousContentState['_source']['value'],
                            'latest' => false,
                            'translated' => $previousContentState['_source']['translated']
                        ]
                    ],
                ],
                [
                    'insert' => [
                        'index' => 'content',
                        'doc' => [
                            'project_id' => $projectId,
                            'pair_id' => $previousContentState['_source']['pair_id'],
                            'type' => $type,
                            'key' => $key,
                            'value' => $value,
                            'latest' => true,
                            'translated' => false
                        ]
                    ]
                ]
            ]
        ];

        $this->client->bulk($doc);

        /* $result = $this->client->sql([
            'mode' => 'raw',
            'body' => [
                [
                    'query' => "UPDATE content SET latest = false WHERE id = '{$previousContentState['_id']}'"
                ],
                [
                    'query' => "INSERT INTO content (project_id, pair_id, type, key, value, latest, translated) SET (
                                                                         $projectId,
                                                                         {previousContentState['pair_id']},
                                                                         '$type',
                                                                         '$key',
                                                                         '$value',
                                                                         true,
                                                                         false
                                                                         )"
                ]
            ]
        ]); */

        $endTime = microtime(true);

        var_dump($endTime - $time);
    }

    public function matchContent(int $projectId, string $search, ?string $type = null): void
    {
        $time = microtime(true);

        $query = [
            'body' => [
                'index' => 'content',
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'match' => [
                                    'value' => "*$search*"
                                ]
                            ],
                            [
                                'equals' => [
                                    'project_id' => $projectId
                                ]
                            ],
                            [
                                'equals' => [
                                    'latest' => 1
                                ]
                            ]
                        ]
                    ]
                ],
                'highlight' => [
                    'fields' => [
                        'value'
                    ]
                ]
            ]
        ];

        if (null !== $type && 'all' !== $type) {
            $query['body']['query']['bool']['must'][] = [
                'equals' => [
                    'type' => $type
                ]
            ];
        }

        $res = $this->client->search($query);
        $endTime = microtime(true);

        $response = [
            'time' => $endTime - $time,
            'results' => $res['hits']['hits']
        ];

        echo json_encode($response);
        die;
    }

    public function getTranslatedPercentage(int $projectId, string $type): void
    {
        $res = $this->client->sql([
            'body' => [
                'query' => "SELECT SUM(IF(translated, 1,0)) as trans, COUNT(*) as total FROM content WHERE project_id = $projectId AND type = '$type' AND latest = true;"
            ]
        ]);

        $response = [
            'percentage' => 100 * $res['hits']['hits'][0]['_source']['trans'] / $res['hits']['hits'][0]['_source']['total']
        ];

        echo json_encode($response);

        die;
    }

    public function indexContent(int $projectId): void
    {
        $time = microtime(true);
        $res = $this->client->search([
            'body' => [
                'index' => 'content',
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'equals' => [
                                    'project_id' => $projectId
                                ]
                            ],
                            [
                                'equals' => [
                                    'latest' => 1
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $endTime = microtime(true);

        $response = [
            'time' => $endTime - $time,
            'results' => $res['hits']['hits']
        ];

        echo json_encode($response);
        die;
    }
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$client = new Client();

if (!isset($_GET['method'])) {
    $client->indexContent(1);
}

if ('search' === $_GET['method']) {
    $client->matchContent(1, $_GET['search'], $_GET['type'] ?? null);
}

if ('update' === $_GET['method'] && isset($_GET['id'], $_GET['value'])) {
    $client->updateContentById(
        (int)$_GET['id'],
        $_GET['value']
    );
}

if ('translated' === $_GET['method'] && isset($_GET['type'])) {
    $client->getTranslatedPercentage(1, $_GET['type']);
}

//$client->updateContent(1,"product","doloribus","Otro contenido para probar los tiempos");
//$client->matchContent(1, 'repudi');