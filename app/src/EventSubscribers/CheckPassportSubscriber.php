<?php

namespace App\EventSubscribers;

use App\Security\Badge\UserRoleBadge;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

class CheckPassportSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => 'checkUserRole',
        ];
    }

    public function checkUserRole(CheckPassportEvent $event): void
    {
        $passport = $event->getPassport();
        if (!$passport->hasBadge(UserRoleBadge::class)) {
            return;
        }
        /** @var UserRoleBadge $userRoleBadge */
        $userRoleBadge = $passport->getBadge(UserRoleBadge::class);

        if ($userRoleBadge->isResolved()) {
            return;
        }

        foreach ($userRoleBadge->getRoles() as $role) {
            if (in_array($role, $passport->getUser()->getRoles())) {
                $userRoleBadge->markResolved();

                return;
            }
        }

        throw new AuthenticationException('ROLE not valid');
    }
}
