<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\StatsBucket;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class StatsBucketTest extends TestCase
{
    /** @test **/
    public function it_builds_stats_bucket_aggregation()
    {
        $bucket = new StatsBucket('the_sum');

        $this->assertEquals([
            'stats_bucket' => [
                'buckets_path' => 'the_sum',
            ],
        ], $bucket->toArray());
    }

    /** @test **/
    public function it_builds_stats_bucket_aggregation_gap_policy()
    {
        $bucket = new StatsBucket('the_sum', StatsBucket::GAP_INSERT_ZEROS);

        $this->assertEquals([
            'stats_bucket' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'insert_zeros',
            ],
        ], $bucket->toArray());

        $bucket = new StatsBucket('the_sum', StatsBucket::GAP_SKIP);

        $this->assertEquals([
            'stats_bucket' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'skip',
            ],
        ], $bucket->toArray());
    }

    /** @test **/
    public function it_builds_stats_bucket_aggregation_format()
    {
        $bucket = new StatsBucket('the_sum', null, '000.00');

        $this->assertEquals([
            'stats_bucket' => [
                'buckets_path' => 'the_sum',
                'format' => '000.00',
            ],
        ], $bucket->toArray());
    }
}
