<?php

namespace Sandstorm\LightweightElasticsearch\Settings;

use Neos\ContentRepository\Core\NodeType\NodeTypeName;
use Neos\Flow\Annotations as Flow;
use Sandstorm\LightweightElasticsearch\SharedModel\ElasticsearchBaseUrl;
use Sandstorm\LightweightElasticsearch\SharedModel\IndexNamePrefix;

/**
 * type safe accessor of relevant Settings.yaml values. Only used inside this package for the upper layers.
 *
 * @internal
 */
#[Flow\Proxy(false)]
readonly class ElasticsearchSettings
{
    /**
     * @param array<mixed> $defaultContext
     * @param array<NodeTypeName> $rootNodeTypeNames
     */
    private function __construct(
        public IndexNamePrefix $nodeIndexNamePrefix,
        public int $transferConnectionTimeout,
        public ElasticsearchBaseUrl $baseUrl,
        public bool $transferSslVerifyPeer,
        public bool $transferSslVerifyHost,
        public DefaultConfigurationPerType $defaultConfigurationPerType,
        public array $defaultContext, // TODO: maybe rename to "indexingEelContext"?
        public int $indexingBatchSizeElements,
        public int $indexingBatchSizeOctets,
        public int $assetMaximumFileSize,
        public array $rootNodeTypeNames,
        public string $nodeTypeFilter,
    ) {
    }

    /**
     * @param array<string,mixed> $settings
     */
    public static function fromArray(array $settings): self
    {
        return new self(
            // OLD: @Flow\InjectConfiguration(path="elasticSearch.indexName", package="Neos.ContentRepository.Search")
            nodeIndexNamePrefix: IndexNamePrefix::fromString($settings['elasticsearch']['indexName'] ?? 'nodeindex'),
            // OLD: @Flow\InjectConfiguration(path="transfer.connectionTimeout", package="Flowpack.Elasticsearch")
            transferConnectionTimeout: $settings['elasticsearch']['connectionTimeout'] ?? 1,
            // TODO where old config?
            baseUrl: ElasticsearchBaseUrl::fromString($settings['elasticsearch']['baseUrl'] ?? throw new \RuntimeException('Base URL not set')),
            // OLD: @Flow\InjectConfiguration(path="transfer.sslVerifyPeer", package="Flowpack.Elasticsearch")
            transferSslVerifyPeer: $settings['transfer']['sslVerifyPeer'] ?? true,
            // OLD: @Flow\InjectConfiguration(path="transfer.sslVerifyHost", package="Flowpack.Elasticsearch")
            transferSslVerifyHost: $settings['transfer']['sslVerifyHost'] ?? true,

            // OLD: @Flow\InjectConfiguration(package="Neos.ContentRepository.Search", path="defaultConfigurationPerType")
            defaultConfigurationPerType: DefaultConfigurationPerType::fromArray($settings['defaultConfigurationPerType'] ?? []),

            // OLD: @Flow\InjectConfiguration(package="Neos.ContentRepository.Search", path="defaultContext")
            defaultContext: $settings['defaultContext'],

            // OLD: @Flow\InjectConfiguration(package="Flowpack.ElasticSearch.ContentRepositoryAdaptor", path="indexing.batchSize.elements")
            indexingBatchSizeElements: $settings['indexing']['batchSize']['elements'] ?? 500,
            // OLD: @Flow\InjectConfiguration(package="Flowpack.ElasticSearch.ContentRepositoryAdaptor", path="indexing.batchSize.octets")
            indexingBatchSizeOctets: $settings['indexing']['batchSize']['octets'] ?? 40_000_000,
            // OLD: * @Flow\InjectConfiguration(package="Flowpack.ElasticSearch.ContentRepositoryAdaptor", path="indexing.assetExtraction.maximumFileSize")
            assetMaximumFileSize: $settings['indexing']['assetExtraction']['maximumFileSize'] ?? 104_857_600,
            rootNodeTypeNames: array_map(
                fn (string $nodeTypeName): NodeTypeName => NodeTypeName::fromString($nodeTypeName),
                array_keys(
                    array_filter(
                        $settings['rootNodeTypeNames'] ?? ['Neos.Neos:Sites' => true]
                    )
                )
            ),
            nodeTypeFilter: $settings['nodeTypeFilter'] ?? 'Neos.Neos:Document',
        );
    }

    public function createIndexParameters(\Sandstorm\LightweightElasticsearch\SharedModel\IndexName $indexName): CreateIndexParameters
    {
        // Settings of Flowpack.Elasticsearch
        // if ($this->client instanceof Client) {
        //            $path = 'indexes.' . $this->client->getBundle() . '.' . $this->settingsKey;
        //        } else {
        //            $path = 'indexes.default' . '.' . $this->settingsKey;
        //        }
        //
        //        $configuration = Arrays::getValueByPath($this->settings, $path);
        return new CreateIndexParameters();
    }
}
