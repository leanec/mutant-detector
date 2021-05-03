<?php

declare(strict_types = 1);

namespace App\Services;

use App\Entities\Record;
use App\Repositories\RecordRepository;

class RecordService
{
    protected RecordRepository $recordRepository;

    public function __construct(RecordRepository $recordRepository) 
    {
        $this->recordRepository = $recordRepository;
    }

    protected function getRecordRepository() : RecordRepository
    {
        return $this->recordRepository;
    }

    public function getOne(array $dna) : object
    {
        $record = $this->getRecordRepository()->getRecord(json_encode($dna));

        return $record;
    }

    public function create(array $dna, bool $mutant): object
    {
        $record = new Record();
        $record->updateDna(json_encode($dna));
        $record->updateMutant($mutant ? 1 : 0);
        $record = $this->getRecordRepository()->create($record);

        return $record;
    }
}