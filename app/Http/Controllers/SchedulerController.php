<?php namespace App\Http\Controllers;

use Request;
use stdClass;
use ReflectionClass;
use App\Models\SchedulerLog;
use App\Http\Requests;

/**
 * Class SchedulerController
 * @package App\Http\Controllers
 */
class SchedulerController extends Controller
{
    /**
     * @var string
     */
    private $schedulerFileName;
    /**
     * @var string
     */
    private $kernelNamespace = 'App\Console\Kernel';
    /**
     * @var string
     */
    private $defaultScheduleRule = 'everyTenMinutes';

    /**
     *
     */
    public function __construct()
    {
        $this->schedulerFileName = storage_path('app/scheduler.json');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $jobs = $this->getSchedulerFile();

        return view('admin.scheduler.index', compact('jobs'));
    }

    /**
     * @param null $jobName
     * @return \Illuminate\View\View
     */
    public function showForm($jobName = null)
    {
        $jobs = $this->getSchedulerFile();

        $job = isset($jobs->{$jobName}) ? $jobs->{$jobName} : null;

        return view('admin.scheduler.form', compact('job', 'jobName'));
    }

    /**
     * @param null $jobName
     * @return \Illuminate\View\View
     */
    public function showJobLog($jobName = null)
    {
        $jobLog = SchedulerLog::where('job', '=', $jobName)
            ->orderBy('updated_at', 'DESC')
            ->paginate(20);

        return view('admin.scheduler.jobLog', compact('jobLog'));
    }

    /**
     * @param null    $jobName
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeForm($jobName = null, Request $request)
    {

        $jobs = $this->getSchedulerFile();

        $job = isset($jobs->{$jobName}) ? $jobs->{$jobName} : null;

        if ($job) {
            $job->active = $request::has('active');
            $job->description = $request::get('description');
            $job->scheduleRule = $request::get('scheduleRule');
            $job->scheduleRuleParameter = $request::get(
                'scheduleRuleParameter'
            );

            $jobs->{$jobName} = $job;
            $this->writeScheduleFile($jobs);
        }

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearJobs()
    {
        @unlink($this->schedulerFileName);

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refreshJobs()
    {
        $this->fillSchedulerFile();

        return redirect()->back();
    }

    /**
     * Создать файл с настройками если не существует
     * @return bool
     */
    public function initSchedulerFile()
    {
        if (!file_exists($this->schedulerFileName)) {
            $this->fillSchedulerFile();
        }

        return true;
    }

    /**
     * Получить содержимое файла с настройками
     * @return mixed
     */
    public function getSchedulerFile()
    {
        $this->initSchedulerFile();

        return json_decode(file_get_contents($this->schedulerFileName));
    }

    /**
     * Заполнить файл с настройками из парамера Kernel::commands
     * @return bool
     */
    public function fillSchedulerFile()
    {
        $schedulerData = file_exists($this->schedulerFileName) ?
            (array)$this->getSchedulerFile() : [];

        $kernelReflection = new ReflectionClass($this->kernelNamespace);
        $properties = $kernelReflection->getDefaultProperties();
        $commands = $properties['commands'];
        array_walk(
            $commands,
            function (&$item) {
                $value = explode('\\', (string)$item);
                $item = end($value);
            }
        );
        $commandNames = array_flip($commands);

        $this->setDefaults($commandNames);

        $jobs = array_merge($commandNames, $schedulerData);

        $this->writeScheduleFile($jobs);

        return true;
    }

    /**
     * Установить настройки по-умолчанию для новых заданий
     * @param $commandNames
     */
    private function setDefaults(&$commandNames)
    {
        foreach ($commandNames as $key => &$commandName) {

            $commandReflection = new ReflectionClass(
                'App\Console\Commands\\' . $key
            );
            $properties = $commandReflection->getDefaultProperties();

            $temp = new stdclass();
            $temp->description = $properties['description'];
            $temp->active = false;
            $temp->scheduleRule = $this->defaultScheduleRule;
            $temp->scheduleRuleParameter = null;

            $commandName = $temp;
        }
    }

    /**
     * @param $jobs
     */
    private function writeScheduleFile($jobs)
    {
        file_put_contents($this->schedulerFileName, json_encode($jobs));
    }
}
