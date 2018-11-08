<?php
/**
 * Created by PhpStorm.
 * User: anygroup
 * Date: 08.11.2018
 * Time: 11:43
 */

namespace AppBundle\Service\Factory;

abstract class DocumentValidatorAbstractFactory
{
    abstract function createCSVEmailValidator();
}