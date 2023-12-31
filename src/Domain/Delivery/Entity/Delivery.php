<?php

namespace App\Domain\Delivery\Entity;

use App\Domain\Commande\Entity\Commande;
use App\Repository\Domain\Delivery\Entity\DeliveryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass:DeliveryRepository::class)]
class Delivery
{

     #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column(type:Types::INTEGER)]
    private int $id;


     #[ORM\Column(type:Types::STRING, length:255)]
    private string $name;


     #[ORM\OneToMany(mappedBy: "delivery", targetEntity: Commande::class)]
    private Collection $commande;

    public function __construct()
    {
        $this->commande = new ArrayCollection();
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

    /**
     * @return Collection<int, Commande>
     */
    public function getCommande(): Collection
    {
        return $this->commande;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commande->contains($commande)) {
            $this->commande[] = $commande;
            $commande->setDelivery($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commande->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getDelivery() === $this) {
                $commande->setDelivery(null);
            }
        }

        return $this;
    }
}
