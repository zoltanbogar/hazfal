<?php

namespace ApiBundle\Service;

use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;

class ResponseHandler
{
    const CONTENT_TYPE = 'application/json';
    const RESPONSE_FORMAT = 'json';
    protected $serializer;
    protected $relevantValues;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
        $this->relevantValues = [
            "unset",
            "no",
            "hide",
            "false",
        ];
    }

    public function errorHandler($type, $message, $statusCode = 404, $success = FALSE)
    {
        $tblResponse = [
            "success" => $success,
            "error" => [
                "type" => $type,
                "code" => $statusCode,
                "message" => $message,
            ],
        ];

        $response = new Response($this->serializer->serialize($tblResponse, self::RESPONSE_FORMAT), $statusCode);
        $response->headers->set('Content-Type', self::CONTENT_TYPE);

        return $response;
    }

    public function successHandler($objResponseData, $additionalQueryData = [], $statusCode = Response::HTTP_OK)
    {
        $serializedResponse = $this->serializer->serialize($objResponseData, self::RESPONSE_FORMAT);
        $objResponseData = $this->controlResult($serializedResponse, $additionalQueryData);

        $response = new Response($objResponseData, $statusCode);
        $response->headers->set('Content-Type', self::CONTENT_TYPE);

        return $response;
    }

    public function controlResult($responseData, $queryParams)
    {
        if (!$queryParams) {
            return $responseData;
        }

        $decodedResponse = json_decode($responseData, TRUE);

        foreach ($queryParams as $key => $param) {
            if (in_array(strtolower($param), $this->relevantValues)) {
                if (count($decodedResponse) > 1) {
                    foreach ($decodedResponse as $count => $data) {
                        $arrayKeys = array_keys($data);
                        if (in_array($key, $arrayKeys)) {
                            unset($decodedResponse[$count][$key]);
                        }
                    }
                } else {
                    $arrayKeys = array_keys($decodedResponse);
                    if (in_array($key, $arrayKeys)) {
                        unset($decodedResponse[$key]);
                    }
                }
            }
        }

        $responseData = json_encode($decodedResponse);

        return $responseData;
    }
}