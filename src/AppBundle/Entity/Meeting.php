<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table("Meetings")
 * @ORM\Entity()
 *
 * Class Meeting
 * @package AppBundle\Entity
 */
class Meeting implements \JsonSerializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $location;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $type;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Race", mappedBy="meeting")
     *
     * @var ArrayCollection
     */
    protected $races;

    public function __construct()
    {
        $this->races = new ArrayCollection();
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
     * @return Meeting
     */
    public function setId(int $id): Meeting
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return Meeting
     */
    public function setLocation(string $location): Meeting
    {
        $this->location = $location;

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
     * @return Meeting
     */
    public function setType(string $type): Meeting
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRaces(): ArrayCollection
    {
        return $this->races;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'location' => $this->location
        ];
    }
}