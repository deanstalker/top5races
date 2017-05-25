<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Table("Races")
 * @ORM\Entity()
 * Class Race
 * @package AppBundle\Entity
 */
class Race implements \JsonSerializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $timetogo;

    /**
     * @ORM\Column(type="string", length=200)
     *
     * @var string
     */
    private $raceName;

    /**
     * @ORM\Column(type="string", length=400)
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $raceNumber;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $link;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Meeting", inversedBy="races")
     * @ORM\JoinColumn(name="meetingId", referencedColumnName="id")
     *
     * @var Meeting
     */
    private $meeting;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Runner", mappedBy="race")
     *
     * @var ArrayCollection
     */
    private $runners;

    /**
     * Race constructor.
     */
    public function __construct()
    {
        $this->runners = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Race
     */
    public function setId(int $id): Race
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimetogo(): int
    {
        return $this->timetogo;
    }

    /**
     * @param int $timetogo
     * @return Race
     */
    public function setTimetogo(int $timetogo): Race
    {
        $this->timetogo = $timetogo;

        return $this;
    }

    /**
     * @return string
     */
    public function getRaceName(): string
    {
        return $this->raceName;
    }

    /**
     * @param string $raceName
     * @return Race
     */
    public function setRaceName(string $raceName): Race
    {
        $this->raceName = $raceName;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Race
     */
    public function setDescription(string $description): Race
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getRaceNumber(): string
    {
        return $this->raceNumber;
    }

    /**
     * @param string $raceNumber
     * @return Race
     */
    public function setRaceNumber(string $raceNumber): Race
    {
        $this->raceNumber = $raceNumber;

        return $this;
    }

    /**
     * @return int
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return Race
     */
    public function setLink(string $link): Race
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return Meeting
     */
    public function getMeeting(): Meeting
    {
        return $this->meeting;
    }

    /**
     * @param Meeting $meeting
     * @return Race
     */
    public function setMeeting(Meeting $meeting): Race
    {
        $this->meeting = $meeting;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRunners()
    {
        return $this->runners;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'timetogo' => $this->timetogo,
            'raceName' => $this->raceName,
            'raceNumber' => $this->raceNumber,
            'meeting' => $this->getMeeting()
        ];
    }


}