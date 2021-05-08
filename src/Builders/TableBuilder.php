<?php


namespace SebRave\Snapshot\Builders;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TableBuilder extends OutputBuilder
{
    public function build($userSelection)
    {
        $filename = self::getFilename($userSelection);

        $output = self::render(
            $this->fetchTableData($userSelection)
        );

        File::put(
            $this->getOutputPath($filename),
            $output
        );

        $this->shareLink($filename);
    }

    private function fetchTableData($userSelection): Collection
    {
        return Collection::wrap($userSelection)
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
    }

    /**
     * @param $userSelection
     * @return string
     */
    public static function getFilename($userSelection): string
    {
        if (is_string($userSelection)) {
            $parts = explode("\\", $userSelection);
            return 'snapshot_' . strtolower($parts[count($parts) - 1]);
        } else {
            return 'snapshot_multiple_tables';
        }
    }

    /**
     * @param Collection $tableData
     * @return string
     */
    public static function render(Collection $tableData): string
    {
        return view('snapshot::data/snapshot')
            ->with([
                'timestamp' => Carbon::parse(now()),
                'tables' => $tableData
            ])
            ->render();
    }
}