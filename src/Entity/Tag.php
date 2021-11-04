<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Tag.
 *
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected ?string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected ?string $slug;

    /**
     * @ORM\Column(type="text")
     */
    protected ?string $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="Tag")
     */
    private $posts;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Stats", inversedBy="tag", cascade={"persist", "remove"})
     */
    protected ?Stats $stats;

    //getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setTag($this);
        }

        return $this;
    }

    public function removePost(Post $post): Post
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getTag() === $this) {
                $post->setTag(null);
            }
        }

        return $this;
    }

    public function getStats(): ?Stats
    {
        return $this->stats;
    }

    public function setStats(?Stats $stats): self
    {
        $this->stats = $stats;

        return $this;
    }

    /**
     * To initialize slug on persist or update.
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function initializeSlug(): void
    {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->name);
    }
}
