<?php

namespace Elastica\Test\Aggregation;

use Elastica\Aggregation\Sum;
use Elastica\Document;
use Elastica\Index;
use Elastica\Query;

class SumTest extends BaseAggregationTest
{
    protected function _getIndexForTest(): Index
    {
        $index = $this->_createIndex();

        $index->addDocuments([
            new Document(1, ['price' => 5]),
            new Document(2, ['price' => 8]),
            new Document(3, ['price' => 1]),
            new Document(4, ['price' => 3]),
        ]);

        $index->refresh();

        return $index;
    }

    /**
     * @group functional
     */
    public function testSumAggregation()
    {
        $agg = new Sum('sum');
        $agg->setField('price');

        $query = new Query();
        $query->addAggregation($agg);
        $results = $this->_getIndexForTest()->search($query)->getAggregation('sum');

        $this->assertEquals(5 + 8 + 1 + 3, $results['value']);
    }
}
