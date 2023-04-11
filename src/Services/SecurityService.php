<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\Customer;
use Symfony\Bundle\SecurityBundle\Security;

class SecurityService
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function ifAuthorisation(Customer $customer): bool
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if ($user->getCustomer()?->getId() == $customer->getId()) {
            return true;
        }

        return false;
    }
}
