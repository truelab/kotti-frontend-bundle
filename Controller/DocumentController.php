<?php

namespace Truelab\KottiFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Truelab\KottiORMBundle\Exception\NodeWherePathNotFoundException;
use Truelab\KottiORMBundle\Repository\NodeRepositoryInterface;

class DocumentController extends Controller
{
    public function indexAction($nodePath)
    {
        try {

            $nodePath = $this->sanitizeNodePath($nodePath);
            $node = $this->getNodeRepository()->getOneWherePath($nodePath);

        } catch (NodeWherePathNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        }

        return $this->render(
            'TruelabKottiFrontendBundle:Document:index.html.twig',
            array('nodePath' => $nodePath, 'node' => $node )
        );
    }

    /**
     * FIXME
     * @param $path
     * @return string
     */
    public function sanitizeNodePath($path)
    {
        $path = rtrim($path, '/') . '/';
        $path = '/' .ltrim($path, '/');
        $path = strtolower($path);
        return $path;
    }

    /**
     * FIXME
     * @return NodeRepositoryInterface
     */
    public function getNodeRepository()
    {
        return $this->get('truelab_kotti_orm.node_repository');
    }
}
