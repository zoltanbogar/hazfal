<?php

namespace ApiBundle\Service;

use AppBundle\Entity\ImportSource;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class ValidationHandler
{
    protected $responseHandler;
    protected $entityManager;
    protected $tblError;

    public function __construct($responseHandler, EntityManager $entityManager)
    {
        $this->responseHandler = $responseHandler;
        $this->entityManager = $entityManager;
        $this->tblError = [
            404 => 'Not found!',
            422 => 'Invalid parameters!',
            1 => 'max',
            2 => 'min',
        ];
    }

    public function inputValidationHandler(array $tblRules, Request $request)
    {
        foreach ($tblRules as $strParameter => $strRuleSet) {
            $rowRule = explode('|', $strRuleSet);
            if (in_array('required', $rowRule)) {
                if (!$request->get($strParameter)) {
                    return $this->getError($strParameter, 404);
                }
            }
            if (in_array('numeric', $rowRule)) {
                if (!is_numeric($request->get($strParameter))) {
                    return $this->getError($strParameter, 422);
                }
            }
            if (($key = $this->strposInArray($rowRule, 'min')) !== FALSE) {
                $minNumber = str_replace('min:', '', $rowRule[$key]);
                $paramLength = strlen($request->get($strParameter));
                if ((int)$minNumber > $paramLength) {
                    return $this->getError($strParameter, 422);
                }
            }
            if (($key = $this->strposInArray($rowRule, 'max')) !== FALSE) {
                $maxNumber = str_replace('max:', '', $rowRule[$key]);
                $paramLength = strlen($request->get($strParameter));
                if ((int)$maxNumber < $paramLength) {
                    return $this->getError($strParameter, 422);
                }
            }
            if (in_array('date', $rowRule)) {
                if (!$request->get($strParameter)) {
                    return $this->getError($strParameter, 422);
                }
            }
        }

        return ['hasError' => FALSE];
    }

    public function inputValidationHandlerArray(array $tblRules, array $arrPayload)
    {
        foreach ($tblRules as $strParameter => $strRuleSet) {
            $rowRule = explode('|', $strRuleSet);
            if (in_array('required', $rowRule)) {
                if (!$arrPayload[$strParameter]) {
                    return $this->getError($strParameter, 404);
                }
            }
            if (in_array('numeric', $rowRule)) {
                if (!$arrPayload[$strParameter]) {
                    return $this->getError($strParameter, 422);
                }
            }
            if (($key = $this->strposInArray($rowRule, 'min')) !== FALSE) {
                $minNumber = str_replace('min:', '', $rowRule[$key]);
                $paramLength = strlen($arrPayload[$strParameter]);
                if ((int)$minNumber > $paramLength) {
                    return $this->getError($strParameter, 422);
                }
            }
            if (($key = $this->strposInArray($rowRule, 'max')) !== FALSE) {
                $maxNumber = str_replace('max:', '', $rowRule[$key]);
                $paramLength = strlen($arrPayload[$strParameter]);
                if ((int)$maxNumber < $paramLength) {
                    return $this->getError($strParameter, 422);
                }
            }
            if (in_array('date', $rowRule)) {
                if (!$arrPayload[$strParameter]) {
                    return $this->getError($strParameter, 422);
                }
            }
        }

        return ['hasError' => FALSE];
    }

    public function importSourceValidationHandler(Request $request)
    {
        $strApiKey = $request->get('api_key');

        $objImportSource = $this->entityManager->getRepository(ImportSource::class)->findBy(['apiKey' => $strApiKey, 'isActive' => 1]);

        if (!$objImportSource) {
            return FALSE;
        }
        if (count($objImportSource) > 1) {
            return FALSE;
        } else {
            $objImportSource = $objImportSource[0];
            //TODO logoljuk valahova
        }

        return $objImportSource;
    }

    private function getError($strParameter, $numCode)
    {
        return [
            'hasError' => TRUE,
            'errorLabel' => $strParameter . '_error',
            'errorText' => $this->tblError[$numCode],
            'errorCode' => $numCode,
            'strParameter' => $strParameter,
        ];
    }

    private function strposInArray($rowRule, $substring)
    {
        foreach ($rowRule as $key => $rule) {
            if (strpos($rule, $substring) !== FALSE) {
                return $key;
            }
        }

        return FALSE;
    }
}