<?php declare(strict_types=1);

namespace Api\Services;

use Api\Models\GroupModel;
use Api\Models\ScheduleModel;
use Core\Exceptions\EntityException;
use Core\Tools\Other;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Query\Parameter;

class ScheduleService
{
    private EntityManager $entityManager;
    private EntityRepository $entityRepository;

    /**
     * @param EntityManager $entityManager
     * @throws NotSupported
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entityRepository = $entityManager->getRepository(ScheduleModel::class);
    }

    /**
     * @param string $group
     * @return array
     * @throws EntityException
     * @throws NotSupported
     */
    public function get(string $group): array
    {
        if ($this->entityManager->getRepository(GroupModel::class)->findBy(["name" => $group]) === null)
            throw new EntityException(
                "group not found",
                404
            );

        /** @var ScheduleModel[] $scheduleByGroup */
        $scheduleByGroup = $this->entityRepository->findBy(["group" => $group]);

        if ($scheduleByGroup === [])
            throw new EntityException(
                "schedule for this group not found",
                404
            );

        $scheduleDays = [];

        foreach ($scheduleByGroup as $schedule) {
            $scheduleDays[] = [
                "date" => $schedule->getDate(),
                "lessons" => $schedule->getLessons()
            ];
        }

        return $scheduleDays;
    }

    /**
     * @param string $group
     * @param string $date
     * @param array $lessons
     * @return bool
     * @throws EntityException
     * @throws NotSupported
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(string $group, string $date, array $lessons): bool
    {
        if ($this->entityManager->getRepository(GroupModel::class)->findBy(["name" => $group]) === [])
            throw new EntityException(
                "group not found",
                404
            );

        if ($this->entityRepository->findBy(["date" => $date]) !== [])
            throw new EntityException(
                "schedule on this date already exists, use set method for update day",
                400
            );

        foreach ($lessons as $key => $values) {
            $lessons[$key] = array_intersect_key(
                $values,
                array_flip(["number", "startTime", "name", "teacher"])
            );
        }

        $this->entityManager->persist(new ScheduleModel($group, $date, $lessons));
        $this->entityManager->flush();

        return true;
    }
}