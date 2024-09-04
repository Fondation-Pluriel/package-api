<?php

namespace FondationPluriel\PackageApi\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTTokenManager
{
    private $jwtManager;

    public function __construct(JWTTokenManagerInterface $jWTTokenManagerInterface)
    {
        $this->jwtManager = $jWTTokenManagerInterface;
    }

    public function create(UserInterface $user): string
    {
        $roles = $user->getRoles();
        $expiration = $this->getExpirationByRoles($roles);

        $payload = ['exp' => time() + $expiration];
        return $this->jwtManager->createFromPayload($user, $payload);
    }

    private function getExpirationByRoles(array $roles): int
    {
        if (in_array('ROLE_WEBAPP', $roles, true)) {
            return 129600; // 1.5 jours pour le web service
        }

        if (in_array('ROLE_QLIKSENS', $roles, true)) {
            return 31536000; // 1 an pour les utilisateurs QlikSens (droit en lecture seul)
        }

        // Durée par défaut si aucun rôle spécifique n'est trouvé
        return 3600; // 1 heure par défaut
    }
}