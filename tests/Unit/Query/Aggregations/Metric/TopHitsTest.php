<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Query\Aggregations\Metric\TopHits;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class TopHitsTest extends TestCase
{
    /** @test **/
    public function it_adds_top_hits_aggregations()
    {
        $top = new TopHits();

        $this->assertEquals([
            'top_hits' => new \stdClass(),
        ], $top->toArray());
    }

    /** @test **/
    public function it_adds_top_hits_aggregations_with_options()
    {
        $top = new TopHits([
            'sort' => ['date' => ['order' => 'desc']],
            '_source' => ['includes' => ['date', 'price']],
            'size' => 1,
            'explain' => true,
            'highlight' => ['fields' => ['name']],
            'stored_fields' => 'user',
            'script_fields' => ['test1' => ['script' => ['lang' => 'painless', 'source' => "doc['price'].value * 2"]]],
            'docvalue_fields' => ['my_ip_field'],
            'version' => true,
            'seq_no_primary_term' => true,
        ]);

        $this->assertEquals([
            'top_hits' => [
                'sort' => ['date' => ['order' => 'desc']],
                '_source' => ['includes' => ['date', 'price']],
                'size' => 1,
                'explain' => true,
                'highlight' => ['fields' => ['name']],
                'stored_fields' => 'user',
                'script_fields' => ['test1' => ['script' => ['lang' => 'painless', 'source' => "doc['price'].value * 2"]]],
                'docvalue_fields' => ['my_ip_field'],
                'version' => true,
                'seq_no_primary_term' => true,
            ],
        ], $top->toArray());
    }

    /** @test **/
    public function it_adds_top_hits_aggregations_with_invalid_options()
    {
        $top = new TopHits([
            'invalid' => 'option',
        ]);

        $this->assertEquals([
            'top_hits' => new \stdClass(),
        ], $top->toArray());
    }

    /** @test **/
    public function it_adds_top_hits_aggregations_sort()
    {
        $top = new TopHits();
        $top->orderBy('created_at', 'desc');
        $top->orderBy('updated_at');

        $this->assertEquals([
            'top_hits' => [
                'sort' => [
                    'created_at' => ['order' => 'desc'],
                    'updated_at' => ['order' => 'asc'],
                ],
            ],
        ], $top->toArray());
    }

    /** @test **/
    public function it_adds_top_hits_aggregations_explain()
    {
        $top = new TopHits();
        $this->assertEquals(['top_hits' => new \stdClass()], $top->toArray());

        $top->explain();
        $this->assertEquals(['top_hits' => ['explain' => true]], $top->toArray());

        $top->explain(false);
        $this->assertEquals(['top_hits' => ['explain' => false]], $top->toArray());
    }

    /** @test **/
    public function it_adds_top_hits_aggregations_version()
    {
        $top = new TopHits();
        $this->assertEquals(['top_hits' => new \stdClass()], $top->toArray());

        $top->version();
        $this->assertEquals(['top_hits' => ['version' => true]], $top->toArray());

        $top->version(false);
        $this->assertEquals(['top_hits' => ['version' => false]], $top->toArray());
    }

    /** @test **/
    public function it_adds_top_hits_aggregations_sequence_numbers()
    {
        $top = new TopHits();
        $this->assertEquals(['top_hits' => new \stdClass()], $top->toArray());

        $top->includeSequenceNumbers();
        $this->assertEquals(['top_hits' => ['seq_no_primary_term' => true]], $top->toArray());

        $top->includeSequenceNumbers(false);
        $this->assertEquals(['top_hits' => ['seq_no_primary_term' => false]], $top->toArray());
    }

    /** @test **/
    public function it_adds_top_hits_aggregations_size()
    {
        $top = new TopHits();
        $this->assertEquals(['top_hits' => new \stdClass()], $top->toArray());

        $top->size(100);
        $this->assertEquals(['top_hits' => ['size' => 100]], $top->toArray());

        $top->size(500);
        $this->assertEquals(['top_hits' => ['size' => 500]], $top->toArray());
    }

    /** @test **/
    public function it_adds_top_hits_aggregations_scripted_fields()
    {
        $top = new TopHits();
        $top->scriptedField('test1', "doc['price'].value * 2");
        $top->scriptedField('test2', "doc['amount'].value * 2");

        $this->assertEquals([
            'top_hits' =>[
                'script_fields' => [
                    'test1' => ['script' => ['lang' => 'painless', 'source' => "doc['price'].value * 2"]],
                    'test2' => ['script' => ['lang' => 'painless', 'source' => "doc['amount'].value * 2"]],
                ]
            ],
        ], $top->toArray());
    }

    /** @test **/
    public function it_adds_top_hits_aggregations_scripted_source_fields()
    {
        $top = new TopHits();
        $top->fields(['price', 'amount']);

        $this->assertEquals([
            'top_hits' =>[
                '_source' => ['includes' => ['price', 'amount']],
            ],
        ], $top->toArray());

        $top->addField('name');

        $this->assertEquals([
            'top_hits' =>[
                '_source' => ['includes' => ['price', 'amount', 'name']],
            ],
        ], $top->toArray());
    }
}
