<?php

namespace App\DataFixtures;

use Algolia\AlgoliaSearch\SearchClient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpClient\HttpClient;

class GouvAlogolia extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $commune = HttpClient::create();
        $list_commune = $commune->request('GET', 'https://geo.api.gouv.fr/communes?fields=nom,code,codesPostaux,codeDepartement,codeRegion,population&format=json&geometry=centre');
        $content = $list_commune->getContent();
        // $content = $list_commune->toArray();
        // $searchService = $this->get('search.service');
        
        $client = SearchClient::create('8V1R9H6WI8', '3f4787643f438dfc0dc54b26b40728ae');
        $index = $client->initIndex('commune');
        $batch = json_decode($content, true);
        $test = $index->saveObjects($batch, ['autoGenerateObjectIDIfNotExist' => true]);
    }
}
