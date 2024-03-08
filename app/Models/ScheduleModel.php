<?php

namespace Api\Models;

use Core\Models\Model;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: "`schedule`")]
class ScheduleModel extends Model
{
    #[Column(name: "`group`", type: Types::STRING)]
    private string $group;
    #[Column(name: "`date`", type: Types::STRING)]
    private string $date;
    #[Column(type: Types::JSON)]
    private array $lessons;

    /**
     * @param string $group
     * @param string $date
     * @param array $lessons
     */
    public function __construct(string $group, string $date, array $lessons)
    {
        $this->group = $group;
        $this->date = $date;
        $this->lessons = $lessons;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return array
     */
    public function getLessons(): array
    {
        return $this->lessons;
    }

    /**
     * @param array $lessons
     */
    public function setLessons(array $lessons): void
    {
        $this->lessons = $lessons;
    }
}