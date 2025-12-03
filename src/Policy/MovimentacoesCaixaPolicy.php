<?php
declare(strict_types=1);

namespace App\Policy;
use Authorization\IdentityInterface;

class MovimentacoesCaixaPolicy extends AppPolicy
{
    public function canIndex(?IdentityInterface $user = null): bool
    {
        return $user->get('nivel') === 'Gestor';
    }
    public function canAdd(?IdentityInterface $user = null): bool
    {
        return $user->get('nivel') === 'Gestor';
    }
    public function canEdit(?IdentityInterface $user = null): bool
    {
        return $user->get('nivel') === 'Gestor';
    }
    public function canDelete(?IdentityInterface $user = null): bool
    {
        return $user->get('nivel') === 'Gestor';
    }
    public function canView(?IdentityInterface $user = null): bool
    {
        return $user->get('nivel') === 'Gestor';
    }
    public function canRelatorio(?IdentityInterface $user = null): bool
    {
        return $user->get('nivel') === 'Gestor';
    }
}
