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
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=100, nullable=false)
     */
    private $adresse;

    /**
     * @var integer
     *
     * @ORM\Column(name="tel", type="integer", nullable=false)
     */
    private $tel;

    /**
     * Constructor
     */
    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=100, nullable=false)
     */
    private $logo;
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

    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return int
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param int $tel
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getIdEmp()
    {
        return $this->idEmp;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $idEmp
     */
    public function setIdEmp($idEmp)
    {
        $this->idEmp = $idEmp;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

}

