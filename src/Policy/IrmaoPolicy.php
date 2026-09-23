<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Irmao;
use Authorization\IdentityInterface;
use Cake\Datasource\EntityInterface;

class IrmaoPolicy extends AppPolicy
{
    public function CanLoginRedirect(): bool
    {
        return true;
    }

    public function canAdd(?IdentityInterface $user = null, $resource = null): bool
    {
        return Irmao::temAcessoGestao($this->nivel($user));
    }

    public function canEdit(?IdentityInterface $user = null, $resource = null): bool
    {
        if (Irmao::temAcessoGestao($this->nivel($user))) {
            return true;
        }

        return $this->eProprioIrmao($user, $resource);
    }

    public function canView(?IdentityInterface $user = null, $resource = null): bool
    {
        return $this->canEdit($user, $resource);
    }

    public function canDelete(?IdentityInterface $user = null, $resource = null): bool
    {
        return Irmao::temAcessoGestao($this->nivel($user));
    }

    public function canEditSenha(?IdentityInterface $user = null, $resource = null): bool
    {
        if (Irmao::temAcessoGestao($this->nivel($user))) {
            return true;
        }

        return $this->eProprioIrmao($user, $resource);
    }

    private function eProprioIrmao(?IdentityInterface $user, $resource): bool
    {
        if (!$resource instanceof EntityInterface) {
            return false;
        }

        $id = (int)$resource->get('id');

        return $id > 0 && $id === $this->usuarioId($user);
    }

    private function nivel(?IdentityInterface $user): ?string
    {
        if ($user === null) {
            return null;
        }

        $nivel = $user->get('nivel');

        return $nivel === null ? null : (string)$nivel;
    }

    private function usuarioId(?IdentityInterface $user): int
    {
        if ($user === null) {
            return 0;
        }

        return (int)$user->get('id');
    }
}
