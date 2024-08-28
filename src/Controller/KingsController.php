<?php

namespace App\Controller;

use App\Service\KingsService;
use App\Utilities\ValidatorService;
use Exception;
use Symfony\Component\HttpFoundation\{JsonResponse, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
    #[Route('/', name: 'home_index', methods: ['GET'])]
    public function getKings(): Response
    {
        $kings = $this->kingsService->getKings();

        return new JsonResponse($kings);
    }

    #[Route('/', name: 'home_index1', methods: ['GET'])]
    public function getKingsClosure(): Response
    {
        try{
            $kings = $this->kingsService->getKingsClosure();
        } catch (Exception $e){
            return new JsonResponse(["foo"=>"barerrror"]);
        }

        #return new JsonResponse($this->kingsService->getKings());
        return new JsonResponse($kings);
    }
}