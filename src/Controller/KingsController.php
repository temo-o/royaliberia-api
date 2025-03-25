<?php

namespace App\Controller;

use App\Service\KingsService;
use App\Utilities\ValidatorService;
use Exception;
use Symfony\Component\HttpFoundation\{JsonResponse, Response};
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Predis\Client as PredisClient;

#[Route('/')]
class KingsController extends BaseController
{
    public function __construct(
        protected SerializerInterface $serializer,
        protected ValidatorService $validator,
        protected KingsService $kingsService
    )
    {
        parent::__construct($this->serializer, $this->validator);
    }

    /**
     * @throws Exception
     */
    #[Route('', name: 'home_index', methods: ['GET'])]
    public function getKings(): Response
    {
        $kings = $this->kingsService->getKings();

        return new JsonResponse($kings);
    }

    /**
     * @throws Exception
     */
    #[Route('/redis', name: 'redis_test', methods: ['GET'])]
    public function getRedis(): Response
    {

        $kings = $this->kingsService->getKings();

        $r = new PredisClient(
            [
                'scheme'   => 'tcp',
                'host'     => '172.26.0.2',
                'port'     => 6379,
                'password' => '',
                'database' => 0,
            ]
        );

        foreach($kings as $king) {
            $kingIdKey = 'king:'.$king->getId();
            $r->executeRaw(['JSON.SET', $kingIdKey , '.', json_encode($king)]);
        }

        do{
            list($cursor, $keys) = $r->scan(0, ['MATCH' => 'king:*', 'COUNT:' => 0]);

            foreach ($keys as $key) {
                $value = $r->executeRaw(['JSON.GET', $key]);
                $getRes[$key] = json_decode($value, true);
            }

        }
        while($cursor > 0);

        return new JsonResponse(['getRes' => $getRes]);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    #[Route('/redisKings', name: 'redis_kings', methods: ['GET'])]
    public function getKingsFromRedis(): Response
    {
        $kings = $this->kingsService->getKingsFromRedis();

        return new JsonResponse($kings);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    #[Route('/redisKingsSet', name: 'redis_kings_set', methods: ['GET'])]
    public function setKingsInRedis(): Response
    {
        $kings = $this->kingsService->getKings();

        foreach($kings as $king){
            $this->kingsService->setKingsInRedis($king->getId(), $king->getFirstName());
        }

        return new JsonResponse($kings);
    }
}