<?php
namespace AflUtils;

use AflUtils\Stats\Measurer;
use AflUtils\Stats\PercentileCalc;
use AflUtils\Support\GetPlayersFromSeasonData;
use AflUtils\Support\MeasurePercentiles;
use AflUtils\Support\MeasurePlayerStats;
use AflUtils\Utils\GeneratorFactory;
use Ds\Deque;

class CalculatePlayerMetrics
{
    protected $measurer;

    /**
     * The data converter
     *
     * @var GetPlayersFromSeasonData
     */
    protected $dataConverter;

    protected $percentileCalc;

    public function __construct(
        Measurer $measurer = null,
        GetPlayersFromSeasonData $dataConverter = null
    ) {
        $this->measurer = $measurer ?? new Measurer();
        $this->dataConverter = $dataConverter ?? new GetPlayersFromSeasonData();
    }

    public function fromSeason(iterable $data)
    {
        $players = $this->dataConverter->process($data);

        [$min, $max, $totals, $perGame] = $this->measurer->measure($players);

        // dd($min);

        $this->percentileCalc = new PercentileCalc($min, $max);

        $generator = GeneratorFactory::create($players, new MeasurePlayerStats(
            new MeasurePercentiles($this->percentileCalc)
        ));

        $percentiles = new Deque();

        foreach ($generator as $data) {
            $percentiles->push($data);
        }

        return [
            'min' => $min,
            'max' => $max,
            'percentiles' => $percentiles,
            'players' => $players
        ];
    }
}
