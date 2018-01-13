<?php

namespace Com\Dritan\Demographics\Service;


class ExcelSheetParser {

    private $table;
    private $rowCount;

    public function __construct($url)
    {
        $this->loadXmlFile($url);
        $this->rowCount = count($this->table);
    }

    public function getCell($rowNum, $colNum)
    {
        return $this->table[$rowNum][$colNum];
    }

    public function getColumn($colNum)
    {
        $column = [];
        foreach($this->table as $row){
            array_push($column, $row[$colNum]);
        }
        return $column;
    }

    public function getRowCount()
    {
        return $this->rowCount;
    }

    public function getRow($rowNum)
    {
        return $this->table[$rowNum];
    }

    private function loadXmlFile($url)
    {
        $xml = simplexml_load_file($url);
        $tableArray = [null];
        $rows = $xml->xpath("//ss:Worksheet")[0]
                    ->children(null, "Table")[0]
                    ->Row;

        foreach ($rows as $row) {
            $cells = $row->Cell;
            $rowArray = [null];

            foreach ($cells as $cell) {
                $cellIndex = $cell->xpath('@ss:Index');

                if (count($cellIndex) > 0) {
                    $gap = $cellIndex[0] - count($rowArray);
                    for ($i = 0; $i < $gap; $i++) {
                        array_push($rowArray,null);
                    }
                }
                array_push($rowArray, strval($cell->Data));
            }
            array_push($tableArray, $rowArray);
        }
        $this->table = $tableArray;
    }

}

?>