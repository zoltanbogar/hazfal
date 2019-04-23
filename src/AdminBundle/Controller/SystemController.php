<?php


namespace AdminBundle\Controller;

use AppBundle\Entity\ImportSource;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SystemController extends Controller
{
    public function getImportSourcesAction(Request $request)
    {
        if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }
        $objImportSources = $this->getDoctrine()
            ->getRepository(ImportSource::class)
            ->findAll();

        return $this->render(
            'Admin\System\importSources.html.twig',
            [
                'objImportSources' => $objImportSources,
                'error' => $error ?? NULL,
            ]
        );
    }

    public function getImportSourceReadAction($importSourceId)
    {
        $objImportSource = $this->getDoctrine()
            ->getRepository(ImportSource::class)
            ->find($importSourceId);

        return $this->render(
            'Admin\System\form.html.twig',
            [
                'objImportSource' => $objImportSource,
                'isEditable' => FALSE,
                'isNew' => FALSE,
                'error' => NULL,
                'success' => NULL,
            ]
        );
    }

    public function getImportSourceEditAction($importSourceId, $error = NULL)
    {
        $objImportSource = $this->getDoctrine()
            ->getRepository(ImportSource::class)
            ->find($importSourceId);

        return $this->render(
            'Admin\System\form.html.twig',
            [
                'objImportSource' => $objImportSource,
                'isEditable' => TRUE,
                'isNew' => FALSE,
                'error' => $error,
                'success' => NULL,
            ]
        );
    }

    public function getImportSourceDeleteAction($importSourceId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $objImportSource = $this->getDoctrine()
            ->getRepository(ImportSource::class)
            ->find($importSourceId);

        $error = NULL;

        try {
            $entityManager->remove($objImportSource);
            $entityManager->flush();
        } catch (\Exception $e) {
            $error = [
                'errorTitle' => 'Nem törölhető',
                'errorText' => $e->getMessage(),
            ];
        }

        return $this->redirectToRoute(
            'admin_get_system_import_sources',
            [
                'error' => $error,
            ]
        );
    }

    public function getImportSourceAddAction($error = NULL)
    {
        $objImportSource = new ImportSource();

        return $this->render(
            'Admin\System\form.html.twig',
            [
                'objImportSource' => $objImportSource,
                'isEditable' => TRUE,
                'isNew' => TRUE,
                'error' => $error,
                'success' => NULL,
            ]
        );
    }

    public function postImportSourceEditAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'inputName' => 'required',
                'inputUsername' => 'required',
                'inputEmail' => 'required',
                'inputSlug' => 'required',
                'apiKey' => 'required',
                'sourceId' => 'required|numeric',
            ],
            $request
        );

        $error = NULL;

        if ($validator['hasError']) {
            $error = $validator;
        }

        if (!$error) {
            $objImportSouce = $this->getDoctrine()->getRepository(ImportSource::class)->find($request->get('sourceId'));

            $entityManager = $this->getDoctrine()->getManager();

            $objImportSouce->setName($request->get('inputName'));
            $objImportSouce->setSlug($request->get('inputSlug'));
            $objImportSouce->setApiKey($request->get('apiKey'));
            $objImportSouce->setEmail($request->get('inputEmail'));
            $objImportSouce->setUsername($request->get('inputUsername'));
            $objImportSouce->setIsActive(1);

            $entityManager->persist($objImportSouce);
            $entityManager->flush();
        } else {
            return $this->getImportSourceEditAction($request->get('sourceId'), $error);
        }

        return $this->redirectToRoute(
            'admin_get_system_import_sources',
            [
                'error' => NULL,
            ]
        );
    }

    public function postImportSourceAddAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'inputName' => 'required',
                'inputUsername' => 'required',
                'inputEmail' => 'required',
                'inputSlug' => 'required',
                'apiKey' => 'required',
            ],
            $request
        );

        $error = NULL;

        if ($validator['hasError']) {
            $error = $validator;
        }

        if (!$error) {
            $objImportSouce = new ImportSource();

            $entityManager = $this->getDoctrine()->getManager();

            $objImportSouce->setName($request->get('inputName'));
            $objImportSouce->setSlug($request->get('inputSlug'));
            $objImportSouce->setApiKey($request->get('apiKey'));
            $objImportSouce->setEmail($request->get('inputEmail'));
            $objImportSouce->setUsername($request->get('inputUsername'));
            $objImportSouce->setIsActive(1);

            $entityManager->persist($objImportSouce);
            $entityManager->flush();
        } else {
            return $this->getImportSourceAddAction($error);
        }

        return $this->redirectToRoute(
            'admin_get_system_import_sources',
            [
                'error' => NULL,
            ]
        );
    }

    public function getGenerateApiKeyAction()
    {
        do {
            $newApiKey = substr(base64_encode(sha1(mt_rand())), 0, 64);
            $objImportSource = $this->getDoctrine()->getRepository(ImportSource::class)->findSourceByApiKey($newApiKey);
        } while ($objImportSource !== NULL);

        return $this->container->get('response_handler')->successHandler($newApiKey, []);
    }
}