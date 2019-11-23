<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Client", inversedBy="products")
     */
    private $id_client;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Price", mappedBy="Product", orphanRemoval=true)
     */
    private $prices;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Documentation", mappedBy="product", orphanRemoval=true)
     */
    private $documentations;



    public function __construct()
    {
        $this->id_client = new ArrayCollection();
        $this->fournisseurs = new ArrayCollection();
        $this->prices = new ArrayCollection();
        $this->documentations = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Client[]
     */

    public function getIdClient(): Collection
    {
        return $this->id_client;
    }

    public function addIdClient(Client $idClient): self
    {
        if (!$this->id_client->contains($idClient)) {
            $this->id_client[] = $idClient;
        }

        return $this;
    }

    public function removeIdClient(Client $idClient): self
    {
        if ($this->id_client->contains($idClient)) {
            $this->id_client->removeElement($idClient);
        }

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->name;
        // to show the id of the Category in the select
//        return $this->id;
    }

    /**
     * @return Collection|Price[]
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(Price $price): self
    {
        if (!$this->prices->contains($price)) {
            $this->prices[] = $price;
            $price->setProduct($this);
        }

        return $this;
    }

    public function removePrice(Price $price): self
    {
        if ($this->prices->contains($price)) {
            $this->prices->removeElement($price);
            // set the owning side to null (unless already changed)
            if ($price->getProduct() === $this) {
                $price->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Documentation[]
     */
    public function getDocumentations(): Collection
    {
        return $this->documentations;
    }

    public function addDocumentation(Documentation $documentation): self
    {
        if (!$this->documentations->contains($documentation)) {
            $this->documentations[] = $documentation;
            $documentation->setProduct($this);
        }

        return $this;
    }

    public function removeDocumentation(Documentation $documentation): self
    {
        if ($this->documentations->contains($documentation)) {
            $this->documentations->removeElement($documentation);
            // set the owning side to null (unless already changed)
            if ($documentation->getProduct() === $this) {
                $documentation->setProduct(null);
            }
        }

        return $this;
    }
}
