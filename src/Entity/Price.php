<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PriceRepository")
 */
class Price
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="prices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fournisseur", inversedBy="prices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Fournisseur;

    /**
     * @ORM\Column(type="float")
     */
    private $publicPrice;

    /**
     * @ORM\Column(type="float")
     */
    private $net_price;

    /**
     * @ORM\Column(type="float")
     */
    private $revente_price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->Product;
    }

    public function setProduct(?Product $Product): self
    {
        $this->Product = $Product;

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->Fournisseur;
    }

    public function setFournisseur(?Fournisseur $Fournisseur): self
    {
        $this->Fournisseur = $Fournisseur;

        return $this;
    }

    public function getPublicPrice(): ?float
    {
        return $this->publicPrice;
    }

    public function setPublicPrice(float $publicPrice): self
    {
        $this->publicPrice = $publicPrice;

        return $this;
    }

    public function getNetPrice(): ?float
    {
        return $this->net_price;
    }

    public function setNetPrice(float $net_price): self
    {
        $this->net_price = $net_price;

        return $this;
    }

    public function getReventePrice(): ?float
    {
        return $this->revente_price;
    }

    public function setReventePrice(float $revente_price): self
    {
        $this->revente_price = $revente_price;

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
//        return $this->name;
        // to show the id of the Category in the select
        return $this->id;
    }
}
