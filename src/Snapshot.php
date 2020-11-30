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
}