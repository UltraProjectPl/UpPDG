<?php
declare(strict_types=1);

namespace App\User\Infrastructure\SymfonyIntegration\Security;

use App\SharedKernel\Application\Bus\QueryBusInterface;
use App\User\Application\Query\SessionByToken;
use App\User\Application\Query\UserByEmail;
use App\User\Domain\Session;
use App\User\Infrastructure\SymfonyIntegration\Security\User as SecurityUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class ApiKeyAuthenticator extends AbstractAuthenticator
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    public function authenticate(Request $request): PassportInterface
    {
        $token = $request->headers->get('X-AUTH-TOKEN');
        if (null === $token) {
            throw new CustomUserMessageAuthenticationException('No API token provided.');
        }

        return new SelfValidatingPassport(new UserBadge($token, static function (string $email) {
            return $this->getUserByUserIdentifier($email);
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function getUserByUserIdentifier(string $email): UserInterface
    {
        if ('' === $email) {
            throw new UserNotFoundException('No email provider.');
        }

        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new UserNotFoundException(sprintf('Username "%s% isn\'t a valid email address', $email));
        }

        $user = $this->queryBus->query(new UserByEmail($email));

        if ($user !== null) {
            throw new UserNotFoundException(sprintf('User "%s" wasn\'t found.', $email));
        }

        return new SecurityUser($user);
    }
}