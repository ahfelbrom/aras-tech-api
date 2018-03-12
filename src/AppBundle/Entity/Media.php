<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


// -----------------------------------------------------------------------------

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

// -----------------------------------------------------------------------------

use JMS\Serializer\Annotation as JMS;

// -----------------------------------------------------------------------------


/**
 * @ORM\Entity
 * @ORM\Table(name="media")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Media
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="label", type="string", length=100)
     *
     * @Assert\NotBlank(message="Please enter a label for the media")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "The maximal length for the label is to {{ limit }} characters"
     * )
     */
    protected $label;

    /**
     * @ORM\Column(name="weight", type="integer")
     * 
     * @Assert\NotBlank(message="Please enter a weight for the media")
     *
     * @JMS\Expose
     */
    private $weight;

    /**
     * @ORM\Column(name="link", type="string", length=100)
     * 
     * @Assert\NotBlank(message="Please enter a link for the media")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "The maximal length for the link is to {{ limit }} characters"
     * )
     *
     * @JMS\Expose
     */
    private $link;

    /**
     * @var Beacon
     *
     * @ORM\ManyToOne(targetEntity="Beacon", inversedBy="medias")
     * @ORM\JoinColumn(name="beacon_id", referencedColumnName="id")
     */
    private $beacon;

    /**
     *
     * Constructor of the Document entity
     *
     */
    public function __construct()
    {
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

    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setBeacon($beacon)
    {
        $this->beacon = $beacon;

        return $this;
    }

    public function getBeacon()
    {
        return $this->beacon;
    }
}