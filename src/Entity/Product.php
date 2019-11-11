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
     * @ORM\ManyToOne(targetEntity="App\Entity\Fournisseur", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_fournisseur;

    public function __construct()
    {
        $this->id_client = new ArrayCollection();
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

    public function getIdFournisseur(): ?Fournisseur
    {
        return $this->id_fournisseur;
    }

    public function setIdFournisseur(?Fournisseur $id_fournisseur): self
    {
        $this->id_fournisseur = $id_fournisseur;

        return $this;
    }

}
