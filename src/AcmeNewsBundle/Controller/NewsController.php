<?php

namespace AcmeNewsBundle\Controller;

use AcmeNewsBundle\Entity\Post;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NewsController
 * @package AcmeNewsBundle\Controller
 */
class NewsController extends Controller
{
    const MAX_POSTS_ON_PAGE = 10;

    /**
     * This method returns page with list of news
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function listAction(Request $request)
    {
        $page = $request->query->has('page') && is_numeric($request->query->get('page')) ?
            $request->query->get('page') : 1;

        $em = $this->getDoctrine()->getManager();

        $postCount = count($em->getRepository('AcmeNewsBundle:Post')->findAll());
        $pageCount = ceil($postCount / 10);

        $news = $em->getRepository('AcmeNewsBundle:Post')->findBy(
            [],
            ['created' => Criteria::DESC],
            self::MAX_POSTS_ON_PAGE,
            ($page - 1) * self::MAX_POSTS_ON_PAGE
        );

        if ($page > $pageCount) {
            return new RedirectResponse(
                $this->generateUrl('news_list') . '?page=' . $pageCount
            );
        }

        return $this->render('AcmeNewsBundle:News:list.html.twig', [
            'news' => $news,
            'page' => $page,
            'pageCount' => $pageCount
        ]);
    }

    /**
     * This method returns page of post
     *
     * @param Post $post
     *
     * @return Response
     * @throws \Exception
     */
    public function viewAction(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        if ($post->isPublished()) {
            $randomPosts = $news = $em->getRepository('AcmeNewsBundle:Post')->getRandomEntities(3);

            return $this->render('AcmeNewsBundle:News:view.html.twig', [
                'post' => $post,
                'randomPosts' => $randomPosts
            ]);
        } else {
            throw new \Exception('Post is not published');
        }
    }

    /**
     * This method returns page with xml-list of news
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function listXMLAction(Request $request)
    {
        $page = $request->query->has('page') && is_numeric($request->query->get('page')) ?
            $request->query->get('page') : 1;

        $em = $this->getDoctrine()->getManager();

        $postCount = count($em->getRepository('AcmeNewsBundle:Post')->findAll());
        $pageCount = ceil($postCount / 10);

        if ($page > $pageCount) {
            return new RedirectResponse(
                $this->generateUrl('news_list') . '?page=' . $pageCount
            );
        }

        $news = $em->getRepository('AcmeNewsBundle:Post')->findBy(
            [],
            ['created' => Criteria::DESC],
            self::MAX_POSTS_ON_PAGE,
            ($page - 1) * self::MAX_POSTS_ON_PAGE
        );

        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        return $this->render('AcmeNewsBundle:News:list.xml.twig', ['news' => $news], $response);
    }
}
