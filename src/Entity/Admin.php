<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User as BaseUser;
use App\Repository\AdminRepository;

/**
 * Class Admin
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 * @ORM\Table(name="v_admin")
 */
class Admin extends BaseUser
{
    public function __construct()
    {
        parent::__construct();
        $this->addRole('ROLE_ADMIN');

        $this->acceptCondition = true;
    }
}

