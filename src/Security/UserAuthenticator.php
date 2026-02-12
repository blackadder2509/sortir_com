<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const string LOGIN_ROUTE = 'app_login';

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UserRepository $userRepository
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository
    ) {}
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository
    ) {
    }

    public function authenticate(Request $request): Passport
    {
        $identifier = $request->request->get('_username', '');
        $password = $request->request->get('_password', '');
        $csrfToken = $request->request->get('_csrf_token');
        // On récupère ce que l'utilisateur a tapé (email ou pseudo)
        $login = $request->request->get('email', '');
        $identifier = $request->getPayload()->getString('email');
        $password = $request->getPayload()->getString('password');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $identifier);

        return new Passport(
            new UserBadge($login, function (string $userIdentifier) {
                // On cherche d'abord par email, sinon par pseudo
                $user = $this->userRepository->findOneBy(['email' => $userIdentifier]);
            new UserBadge($identifier, function($userIdentifier) {
                $user = $this->userRepository->findOneByEmailOrUsername($userIdentifier);

                if (!$user) {
                    $user = $this->userRepository->findOneBy(['pseudo' => $userIdentifier]);
                }

                if (!$user->isActif()) {
                    throw new CustomUserMessageAuthenticationException("Ce compte est désactivé.");
                }

                return $user;
            }),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $csrfToken),
                new RememberMeBadge(),
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
                new RememberMeBadge(), // Point n°2 : Se souvenir de moi
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate(name: 'app_sortie_index'));
        // Redirection vers ta liste des sorties après connexion
        return new RedirectResponse($this->urlGenerator->generate('app_sortie_index'));
        // Correction : Utilisez le NOM de la route, pas l'URL
        // Si ta route pour /campus s'appelle 'app_campus_index' :
        return new RedirectResponse($this->urlGenerator->generate('app_campus_index'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
