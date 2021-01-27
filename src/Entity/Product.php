<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $crush;

    /**
     * @ORM\Column(type="array")
     */
    private $color_list = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url_image;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $special_offer;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="product", orphanRemoval=true)
     */
    private $reviews_list;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->reviews_list = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getCrush(): ?bool
    {
        return $this->crush;
    }

    public function setCrush(?bool $crush): self
    {
        $this->crush = $crush;

        return $this;
    }

    public function getColorList(): ?array
    {
        return $this->color_list;
    }

    public function setColorList(array $color_list): self
    {
        $this->color_list = $color_list;

        return $this;
    }

    public function getUrlImage(): ?string
    {
        return $this->url_image;
    }

    public function setUrlImage(?string $url_image): self
    {
        $this->url_image = $url_image;

        return $this;
    }

    public function getSpecialOffer(): ?int
    {
        return $this->special_offer;
    }

    public function setSpecialOffer(?int $special_offer): self
    {
        $this->special_offer = $special_offer;

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviewsList(): Collection
    {
        return $this->reviews_list;
    }

    public function addReviewsList(Review $reviewsList): self
    {
        if (!$this->reviews_list->contains($reviewsList)) {
            $this->reviews_list[] = $reviewsList;
            $reviewsList->setProduct($this);
        }

        return $this;
    }

    public function removeReviewsList(Review $reviewsList): self
    {
        if ($this->reviews_list->removeElement($reviewsList)) {
            // set the owning side to null (unless already changed)
            if ($reviewsList->getProduct() === $this) {
                $reviewsList->setProduct(null);
            }
        }

        return $this;
    }

}
