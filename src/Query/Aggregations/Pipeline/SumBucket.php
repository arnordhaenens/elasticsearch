<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class SumBucket
 */
class SumBucket implements Arrayable
{
    use HasAggregations;

    public const GAP_SKIP = 'skip';
    public const GAP_INSERT_ZEROS = 'insert_zeros';

    /**
     * This path to the buckets we wish to find the max.
     *
     * @var array|string
     */
    private $buckets;

    /**
     * The policy to apply when gaps are found in the data.
     *
     * @var string|null
     */
    private $gap;

    /**
     * format to apply to the output value of this aggregation.
     *
     * @var string|null
     */
    private $format;

    /**
     * SumBucket constructor.
     *
     * @param array|string $buckets
     * @param string|null $gap
     * @param string|null $format
     */
    public function __construct($buckets, ?string $gap = null, ?string $format = null)
    {
        $this->key = 'sum_bucket';
        $this->aggregations = new Aggregation();

        $this->buckets = $buckets;
        $this->gap = $gap;
        $this->format = $format;
    }

    protected function toElastic(): array
    {
        $params = ['buckets_path' => $this->buckets];

        if ($this->gap) {
            $params['gap_policy'] = $this->gap;
        }

        if ($this->format) {
            $params['format'] = $this->format;
        }

        return $params;
    }
}
