<?php


namespace SebRave\Snapshot;


use SebRave\Snapshot\Builders\ChartBuilder;
use SebRave\Snapshot\Builders\TableBuilder;

class Snapshot
{
    public function show($userSelection)
    {
        (new TableBuilder())->build($userSelection);
    }

    public function draw($userSelection, $columnX, $columnY)
    {
        (new ChartBuilder())->build($userSelection, $columnX, $columnY);
    }
}