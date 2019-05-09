<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CspViolationRepository")
 */
class CspViolation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $violatedDirective;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $documentUri;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $blockedUri;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $route;

    /**
     * @ORM\Column(type="bigint")
     */
    private $count;

    /**
     * @ORM\Column(type="datetime")
     */
    private $firstViolationAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastViolationAt;

    /**
     * CspViolation constructor.
     * @param array $violationRawData
     * @throws Exception
     */
    public function __construct(array $violationRawData)
    {
        $this->violatedDirective = $violationRawData['violated-directive'];
        $this->documentUri = $violationRawData['document-uri'];
        $this->blockedUri = $violationRawData['blocked-uri'];
        $this->route = $violationRawData['route'];
        $this->count = 1;
        $this->firstViolationAt = new \DateTime();
        $this->lastViolationAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getViolatedDirective(): ?string
    {
        return $this->violatedDirective;
    }

    public function setViolatedDirective(string $violatedDirective): self
    {
        $this->violatedDirective = $violatedDirective;

        return $this;
    }

    public function getDocumentUri(): ?string
    {
        return $this->documentUri;
    }

    public function setDocumentUri(string $documentUri): self
    {
        $this->documentUri = $documentUri;

        return $this;
    }

    public function getBlockedUri(): ?string
    {
        return $this->blockedUri;
    }

    public function setBlockedUri(string $blockedUri): self
    {
        $this->blockedUri = $blockedUri;

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getFirstViolationAt(): ?\DateTimeInterface
    {
        return $this->firstViolationAt;
    }

    public function setFirstViolationAt(\DateTimeInterface $firstViolationAt): self
    {
        $this->firstViolationAt = $firstViolationAt;

        return $this;
    }

    public function getLastViolationAt(): ?\DateTimeInterface
    {
        return $this->lastViolationAt;
    }

    public function setLastViolationAt(\DateTimeInterface $lastViolationAt): self
    {
        $this->lastViolationAt = $lastViolationAt;

        return $this;
    }
}
