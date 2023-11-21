<?php

namespace App\Controller;

use App\Service\User\UserHttpClient;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/cache', name: 'cache_')]
class CacheController extends AbstractController
{
    const CACHE_USER_KEY = 'user_http_client';
    const CACHE_PSR6_USER_KEY = 'user_http_client_psr6';

    public function __construct(
        private readonly UserHttpClient $userHttpClient,
        private readonly CacheInterface $cache,
        private readonly CacheItemPoolInterface $cacheItemPool
    )
    {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $users = $this->cache->get(self::CACHE_USER_KEY, function(ItemInterface $item) {
            $item->expiresAfter(120); // en seconde

            return $this->userHttpClient->get();
        });

        return $this->render('cache/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/user/remove', name: 'user_remove')]
    public function removeCacheUser(): Response
    {
        $this->cache->delete(self::CACHE_USER_KEY);
        $this->cacheItemPool->deleteItem(self::CACHE_PSR6_USER_KEY);

        return $this->redirectToRoute('cache_index');
    }

    #[Route('/psr6', name: 'psr6')]
    public function psr6(): Response
    {
        $item = $this->cacheItemPool->getItem(self::CACHE_PSR6_USER_KEY);
        // si le cache n'existe pas
        if(!$item->isHit()){
            $item->set($this->userHttpClient->get());
            $item->expiresAfter(120);
            $this->cacheItemPool->save($item);
        }

        // quand le cache existe
        $users = $item->get();

        return $this->render('cache/index.html.twig', [
            'users' => $users
        ]);
    }
}
