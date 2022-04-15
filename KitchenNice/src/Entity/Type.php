<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeRepository")
 */
class Type
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Recette", mappedBy="types")
     * @ORM\OrderBy({"date_creation" = "DESC"})
     */
    private $recette;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Membre", mappedBy="types")
     */
    private $membre_abo;

    public function __construct()
    {
        $this->recette = new ArrayCollection();
        $this->membre_abo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Recette[]
     */
    public function getRecette(): Collection
    {
        return $this->recette;
    }

    public function addRecette(Recette $recette): self
    {
        if (!$this->recette->contains($recette)) {
            $this->recette[] = $recette;
        }

        return $this;
    }

    public function removeRecette(Recette $recette): self
    {
        if ($this->recette->contains($recette)) {
            $this->recette->removeElement($recette);
        }

        return $this;
    }

    /**
     * @return Collection|Membre[]
     */
    public function getMembreAbo(): Collection
    {
        return $this->membre_abo;
    }

    public function addMembreAbo(Membre $membreAbo): self
    {
        if (!$this->membre_abo->contains($membreAbo)) {
            $this->membre_abo[] = $membreAbo;
        }

        return $this;
    }

    public function removeMembreAbo(Membre $membreAbo): self
    {
        if ($this->membre_abo->contains($membreAbo)) {
            $this->membre_abo->removeElement($membreAbo);
        }

        return $this;
    }
}
