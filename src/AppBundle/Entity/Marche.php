<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marche
 *
 * @ORM\Table(name="marche")
 * @ORM\Entity
 */
class Marche
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_marche", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMarche;

    /**
     * @var string
     *
     * @ORM\Column(name="libellle", type="string", length=100, nullable=false)
     */
    private $libellle;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbrrepos", type="integer", nullable=false)
     */
    private $nbrrepos;

    /**
     * @var float
     *
     * @ORM\Column(name="spjfixe", type="float", precision=10, scale=0, nullable=false)
     */
    private $spjfixe;

    /**
     * @var float
     *
     * @ORM\Column(name="spjchef", type="float", precision=10, scale=0, nullable=true)
     */
    private $spjchef;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idEmp = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->libellle;
    }

    /**
     * @return int
     */
    public function getIdMarche()
    {
        return $this->idMarche;
    }

    /**
     * @param int $idMarche
     */
    public function setIdMarche($idMarche)
    {
        $this->idMarche = $idMarche;
    }

    /**
     * @return string
     */
    public function getLibellle()
    {
        return $this->libellle;
    }

    /**
     * @param string $libellle
     */
    public function setLibellle($libellle)
    {
        $this->libellle = $libellle;
    }

    /**
     * @return int
     */
    public function getNbrrepos()
    {
        return $this->nbrrepos;
    }

    /**
     * @param int $nbrrepos
     */
    public function setNbrrepos($nbrrepos)
    {
        $this->nbrrepos = $nbrrepos;
    }

    /**
     * @return float
     */
    public function getSpjfixe()
    {
        return $this->spjfixe;
    }

    /**
     * @param float $spjfixe
     */
    public function setSpjfixe($spjfixe)
    {
        $this->spjfixe = $spjfixe;
    }

    /**
     * @return float
     */
    public function getSpjchef()
    {
        return $this->spjchef;
    }

    /**
     * @param float $spjchef
     */
    public function setSpjchef($spjchef)
    {
        $this->spjchef = $spjchef;
    }

}

