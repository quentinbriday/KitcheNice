<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mgilet\NotificationBundle\Annotation\Notifiable;
use Mgilet\NotificationBundle\NotifiableInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MembreRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("mail")
 * @Notifiable(name="Membre")
 */
class Membre implements UserInterface, \Serializable, NotifiableInterface
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
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(mode="html5")
     */
    private $mail;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_derniere_modif;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Type", inversedBy="membre_abo")
     */
    private $types;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Menu", mappedBy="membre", orphanRemoval=true)
     */
    private $menus;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Recette", mappedBy="membre", orphanRemoval=true)
     * @ORM\OrderBy({"date_creation" = "DESC"})
     */
    private $recettes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Membre", inversedBy="membresAbonnes")
     */
    private $abonnements;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Membre", mappedBy="abonnements")
     */
    private $membresAbonnes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invitation", mappedBy="membre_demandeur", orphanRemoval=true, cascade={"persist"})
     */
    private $invitation_demandee;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invitation", mappedBy="membre_receveur", orphanRemoval=true, cascade={"persist"})
     */
    private $invitation_recue;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cahier", mappedBy="membre", orphanRemoval=true)
     */
    private $cahiers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MessagePrive", mappedBy="envoyeur", orphanRemoval=true)
     */
    private $messagePrives;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Conversation", mappedBy="membre1", orphanRemoval=true)
     */
    private $conversations;

    public function __construct()
    {
        $this->types = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->date_creation = new \DateTime();
        $this->date_derniere_modif = new \DateTime();
        $this->recettes = new ArrayCollection();
        $this->abonnements = new ArrayCollection();
        $this->membresAbonnes = new ArrayCollection();
        $this->amis = new ArrayCollection();
        $this->membresAmis = new ArrayCollection();
        $this->invitation_demandee = new ArrayCollection();
        $this->invitation_recue = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->cahiers = new ArrayCollection();
        $this->messagePrives = new ArrayCollection();
        $this->conversations = new ArrayCollection();
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

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
            $type->addMembreAbo($this);
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        if ($this->types->contains($type)) {
            $this->types->removeElement($type);
            $type->removeMembreAbo($this);
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
            $menu->setMembre($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->contains($menu)) {
            $this->menus->removeElement($menu);
            // set the owning side to null (unless already changed)
            if ($menu->getMembre() === $this) {
                $menu->setMembre(null);
            }
        }

        return $this;
    }

    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->username,
                $this->password,
            ]
        );
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|Recette[]
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): self
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes[] = $recette;
            $recette->setMembre($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): self
    {
        if ($this->recettes->contains($recette)) {
            $this->recettes->removeElement($recette);
            // set the owning side to null (unless already changed)
            if ($recette->getMembre() === $this) {
                $recette->setMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getAbonnements(): Collection
    {
        return $this->abonnements;
    }

    public function addAbonnement(self $abonnement): bool
    {
        if (!$this->abonnements->contains($abonnement)) {
            $this->abonnements[] = $abonnement;
            return true;
        }

        return false;
    }

    public function removeAbonnement(self $abonnement): bool
    {
        if ($this->abonnements->contains($abonnement)) {
            $this->abonnements->removeElement($abonnement);
            return true;
        }

        return false;
    }

    public function hasAbonnement($username): bool
    {
        foreach ($this->getAbonnements() as $abonnement)
        {
            if ($abonnement->getUsername() == $username) return true;
        }
        return false;
    }

    /**
     * @return Collection|self[]
     */
    public function getMembresAbonnes(): Collection
    {
        return $this->membresAbonnes;
    }

    public function addMembresAbonne(self $membresAbonne): self
    {
        if (!$this->membresAbonnes->contains($membresAbonne)) {
            $this->membresAbonnes[] = $membresAbonne;
            $membresAbonne->addAbonnement($this);
        }

        return $this;
    }

    public function removeMembresAbonne(self $membresAbonne): self
    {
        if ($this->membresAbonnes->contains($membresAbonne)) {
            $this->membresAbonnes->removeElement($membresAbonne);
            $membresAbonne->removeAbonnement($this);
        }

        return $this;
    }

    /**
     * @return Collection|Invitation[]
     */
    public function getInvitationDemandee(): Collection
    {
        return $this->invitation_demandee;
    }

    public function addInvitationDemandee(Invitation $invitationDemandee): self
    {
        if (!$this->invitation_demandee->contains($invitationDemandee)) {
            $this->invitation_demandee[] = $invitationDemandee;
            $invitationDemandee->setMembreDemandeur($this);
        }

        return $this;
    }

    public function removeInvitationDemandee(Invitation $invitationDemandee): self
    {
        if ($this->invitation_demandee->contains($invitationDemandee)) {
            $this->invitation_demandee->removeElement($invitationDemandee);
            // set the owning side to null (unless already changed)
            if ($invitationDemandee->getMembreDemandeur() === $this) {
                $invitationDemandee->setMembreDemandeur(null);
            }
        }

        return $this;
    }

    public function hasInvitationSend($username): bool
    {
        foreach ($this->getInvitationDemandee() as $invitation)
        {
            if ($invitation->getMembreReceveur()->getUsername() == $username && $invitation->getEtat() == 0) return true;
        }
        return false;
    }

    public function hasAsFriend($username): bool
    {
        foreach ($this->getInvitationDemandee() as $invitation)
        {
            if ($invitation->getMembreReceveur()->getUsername() == $username && $invitation->getEtat() == 1) return true;
        }
        return false;
    }

    public function hasValidateInvitationDemandee(): bool
    {
        foreach ($this->getInvitationDemandee() as $invitation)
        {
            if ($invitation->getEtat() == 1) return true;
        }
        return false;
    }

    /**
     * @return Collection|Invitation[]
     */
    public function getInvitationRecue(): Collection
    {
        return $this->invitation_recue;
    }

    public function addInvitationRecue(Invitation $invitationRecue): self
    {
        if (!$this->invitation_recue->contains($invitationRecue)) {
            $this->invitation_recue[] = $invitationRecue;
            $invitationRecue->setMembreReceveur($this);
        }

        return $this;
    }

    public function removeInvitationRecue(Invitation $invitationRecue): self
    {
        if ($this->invitation_recue->contains($invitationRecue)) {
            $this->invitation_recue->removeElement($invitationRecue);
            // set the owning side to null (unless already changed)
            if ($invitationRecue->getMembreReceveur() === $this) {
                $invitationRecue->setMembreReceveur(null);
            }
        }

        return $this;
    }

    public function hasInvitationReceive($username): bool
    {
        foreach ($this->getInvitationRecue() as $invitation)
        {
            if ($invitation->getMembreDemandeur()->getUsername() == $username && $invitation->getEtat() == 0) return true;
        }
        return false;
    }

    public function getInvitationId($username): int
    {
        foreach ($this->getInvitationRecue() as $invitation)
        {
            if ($invitation->getMembreDemandeur()->getUsername() == $username && $invitation->getEtat() == 0) return $invitation->getId();
        }
        return -1;
    }

    public function hasValidateInvitationRecue(): bool
    {
        foreach ($this->getInvitationRecue() as $invitation)
        {
            if ($invitation->getEtat() == 1) return true;
        }
        return false;
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
            $cahier->setMembre($this);
        }

        return $this;
    }

    public function removeCahier(Cahier $cahier): self
    {
        if ($this->cahiers->contains($cahier)) {
            $this->cahiers->removeElement($cahier);
            // set the owning side to null (unless already changed)
            if ($cahier->getMembre() === $this) {
                $cahier->setMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MessagePrive[]
     */
    public function getMessagePrives(): Collection
    {
        return $this->messagePrives;
    }

    public function addMessagePrife(MessagePrive $messagePrife): self
    {
        if (!$this->messagePrives->contains($messagePrife)) {
            $this->messagePrives[] = $messagePrife;
            $messagePrife->setEnvoyeur($this);
        }

        return $this;
    }

    public function removeMessagePrife(MessagePrive $messagePrife): self
    {
        if ($this->messagePrives->contains($messagePrife)) {
            $this->messagePrives->removeElement($messagePrife);
            // set the owning side to null (unless already changed)
            if ($messagePrife->getEnvoyeur() === $this) {
                $messagePrife->setEnvoyeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->setMembre1($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->contains($conversation)) {
            $this->conversations->removeElement($conversation);
            // set the owning side to null (unless already changed)
            if ($conversation->getMembre1() === $this) {
                $conversation->setMembre1(null);
            }
        }

        return $this;
    }
}
