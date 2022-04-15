<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvitationRepository")
 */
class Invitation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Membre", inversedBy="invitation_demandee")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre_demandeur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Membre", inversedBy="invitation_recue")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre_receveur;

    public function __construct()
    {
        $this->etat = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getMembreDemandeur(): ?Membre
    {
        return $this->membre_demandeur;
    }

    public function setMembreDemandeur(?Membre $membre_demandeur): self
    {
        $this->membre_demandeur = $membre_demandeur;

        return $this;
    }

    public function getMembreReceveur(): ?Membre
    {
        return $this->membre_receveur;
    }

    public function setMembreReceveur(?Membre $membre_receveur): self
    {
        $this->membre_receveur = $membre_receveur;

        return $this;
    }
}
