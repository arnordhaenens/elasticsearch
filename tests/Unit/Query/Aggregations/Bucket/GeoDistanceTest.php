<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Bucket\GeoDistance;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class GeoDistanceTest extends TestCase
{
    /** @test **/
    public function it_builds_a_geo_distance_bucket_aggregation()
    {
        $geoDistance = new GeoDistance('location', 52.3760, 4.894, [
            ['to' => 100000],
            ['from' => 100000, 'to' => 300000],
            ['from' => 300000],
        ]);

        $this->assertEquals([
            'geo_distance' => [
                'field' => 'location',
                'keyed' => true,
                'origin' => ['lat' => 52.3760, 'lon' => 4.894],
                'unit' => 'm',
                'ranges' => [
                    ['to' => 100000],
                    ['from' => 100000, 'to' => 300000],
                    ['from' => 300000],
                ],
            ],
        ], $geoDistance->toArray());
    }

    /** @test **/
    public function it_can_add_unit()
    {
        $geoDistance = new GeoDistance('location', 52.3760, 4.894, [
            ['to' => 100000],
            ['from' => 100000, 'to' => 300000],
            ['from' => 300000],
        ], GeoDistance::M);

        $this->assertEquals([
            'geo_distance' => [
                'field' => 'location',
                'keyed' => true,
                'origin' => ['lat' => 52.3760, 'lon' => 4.894],
                'unit' => 'm',
                'ranges' => [
                    ['to' => 100000],
                    ['from' => 100000, 'to' => 300000],
                    ['from' => 300000],
                ],
            ],
        ], $geoDistance->toArray());
    }

    /** @test **/
    public function it_can_add_ranges_dynamically()
    {
        $geoDistance = new GeoDistance('location', 52.3760, 4.894, GeoDistance::CM);
        $geoDistance->to(100000);
        $geoDistance->range(100000, 300000);
        $geoDistance->from(300000);

        $this->assertEquals([
            'geo_distance' => [
                'field' => 'location',
                'keyed' => true,
                'origin' => ['lat' => 52.3760, 'lon' => 4.894],
                'unit' => 'cm',
                'ranges' => [
                    ['to' => 100000],
                    ['from' => 100000, 'to' => 300000],
                    ['from' => 300000],
                ],
            ],
        ], $geoDistance->toArray());
    }

    /** @test **/
    public function it_can_define_custom_keys()
    {
        $geoDistance = new GeoDistance('location', 52.3760, 4.894);
        $geoDistance->to(100000, 'first');
        $geoDistance->range(100000, 300000, 'second');
        $geoDistance->from(300000, 'third');

        $this->assertEquals([
            'geo_distance' => [
                'field' => 'location',
                'keyed' => true,
                'origin' => ['lat' => 52.3760, 'lon' => 4.894],
                'unit' => 'm',
                'ranges' => [
                    ['to' => 100000, 'key' => 'first'],
                    ['from' => 100000, 'to' => 300000, 'key' => 'second'],
                    ['from' => 300000, 'key' => 'third'],
                ],
            ],
        ], $geoDistance->toArray());
    }
}
