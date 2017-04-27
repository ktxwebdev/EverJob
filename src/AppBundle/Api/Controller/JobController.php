<?php

namespace AppBundle\Api\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Rest\RouteResource("Job")
 * @Rest\NamePrefix("ever_job_api_jobs_")
 */
class JobController extends FOSRestController {

    /**
     * Returns a paginated list of jobs.
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Jobs",
     *  description="Get the job list",
     *  output={"class"="Sonata\JobBundle\Model\JobInterface", "groups"="list"},
     * )
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page for jobs list pagination (1-indexed)")
     * @Rest\QueryParam(name="count", requirements="\d+", default="10", description="Number of jobs by page")
     * @Rest\QueryParam(name="orderBy", map=true, requirements="ASC|DESC", nullable=true, strict=true, default="",description="Query jobs order by clause (key is field, value is direction")
     * @Rest\QueryParam(name="title", nullable=true, description="Title.")
     * @Rest\QueryParam(name="company", nullable=true, description="Company.")
     *
     * @Rest\Get("/jobs/", name="list", options={ "method_prefix" = false })
     * @Rest\View(serializerGroups={"job-list"}, serializerEnableMaxDepthChecks=true)
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return JobInterface[]
     */
    public function getListJobAction(ParamFetcherInterface $paramFetcher) {
        $supportedFilters = array(
            'title' => '',
            'company' => '',
        );

        $page = $paramFetcher->get('page') - 1;
        $count = $paramFetcher->get('count');
        $orderBy = $paramFetcher->get('orderBy');

        if (isset($orderBy[0]) || ($orderBy == "")) {
            $orderBy = array('dateCreation' => 'DESC');
        }

        $filters = array_intersect_key($paramFetcher->all(), $supportedFilters);

        foreach ($filters as $key => $value) {
            if (null === $value || (is_array($value) && empty($value))) {
                unset($filters[$key]);
            }
        }

        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Job')->getJobListApi($filters, $orderBy, $count, $page);
    }

}
