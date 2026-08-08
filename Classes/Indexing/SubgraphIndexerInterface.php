<?php

declare(strict_types=1);

namespace Sandstorm\LightweightElasticsearch\Indexing;

use Neos\ContentRepository\Core\Projection\ContentGraph\ContentSubgraphInterface;
use Neos\ContentRepository\Core\SharedModel\Workspace\WorkspaceName;
use Sandstorm\LightweightElasticsearch\Elasticsearch;
use Sandstorm\LightweightElasticsearch\SharedModel\IndexName;

interface SubgraphIndexerInterface
{
    public function indexSubgraph(
        ContentSubgraphInterface $subgraph,
        WorkspaceName $workspaceName,
        IndexName $indexName,
        Elasticsearch $elasticsearch
    ): void;
}
