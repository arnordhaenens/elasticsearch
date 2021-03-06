<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\ExtendedStatsBucket;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class ExtendedStatsBucketTest extends TestCase
{
    /** @test **/
    public function it_builds_extended_stats_bucket_aggregation()
    {
        $bucket = new ExtendedStatsBucket('the_sum');

        $this->assertEquals([
            'extended_stats_bucket' => [
                'buckets_path' => 'the_sum',
            ],
        ], $bucket->toArray());
    }

    /** @test **/
    public function it_builds_extended_stats_bucket_aggregation_gap_policy()
    {
        $bucket = new ExtendedStatsBucket('the_sum', ExtendedStatsBucket::GAP_INSERT_ZEROS);

        $this->assertEquals([
            'extended_stats_bucket' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'insert_zeros',
            ],
        ], $bucket->toArray());

        $bucket = new ExtendedStatsBucket('the_sum', ExtendedStatsBucket::GAP_SKIP);

        $this->assertEquals([
            'extended_stats_bucket' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'skip',
            ],
        ], $bucket->toArray());
    }

    /** @test **/
    public function it_builds_extended_stats_bucket_aggregation_format()
    {
        $bucket = new ExtendedStatsBucket('the_sum', null, '000.00');

        $this->assertEquals([
            'extended_stats_bucket' => [
                'buckets_path' => 'the_sum',
                'format' => '000.00',
            ],
        ], $bucket->toArray());
    }

    /** @test **/
    public function it_builds_extended_stats_bucket_aggregation_sigma()
    {
        $bucket = new ExtendedStatsBucket('the_sum', null, null, 2);

        $this->assertEquals([
            'extended_stats_bucket' => [
                'buckets_path' => 'the_sum',
                'sigma' => 2
            ],
        ], $bucket->toArray());
    }
}
