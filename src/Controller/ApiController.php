<?php

namespace FondationPluriel\packageApi\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Entity\User;
use FondationPluriel\packageApi\Security\JWTTokenManager;

class ApiController extends AbstractController
{
    private $passwordHasher;
    private $jwtManager;
    private $jwtTokenManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager, JWTTokenManager $jwtTokenManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->jwtManager = $jwtManager;
        $this->jwtTokenManager = $jwtTokenManager;
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $user = $userRepository->findOneBy(['username' => $username]);

        if(!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }
        $jwt = $this->jwtTokenManager->create($user);
        return new JsonResponse(['token' => $jwt]);
    }

    // #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    // public function login(Request $request, UserRepository $userRepository): JsonResponse
    // {
    //     $data = json_decode($request->getContent(), true);
    //     $username = $data['username'] ?? '';
    //     $password = $data['password'] ?? '';

    //     $user = $userRepository->findOneBy(['username' => $username]);

    //     if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
    //         return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
    //     }

    //     $token = $this->jwtManager->create($user);

    //     return new JsonResponse(['token' => $token]);
    // }
}
