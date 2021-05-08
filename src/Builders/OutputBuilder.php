<?php


namespace SebRave\Snapshot\Builders;


class OutputBuilder
{
    /**
     * @param $filename
     * @return string
     */
    public function getOutputPath($filename): string
    {
        return config('snapshot.output_folder') . $filename . '.html';
    }

    /**
     * @param $filename
     */
    protected function shareLink($filename)
    {
        if (!$this->shouldShareLink()) {
            return;
        }

        dump(
            $link = env('APP_URL') . '/' . $filename . '.html'
        );
    }

    private function shouldShareLink()
    {
        return config('snapshot.dump_snapshot_link');
    }
}