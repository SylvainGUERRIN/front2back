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

    //user relation

    //post relation

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
}
