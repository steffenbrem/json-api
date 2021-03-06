<?php namespace Neomerx\JsonApi\Encoder\Parser;

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

use \Neomerx\JsonApi\Contracts\Encoder\Stack\StackReadOnlyInterface;

/**
 * @package Neomerx\JsonApi
 */
class ParserEmptyReply extends BaseReply
{
    /**
     * @param int                    $replyType
     * @param StackReadOnlyInterface $stack
     */
    public function __construct($replyType, StackReadOnlyInterface $stack)
    {
        assert(
            '$replyType === ' . self::REPLY_TYPE_NULL_RESOURCE_STARTED . ' || '.
            '$replyType === ' . self::REPLY_TYPE_EMPTY_RESOURCE_STARTED
        );

        parent::__construct($replyType, $stack);
    }
}
