<?php namespace Neomerx\Tests\JsonApi\Parameters\Headers;

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

use \Neomerx\Tests\JsonApi\BaseTestCase;
use \Neomerx\JsonApi\Parameters\Headers\MediaType;

/**
 * @package Neomerx\Tests\JsonApi
 */
class MediaTypeTest extends BaseTestCase
{
    /**
     * Test invalid constructor parameters.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidConstructorParams1()
    {
        new MediaType(null, 'subtype');
    }

    /**
     * Test invalid constructor parameters.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidConstructorParams2()
    {
        new MediaType('type', null);
    }

    /**
     * Test invalid constructor parameters.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidConstructorParams3()
    {
        new MediaType('type', 'subtype', 123);
    }

    /**
     * Test invalid parse parameters.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidParseParams1()
    {
        MediaType::parse(1, 'boo.bar+baz');
    }

    /**
     * Test invalid parse parameters.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidParseParams2()
    {
        MediaType::parse(1, 'boo/bar+baz;param');
    }
}
