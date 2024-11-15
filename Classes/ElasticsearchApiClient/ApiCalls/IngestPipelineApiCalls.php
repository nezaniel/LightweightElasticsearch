<?php

namespace Sandstorm\LightweightElasticsearch\ElasticsearchApiClient\ApiCalls;

use Sandstorm\LightweightElasticsearch\ElasticsearchApiClient\ApiCaller;
use Sandstorm\LightweightElasticsearch\SharedModel\ElasticsearchBaseUrl;

class IngestPipelineApiCalls
{
    /**
     * @param array<mixed> $request
     * @return array<mixed>
     */
    public function simulate(ApiCaller $apiCaller, ElasticsearchBaseUrl $baseUrl, array $request): array
    {
        $response = $apiCaller->request('POST', $baseUrl->withPathSegment('_ingest/pipeline/_simulate'), json_encode($request, JSON_THROW_ON_ERROR));
        return json_decode($response->getBody()->getContents(), true);
    }
}
