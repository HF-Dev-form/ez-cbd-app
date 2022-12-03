<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdressRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AdressRepository::class)
 */
class Adress
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="adresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     *  
     *
     * @Assert\NotBlank(
     *  message= "Ce champs est requis."
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * 
     * 
     * @Assert\NotBlank(
     *  message= "Ce champs est requis."
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     *    
     * @Assert\NotBlank(
     *  message= "Ce champs est requis."
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company;

    /**
     *
     *
     * @Assert\NotBlank(
     *  message= "Ce champs est requis."
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

    /**
     *
     *
     * @Assert\NotBlank(
     *  message= "Ce champs est requis."
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $postal;

    /**
     *
     *
     * @Assert\NotBlank(
     *  message= "Ce champs est requis."
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     *
     *
     * @Assert\NotBlank(
     *  message= "Ce champs est requis."
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @Assert\Regex("/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/")
     *
     * @Assert\NotBlank(
     *  message= "Ce champs est requis."
     * )   
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPostal(): ?string
    {
        return $this->postal;
    }

    public function setPostal(string $postal): self
    {
        $this->postal = $postal;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function __toString()
    {
        return $this->getName().' - '.$this->getAdress().' '.$this->getPostal().' '.$this->getCity();
    }
}
