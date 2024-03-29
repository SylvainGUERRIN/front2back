<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Post.
 *
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *     fields={"title"},
 *     message="Une autre veille posséde déjà ce titre, merci de le modifier"
 * )
 * @UniqueEntity(
 *     fields={"slug"},
 *     message="Ce slug est déjà utilisé pour une autre veille."
 * )
 * @Vich\Uploadable()
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected ?string $title;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected ?string $slug;

    /**
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/jpg", "image/png"}
     * )
     * @Vich\UploadableField(mapping="post_image", fileNameProperty="url_image")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected ?string $url_image;

    /**
     * @ORM\Column(type="text")
     */
    protected ?string $excerpt;

    /**
     * @ORM\Column(type="text")
     */
    protected ?string $content;

    /**
     * @ORM\Column(type="text")
     */
    protected ?string $ref_description;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $post_created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $post_modified_at;

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $validatedAt;

    /**
     * @ORM\Column(type="integer")
     */
    protected int $viewsCount = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     */
    protected ?User $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Favorite", mappedBy="post")
     */
    private $favorites;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="posts")
     */
    protected Collection $tag;

    public function __construct()
    {
        $this->tag = new ArrayCollection();
    }

    //getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            $this->updatedAt = new \DateTime('now');
        }

//        return $this;
    }

    public function getUrlImage(): ?string
    {
        return $this->url_image;
    }

    public function setUrlImage(?string $urlImage): self
    {
        $this->url_image = $urlImage;

        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(string $excerpt): self
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRefDescription(): ?string
    {
        return $this->ref_description;
    }

    public function setRefDescription(string $ref_description): self
    {
        $this->ref_description = $ref_description;

        return $this;
    }

    public function getPostCreatedAt(): ?\DateTimeInterface
    {
        return $this->post_created_at;
    }

    public function setPostCreatedAt(\DateTimeInterface $post_created_at): self
    {
        $this->post_created_at = $post_created_at;

        return $this;
    }

    public function getPostModifiedAt(): ?\DateTimeInterface
    {
        return $this->post_modified_at;
    }

    public function setPostModifiedAt(?\DateTimeInterface $post_modified_at): self
    {
        $this->post_modified_at = $post_modified_at;

        return $this;
    }

    public function isValidatedAt(): ?bool
    {
        return $this->validatedAt;
    }

    public function setValidatedAt(bool $validatedAt): self
    {
        $this->validatedAt = $validatedAt;

        return $this;
    }

    public function getViewsCount(): ?int
    {
        return $this->viewsCount;
    }

    public function setViewsCount(int $viewsCount): self
    {
        $this->viewsCount = $viewsCount;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $user): self
    {
        $this->author = $user;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): Comment
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Favorite[]
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): Post
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->setPost($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): Post
    {
        if ($this->favorites->contains($favorite)) {
            $this->favorites->removeElement($favorite);
            // set the owning side to null (unless already changed)
            if ($favorite->getPost() === $this) {
                $favorite->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
            $tag->addPost($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->contains($tag)) {
            $this->tag->removeElement($tag);
            $tag->removePost($this);
        }

        return $this;
    }

//    public function getTag(): ?Tag
//    {
//        return $this->tag;
//    }
//
//    public function setTag(?Tag $tag): self
//    {
//        $this->tag = $tag;
//
//        return $this;
//    }

//    public function getUser(): ?User
//    {
//        return $this->user;
//    }
//
//    public function setUser(?User $user): self
//    {
//        $this->user = $user;
//
//        // set (or unset) the owning side of the relation if necessary
//        $newAvatar = null === $user ? null : $this;
//        if ($user->getAvatar() !== $newAvatar) {
//            $user->setAvatar($newAvatar);
//        }
//
//        return $this;
//    }

    public function __toString()
    {
        return (string) $this->getUrlImage();
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
        $this->slug = $slugify->slugify($this->title);
//        if(empty($this->slug)){
//            $slugify = new Slugify();
//            $this->slug = $slugify->slugify($this->title);
//        }
//        if(!empty($this->slug)){
//            $slugify = new Slugify();
//            $this->slug = $slugify->slugify($this->title);
//        }
    }
}
