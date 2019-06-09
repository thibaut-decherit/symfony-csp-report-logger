<?php

namespace App\Controller;

use App\Entity\CspViolation;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class ViolationController
 * @package App\Controller
 *
 * @Route("/csp-violation-logger")
 */
class ViolationController extends DefaultController
{
    /**
     * @param Request $request
     * @Route("/new", name="new_violation", methods="POST")
     * @return Response
     * @throws Exception
     */
    public function new(Request $request): Response
    {
        $cspViolation = $this->parseReport($request->getContent());

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(CspViolation::class);

        $duplicate = $repository->findDuplicate($cspViolation);

        if ($duplicate) {
            $repository->incrementCount($duplicate);
        } else {
            $em->persist($cspViolation);
            $em->flush();
        }

        return new Response();
    }

    /**
     * Converts report into an associative array then uses the data to create a new CspViolation object.
     *
     * @param string $report
     * @return CspViolation
     * @throws Exception
     */
    private function parseReport(string $report): CspViolation
    {
        $violationRawData = (array)json_decode($report)->{'csp-report'};
        $violationRawData['route'] = $this->getRouteFromAbsoluteUrl($violationRawData['document-uri']);

        return new CspViolation($violationRawData);
    }

    /**
     * @param string $url
     * @return string|null
     */
    private function getRouteFromAbsoluteUrl(string $url): ?string
    {
        $explodedUrl = explode('/', $url);
        $explodedPathInfoFragments = array_slice($explodedUrl, 3);
        $pathInfo = '/' . implode('/', $explodedPathInfoFragments);

        // Removes GET parameters.
        $pathInfo = explode('?', $pathInfo)[0];

        try {
            $route = $this->get('router')->getMatcher()->match($pathInfo)['_route'];
        } catch (NoConfigurationException|ResourceNotFoundException|MethodNotAllowedException $exception) {
            return null;
        }

        return $route;
    }
}
