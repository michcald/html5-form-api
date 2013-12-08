<?php

namespace Michael\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Michael\AppBundle\Annotations\FormElement;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity
 */
class Article
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @FormElement(
     *      type = "text",
     *      label = "Title",
     *      value = "",
     *      placeholder = "Insert here",
     *      options = {
     *          "max-length" = "10",
     *          "min-length" = "2"
     *      }
     * )
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     *
     * @FormElement(
     *      type = "textarea",
     *      label = "Text",
     *      value = "",
     *      options = {
     *          "max-length" = "10"
     *      }
     * )
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     *
     * @FormElement(
     *      type = "datetime",
     *      label = "Date",
     *      value = ""
     * )
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean")
     *
     * @FormElement(
     *      type = "trigger",
     *      label = "Is published",
     *      value = "false"
     * )
     */
    private $published;

    /**
     * @var integer
     *
     * @ORM\Column(name="views", type="integer")
     *
     * @FormElement(
     *      type = "integer",
     *      label = "Views",
     *      value = "0",
     *      options = {
     *          "max" = "1000",
     *          "min" = "0"
     *      }
     * )
     */
    private $views;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     *
     * @FormElement(
     *      type = "money",
     *      label = "Insert the price",
     *      options = {
     *          "min" = "0"
     *      }
     * )
     */
    private $price;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Article
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return Article
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set views
     *
     * @param integer $views
     * @return Article
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Article
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }
}
