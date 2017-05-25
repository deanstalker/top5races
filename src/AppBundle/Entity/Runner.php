<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table("Runners")
 * @ORM\Entity()
 *
 * Class Runner
 * @package AppBundle\Entity
 */
class Runner
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=200)
     *
     * @var string
     */
    private $type;
    /**
     * @ORM\Column(type="string", length=200)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $barrierNumber;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $saddleNumber;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Race", inversedBy="runners")
     * @ORM\JoinColumn(name="raceId", referencedColumnName="id")
     *
     * @var Race
     */
    private $race;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Runner
     */
    public function setId(int $id): Runner
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return Runner
     */
    public function setPosition(int $position): Runner
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Runner
     */
    public function setType(string $type): Runner
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Runner
     */
    public function setName(string $name): Runner
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getBarrierNumber(): int
    {
        return $this->barrierNumber;
    }

    /**
     * @param int $barrierNumber
     * @return Runner
     */
    public function setBarrierNumber(int $barrierNumber): Runner
    {
        $this->barrierNumber = $barrierNumber;

        return $this;
    }

    /**
     * @return int
     */
    public function getSaddleNumber(): int
    {
        return $this->saddleNumber;
    }

    /**
     * @param int $saddleNumber
     * @return Runner
     */
    public function setSaddleNumber(int $saddleNumber): Runner
    {
        $this->saddleNumber = $saddleNumber;

        return $this;
    }

    /**
     * @return Race
     */
    public function getRace(): Race
    {
        return $this->race;
    }

    /**
     * @param Race $race
     * @return Runner
     */
    public function setRace(Race $race): Runner
    {
        $this->race = $race;

        return $this;
    }


}