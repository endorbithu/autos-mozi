<?php
declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * A Services\RESTService által kezelt (vagy formailag azzal megegyező) REST műveleteket tesztelő logika.
 * Az ebből származtatott test classoknál csak az örökölt property-ket kell megfelelően kitölteni az egyes
 * entitások adataival, és az adatok alapján ez intézi a tesztelést.
 */
class RESTApi extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    //URL: /api/$entityPath/...
    protected string $entityPath = '';

    //Ezt a recordot fogja létrehozni
    protected array $createRecord = [];

    //Frissítéshez az értékek, amivel a createRecord-ot felülírjuk
    protected array $updateRecord = [];

    //Szándékos elrontása egy vagy több mezőnek, hogy teszteljük a validálást
    protected array $invalidFields = [];

    public function test(): void
    {
        $this->_index();
        $this->_create();
        $this->_show();
        $this->_update();
        $this->_delete();
    }

    protected function _index()
    {
        $url = '/api/' . $this->entityPath;
        $response = $this->getJson($url);

        $response->assertStatus(200);
        $this->assertGreaterThan(1, count($response->json('data')));

        $result = $response->json('data');

        $this->assertEmpty(array_diff(array_keys($this->createRecord), array_keys($result[array_rand($result)])));

        $firstHit = $result[0];

        $response = $this->getJson($url . '?orderBy[id]=desc');
        $orderByItem = $response->json('data')[0];
        $this->assertGreaterThan($firstHit['id'], $orderByItem['id']);

        if ($response['last_page'] > 1) {
            $secondPage = $this->getJson($url . '?page=2')->json();
            $this->assertEquals(1, $secondPage['prev_page']);
            $this->assertNotEquals($firstHit['id'], $secondPage['data'][0]['id']);
        }

        $where = [
            'where' => [
                ['id', '>', ($firstHit['id'] - 1)],
                ['id', '<', ($firstHit['id'] + 1)]
            ]
        ];

        $whereResponse = $this->getJson($url . '?' . http_build_query($where))->json();
        $this->assertEquals(1, $whereResponse['count']);
        $this->assertEquals(1, count($whereResponse['data']));
    }

    protected function _create()
    {
        $response = $this->postJson('/api/' . $this->entityPath, $this->createRecord);
        $response->assertStatus(201);
        $this->assertIsNumeric($response->json('data.id'));
        $this->createRecord['id'] = $response->json('data.id');
    }

    protected function _show()
    {
        $response = $this->getJson('/api/' . $this->entityPath . '/' . $this->createRecord['id']);
        $diff = (array_diff_assoc($this->createRecord, $response->json('data')));

        $this->assertEmpty($diff);

    }


    protected function _update()
    {
        $modData = $this->updateRecord;
        $modData = array_merge_recursive($modData, $this->invalidFields);
        $response = $this->putJson('/api/' . $this->entityPath . '/' . $this->createRecord['id'], $modData);

        //validator check
        $response->assertStatus(422);
        $response = $this->putJson('/api/' . $this->entityPath . '/' . $this->createRecord['id'], $this->updateRecord);

        $response->assertStatus(200);
        $this->assertEqualsIgnoringCase('updated', $response->json('message'));

        $response = $this->getJson('/api/' . $this->entityPath . '/' . $this->createRecord['id']);
        $response->assertStatus(200);

        $this->assertEmpty(array_diff_assoc($this->updateRecord, $response->json('data')));
    }

    protected function _delete()
    {
        $response = $this->getJson('/api/' . $this->entityPath . '/' . $this->createRecord['id']);
        $response->assertStatus(200);

        $response = $this->delete('/api/' . $this->entityPath . '/' . $this->createRecord['id']);
        $this->assertEqualsIgnoringCase('deleted', $response->json('message'));

        $response = $this->getJson('/api/' . $this->entityPath . '/' . $this->createRecord['id']);
        $response->assertStatus(404);

    }
}
