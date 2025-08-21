<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\I18n\FrozenTime;
use Cake\ORM\Table;

class AppTable extends Table
{
    public function checkCep(string $value): bool
    {
        if (!preg_match('/^[0-9]{5,5}([- ]?[0-9]{3,3})?$/', $value)) {
            return false;
        }

        return true;
    }

    public function validCpf(string $value): bool
    {
        $value = preg_replace('/[^0-9]/', '', $value);
        $retorno = false;
        $retorno = $this->validaCpf($value);

        return $retorno;
    }

    public function validaCpf(?string $cpf): bool
    {
        if (!$cpf) {
            return false;
        }
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        $digitos = substr($cpf, 0, 9);
        $novoCpf = $this->calcDigitosPosicoes($digitos);
        $novoCpf = $this->calcDigitosPosicoes($novoCpf, 11);

        return $novoCpf === $cpf;
    }

    public function calcDigitosPosicoes(string $digitos, int $posicoes = 10, int $somaDigitos = 0): string
    {
        for ($i = 0; $i < strlen($digitos); $i++) {
            $somaDigitos = $somaDigitos + ((int)$digitos[$i] * $posicoes);
            $posicoes--;
        }
        $somaDigitos = $somaDigitos % 11;

        if ($somaDigitos < 2) {
            $somaDigitos = 0;
        } else {
            $somaDigitos = 11 - $somaDigitos;
        }

        $cpf = $digitos . $somaDigitos;

        return $cpf;
    }

    public function dataMaiorQueDataAtual(string $check): bool
    {
        $time = FrozenTime::createFromFormat('d/m/Y', $check);
        $now = FrozenTime::now();

        return $time > $now;
    }

    public function dataMenorQueDataAtual(string $check): bool
    {
        $time = FrozenTime::createFromFormat('d/m/Y', $check);
        $now = FrozenTime::now();

        return $time <= $now;
    }

    /** @return \Cake\Datasource\EntityInterface|false */
    public function excluir(EntityInterface $value)
    {
        $value['deleted'] = date('Y-m-d H:i');

        return $this->save($value);
    }

    public function apagar(EntityInterface $value): bool
    {
        return $this->delete($value);
    }

    public function uploadImagem(string $imagem, string $local, string $nome): ?string
    {
        $retorno = null;

        if (file_put_contents($local . $nome, $imagem)) {
            $retorno = $nome;
        }

        return $retorno;
    }

    public function getNextCodigo(int $orgaoId): int
    {
        $nextCodigo = 0;
        $conditions = ['orgao_id' => $orgaoId];
        $fields = ['numero' => 'MAX(numero)'];
        $retorno = $this->find('all', compact('conditions', 'fields'));
        $nextCodigo = $retorno->toArray()[0]['numero'];

        return $nextCodigo + 1;
    }

    public function subirImagem(array $imagem, int $orgaoId): ?string
    {
        $nomeImagem = null;

        if (is_array($imagem)) {
            if (is_uploaded_file($imagem['file']->getStream()->getMetadata('uri'))) {
                $tipo = explode('/', $imagem['file']->getClientMediaType());
                move_uploaded_file($imagem['file']->getStream()->getMetadata('uri'), BRASAO . $orgaoId . '.' . $tipo[1]);
                $nomeImagem = $orgaoId . '.' . $tipo[1];
            }
        }

        return $nomeImagem;
    }

    public function formatarCPF(string $cpf): string
    {
        return str_replace(['.', '-'], '', $cpf);
    }

    public function formatarCPFComPontuacao(string $cpf): ?string
    {
        if (strlen($cpf) == 11) {
            $cnpjMask = '%s%s%s.%s%s%s.%s%s%s-%s%s';
            $cpf = vsprintf($cnpjMask, str_split($cpf));
        }

        return $cpf;
    }
}
