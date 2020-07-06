<?php

namespace App\Security\Voter;

use App\Entity\Propietario;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class PropietarioVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['MANAGE'])
            && $subject instanceof Propietario;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Propietario $subject */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'MANAGE':
                // validar si este recurso tiene asociado algun atributo de usuario o si fue creado por ese usuario
               if($subject->getRazonSocial() != ""){
                   return true;
               }
               if ($this->security->isGranted('ROLE_ADMIN_PROPIETARIO')){
                   return true;
               }

               return false;
        }

        return false;
    }
}
