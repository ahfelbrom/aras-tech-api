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
 * @ORM\Table(name="quizz")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Quizz
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="title", type="string", length=100)
     * 
     * @Assert\NotBlank(message="Please enter a title for the quizz")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "The maximal length for the title is to {{ limit }} characters"
     * )
     *
     * @JMS\Expose
     */
    private $title;

    /**
     * @ORM\Column(name="duration", type="integer")
     * 
     * @Assert\NotBlank(message="Please enter a duration for the quizz")
     *
     * @JMS\Expose
     */
    private $duration;

    /**
     * @ORM\Column(name="color", type="string", length=50)
     * 
     * @Assert\NotBlank(message="Please enter a color for the quizz")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "The maximal length for the color is to {{ limit }} characters"
     * )
     *
     * @JMS\Expose
     */
    private $color;

    /**
     * @ORM\Column(name="difficulty", type="string", length=50)
     * 
     * @Assert\NotBlank(message="Please enter a difficulty for the quizz")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "The maximal length for the difficulty is to {{ limit }} characters"
     * )
     *
     * @JMS\Expose
     */
    private $difficulty;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Question", cascade={"persist", "remove"}, orphanRemoval=true, mappedBy="quizz")
     */
    private $questions;

    /**
     * @var Beacon
     *
     * @ORM\ManyToOne(targetEntity="Beacon", inversedBy="quizzs")
     * @ORM\JoinColumn(nullable=true, name="beacon_id", referencedColumnName="id")
     */
    private $beacon;


    /**
     *
     * Constructor of the Document entity
     *
     */
    public function __construct()
    {
        $this->questions = new ArrayCollection();
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

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getDifficulty()
    {
        return $this->difficulty;
    }

    public function addQuestion(Question $question)
    {
        if (!($this->questions->contains($question)))
        {
            $question->setQuizz($this);
            $this->questions->add($question);
        }

        return $this;
    }

    public function removeQuestion(Question $question)
    {
        if ($this->questions->contains($question))
        {
            $this->questions->removeElement($question);
        }

        return $this;
    }

    public function setQuestions($questions)
    {
        if ($questions instanceof Question)
        {
            $this->addQuestion($questions);
        }
        else
        {
            if ($questions instanceof ArrayCollection || $questions instanceof PersistentCollection)
            {
                foreach ($questions as $question)
                {
                    $this->addQuestion($question);
                }
            }
        }

        return $this;
    }

    public function getQuestions()
    {
        return $this->questions;
    }

    public function setBeacon(Beacon $beacon)
    {
        $this->beacon = $beacon;

        return $this;
    }

    public function getBeacon()
    {
        return $this->beacon;
    }

    public function update($quizz)
    {
        $this
            ->setTitle($quizz->getTitle())
            ->setDuration($quizz->getDuration())
            ->setDifficulty($quizz->getDifficulty())
            ->setColor($quizz->getColor());

        return $this;
    }
}