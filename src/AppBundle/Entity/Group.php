<?php

namespace AppBundle\Entity;

// -----------------------------------------------------------------------------

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

// -----------------------------------------------------------------------------


/**
 * @ORM\Entity
 * @ORM\Table(name="groupe")
 *
 * @UniqueEntity("label", message="{{ value }} is already in database")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="label", type="string", length=100, unique=true)
     * 
     * @Assert\NotBlank(message="Please enter a label for the group")
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "The maximal length for the label is to {{ limit }} characters"
     * )
     *
     * @JMS\Expose
     */
    private $label;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Beacon", cascade={"persist", "remove"}, orphanRemoval=true, mappedBy="group")
     */
    private $beacons;


    /**
     *
     * Constructor of the Document entity
     *
     */
    public function __construct()
    {
        $this->beacons = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function addBeacon(Beacon $beacon)
    {
        if (!($this->beacons->contains($beacon)))
        {
            $beacon->setGroup($this);
            $this->beacons->add($beacon);
        }

        return $this;
    }

    public function removeBeacon(Beacon $beacon)
    {
        if ($this->beacons->contains($beacon))
        {
            $this->beacons->removeElement($beacon);
        }

        return $this;
    }

    public function setBeacons($beacons)
    {
        if ($beacons instanceof Beacon)
        {
            $this->addBeacon($beacons);
        }
        else
        {
            if ($beacons instanceof ArrayCollection || $beacons instanceof PersistentCollection)
            {
                foreach ($beacons as $beacon)
                {
                    $this->addBeacon($beacon);
                }
            }
        }

        return $this;
    }

    public function getBeacons()
    {
        return $this->beacons;
    }
}