<?php

namespace App\Security\Voter;

use App\Entity\Conta;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ContaVoter extends Voter
{
    public function __construct(private Security $security)
    {

    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [Conta::CREATE, Conta::SHOW, Conta::UPDATE, Conta::DELETE, Conta::CREDIT, Conta::DEBIT])
            && $subject instanceof \App\Entity\Conta;
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
            case Conta::CREATE:
                return $isAuth && ($this->security->isGranted('ROLE_USER'));
                break;
            case Conta::SHOW:
                return $isAuth 
                    && (($this->security->isGranted('ROLE_USER') && $subject->getUser()->getId() === $user->getId())
                    || ($this->security->isGranted('ROLE_SYS_ADMIN'))
                );
                break;
            case Conta::UPDATE:
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case Conta::DELETE:
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case Conta::CREDIT:
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case Conta::DEBIT:
                // logic to determine if the user can EDIT
                // return true or false
                break;
        }

        return false;
    }
}
