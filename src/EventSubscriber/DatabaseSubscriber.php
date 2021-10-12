<?php

namespace App\EventSubscriber;

use App\Entity\Proposition;
use App\Entity\Sale;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DatabaseSubscriber implements EventSubscriber
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * DatabaseSubscriber constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Proposition) {
            /** @var Proposition $entity */
            $entity->setCreatedAt(new \DateTimeImmutable());

            if (is_null($entity->getVendor())) {
                $entity->setVendor($this->tokenStorage->getToken()->getUser());
            }
        }

        if ($entity instanceof Sale) {
            /** @var Sale $entity */
            $entity->setGoal($entity->getProposition()->getVendor()->getGoal());
        }
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }
}
