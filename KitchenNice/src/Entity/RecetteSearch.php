<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

class RecetteSearch
{
    /**
     * @var string|null
     */
    private $titre;

    /**
     * @var string|null
     */
    private $difficulte;

    /**
     * @var string|null
     */
    private $cout;

    /**
     * @var Collection|Ingredient[]
     */
    private $ingredients;

    /**
     * @var Collection|Type[]
     */
    private $types;

    /**
     * @return string|null
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * @param string|null $titre
     */
    public function setTitre(?string $titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @return string|null
     */
    public function getDifficulte(): ?string
    {
        return $this->difficulte;
    }

    /**
     * @param string|null $difficulte
     */
    public function setDifficulte(?string $difficulte): void
    {
        $this->difficulte = $difficulte;
    }

    /**
     * @return string|null
     */
    public function getCout(): ?string
    {
        return $this->cout;
    }

    /**
     * @param string|null $cout
     */
    public function setCout(?string $cout): void
    {
        $this->cout = $cout;
    }

    /**
     * @return Ingredient[]|Collection
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * @param Ingredient[]|Collection $ingredients
     */
    public function setIngredients($ingredients): void
    {
        $this->ingredients = $ingredients;
    }

    /**
     * @return Type[]|Collection
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param Type[]|Collection $types
     */
    public function setTypes($types): void
    {
        $this->types = $types;
    }

}