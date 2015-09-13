<?php

namespace MusicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    public function newsCategoriesFragmentAction()
    {
        $categories = $this->getDoctrine()->getManager()
            ->getRepository('MusicBundle:NewsCategory')
            ->findAll();

        return $this->render('MusicBundle:Music:_news_categories.html.twig', ['categories' => $categories]);
    }

    public function indexAction(Request $request, $type = null)
    {
        $em = $this->getDoctrine()->getEntityManager();

        if ($type == 'news') {
            $repo = 'NewsItem';
        } else if ($type == 'release') {
            $repo = 'ReleaseItem';
        } else if ($type == 'mix') {
            $repo = 'MixItem';
        } else {
            $repo = 'MediaItem';
        }

        $qb = $em->getRepository(sprintf('MusicBundle:%s', $repo))
            ->createQueryBuilder('i')
            ->select('i')
            ->orderBy('i.createdAt', 'desc');

        if ($request->query->has('search')) {
            $s = $qb->expr()->literal('%' . str_replace(' ', '%', $request->query->get('search')) . '%');
            $qb->andWhere($qb->expr()->like('i.title', $s));
        }

        $items = $this->get('knp_paginator')
            ->paginate($qb->getQuery(), $request->get('page', 1), 9);

        return $this->render('MusicBundle:Music:index.html.twig', [
            'items' => $items
        ]);
    }

    public function newsAction($slug)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $item = $em->getRepository('MusicBundle:NewsItem')
            ->findOneBySlug($slug);

        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        return $this->render('MusicBundle:Music:news_item.html.twig', [
            'item' => $item
        ]);
    }

    public function releaseAction($slug)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $tokenManager = $this->get('music.token_manager');

        $item = $em->getRepository('MusicBundle:ReleaseItem')
            ->findOneBySlug($slug);

        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        $tokens = $tokenManager->createTokens($item);

        return $this->render('MusicBundle:Music:release_item.html.twig', [
            'item' => $item,
            'tokens' => $tokens,
        ]);
    }

    public function mixAction($slug)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $tokenManager = $this->get('music.token_manager');

        $item = $em->getRepository('MusicBundle:MixItem')
            ->findOneBySlug($slug);

        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        $tokens = $tokenManager->createTokens($item);

        return $this->render('MusicBundle:Music:mix_item.html.twig', [
            'item' => $item,
            'tokens' => $tokens,
        ]);
    }

    public function aboutAction()
    {
        return $this->render('MusicBundle:Music:about.html.twig');
    }
}
