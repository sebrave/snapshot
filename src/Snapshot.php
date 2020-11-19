<?php


namespace SebRave\Snapshot;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Snapshot
{
    public function show(string $modelName)
    {
        $data = (new $modelName())::query()->get();

        $parts = explode("\\", $modelName);

        $filename = 'snapshot_' . strtolower($parts[count($parts) - 1]);

        File::put('public/' . $filename . '.html',
            view('snapshot::data/snapshot')
                ->with([
                    'timestamp' => Carbon::parse(now()),
                    'name' => $modelName,
                    'data' => $data
                ])
                ->render()
        );

        dump(env('APP_URL') . '/' . $filename . '.html');
    }
}