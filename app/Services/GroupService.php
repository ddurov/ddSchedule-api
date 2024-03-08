<?php

namespace Api\Services;

use Api\Models\GroupModel;
use Core\Exceptions\EntityException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class GroupService
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
        $this->entityRepository = $entityManager->getRepository(GroupModel::class);
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        /** @var GroupModel[] $groups */
        $groups = $this->entityRepository->findAll();

        $names = [];

        foreach ($groups as $group) {
            $names[] = $group->getName();
        }

        return $names;
    }

    /**
     * @param string $name
     * @return int
     * @throws EntityException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(string $name): int
    {
        if ($this->entityRepository->findBy(["name" => $name]) != null)
            throw new EntityException("this group already exists", 400);

        $newGroup = new GroupModel($name);
        $this->entityManager->persist($newGroup);
        $this->entityManager->flush();

        return $newGroup->getId();
    }
}