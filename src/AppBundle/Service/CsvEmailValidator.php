<?php

namespace AppBundle\Service;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use League\Csv\Writer;
use Iterator;

class CsvEmailValidator implements DocumentValidatorInterface
{
    const RETURN_INCORRECT = false;
    const RETURN_CORRECT = true;

    /**
     * @param Iterator $records
     * @param bool|null $outputType
     * @return array
     *
     * Depends on $outputType method return array with correct/existing or incorrect/non-existing emails
     * otherwise if no outputType parameter given or null then array with both above arrays and their counters
     */
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

        if (null === $outputType) {

            $all['correct'][$correctCount] = $correct;
            $all['incorrect'][$incorrectCount] = $incorrect;

            return $all;

        } elseif (false === $outputType) {

            return $incorrect;
        } else {

            return $correct;
        }
    }

    /**
     * @param Iterator $records
     * @return bool|int
     *
     * Create .txt file with validation resume that include number of correct/existing and incorrect/non-existing emails
     */
    public function createValidationWithResume(Iterator $records)
    {
        //TODO:improve filename + datetime, move filepaths in the future, better error catching
        $emails = $this->validate($records);
        $content = 'Correct emails: ' . key($emails['correct']) . ' Incorrect emails: ' . key($emails['incorrect']);
        $size = file_put_contents('%kernel.root_dir%/../var/csv/validation-output/resume.txt', nl2br($content));

        return $size;
    }

    /**
     * @param Iterator $records
     * @return int
     *
     * Create .csv file that include correct/existing emails
     */
    public function createCsvEmailCorrect(Iterator $records)
    {
        $emails = $this->validate($records, self::RETURN_CORRECT);
        $writer = Writer::createFromPath('%kernel.root_dir%/../var/csv/validation-output/correct.csv', 'w+');
        $size = $writer->insertAll($emails);

        return $size;
    }


    /**
     * @param Iterator $records
     * @return int
     *
     * Create .csv file that include incorrect/non-existing emails
     */
    public function createCsvEmailIncorrect(Iterator $records)
    {
        $emails = $this->validate($records, self::RETURN_INCORRECT);
        $writer = Writer::createFromPath('%kernel.root_dir%/../var/csv/validation-output/incorrect.csv', 'w+');
        $size = $writer->insertAll($emails);

        return $size;
    }
}