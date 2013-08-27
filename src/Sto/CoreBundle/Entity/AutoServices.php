<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AutoServices
 *
 * @ORM\Entity()
 */
class AutoServices
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="short_name", type="string", length=15, nullable=true)
     */
    private $shortName;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AutoServices", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="AutoServices", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\CompanyType", cascade={"persist"})
     * @ORM\JoinTable(name="company_type_auto_service",
     *     joinColumns={@ORM\JoinColumn(name="auto_service_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="company_type_id", referencedColumnName="id")}
     * )
     */
    private $companyType;

    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\Company")
     */
    private $companies;

    /**
     * @ORM\ManyToMany(targetEntity="\Sto\CoreBundle\Entity\Deal")
     */
    private $deals;

    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->companyType = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param  string $code
     * @return AutoServices
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * Get name
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return AutoServices
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set parentId
     *
     * @param  integer $parentId
     * @return AutoServices
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set parent
     */
    public function setParent(AutoServices $parent = null)
    {
        $this->parent = $parent;
        if ($parent != null) {
            $this->parentId = $parent->getId();
        }

        return $this;
    }

    /**
     * Get parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     */
    public function addChildren(AutoServices $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     */
    public function removeChildren(AutoServices $children)
    {
        $this->children->removeElement($children);

        return $this;
    }

    /**
     * Get children
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * set Position
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * get Position
     */
    public function getPosition()
    {
        return $this->position;
    }

    public function setCode($value)
    {
        $this->code = $value;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCompanyType($value)
    {
        $this->companyType = $value;

        return $this;
    }

    public function getCompanyType()
    {
        return $this->companyType;
    }

    public function addCompanyType($value)
    {
        $this->companyType[] = $value;

        return $this;
    }

    public function removeCompanyType($value)
    {
        $this->companyType->removeElement($value);

        return $this;
    }
}