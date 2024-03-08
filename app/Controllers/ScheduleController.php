<?php

namespace Api\Controllers;

use Api\Services\ScheduleService;
use Api\Singleton\Database;
use Core\Controllers\Controller;
use Core\DTO\SuccessResponse;
use Core\Exceptions\EntityException;
use Core\Exceptions\ParametersException;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class ScheduleController extends Controller
{
    private ScheduleService $scheduleService;

    /**
     * @throws Exception
     * @throws MissingMappingDriverImplementation
     * @throws NotSupported
     */
    public function __construct()
    {
        $this->scheduleService = new ScheduleService(Database::getInstance());
        parent::__construct();
    }

    /**
     * @return void
     * @throws EntityException
     * @throws NotSupported
     * @throws ParametersException
     */
    public function get(): void
    {
        parent::validateData(parent::$inputData["data"], [
            "group" => "required"
        ]);

        (new SuccessResponse())->setBody(
            $this->scheduleService->get(parent::$inputData["data"]["group"])
        )->send();
    }

    /**
     * @return void
     * @throws ParametersException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws EntityException
     */
    public function add(): void
    {
        parent::validateData(parent::$inputData["data"], [
            "group" => "required",
            "date" => "required|date:d/m/Y",
            "lessons" => "required|array",
            "lessons.*.number" => "required",
            "lessons.*.startTime" => "required",
            "lessons.*.name" => "required",
            "lessons.*.teacher" => "required",
        ]);

        (new SuccessResponse())->setBody(
            $this->scheduleService->add(
                parent::$inputData["data"]["group"],
                parent::$inputData["data"]["date"],
                parent::$inputData["data"]["lessons"],
            )
        )->send();
    }
}