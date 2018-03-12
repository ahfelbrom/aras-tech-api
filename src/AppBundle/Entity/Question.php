<?php

namespace AppBundle\Entity;

// -----------------------------------------------------------------------------

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

// -----------------------------------------------------------------------------


/**
 * @ORM\Entity
 * @ORM\Table(name="question")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Question
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
     * @Assert\NotBlank(message="Please enter a label for the question")
     * @Assert\Length(
     *      max = 100,
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
     * @ORM\OneToMany(targetEntity="Answer", cascade={"persist", "remove"}, orphanRemoval=true, mappedBy="question")
     */
    private $answers;

    /**
     * @var Quizz
     *
     * @ORM\ManyToOne(targetEntity="Quizz", inversedBy="questions")
     * @ORM\JoinColumn(name="quizz_id", referencedColumnName="id")
     */
    private $quizz;


    /**
     *
     * Constructor of the Document entity
     *
     */
    public function __construct()
    {
        $this->answers = new ArrayCollection();
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

    public function addAnswer(Answer $answer)
    {
        if (!($this->answers->contains($answer)))
        {
            $answer->setQuestion($this);
            $this->answers->add($answer);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer)
    {
        if ($this->answers->contains($answer))
        {
            $this->answers->removeElement($answer);
        }

        return $this;
    }

    public function setAnswers($answers)
    {
        if ($answers instanceof Answer)
        {
            $this->addAnswer($answers);
        }
        else
        {
            if ($answers instanceof ArrayCollection || $answers instanceof PersistentCollection)
            {
                foreach ($answers as $answer)
                {
                    $this->addAnswer($answer);
                }
            }
        }

        return $this;
    }

    public function getAnswers()
    {
        return $this->answers;
    }

    public function setQuizz(Quizz $quizz)
    {
        $this->quizz = $quizz;

        return $this;
    }

    public function getQuizz()
    {
        return $this->quizz;
    }
}