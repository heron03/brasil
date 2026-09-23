<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Irmao;
use Authorization\IdentityInterface;

class LojaPolicy extends AppPolicy
{
    public function canIndex(?IdentityInterface $user = null, $resource = null): bool
    {
        return $this->gestao($user);
    }

    public function canAdd(?IdentityInterface $user = null, $resource = null): bool
    {
        return $this->gestao($user);
    }

    public function canEdit(?IdentityInterface $user = null, $resource = null): bool
    {
        return $this->gestao($user);
    }

    public function canDelete(?IdentityInterface $user = null, $resource = null): bool
    {
        return $this->gestao($user);
    }

    public function canView(?IdentityInterface $user = null, $resource = null): bool
    {
        return $this->gestao($user);
    }

    private function gestao(?IdentityInterface $user): bool
    {
        if ($user === null) {
            return false;
        }

        $nivel = $user->get('nivel');

        return Irmao::temAcessoGestao($nivel === null ? null : (string)$nivel);
    }
}
