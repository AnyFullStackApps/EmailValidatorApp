<?php
/**
 * Created by PhpStorm.
 * User: anygroup
 * Date: 08.11.2018
 * Time: 11:37
 */

namespace AppBundle\Service;

use Iterator;

interface DocumentValidatorInterface
{
    public function validate(Iterator $records, bool $outputType = null);

    public function createValidationWithResume(Iterator $records);
}