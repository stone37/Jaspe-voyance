<?php

namespace App\Handler;

use App\Entity\NewsletterData;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NewsletterSubscriptionHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $em;


    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * @param string $email
     * @param string $firstname
     */
    public function subscribe(string $email, string $firstname)
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user instanceof User) {
            $this->updateUser($user);
        }

        $this->createNewsletter($email, $firstname);
    }

    public function unsubscribe(NewsletterData $data)
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $data->getEmail()]);

        if ($user instanceof User) {
            $this->updateUser($user, false);
        }

        $this->deleteNewsletter($data);
    }

    /**
     * @param string $email
     */
    private function createNewsletter($email, $firstname)
    {
        $newsletter = (new NewsletterData())
                    ->setEmail($email)
                    ->setFirstName($firstname);

        $this->em->persist($newsletter);
        $this->em->flush();
    }

    private function deleteNewsletter(NewsletterData $data)
    {
        $this->em->remove($data);
        $this->em->flush();
    }


    private function updateUser(User $user, $subscribedToNewsletter = true)
    {
        $user->setSubscribedToNewsletter($subscribedToNewsletter);
        $this->em->flush();
    }
}
