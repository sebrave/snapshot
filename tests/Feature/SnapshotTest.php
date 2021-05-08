<?php


namespace SebRave\Snapshot\Tests\Feature;


use Illuminate\Support\Str;
use League\CommonMark\Extension\Table\TableRenderer;
use SebRave\Snapshot\Builders\TableBuilder;
use SebRave\Snapshot\Snapshot;
use SebRave\Snapshot\Tests\dummy\Course;
use SebRave\Snapshot\Tests\TestCase;

class SnapshotTest extends TestCase
{
    /** @test * */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Snapshot::class, app('snapshot'));
    }

    /** @test * */
    public function it_renders_a_single_table_of_data()
    {
        $dummyData = json_decode(file_get_contents('tests/dummy/courses.json'), true);

        $courses = collect($dummyData)
            ->map(function ($course) {
                return (new Course($course))->toArray();
            });

        $output = TableBuilder::render(
            collect([
                collect([
                    'name' => $tableName = Str::random(),
                    'shortName' => $shortName = Str::random(),
                    'data' => $courses
                ])
            ])
        );

        $this->assertStringContainsString($tableName, $output);
        $this->assertStringContainsString($shortName, $output);

        $courses->each(function ($course) use ($output) {
            collect($course)->each(function ($attribute) use ($output) {
                $this->assertStringContainsString($attribute, $output);
            });
        });
    }

    /** @test * */
    public function it_generates_the_correct_filename()
    {
        $tableName = Str::random();

        $this->assertEquals(
            Str::of('snapshot_' . $tableName)->lower(),
            TableBuilder::getFilename($tableName)
        );
    }
}