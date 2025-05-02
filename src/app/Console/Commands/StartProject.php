<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartProject extends Command
{
    protected $signature = 'project:start';
    protected $description = 'Executa setup completo via Docker: builda, instala dependências e roda o dev.';

    public function handle()
    {
        $this->info('🔧 Buildando containers...');
        $this->runLocalShell('docker-compose up --build -d');

        $this->info('📦 Instalando dependências PHP...');
        $this->runLocalShell('docker-compose exec app composer install');

        $this->info('🧱 Migrando e populando banco...');
        $this->runLocalShell('docker-compose exec app php artisan migrate --seed');

        $this->info('📦 Instalando dependências JS...');
        $this->runLocalShell('docker-compose exec app npm install');

        $this->info('🎨 Iniciando Vite em modo dev...');
        $this->runLocalShell('docker-compose exec app npm run dev');

        $this->info('✅ Projeto iniciado com sucesso!');
        return 0;
    }

    protected function runLocalShell($command)
    {
        $this->info("→ Executando: $command");
        passthru($command);
    }
}
