<?php


namespace SebRave\Snapshot;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Snapshot
{
    public function show($modelName)
    {
        $tables = Collection::wrap($modelName)
            ->unique()
            ->sort()
            ->map(function (string $name) {
                if (class_exists($name)) {
                    $data = (new $name())::query()->get()
                        ->map(function (Model $model) {
                            return $model->toArray();
                        });
                } else {
                    $data = DB::table($name)->get();
                }

                return [
                    'name' => $name,
                    'shortName' => collect(explode("\\", $name))->last(),
                    'data' => $data
                ];
            });

        if (is_string($modelName)) {
            $parts = explode("\\", $modelName);
            $filename = 'snapshot_' . strtolower($parts[count($parts) - 1]);
        } else {
            $filename = 'snapshot_multiple_tables';
        }

        File::put(config('snapshot.output_folder') . $filename . '.html',
            view('snapshot::data/snapshot')
                ->with([
                    'timestamp' => Carbon::parse(now()),
                    'tables' => $tables
                ])
                ->render()
        );

        if (config('snapshot.dump_snapshot_link')) {
            dump(env('APP_URL') . '/' . $filename . '.html');
        }
    }

    public function draw(string $modelName, $columnX, $columnY)
    {
        if (class_exists($modelName)) {
            $data = (new $modelName())::query()
                ->orderBy($columnX)
                ->get()
                ->map(function ($model) use ($columnX, $columnY) {
                    return [
                        "x" => $model[$columnX],
                        "y" => $model[$columnY]
                    ];
                });
        } else {
            $data = DB::table($modelName)
                ->orderBy($columnX)
                ->get()
                ->map(function ($model) use ($columnX, $columnY) {
                    return [
                        "x" => $model->$columnX,
                        "y" => $model->$columnY
                    ];
                });
        }

        $parts = explode("\\", $modelName);

        $filename = 'snapshot_chart_' . strtolower($parts[count($parts) - 1]);

        File::put(config('snapshot.output_folder') . $filename . '.html',
            view('snapshot::data/chart')
                ->with([
                    'timestamp' => Carbon::parse(now()),
                    'name' => $modelName,
                    'data' => json_encode($data)
                ])
                ->render()
        );

        if (config('snapshot.dump_snapshot_link')) {
            dump(env('APP_URL') . '/' . $filename . '.html');
        }
    }
}