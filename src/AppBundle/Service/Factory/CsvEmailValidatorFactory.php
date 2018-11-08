<?php
/**
 * Created by PhpStorm.
 * User: anygroup
 * Date: 08.11.2018
 * Time: 11:50
 */

namespace AppBundle\Service\Factory;


use AppBundle\Service\CsvEmailValidator;

class CsvEmailValidatorFactory extends DocumentValidatorAbstractFactory
{
    function createCSVEmailValidator()
    {
        return new CsvEmailValidator();
    }
}