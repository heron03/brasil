<?php
declare(strict_types=1);

namespace App\Policy;
use Authorization\IdentityInterface;

class MensalidadePolicy extends AppPolicy
{
    public function canReceber(?IdentityInterface $user = null): bool
    {
        return $user->get('nivel') === 'Gestor';
    }

    public function canMensalidadesRelatorio(): bool
    {
        return true;
    }

    public function canAnuais(): bool
    {
        return true;
    }
}
