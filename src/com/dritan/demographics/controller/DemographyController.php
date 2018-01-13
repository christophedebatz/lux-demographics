<?php

namespace Com\Dritan\Demographics\Controller;

use Com\Dritan\Demographics\Exception\InvalidParameterException;
use Com\Dritan\Demographics\Exception\FileNotFoundException;
use Com\Dritan\Demographics\Service\DemographyService;
use Com\Dritan\Demographics\Service\FileService;


class DemographyController extends TemplatedController
{
    private static $XML_FILES_PATH = './data/';

    public function indexAction()
    {
        $this->bindInputFilesVariables();
        $this->render('index');
    }

    public function displayCitiesAction()
    {
        if (!isset($_POST['file'])) {
            throw new InvalidParameterException(['file']);
        }

        $filePath = self::retrieveFilePath($_POST['file']);
        $service = new DemographyService($filePath);

        $this->bind('cities', $service->getCities());
        $this->bindInputFilesVariables();
        $this->render('index');
    }

    public function displayChartAction()
    {
        if (!isset($_POST['file']) || !isset($_POST['cities']) || !is_array($_POST['cities'])) {
            throw new InvalidParameterException(['file', 'cities']);
        }

        $filePath = self::retrieveFilePath($_POST['file']);
        $service = new DemographyService($filePath);

        $this->bind('years', $service->getYears());
        $this->bind('colors', self::generateRGBColors(count($_POST['cities'])));
        $this->bind('demographics', $service->getCitiesDemographics($_POST['cities']));
        $this->render('index');
    }

    private function bindInputFilesVariables() {
        $candidateFiles = FileService::listXmlFiles(self::$XML_FILES_PATH);

        $this->bind('directory', self::$XML_FILES_PATH);
        if (count($candidateFiles) > 0) {
            $this->bind('files', $candidateFiles);
        }
    }

    private static function retrieveFilePath($fileName)
    {
        $filePath = self::$XML_FILES_PATH . trim($fileName);
        if (!is_file($filePath)) {
            throw new FileNotFoundException($filePath);
        }
        return $filePath;
    }

    private static function generateRGBColors($n)
    {
        $colors = [];
        for ($i = 0; $i < $n; $i++) {
            $hash = md5(strval(mt_rand(0, PHP_INT_MAX) . $i));
            $colors[] = [
                hexdec(substr($hash, 0, 2)), // r
                hexdec(substr($hash, 2, 2)), // g
                hexdec(substr($hash, 4, 2))  // b
            ];
        }
        return $colors;
    }

}

?>
