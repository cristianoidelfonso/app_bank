<?php

namespace App\Security\Voter;

use App\Entity\Banco;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BancoVoter extends Voter
{
    public function __construct(private Security $security)
    {

    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [Banco::CREATE, Banco::EDIT, Banco::VIEW])
            && $subject instanceof \App\Entity\Banco;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        /** @var User $user */
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        // if (!$user instanceof UserInterface) {
        //     return false;
        // }

        $isAuth = $user instanceof UserInterface;

        if ($this->security->isGranted('ROLE_SYS_ADMIN')) {
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case Banco::CREATE:
                return $isAuth && (
                    $this->security->isGranted('ROLE_SYS_ADMIN') || $subject->getAuthor()->getId() === $user->getId()
                );
                break;
            case Banco::EDIT:
                return $isAuth && (
                    $this->security->isGranted('ROLE_ADMIN_BANCO') || $subject->getAuthor()->getId() === $user->getId()
                );
                break;
            case Banco::VIEW:
                return true;
                break;
        }

        return false;
    }
}
