<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avance
 *
 * @ORM\Table(name="affectation", indexes={@ORM\Index(name="fk_agent", columns={"id_agent_rotation"}), @ORM\Index(name="fk_marche", columns={"id_marche"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AvanceRepository")
 */
class Affectation_Agent_Rotation
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
     * @var \Agent_de_rotation
     *
     * @ORM\ManyToOne(targetEntity="Agent_de_rotation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_agent_rotation", referencedColumnName="id")
     * })
     */
    private $idAgent;

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
     * @return \Agent_de_rotation
     */
    public function getIdAgent()
    {
        return $this->idAgent;
    }

    /**
     * @param \Agent_de_rotation $idAgent
     */
    public function setIdAgent($idAgent)
    {
        $this->idAgent = $idAgent;
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

