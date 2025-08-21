<?php
declare(strict_types=1);

namespace App\Policy;

class AppPolicy
{
    public function canAdd(): bool
    {
        return true;
    }

    public function canEdit(): bool
    {
        return true;
    }

    public function canDelete(): bool
    {
        return true;
    }

    public function canView(): bool
    {
        return true;
    }

    public function canIndex(): bool
    {
        return true;
    }

    public function canReport(): bool
    {
        return true;
    }
}
