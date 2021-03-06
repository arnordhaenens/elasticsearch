<?php

namespace AviationCode\Elasticsearch\Tests\Feature;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\Must;
use AviationCode\Elasticsearch\Tests\Feature\TestModels\Article;
use Elasticsearch\Client;

class CountTest extends TestCase
{
    /**
     * @var Client|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->instance('elasticsearch.client', $this->client = \Mockery::mock(Client::class));
    }

    /** @test */
    public function it_can_count_the_documents_without_filtering()
    {
        $this->client
            ->shouldReceive('count')
            ->with(['index' => 'article', 'body' => []])
            ->andReturn(
                [
                    'count' => 122,
                    '_shards' => [
                        'total' => 1,
                        'successful' => 1,
                        'skipped' => 0,
                        'failed' => 0,
                    ],
                ]
            );

        $this->assertSame(122, $this->elastic->query(Article::class)->count());
    }

    /** @test */
    public function it_can_count_the_documents_without_an_eloquent_model()
    {
        $this->client
            ->shouldReceive('count')
            ->with(['index' => 'article', 'body' => []])
            ->andReturn(
                [
                    'count' => 6,
                    '_shards' => [
                        'total' => 1,
                        'successful' => 1,
                        'skipped' => 0,
                        'failed' => 0,
                    ],
                ]
            );

        $this->assertSame(6, $this->elastic->query('article')->count());
    }

    /** @test */
    public function it_can_count_the_matching_documents_with_filter()
    {
        $this->client
            ->shouldReceive('count')
            ->with(
                [
                    'index' => 'article',
                    'body' => [
                        'query' => [
                            'bool' => [
                                'must' => [
                                    [
                                        'exists' => [
                                            'field' => 'published_at',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            )
            ->andReturn(
                [
                    'count' => 12,
                    '_shards' => [
                        'total' => 1,
                        'successful' => 1,
                        'skipped' => 0,
                        'failed' => 0,
                    ],
                ]
            );

        $this->assertSame(
            12,
            $this->elastic
                ->query('article')
                ->must(
                    function (Must $must) {
                        $must->exists('published_at');
                    }
                )
                ->count()
        );
    }
}
