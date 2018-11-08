<?php
/**
 * Created by PhpStorm.
 * User: anygroup
 * Date: 08.11.2018
 * Time: 11:39
 */

namespace AppBundle\Service;


use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use Iterator;

class CsvEmailValidator implements DocumentValidatorInterface
{
    const RETURN_INCORRECT = false;
    const RETURN_CORRECT = true;

    public function validate(Iterator $records, bool $outputType = null)
    {
        $validator = new EmailValidator();
        $multipleValidations = new MultipleValidationWithAnd([
            new RFCValidation(),
            new DNSCheckValidation()
        ]);
        $correct = [];
        $correctCount = 0;
        $incorrectCount = 0;
        $incorrect = [];

        foreach ($records as $key => $email) {

            if ($validator->isValid($email[0], $multipleValidations)) {
                $correct[] = $email;
                $correctCount++;
            } else {
                $incorrect[] = $email;
                $incorrectCount++;
            }
        }

        if(null === $outputType){
            $all['correct'][$correctCount] = $correct;
            $all['incorrect'][$incorrectCount] = $incorrect;

            return $all;
        }elseif (false === $outputType){
            return $incorrect;
        }else{
            return $correct;
        }
    }

    public function createValidationWithResume(Iterator $records)
    {
        //TODO:improve filename + datetime, move filepaths in the future, better error catching
        $emails = $this->validate($records);
        $content = 'Correct emails: '.key($emails['correct']).' Incorrect emails: '.key($emails['incorrect']);
        file_put_contents('%kernel.root_dir%/../var/csv/validation-output/resume.txt', nl2br($content));

    }
}