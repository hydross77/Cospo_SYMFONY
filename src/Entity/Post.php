<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
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
    private $content_post;

    /**
     * @ORM\Column(type="date")
     */
    private $create_post;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="post")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentPost(): ?string
    {
        return $this->content_post;
    }

    public function setContentPost(string $content_post): self
    {
        $this->content_post = $content_post;

        return $this;
    }

    public function getCreatePost(): ?\DateTimeInterface
    {
        return $this->create_post;
    }

    public function setCreatePost(\DateTimeInterface $create_post): self
    {
        $this->create_post = $create_post;

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
}
