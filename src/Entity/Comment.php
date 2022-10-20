<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\CommentRepository;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content_comment;

    /**
     * @ORM\Column(type="date")
     */
    private $create_comment;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="comments")
     * @JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $events;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentComment(): ?string
    {
        return $this->content_comment;
    }

    public function setContentComment(string $content_comment): self
    {
        $this->content_comment = $content_comment;

        return $this;
    }

    public function getCreateComment(): ?\DateTimeInterface
    {
        return $this->create_comment;
    }

    public function setCreateComment(\DateTimeInterface $create_comment): self
    {
        $this->create_comment = $create_comment;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getEvents(): ?Event
    {
        return $this->events;
    }

    public function setEvents(?Event $events): self
    {
        $this->events = $events;

        return $this;
    }
}
