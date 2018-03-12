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
 * @ORM\Table(name="beacon")
 * @UniqueEntity("code")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Beacon
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="code", type="string", length=15)
     *
     * @Assert\NotBlank(message="Please enter a code for the beacon")
     * @Assert\Length(
     * min=15,
     * max=15,
     * minMessage="The code has strictly 15 characters",
     * maxMessage="The code has strictly 15 characters"
     * )
     *
     * @JMS\Expose
     */
    private $code;

    /**
     * @ORM\Column(name="title", type="string", length=100)
     * 
     * @Assert\NotBlank(message="Please enter a title for the beacon")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "The maximal length for the title is to {{ limit }} characters"
     * )
     *
     * @JMS\Expose
     */
    private $title;

    /**
     * @ORM\Column(name="subtitle", type="string", length=100)
     * 
     * @Assert\NotBlank(message="Please enter a subtitle for the beacon")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "The maximal length for the subtitle is to {{ limit }} characters"
     * )
     *
     * @JMS\Expose
     */
    private $subtitle;

    /**
     * @ORM\Column(name="description", type="text")
     * 
     * @Assert\NotBlank(message="Please enter a description for the beacon")
     *
     * @JMS\Expose
     */
    private $description;

    /**
     * 
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Quizz", cascade={"persist", "remove"}, orphanRemoval=true, mappedBy="beacon")
     */
    private $quizzs;

    /**
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="beacons")
     * @ORM\JoinColumn(nullable=true, name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Media", cascade={"persist", "remove"}, orphanRemoval=true, mappedBy="beacon")
     */
    private $medias;


    /**
     *
     * Constructor of the Document entity
     *
     */
    public function __construct()
    {
        $this->quizzs = new ArrayCollection();
        $this->medias = new ArrayCollection();
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

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getSubtitle()
    {
        return $this->subtitle;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function addQuizz(Quizz $quizz)
    {
        if (!($this->quizzs->contains($quizz)))
        {
            $quizz->setBeacon($this);
            $this->quizzs->add($quizz);
        }

        return $this;
    }

    public function removeQuizz(Quizz $quizz)
    {
        if ($this->quizzs->contains($quizz))
        {
            $this->quizzs->removeElement($quizz);
        }

        return $this;
    }

    public function setQuizzs($quizzs)
    {
        if ($quizzs instanceof Quizz)
        {
            $this->addQuizz($quizzs);
        }
        else
        {
            if ($quizzs instanceof ArrayCollection || $quizzs instanceof PersistentCollection)
            {
                foreach ($quizzs as $quizz)
                {
                    $this->addQuizz($quizz);
                }
            }
        }

        return $this;
    }

    public function getQuizzs()
    {
        return $this->quizzs;
    }

    public function setGroup(Group $group)
    {
        $this->group = $group;

        return $this;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function addMedia(Media $media)
    {
        if (!($this->medias->contains($media)))
        {
            $media->setBeacon($this);
            $this->medias->add($media);
        }

        return $this;
    }

    public function removeMedia(Media $media)
    {
        if ($this->medias->contains($media))
        {
            $this->medias->removeElement($media);
        }

        return $this;
    }

    public function setMedias($medias)
    {
        if ($medias instanceof Media)
        {
            $this->addMedia($medias);
        }
        else
        {
            if ($medias instanceof ArrayCollection || $medias instanceof PersistentCollection)
            {
                foreach ($medias as $media)
                {
                    $this->addMedia($media);
                }
            }
        }

        return $this;
    }

    public function getMedias()
    {
        return $this->medias;
    }

    public function update($beacon)
    {
        if ($beacon->getCode() != "AAAAAAAAAAAAAAA")
        {
            $this->setCode($beacon->getCode());
        }
        $this
            ->setTitle($beacon->getTitle())
            ->setSubtitle($beacon->getSubtitle())
            ->setDescription($beacon->getDescription());

        return $this;
    }
}