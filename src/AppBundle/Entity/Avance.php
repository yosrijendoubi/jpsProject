<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avance
 *
 * @ORM\Table(name="avance", indexes={@ORM\Index(name="fk_emp", columns={"id_emp"}), @ORM\Index(name="fk_marche", columns={"id_marche"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AvanceRepository")
 */
class Avance
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @var \Marche
     *
     * @ORM\ManyToOne(targetEntity="Marche")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_marche", referencedColumnName="id_marche")
     * })
     */
    private $idMarche;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float")
     */
    private $montant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idEmp
     *
     * @param integer $idEmp
     *
     * @return Avance
     */
    public function setIdEmp($idEmp)
    {
        $this->idEmp = $idEmp;

        return $this;
    }

    /**
     * Get idEmp
     *
     * @return int
     */
    public function getIdEmp()
    {
        return $this->idEmp;
    }

    /**
     * Set idMarche
     *
     * @param integer $idMarche
     *
     * @return Avance
     */
    public function setIdMarche($idMarche)
    {
        $this->idMarche = $idMarche;

        return $this;
    }

    /**
     * Get idMarche
     *
     * @return int
     */
    public function getIdMarche()
    {
        return $this->idMarche;
    }

    /**
     * Set montant
     *
     * @param float $montant
     *
     * @return Avance
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return float
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Avance
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
}

