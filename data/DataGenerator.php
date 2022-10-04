<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use Ulid\Ulid;

$config = [
    'host' => '127.0.0.1',
    'port' => '9308'
];

$client = new \Manticoresearch\Client($config);
$index = $client->index('content');

$faker = Faker\Factory::create();

$contentTypes = [
    "theme",
    "post",
    "article",
    "page",
    "metafield",
    "product"
];

$contentKeys = [
    "voluptatibus",
    "omnis",
    "est",
    "voluptatem",
    "natus",
    "quas",
    "corrupti",
    "dolores",
    "optio",
    "magnam",
    "repellendus",
    "dolorum",
    "hic",
    "et",
    "doloribus",
    "voluptas",
    "ad",
    "id",
    "praesentium",
    "delectus"
];

$amount = 10_000_000;

//$handler = fopen('data.jsonl', 'wb+');
//
//if (!$handler) {
//    die('SAdasd');
//}

for ($i = 0; $i < $amount; ++$i) {
    $data = generateContentData();
    $index->addDocument($data);
    //fwrite($handler, json_encode($data));
    //fwrite($handler, PHP_EOL);
}

generateLatestContents();

function generateContentData(): array
{
    global $faker;
    global $contentKeys;
    global $contentTypes;

    return [
        "project_id" => $faker->numberBetween(0, 4),
        "pair_id" => 1,
        "type" => $faker->randomElement($contentTypes),
        "key" => $faker->randomElement($contentKeys),
        "value" => $faker->sentence(),
        "latest" => false,
        "translated" => $faker->boolean()
    ];
}

function generateLatestContents(): void
{
    global $contentTypes;
    global $contentKeys;
    global $index;
    global $faker;

    foreach($contentTypes as $contentType) {
        foreach ($contentKeys as $contentKey) {
            for ($i = 0; $i < 5; ++$i) {
                $index->addDocument([
                   "project_id" => $i,
                   "pair_id" => 1,
                   "type" => $contentType,
                   "key" => $contentKey,
                   "value" => $faker->sentence(),
                   "latest" => true,
                   "translated" => $faker->boolean()
                ]);
            }
        }
    }
}
