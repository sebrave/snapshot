<?php


namespace SebRave\Snapshot;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Snapshot
{
    public function show(string $modelName)
    {
        if (class_exists($modelName)) {
            $data = (new $modelName())::query()->get()
                ->map(function (Model $model) {
                    return $model->toArray();
                });
        } else {
            $data = DB::table($modelName)->get();
        }

        $parts = explode("\\", $modelName);

        $filename = 'snapshot_' . strtolower($parts[count($parts) - 1]);

        File::put(config('snapshot.output_folder') . $filename . '.html',
            view('snapshot::data/snapshot')
                ->with([
                    'timestamp' => Carbon::parse(now()),
                    'name' => $modelName,
                    'data' => $data
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
            dump($data);
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