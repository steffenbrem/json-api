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

use \Neomerx\JsonApi\Contracts\Schema\LinkInterface;
use \Neomerx\JsonApi\Contracts\Schema\ResourceObjectInterface;
use \Neomerx\JsonApi\Contracts\Schema\SchemaProviderInterface;

/**
 * @package Neomerx\JsonApi
 */
class ResourceObject implements ResourceObjectInterface
{
    /**
     * @var string
     */
    private $idx;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @var mixed
     */
    private $primaryMeta;

    /**
     * @var bool
     */
    private $isPrimaryMetaSet = false;

    /**
     * @var LinkInterface
     */
    private $selfSubLink;

    /**
     * @var bool
     */
    private $isInArray;

    /**
     * @var SchemaProviderInterface
     */
    private $schema;

    /**
     * @var object
     */
    private $resource;

    /**
     * @var array<string,int>|null
     */
    private $attributeKeysFilter;

    /**
     * @var bool
     */
    private $isSelfSubLinkSet = false;

    /**
     * @var bool
     */
    private $isRelationshipMetaSet = false;

    /**
     * @var mixed
     */
    private $relationshipMeta;

    /**
     * @var bool
     */
    private $isInclusionMetaSet = false;

    /**
     * @var mixed
     */
    private $inclusionMeta;

    /**
     * @var bool
     */
    private $isRelPrimaryMetaSet = false;

    /**
     * @var mixed
     */
    private $relPrimaryMeta;

    /**
     * @var bool
     */
    private $isRelIncMetaSet = false;

    /**
     * @var mixed
     */
    private $relInclusionMeta;

    /**
     * @param SchemaProviderInterface $schema
     * @param object                  $resource
     * @param bool                    $isInArray
     * @param array<string,int>|null  $attributeKeysFilter
     */
    public function __construct(
        SchemaProviderInterface $schema,
        $resource,
        $isInArray,
        array $attributeKeysFilter = null
    ) {
        assert(
            'is_bool($isInArray) && is_object($resource) && '.
            '($attributeKeysFilter === null || is_array($attributeKeysFilter))'
        );

        $this->schema              = $schema;
        $this->resource            = $resource;
        $this->isInArray           = $isInArray;
        $this->attributeKeysFilter = $attributeKeysFilter;
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return $this->schema->getResourceType();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->idx === null ? $this->idx = (string)$this->schema->getId($this->resource) : $this->idx;
    }

    /**
     * @inheritdoc
     */
    public function getAttributes()
    {
        if ($this->attributes === null) {
            $attributes = $this->schema->getAttributes($this->resource);
            if ($this->attributeKeysFilter !== null) {
                $attributes = array_intersect_key($attributes, $this->attributeKeysFilter);
            }
            $this->attributes = $attributes;
        }

        return $this->attributes;
    }

    /**
     * @inheritdoc
     */
    public function getSelfSubLink()
    {
        if ($this->isSelfSubLinkSet === false) {
            $this->selfSubLink      = $this->schema->getSelfSubLink($this->resource);
            $this->isSelfSubLinkSet = true;
        }

        return $this->selfSubLink;
    }

    /**
     * @inheritdoc
     */
    public function isShowSelf()
    {
        return $this->schema->isShowSelf();
    }

    /**
     * @inheritdoc
     */
    public function isShowSelfInIncluded()
    {
        return $this->schema->isShowSelfInIncluded();
    }

    /**
     * @inheritdoc
     */
    public function isShowAttributesInIncluded()
    {
        return $this->schema->isShowAttributesInIncluded();
    }

    /**
     * @inheritdoc
     */
    public function isShowRelationshipsInIncluded()
    {
        return $this->schema->isShowRelationshipsInIncluded();
    }

    /**
     * @inheritdoc
     */
    public function isInArray()
    {
        return $this->isInArray;
    }

    /**
     * @inheritdoc
     */
    public function getPrimaryMeta()
    {
        if ($this->isPrimaryMetaSet === false) {
            $this->primaryMeta = $this->schema->getPrimaryMeta($this->resource);
            $this->isPrimaryMetaSet = true;
        }

        return $this->primaryMeta;
    }

    /**
     * @inheritdoc
     */
    public function getInclusionMeta()
    {
        if ($this->isInclusionMetaSet === false) {
            $this->inclusionMeta = $this->schema->getInclusionMeta($this->resource);
            $this->isInclusionMetaSet = true;
        }

        return $this->inclusionMeta;
    }

    /**
     * @inheritdoc
     */
    public function getRelationshipsPrimaryMeta()
    {
        if ($this->isRelPrimaryMetaSet === false) {
            $this->relPrimaryMeta = $this->schema->getRelationshipsPrimaryMeta($this->resource);
            $this->isRelPrimaryMetaSet = true;
        }

        return $this->relPrimaryMeta;
    }

    /**
     * @inheritdoc
     */
    public function getRelationshipsInclusionMeta()
    {
        if ($this->isRelIncMetaSet === false) {
            $this->relInclusionMeta = $this->schema->getRelationshipsInclusionMeta($this->resource);
            $this->isRelIncMetaSet = true;
        }

        return $this->relInclusionMeta;
    }

    /**
     * @inheritdoc
     */
    public function getLinkageMeta()
    {
        if ($this->isRelationshipMetaSet === false) {
            $this->relationshipMeta = $this->schema->getLinkageMeta($this->resource);
            $this->isRelationshipMetaSet = true;
        }

        return $this->relationshipMeta;
    }
}
