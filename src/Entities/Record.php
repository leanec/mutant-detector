<?php

declare(strict_types = 1);

namespace App\Entities;

class Record
{
    private string $dna;
    private int $mutant;

    public function toJson(): object
    {
        return json_decode((string) json_encode(get_object_vars($this)), false);
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