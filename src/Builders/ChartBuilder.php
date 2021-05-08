<?php


namespace SebRave\Snapshot\Builders;


use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ChartBuilder extends OutputBuilder
{
    public function build(string $userSelection, $columnX, $columnY)
    {
        $chartData = $this->fetchData($userSelection, $columnX, $columnY);

        $filename = $this->getFilename($userSelection);

        File::put(
            $this->getOutputPath($filename),
            $this->render($userSelection, $chartData)
        );

        $this->shareLink($filename);
    }

    /**
     * @param string $userSelection
     * @param $columnX
     * @param $columnY
     * @return Collection
     */
    public function fetchData(string $userSelection, $columnX, $columnY): Collection
    {
        if (class_exists($userSelection)) {
            return (new $userSelection())::query()
                ->orderBy($columnX)
                ->get()
                ->map(function ($model) use ($columnX, $columnY) {
                    return [
                        "x" => $model[$columnX],
                        "y" => $model[$columnY]
                    ];
                });
        } else {
            return DB::table($userSelection)
                ->orderBy($columnX)
                ->get()
                ->map(function ($model) use ($columnX, $columnY) {
                    return [
                        "x" => $model->$columnX,
                        "y" => $model->$columnY
                    ];
                });
        }
    }

    /**
     * @param string $userSelection
     * @return string
     */
    public function getFilename(string $userSelection): string
    {
        $parts = explode("\\", $userSelection);
        return 'snapshot_chart_' . strtolower($parts[count($parts) - 1]);
    }

    /**
     * @param string $title
     * @param Collection $chartData
     * @return string
     */
    public function render(string $title, Collection $chartData): string
    {
        return view('snapshot::data/chart')
            ->with([
                'timestamp' => Carbon::parse(now()),
                'name' => Str::of($title)->explode("\\")->last(),
                'data' => json_encode($chartData)
            ])
            ->render();
    }
}