<?php

namespace App\Validator\Constraints;

use App\Entity\NewsletterData;
use App\Repository\NewsletterDataRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueNewsletterEmailValidator extends ConstraintValidator
{
    private $repository;

    public function __construct(NewsletterDataRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate($email, Constraint $constraint)
    {
        if ($this->isEmailValid($email) === false) {
            $this->context->addViolation($constraint->message);
        }
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    private function isEmailValid($email)
    {
        $newsletter = $this->repository->findOneBy(['email' => $email]);

        if ($newsletter instanceof NewsletterData) {
            return false;
        }

        return true;
    }
}
