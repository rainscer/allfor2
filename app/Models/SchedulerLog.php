<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SchedulerLog
 * @package App
 */
class SchedulerLog extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'job',
        'description'
    ];

    /**
     * @param $job
     * @param $description
     * @return static
     */
    public static function write($job, $description)
    {
        if (is_array($description)) {
            $description = implode(', ', $description);
        }

        $newRow = self::create(
            [
                'job'         => $job,
                'description' => $description
            ]
        );

        return $newRow;
    }

    /**
     * @param $job
     * @param $description
     * @return mixed
     */
    public static function touchLast($job, $description)
    {
        if (is_array($description)) {
            $description = implode('\n', $description);
        }

        $updatedRow = self::where('job', '=', $job)
            ->where('description', '=', $description)
            ->orderBy('id', 'DESC')
            ->first();

        if (!$updatedRow) {

            return self::write($job, $description);
        }

        $updatedRow->touch();

        return $updatedRow;
    }

}
