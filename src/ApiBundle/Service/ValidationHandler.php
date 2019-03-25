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
            if (in_array('date', $rowRule)) {
                if (!$request->get($strParameter)) {
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

        return TRUE;
    }

    private function getError($strParameter, $numCode)
    {
        return [
            'hasError' => TRUE,
            'errorLabel' => $strParameter.'_error',
            'errorText' => $this->tblError[$numCode],
            'errorCode' => $numCode,
        ];
    }
}