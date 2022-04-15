<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecetteRepository")
 * @Vich\Uploadable()
 */
class Recette
{

    /**
     * @CaptchaAssert\ValidCaptcha(
     *      message = "Erreur dans la validation du CAPTCHA, veuillez réessayer."
     * )
     */
    protected $captchaCode;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_nom;

    /**
     * @Assert\Image(
     *     mimeTypes="image/jpeg"
     * )
     * @var File|null
     * @Vich\UploadableField(mapping="recette_image", fileNameProperty="image_nom")
     */
    private $image_fichier;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="time")
     */
    private $duree_preparation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $difficulte;

    /**
     * @ORM\Column(type="time")
     */
    private $duree_cuisson;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(0)
     */
    private $nb_personnes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cout;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remarque;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_private;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_derniere_modif;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Etape", mappedBy="recette", orphanRemoval=true, cascade={"persist"})
     */
    private $etapes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ustensil", inversedBy="recette")
     */
    private $ustensils;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quantite", mappedBy="recette", orphanRemoval=true, cascade={"persist"})
     */
    private $quantites;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Type", inversedBy="recette")
     */
    private $types;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Allergene", inversedBy="recette")
     */
    private $allergenes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Menu", mappedBy="recette")
     */
    private $menus;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Membre", inversedBy="recettes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Cahier", inversedBy="recettes")
     */
    private $cahiers;

    public function __construct()
    {
        $this->etapes = new ArrayCollection();
        $this->ustensils = new ArrayCollection();
        $this->quantites = new ArrayCollection();
        $this->types = new ArrayCollection();
        $this->allergenes = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->date_creation = new \DateTime();
        $this->date_derniere_modif = $this->date_creation;
        $this->cahiers = new ArrayCollection();
    }

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDureePreparation(): ?\DateTimeInterface
    {
        return $this->duree_preparation;
    }

    public function setDureePreparation(\DateTimeInterface $duree_preparation): self
    {
        $this->duree_preparation = $duree_preparation;

        return $this;
    }

    public function getDifficulte(): ?string
    {
        return $this->difficulte;
    }

    public function setDifficulte(string $difficulte): self
    {
        $this->difficulte = $difficulte;

        return $this;
    }

    public function getDureeCuisson(): ?\DateTimeInterface
    {
        return $this->duree_cuisson;
    }

    public function setDureeCuisson(\DateTimeInterface $duree_cuisson): self
    {
        $this->duree_cuisson = $duree_cuisson;

        return $this;
    }

    public function getNbPersonnes(): ?int
    {
        return $this->nb_personnes;
    }

    public function setNbPersonnes(int $nb_personnes): self
    {
        $this->nb_personnes = $nb_personnes;

        return $this;
    }

    public function getCout(): ?string
    {
        return $this->cout;
    }

    public function setCout(string $cout): self
    {
        $this->cout = $cout;

        return $this;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(string $remarque): self
    {
        $this->remarque = $remarque;

        return $this;
    }

    public function getIsPrivate(): ?bool
    {
        return $this->is_private;
    }

    public function setIsPrivate(bool $is_private): self
    {
        $this->is_private = $is_private;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateDerniereModif(): ?\DateTimeInterface
    {
        return $this->date_derniere_modif;
    }

    public function setDateDerniereModif(\DateTimeInterface $date_derniere_modif): self
    {
        $this->date_derniere_modif = $date_derniere_modif;

        return $this;
    }

    /**
     * @return Collection|Etape[]
     */
    public function getEtapes(): Collection
    {
        return $this->etapes;
    }

    public function addEtape(Etape $etape): self
    {
        if (!$this->etapes->contains($etape)) {
            $this->etapes[] = $etape;
            $etape->setRecette($this);
        }

        return $this;
    }

    public function removeEtape(Etape $etape): self
    {
        if ($this->etapes->contains($etape)) {
            $this->etapes->removeElement($etape);
            // set the owning side to null (unless already changed)
            if ($etape->getRecette() === $this) {
                $etape->setRecette(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ustensil[]
     */
    public function getUstensils(): Collection
    {
        return $this->ustensils;
    }

    public function addUstensil(Ustensil $ustensil): self
    {
        if (!$this->ustensils->contains($ustensil)) {
            $this->ustensils[] = $ustensil;
            $ustensil->addRecette($this);
        }

        return $this;
    }

    public function removeUstensil(Ustensil $ustensil): self
    {
        if ($this->ustensils->contains($ustensil)) {
            $this->ustensils->removeElement($ustensil);
            $ustensil->removeRecette($this);
        }

        return $this;
    }

    /**
     * @return Collection|Quantite[]
     */
    public function getQuantites(): Collection
    {
        return $this->quantites;
    }

    public function addQuantite(Quantite $quantite): self
    {
        if (!$this->quantites->contains($quantite)) {
            $this->quantites[] = $quantite;
            $quantite->setRecette($this);
        }

        return $this;
    }

    public function removeQuantite(Quantite $quantite): self
    {
        if ($this->quantites->contains($quantite)) {
            $this->quantites->removeElement($quantite);
            // set the owning side to null (unless already changed)
            if ($quantite->getRecette() === $this) {
                $quantite->setRecette(null);
            }
        }

        return $this;
    }

    public function quantitesToString(): string
    {
        $string = "";
        $quantites = $this->getQuantites();
        foreach ($quantites as $qte)
        {
            $string = $string.$qte->getIngredient()->getNom()." / ";
        }
        return $string;
    }

    /**
     * @return Collection|Type[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
            $type->addRecette($this);
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        if ($this->types->contains($type)) {
            $this->types->removeElement($type);
            $type->removeRecette($this);
        }

        return $this;
    }

    /**
     * @return Collection|Allergene[]
     */
    public function getAllergenes(): Collection
    {
        return $this->allergenes;
    }

    public function addAllergene(Allergene $allergene): self
    {
        if (!$this->allergenes->contains($allergene)) {
            $this->allergenes[] = $allergene;
            $allergene->addRecette($this);
        }

        return $this;
    }

    public function removeAllergene(Allergene $allergene): self
    {
        if ($this->allergenes->contains($allergene)) {
            $this->allergenes->removeElement($allergene);
            $allergene->removeRecette($this);
        }

        return $this;
    }

    /**
     * @return Collection|Menu[]
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->addRecette($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->contains($menu)) {
            $this->menus->removeElement($menu);
            $menu->removeRecette($this);
        }

        return $this;
    }

    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    public function setMembre(?Membre $membre): self
    {
        $this->membre = $membre;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageNom(): ?string
    {
        return $this->image_nom;
    }

    /**
     * @param string|null $image_nom
     * @return Recette
     */
    public function setImageNom(?string $image_nom): Recette
    {
        $this->image_nom = $image_nom;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFichier(): ?File
    {
        return $this->image_fichier;
    }

    /**
     * @param File|null $image_fichier
     * @return Recette
     * @throws \Exception
     */
    public function setImageFichier(?File $image_fichier): Recette
    {
        $this->image_fichier = $image_fichier;
        if ($this->image_fichier instanceof UploadedFile)
        {
            $this->date_derniere_modif = new \DateTime();
        }
        return $this;
    }

    /**
     * @return Collection|Cahier[]
     */
    public function getCahiers(): Collection
    {
        return $this->cahiers;
    }

    public function addCahier(Cahier $cahier): self
    {
        if (!$this->cahiers->contains($cahier)) {
            $this->cahiers[] = $cahier;
            $cahier->addRecette($this);
        }

        return $this;
    }

    public function removeCahier(Cahier $cahier): self
    {
        if ($this->cahiers->contains($cahier)) {
            $this->cahiers->removeElement($cahier);
            $cahier->removeRecette($this);
        }

        return $this;
    }


}