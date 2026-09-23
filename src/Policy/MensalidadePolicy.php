<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Irmao;
use Authorization\IdentityInterface;
use Cake\Datasource\EntityInterface;

class MensalidadePolicy extends AppPolicy
{
    public function canAdd(?IdentityInterface $user = null, $resource = null): bool
    {
        return Irmao::temAcessoGestao($this->nivel($user));
    }

    public function canEdit(?IdentityInterface $user = null, $resource = null): bool
    {
        return Irmao::temAcessoGestao($this->nivel($user));
    }

    public function canDelete(?IdentityInterface $user = null, $resource = null): bool
    {
        return Irmao::temAcessoGestao($this->nivel($user));
    }

    public function canReceber(?IdentityInterface $user = null, $resource = null): bool
    {
        return Irmao::temAcessoGestao($this->nivel($user));
    }

    public function canLimparPagamento(?IdentityInterface $user = null, $resource = null): bool
    {
        return Irmao::temAcessoGestao($this->nivel($user));
    }

    public function canView(?IdentityInterface $user = null, $resource = null): bool
    {
        return $this->podeVerPropria($user, $resource);
    }

    public function canRecibo(?IdentityInterface $user = null, $resource = null): bool
    {
        return $this->podeVerPropria($user, $resource);
    }

    public function canMensalidadesRelatorio(): bool
    {
        return true;
    }

    public function canAnuais(): bool
    {
        return true;
    }

    private function podeVerPropria(?IdentityInterface $user, $resource): bool
    {
        if (Irmao::temAcessoGestao($this->nivel($user))) {
            return true;
        }

        if (!$resource instanceof EntityInterface) {
            return false;
        }

        $irmaoId = (int)$resource->get('irmao_id');

        return $irmaoId > 0 && $irmaoId === $this->usuarioId($user);
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
