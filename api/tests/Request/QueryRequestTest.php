<?php

namespace App\Tests\Request;

use App\Request\QueryRequest;
use PHPUnit\Framework\TestCase;

class QueryRequestTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $queryRequest = new QueryRequest('foo');

        $this->assertEquals('foo', $queryRequest->getQuery());
    }
}
