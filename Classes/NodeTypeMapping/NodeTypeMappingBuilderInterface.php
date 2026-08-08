<?php

declare(strict_types=1);

namespace Sandstorm\LightweightElasticsearch\NodeTypeMapping;

use Neos\ContentRepository\Core\NodeType\NodeTypeManager;
use Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId;
use Sandstorm\LightweightElasticsearch\SharedModel\MappingDefinition;

interface NodeTypeMappingBuilderInterface
{
    public function build(ContentRepositoryId $contentRepositoryId, NodeTypeManager $nodeTypeManager): MappingDefinition;
}
