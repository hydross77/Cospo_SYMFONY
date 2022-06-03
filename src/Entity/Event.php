<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $title_event;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_places;

    /**
     * @ORM\Column(type="text")
     */
    private $content_event;

    /**
     * @ORM\Column(type="date")
     */
    private $date_event;

    /**
     * @ORM\Column(type="datetime")
     */
    private $heure_start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_end;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="events")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Level::class, inversedBy="events")
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity=Sport::class, inversedBy="events")
     */
    private $sport;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="participate")
     */
    private $participants;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="events")
     */
    private $comments;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleEvent(): ?string
    {
        return $this->title_event;
    }

    public function setTitleEvent(string $title_event): self
    {
        $this->title_event = $title_event;

        return $this;
    }

    public function getNbPlaces(): ?int
    {
        return $this->nb_places;
    }

    public function setNbPlaces(int $nb_places): self
    {
        $this->nb_places = $nb_places;

        return $this;
    }

    public function getContentEvent(): ?string
    {
        return $this->content_event;
    }

    public function setContentEvent(string $content_event): self
    {
        $this->content_event = $content_event;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->date_event;
    }

    public function setDateEvent(\DateTimeInterface $date_event): self
    {
        $this->date_event = $date_event;

        return $this;
    }

    public function getHeureStart(): ?\DateTimeInterface
    {
        return $this->heure_start;
    }

    public function setHeureStart(\DateTimeInterface $heure_start): self
    {
        $this->heure_start = $heure_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getSport(): ?Sport
    {
        return $this->sport;
    }

    public function setSport(?Sport $sport): self
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->addParticipate($this);
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeParticipate($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setEvents($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getEvents() === $this) {
                $comment->setEvents(null);
            }
        }

        return $this;
    }
}
