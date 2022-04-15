<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConversationRepository")
 */
class Conversation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Membre", inversedBy="conversations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Membre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre2;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MessagePrive", mappedBy="conversation", orphanRemoval=true)
     */
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
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

    public function getMembre1(): ?Membre
    {
        return $this->membre1;
    }

    public function setMembre1(?Membre $membre1): self
    {
        $this->membre1 = $membre1;

        return $this;
    }

    public function getMembre2(): ?Membre
    {
        return $this->membre2;
    }

    public function setMembre2(?Membre $membre2): self
    {
        $this->membre2 = $membre2;

        return $this;
    }

    /**
     * @return Collection|MessagePrive[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(MessagePrive $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(MessagePrive $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }
}
