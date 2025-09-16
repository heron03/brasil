<?php
declare(strict_types=1);

namespace App\Policy;

class IrmaoPolicy extends AppPolicy
{
    public function CanLoginRedirect(): bool
    {
        return true;
    }
}
