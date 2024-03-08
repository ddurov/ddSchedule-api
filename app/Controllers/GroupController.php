<?php

namespace Api\Controllers;

use Api\Services\GroupService;
use Api\Singleton\Database;
use Core\Controllers\Controller;
use Core\DTO\SuccessResponse;
use Core\Exceptions\EntityException;
use Core\Exceptions\ParametersException;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;

class GroupController extends Controller
{
    private GroupService $groupService;

    /**
     * @throws Exception
     * @throws MissingMappingDriverImplementation
     * @throws NotSupported
     */
    public function __construct()
    {
        $this->groupService = new GroupService(Database::getInstance());
        parent::__construct();
    }

    /**
     * @return void
     */
    public function getAll(): void
    {
        (new SuccessResponse())->setBody(
            $this->groupService->getAll()
        )->send();
    }

    /**
     * @return void
     * @throws ParametersException
     * @throws EntityException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(): void
    {
        parent::validateData(parent::$inputData["data"], [
            "name" => "required"
        ]);

        (new SuccessResponse())->setBody(
            $this->groupService->add(parent::$inputData["data"]["name"])
        )->send();
    }
}