<?php

namespace Com\Dritan\Demographics\Service;


class DemographyService
{
    private $parser;
    private static $years = [];
    private static $cities = [];

    public function __construct($filePath)
    {
        // both rows and columns indexes are 1-based
        // parse xml file and retains nodes into memory
        $this->parser = new ExcelSheetParser($filePath);
    }

    public function getCities()
    {
        if (self::$cities == null) {
            self::$cities = $this->extractCities();
        }
        return self::$cities;
    }
    
    public function getYears()
    {
        if (self::$years == null) {
            self::$years = $this->extractYears();
        }
        return self::$years;
    }

    public function getCitiesDemographics(array $cityNames)
    {
        $demographics = [];
        foreach ($cityNames as $cityName) {
            $demographics[$cityName] = $this->getCityDemography($cityName);
        }
        return $demographics;
    }

    private function getCityDemography($cityName)
    {
        $cityRowNum = -1;
        for ($rowNum = 1; $rowNum < $this->parser->getRowCount(); $rowNum++) {
            $city = strtolower($this->parser->getCell($rowNum, 1));
            if ($city == strtolower($cityName)) {
                $cityRowNum = $rowNum;
            }
        }
        if ($cityRowNum < 0) {
            throw new \RuntimeException("City " . $cityName . " has been not found on data file.");
        }

        $demography = [];
        $years = $this->getYears();
        foreach ($years as $yearColumn => $yearValue) {
            $demography[$yearValue] = $this->parser->getCell($cityRowNum, $yearColumn);
        }

        return $demography;
    }

    private function extractCities()
    {
        $shiftCount = 5;
        $cities = $this->parser->getColumn(1);
        for ($i = 1; $i < $shiftCount; $i++) {
            array_shift($cities);
        }
        return $cities;
    }

    private function extractYears()
    {
        $shiftColumnCount = 1;
        $shiftRowCount = 1;
        $years = [];

        $i = 1 + $shiftColumnCount;
        $year = null;
        do {
            $year = intval($this->parser->getCell($shiftRowCount + 1, $i));
            if ($year > 0) {
                $years[$i] = $year;
                $i++;
            }
        } while ($year != null);

        return $years;
    }

}

?>
