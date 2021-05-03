<?php

declare(strict_types = 1);

namespace App\Entities;

class Record
{
    private int $id;
    private string $dna;
    private int $mutant;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDna(): string
    {
        return $this->dna;
    }

    public function updateDna(string $dna): self
    {
        $this->dna = $dna;

        return $this;
    }

    public function getMutant(): int
    {
        return $this->mutant;
    }

    public function updateMutant(int $mutant): self
    {
        $this->mutant = $mutant;

        return $this;
    }
}