<?php

namespace App\Services;

class GedService
{
    public function setoresRoot()
    {
        return '\\\\ti-pc02\\GED D';
    }

    public function root($tipo)
    {
        return match ($tipo) {
            'pf' => $this->pfRoot(),
            'pj' => $this->pjRoot(),
            'setores' => $this->setoresRoot(),
            default => null,
        };
    }

    public function pfRoot()
    {
        return '\\\\ti-pc02\\Setor de Arquivo 2020\\ARQUIVOS\\ARQUIVO DIGITAL CRF-PB\\PESSOA FISICA';
    }

    public function pjRoot()
    {
        return '\\\\ti-pc02\\Setor de Arquivo 2020\\ARQUIVOS\\ARQUIVO DIGITAL CRF-PB\\PESSOA JURIDICA';
    }

    public function fullPath($tipo, $path = '')
    {
        $base = $this->root($tipo);

        if (!$base) {
            return null;
        }

        $path = trim(urldecode($path));

        if ($path === '') {
            return $base;
        }

        return $base . '\\' . str_replace('/', '\\', $path);
    }
}