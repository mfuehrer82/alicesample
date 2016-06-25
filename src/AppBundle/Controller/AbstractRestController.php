<?php

namespace AppBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractRestController
 *
 * @package AppBundle\RestController
 */
abstract class AbstractRestController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @param Request      $request
     * @param QueryBuilder $queryBuilder
     *
     * @return \Hateoas\Representation\PaginatedRepresentation
     */
    protected function createRepresentation(Request $request, QueryBuilder $queryBuilder)
    {
        $pagerAdapter = new DoctrineORMAdapter($queryBuilder);
        $pager = new Pagerfanta($pagerAdapter);
        $limit = $request->query->getInt('limit', 10);
        $page = $request->query->getInt('page', 1);

        $pager->setCurrentPage($page);
        $pager->setMaxPerPage($limit);

        $pagerFactory = new PagerfantaFactory();

        return $pagerFactory->createRepresentation(
            $pager,
            new Route($request->attributes->get('_route'), ['limit' => $limit, 'page' => $page])
        );
    }
}
