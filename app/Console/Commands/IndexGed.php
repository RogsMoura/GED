<?php

namespace App\Console\Commands;

use App\Services\GedIndexerService;
use App\Services\GedService;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:index-ged')]
#[Description('Command description')]
class IndexGed extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(GedIndexerService $indexer, GedService $ged)
    {
        $this->info('Iniciando PF...');

        $indexer->indexFolder('pf', $ged->pfRoot());

        $this->info('PF concluído');

        $this->info('Iniciando PJ...');

        $indexer->indexFolder('pj', $ged->pjRoot());

        $this->info('PJ concluído');

        $this->info('Iniciando Setores...');

        $indexer->indexFolder('setores', $ged->setoresRoot());

        $this->info('Setores concluído');
    }
}
