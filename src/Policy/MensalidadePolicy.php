<?php
declare(strict_types=1);

namespace App\Policy;

class MensalidadePolicy extends AppPolicy
{
    public function canReceber(): bool
    {
        return true;
    }
}
