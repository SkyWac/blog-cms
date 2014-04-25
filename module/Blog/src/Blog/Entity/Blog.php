<?php

namespace Blog\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Blog entity & form creator using annotation
 *
 * @ORM\Entity
 * @ORM\Table(name="blog")
 * @Annotation\Name("blog")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 *
 * @author Camille Greselle <camille.greselle@epitech.eu>
 */

class Blog
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Annotation\Exclude()
     */
    public $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":"5"}})
     * @Annotation\Options({"label":"Blog Name:", "id":"titre"})
     */
    public $titre;

    /**
     * @var string
     * @ORM\Column(type="string", length=1000, unique=false, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":"5"}})
     * @Annotation\Options({"label":"Description:",
     *                      "class":"form-control"})
     */
    public $description;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Validator({"name":"regex", 
     * "options":{
     *   "pattern":"/^[a-z]+(\-[a-z]+)*$/",
     *   "messages":{
     *       "regexInvalid":"The regex is not correct",
     *       "regexNotMatch": "The slug is invalid",
     *       "regexErrorous": "Dunno what happened"
     *   }
     * }})
     * @Annotation\Options({"label":"Slug:"})
     */
    public $slug;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     * @Annotation\Type("Zend\Form\Element\Radio")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Status:",
     *                      "value_options" : {"0":"Offline","1":"Online"},
     *                      "class" : "form-control"})
     */
    public $online;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\JoinTable(name="users",
     *      joinColumns={@ORM\JoinColumn(name="id", referencedColumnName="id")})
     * @Annotation\Exclude()
     */
    public $ownerId;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Create my Blog"})
     */
    public $submit;

    public function attach($data)
    {
        foreach($data as $key => $value) {
            if(!empty($value))
                $this->$key = $value;
        }
    }

    /**
     * @return array
     * Get an Entity Blog and returns a converted array of entity's attributes to parse it to the form
     */

    public function blogToArray($blog)
    {
        foreach($blog as $key => $value)
            $arr[$key] = $value;
        $arr['submit'] = 'Submit Changes';
        return $arr;
    }
}
