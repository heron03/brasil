<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Irmao;
use Authorization\IdentityInterface;

class MovimentacoesCaixaPolicy extends AppPolicy
{
    public function canIndex(?IdentityInterface $user = null): bool
    {
        return Irmao::temAcessoGestao($user->get('nivel'));
    }
    public function canAdd(?IdentityInterface $user = null): bool
    {
        return Irmao::temAcessoGestao($user->get('nivel'));
    }
    public function canEdit(?IdentityInterface $user = null): bool
    {
        return Irmao::temAcessoGestao($user->get('nivel'));
    }
    public function canDelete(?IdentityInterface $user = null): bool
    {
        return Irmao::temAcessoGestao($user->get('nivel'));
    }
    public function canView(?IdentityInterface $user = null): bool
    {
        return Irmao::temAcessoGestao($user->get('nivel'));
    }
    public function canRelatorio(?IdentityInterface $user = null): bool
    {
        return Irmao::temAcessoGestao($user->get('nivel'));
    }
}
