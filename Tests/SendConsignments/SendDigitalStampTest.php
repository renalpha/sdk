<?php

use MyParcelNL\Sdk\src\Concerns\HasCustomItems;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Repository\MyParcelConsignmentRepository;


/**
 * Class SendDigitalStampTest
 * @package MyParcelNL\Sdk\tests\SendDigitalStampTest
 */
class SendDigitalStampTest extends \PHPUnit\Framework\TestCase
{
    use HasCustomItems;

    /**
     * Test one shipment with createConcepts()
     * @throws \Exception
     */
    public function testSendOneConsignment()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        if (getenv('API_KEY') == null) {
            echo "\033[31m Set MyParcel API-key in 'Environment variables' before running UnitTest. Example: API_KEY=f8912fb260639db3b1ceaef2730a4b0643ff0c31. PhpStorm example: http://take.ms/sgpgU5\n\033[0m";
            return $this;
        }

        foreach ($this->additionProvider() as $consignmentTest) {

            $myParcelCollection = new MyParcelCollection();

            $consignment = (new MyParcelConsignmentRepository())
                ->setApiKey($consignmentTest['api_key'])
                ->setPackageType(4)
                ->setCountry($consignmentTest['cc'])
                ->setPerson($consignmentTest['person'])
                ->setCompany($consignmentTest['company'])
                ->setFullStreet($consignmentTest['full_street'])
                ->setPostalCode($consignmentTest['postal_code'])
                ->setCity($consignmentTest['city'])
                ->setEmail('your_email@test.nl')
                ->setPhone($consignmentTest['phone'])
                ->setPhysicalProperties($consignmentTest['physical_properties']);

            if (key_exists('package_type', $consignmentTest)) {
                $consignment->setPackageType($consignmentTest['package_type']);
            }

            if (key_exists('label_description', $consignmentTest)) {
                $consignment->setLabelDescription($consignmentTest['label_description']);
            }

            $myParcelCollection
                ->addConsignment($consignment)
                ->setLinkOfLabels();

            $this->assertEquals(true, preg_match("#^https://api.myparcel.nl/pdfs#", $myParcelCollection->getLinkOfLabels()), 'Can\'t get link of PDF');

            echo "\033[32mGenerated digital stamp shipment: \033[0m";
            print_r($myParcelCollection->getLinkOfLabels());
            echo "\n\033[0m";
        }
    }

    /**
     * Data for the test
     *
     * @return array
     */
    public function additionProvider()
    {
        return [
            [
                'api_key' => getenv('API_KEY'),
                'cc' => 'NL',
                'person' => 'Reindert',
                'company' => 'Big Sale BV',
                'full_street_test' => 'Plein 1940-45 3b',
                'full_street' => 'Plein 1940-45 3 b',
                'street' => 'Plein 1940-45',
                'number' => 3,
                'number_suffix' => 'b',
                'postal_code' => '2231JE',
                'city' => 'Rijnsburg',
                'phone' => '123456',
                'package_type' => 4,
                'label_description' => 112345,
                'physical_properties' => [
                    'weight' => 76
                ]
            ],
            [
                'api_key' => getenv('API_KEY'),
                'cc' => 'NL',
                'person' => 'Reindert',
                'company' => 'Big Sale BV',
                'full_street_test' => 'Plein 1940-45 3b',
                'full_street' => 'Plein 1940-45 3 b',
                'street' => 'Plein 1940-45',
                'number' => 3,
                'number_suffix' => 'b',
                'postal_code' => '2231JE',
                'city' => 'Rijnsburg',
                'phone' => '123456',
                'package_type' => 4,
                'label_description' => 112345,
                'physical_properties' => [
                    'weight' => 1999
                ]
            ],
            [
                'api_key' => getenv('API_KEY'),
                'cc' => 'NL',
                'person' => 'Reindert',
                'company' => 'Big Sale BV',
                'full_street_test' => 'Plein 1940-45 3b',
                'full_street' => 'Plein 1940-45 3 b',
                'street' => 'Plein 1940-45',
                'number' => 3,
                'number_suffix' => 'b',
                'postal_code' => '2231JE',
                'city' => 'Rijnsburg',
                'phone' => '123456',
                'package_type' => 4,
                'label_description' => 112345,
                'physical_properties' => [
                    'weight' => 0
                ]
            ],
        ];
    }
}