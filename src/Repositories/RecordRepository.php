<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Entities\Record;

class RecordRepository
{
    protected \PDO $database;

    public function __construct(\PDO $database)
    {
        $this->database = $database;
    }

    protected function getDb(): \PDO
    {
        return $this->database;
    }

    public function getRecord(String $dna) : ?Record
    {
        $query = '
            SELECT * FROM `records` WHERE `dna` = :dna
        ';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('dna', $dna);
        $statement->execute();
        $record = $statement->fetchObject(Record::class);
        if (!$record) {
            return null;
        }

        return $record;
    }

    public function create(Record $record) : Record
    {
        $query = '
            INSERT INTO `records`
                (`dna`, `mutant`)
            VALUES
                (:dna, :mutant)
        ';
        $statement = $this->getDb()->prepare($query);
        $dna = $record->getDna();
        $mutant = $record->getMutant();
        $statement->bindParam('dna', $dna);
        $statement->bindParam('mutant', $mutant);
        $statement->execute();

        return $this->getRecord($dna);
    }

    public function getStats(): array
    {
        $query = "
            SELECT COUNT(NULLIF(mutant,0)) as mutants, COUNT(NULLIF(mutant,1)) as humans 
            FROM `records`
        ";
        $statement = $this->getDb()->prepare($query);
        $statement->execute();

        return (array) $statement->fetch();
    }
}