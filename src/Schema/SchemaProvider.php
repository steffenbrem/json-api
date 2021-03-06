<?php namespace Neomerx\JsonApi\Schema;

/**
 * Copyright 2015 info@neomerx.com (www.neomerx.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use \Closure;
use \Neomerx\JsonApi\Contracts\Schema\LinkInterface;
use \Neomerx\JsonApi\Contracts\Schema\ContainerInterface;
use \Neomerx\JsonApi\Contracts\Document\DocumentInterface;
use \Neomerx\JsonApi\Contracts\Schema\SchemaFactoryInterface;
use \Neomerx\JsonApi\Contracts\Schema\SchemaProviderInterface;

/**
 * @package Neomerx\JsonApi
 */
abstract class SchemaProvider implements SchemaProviderInterface
{
    /** Links information */
    const LINKS = DocumentInterface::KEYWORD_LINKS;

    /** Linked data key. */
    const DATA = DocumentInterface::KEYWORD_DATA;

    /** Relationship meta */
    const META = DocumentInterface::KEYWORD_META;

    /** If 'self' URL should be shown. */
    const SHOW_SELF = 'showSelf';

    /** If 'related' URL should be shown. */
    const SHOW_RELATED = 'related';

    /** If data should be shown in relationships. */
    const SHOW_DATA = 'showData';

    /** Property name */
    const ATTRIBUTES = DocumentInterface::KEYWORD_ATTRIBUTES;

    /** Property name */
    const RELATIONSHIPS = DocumentInterface::KEYWORD_RELATIONSHIPS;

    /** Property name */
    const INCLUDED = DocumentInterface::KEYWORD_INCLUDED;

    /**
     * @var string
     */
    protected $resourceType;

    /**
     * @var string
     */
    protected $selfSubUrl;

    /**
     * @var bool
     */
    protected $isShowSelf = true;

    /**
     * @var bool
     */
    protected $isShowSelfInIncluded = false;

    /**
     * @var bool
     */
    protected $isShowAttributesInIncluded = true;

    /**
     * @var bool
     */
    protected $isShowRelShipsInIncluded = false;

    /**
     * @var SchemaFactoryInterface
     */
    private $factory;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param SchemaFactoryInterface $factory
     * @param ContainerInterface     $container
     */
    public function __construct(SchemaFactoryInterface $factory, ContainerInterface $container)
    {
        assert('is_string($this->resourceType) && empty($this->resourceType) === false', 'Resource type not set');
        assert('is_bool($this->isShowSelfInIncluded) && is_bool($this->isShowRelShipsInIncluded)');
        assert('is_string($this->selfSubUrl) && empty($this->selfSubUrl) === false', '\'self\' sub-URL not set');
        assert('substr($this->selfSubUrl, -1) === \'/\'', 'Sub-url should end with \'/\' separator');

        $this->factory   = $factory;
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function getResourceType()
    {
        return $this->resourceType;
    }

    /**
     * @inheritdoc
     */
    public function getSelfSubLink($resource)
    {
        return new Link($this->selfSubUrl . $this->getId($resource));
    }

    /**
     * @inheritdoc
     */
    public function getPrimaryMeta($resource)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getLinkageMeta($resource)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getInclusionMeta($resource)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getRelationshipsPrimaryMeta($resource)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getRelationshipsInclusionMeta($resource)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function isShowSelf()
    {
        return $this->isShowSelf;
    }

    /**
     * @inheritdoc
     */
    public function isShowSelfInIncluded()
    {
        return $this->isShowSelfInIncluded;
    }

    /**
     * @inheritdoc
     */
    public function isShowAttributesInIncluded()
    {
        return $this->isShowAttributesInIncluded;
    }

    /**
     * @inheritdoc
     */
    public function isShowRelationshipsInIncluded()
    {
        return $this->isShowRelShipsInIncluded;
    }

    /**
     * Get resource links.
     *
     * @param object $resource
     *
     * @return array
     */
    public function getRelationships($resource)
    {
        $resource ?: null;
        return [];
    }

    /**
     * @inheritdoc
     */
    public function createResourceObject($resource, $isOriginallyArrayed, $attributeKeysFilter = null)
    {
        return $this->factory->createResourceObject($this, $resource, $isOriginallyArrayed, $attributeKeysFilter);
    }

    /**
     * @inheritdoc
     */
    public function getRelationshipObjectIterator($resource)
    {
        foreach ($this->getRelationships($resource) as $name => $desc) {
            $data          = $this->readData($desc);
            $meta          = $this->getValue($desc, self::META, null);
            $isShowSelf    = ($this->getValue($desc, self::SHOW_SELF, false) === true);
            $isShowRelated = ($this->getValue($desc, self::SHOW_RELATED, false) === true);
            $isShowData    = ($this->getValue($desc, self::SHOW_DATA, true) === true);
            $links         = $this->readLinks($name, $desc, $isShowSelf, $isShowRelated);

            yield $this->factory->createRelationshipObject($name, $data, $links, $meta, $isShowData);
        }
    }

    /**
     * @inheritdoc
     */
    public function getIncludePaths()
    {
        return [];
    }

    /**
     * @param string $relationshipName
     * @param array  $description
     * @param bool   $isShowSelf
     * @param bool   $isShowRelated
     *
     * @return array <string,LinkInterface>
     */
    protected function readLinks($relationshipName, array $description, $isShowSelf, $isShowRelated)
    {
        $links = $this->getValue($description, self::LINKS, []);
        if ($isShowSelf === true && isset($links[LinkInterface::SELF]) === false) {
            $links[LinkInterface::SELF] = $this->factory->createLink('relationships/'.$relationshipName);
        }
        if ($isShowRelated === true && isset($links[LinkInterface::RELATED]) === false) {
            $links[LinkInterface::RELATED] = $this->factory->createLink($relationshipName);
        }

        return $links;
    }

    /**
     * @param array  $array
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    private function getValue(array $array, $key, $default = null)
    {
        return (isset($array[$key]) === true ? $array[$key] : $default);
    }

    /**
     * @param array $description
     *
     * @return mixed
     */
    private function readData(array $description)
    {
        $data = $this->getValue($description, self::DATA);
        if ($data instanceof Closure) {
            $data = $data();
        }
        return $data;
    }
}
