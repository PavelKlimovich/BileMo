<?php

namespace App\Services;

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
        if ($this->security->getUser()?->getCustomer()?->getId() == $customer->getId()) {
            return true;
        }

        return false;
    }
}