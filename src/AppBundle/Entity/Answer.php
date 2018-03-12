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
 * @ORM\Table(name="answer")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Answer
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="label", type="string", length=150)
     * 
     * @Assert\NotBlank(message="Please enter a label for the answer")
     * @Assert\Length(
     *      max = 150,
     *      maxMessage = "The maximal length for the answer is to {{ limit }} characters"
     * )
     *
     * @JMS\Expose
     */
    private $label;

    /**
     * @ORM\Column(name="is_right", type="boolean")
     * 
     * @Assert\NotBlank(message="Please enter a boolean value if the answer is the correct one")
     *
     * @JMS\Expose
     */
    private $isRight;

    /**
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="answers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;


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

    public function setRight($isRight)
    {
        $this->isRight = $isRight;

        return $this;
    }

    public function isRight()
    {
        return $this->isRight;
    }

    public function setQuestion(Question $question)
    {
        $this->question = $question;

        return $this;
    }

    public function getQuestion()
    {
        return $this->question;
    }
}