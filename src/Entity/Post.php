<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Post.
 *
 * @ORM\Entity(repositoryClass=PostRepository::class)
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
     * @ORM\Column(type="string", length=255)
     */
    protected ?string $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected ?string $slug;

    /**
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/jpg", "image/png"}
     * )
     * @Vich\UploadableField(mapping="avatar_image", fileNameProperty="url_image")
     */
    private ?File $imageFile;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     */
    protected ?User $author;

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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $user): self
    {
        $this->author = $user;

        return $this;
    }

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
}
