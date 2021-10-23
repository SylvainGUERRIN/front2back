<?php

namespace App\Entity;

use App\Repository\FavoriteRepository;
use Doctrine\ORM\Mapping as ORM;

//use Symfony\Component\Validator\Constraints as Assert;
//use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Comment.
 *
 * @ORM\Entity(repositoryClass=FavoriteRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Favorite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $liked_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="favorites")
     */
    protected ?User $user;

    //post relation (many to one, bind with favorite in post entity)
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="favorites")
     */
    protected ?Post $post;

    //getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLikedAt(): ?\DateTimeInterface
    {
        return $this->liked_at;
    }

    public function setLikedAt(\DateTimeInterface $liked_at): self
    {
        $this->liked_at = $liked_at;

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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->user = $post;

        return $this;
    }
}
