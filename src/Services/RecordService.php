<?php

declare(strict_types = 1);

namespace App\Services;

use App\Entities\Record;
use App\Repositories\RecordRepository;
use App\Services\RedisService;

class RecordService
{
    private const REDIS_KEY = 'dna:%s';

    protected RecordRepository $recordRepository;

    protected RedisService $redisService;

    public function __construct(RecordRepository $recordRepository, RedisService $redisService) 
    {
        $this->recordRepository = $recordRepository;
        $this->redisService = $redisService;
    }

    protected function getRecordRepository() : RecordRepository
    {
        return $this->recordRepository;
    }

    public function getOne(array $dna) : ?object
    {
        if ($this->isRedisEnabled() === true) {
            $record = $this->getCacheRecord(json_encode($dna));
        } else {
            $record = $this->getDbRecord(json_encode($dna));
            if (!is_null($record)) {
                $record = $record->toJson();
            }
        }

        return $record;
    }

    public function create(array $dna, bool $mutant): object
    {
        $record = new Record();
        $record->updateDna(json_encode($dna));
        $record->updateMutant($mutant ? 1 : 0);
        $record = $this->getRecordRepository()->create($record);
        if ($this->isRedisEnabled() === true) {
            $this->saveInCache($record->getDna(), $record->toJson());
        }

        return $record->toJson();
    }

    public function getStats() : array
    {
        $stats = $this->getRecordRepository()->getStats();

        return $this->processStats($stats);
    }

    protected function processStats(array $stats) : array
    {
        $processedStats = [
            "count_mutant_dna" => $stats["mutants"],
            "count_human_dna" => $stats["humans"],
            "ratio" => $stats["humans"] > 0 ? $stats["mutants"] / $stats["humans"] : 0
        ];

        return $processedStats;
    }

    protected function isRedisEnabled() : bool
    {
        return filter_var($_SERVER['REDIS_ENABLED'], FILTER_VALIDATE_BOOLEAN);
    }

    protected function getCacheRecord(String $dna) : ?object
    {
        $redisKey = sprintf(self::REDIS_KEY, $dna);
        $key = $this->redisService->generateKey($redisKey);

        if ($this->redisService->exists($key)) {
            $record = $this->redisService->get($key);
        } else {
            $record = $this->getDbRecord($dna);    
            if (!is_null($record)) {
                $this->redisService->set($key, $record);
            }
        }

        return $record;
    }

    protected function getDbRecord(String $dna) : ?Record
    {
        return $this->getRecordRepository()->getRecord($dna);
    }

    protected function saveInCache(string $dna, object $record) : void
    {
        $redisKey = sprintf(self::REDIS_KEY, $dna);
        $key = $this->redisService->generateKey($redisKey);

        $this->redisService->set($key, $record);
    }
}