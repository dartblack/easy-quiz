<?php

namespace App\Security\Badge;

use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

class UserRoleBadge implements BadgeInterface
{
    private bool $resolved = false;

    private array $roles;

    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @internal
     */
    public function markResolved(): void
    {
        $this->resolved = true;
    }

    public function isResolved(): bool
    {
        return $this->resolved;
    }
}
