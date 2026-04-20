<?php

namespace App\Service;

class JsonDataLoader
{
    private string $dataDir;
    public function __construct()
    {
        $this->dataDir = dirname(__DIR__, 2) . '/data';
    }

    private function parseJson(string $fileName):array
    {
        $path = $this->dataDir . '/' . $fileName;

        if (!file_exists($path)){
            throw new \RuntimeException("Json file not found: $path");
        }
        $content = file_get_contents($path);
        $data= json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE){
            throw new \RuntimeException(json_last_error_msg());
        }

        return $data;
    }

    public function loadTechnicians():array
    {
        return $this->parseJson('technicians.json');

    }

    public function loadSamples():array
    {
        return $this->parseJson('samples.json');
    }

    public function loadPatients():array
    {
        return $this->parseJson('patients.json');
    }

    public function loadEquipments():array
    {
        return $this->parseJson('equipments.json');
    }
}
