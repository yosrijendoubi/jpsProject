<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Presence
 *
 * @ORM\Table(name="presence", indexes={@ORM\Index(name="fk_emp", columns={"id_emp"}), @ORM\Index(name="fk_marche", columns={"id_marche"})})
 * @ORM\Entity
 */
class Presence
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_presence", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPresence;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="etat", type="integer", nullable=false)
     */
    private $etat = '0';

    /**
     * @var \Employe
     *
     * @ORM\ManyToOne(targetEntity="Employe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_emp", referencedColumnName="id_emp")
     * })
     */
    private $idEmp;

    /**
     * @return int
     */
    public function getIdPresence()
    {
        return $this->idPresence;
    }

    /**
     * @param int $idPresence
     */
    public function setIdPresence($idPresence)
    {
        $this->idPresence = $idPresence;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param int $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    /**
     * @return \Employe
     */
    public function getIdEmp()
    {
        return $this->idEmp;
    }

    /**
     * @param \Employe $idEmp
     */
    public function setIdEmp($idEmp)
    {
        $this->idEmp = $idEmp;
    }

    /**
     * @return \Marche
     */
    public function getIdMarche()
    {
        return $this->idMarche;
    }

    /**
     * @param \Marche $idMarche
     */
    public function setIdMarche($idMarche)
    {
        $this->idMarche = $idMarche;
    }

    /**
     * @var \Marche
     *
     * @ORM\ManyToOne(targetEntity="Marche")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_marche", referencedColumnName="id_marche")
     * })
     */
    private $idMarche;


}

