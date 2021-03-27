<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     indexes={
 *          @ORM\Index(name="sytem_architecture_month_name", columns={"month", "name"}),
 *          @ORM\Index(name="sytem_architecture_month", columns={"month"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\SystemArchitectureRepository")
 */
class SystemArchitecture implements CountableInterface
{
    /**
     * @var string
     * @Assert\Choice({"x86_64", "x86_64_v2", "x86_64_v3", "x86_64_v4", "i686", "aarch64", "armv7", "armv6", "armv5"})
     *
     * @ORM\Column(name="name", type="string", length=10)
     * @ORM\Id
     */
    private $name;

    /**
     * @var integer
     * @Assert\NotBlank
     * @Assert\DateTime("Ym")
     *
     * @ORM\Column(name="month", type="integer")
     * @ORM\Id
     */
    private $month;

    /**
     * @var integer
     * @Assert\Positive
     *
     * @ORM\Column(name="count", type="integer", nullable=false)
     */
    private $count = 1;

    /**
     * @param string $name
     * @param int|null $month
     */
    public function __construct(string $name, ?int $month = null)
    {
        $this->name = $name;
        if ($month === null) {
            $month = (int)date('Ym');
        }
        $this->month = $month;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getMonth(): int
    {
        return $this->month;
    }

    /**
     * @return SystemArchitecture
     */
    public function incrementCount(): SystemArchitecture
    {
        $this->count++;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCount(): ?int
    {
        return $this->count;
    }
}
