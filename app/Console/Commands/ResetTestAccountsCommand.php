<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetTestAccountsCommand extends Command
{
    protected $signature = 'timeplus:reset-test-accounts
        {--force : Executa a exclusão sem confirmação interativa}
        {--dry-run : Mostra o que seria excluído, sem alterar o banco}';

    protected $description = 'Remove contas e dados de teste de usuários, especialistas e empresas, preservando masters.';

    public function handle(): int
    {
        $tables = [
            'company_credit_usages',
            'company_credit_balances',
            'user_credit_usages',
            'user_credits',
            'payments',
            'rooms',
            'appointments',
            'availabilities',
            'favorites',
            'reason_specialists',
            'trainings',
            'specialist_payment_profiles',
            'subscribes',
            'company_user',
            'company_plans',
            'users',
            'specialists',
            'companies',
            'sessions',
        ];

        $counts = collect($tables)
            ->filter(fn (string $table) => Schema::hasTable($table))
            ->mapWithKeys(fn (string $table) => [$table => DB::table($table)->count()]);

        $this->table(['Tabela', 'Registros'], $counts->map(fn ($count, $table) => [$table, $count])->values()->all());

        if ($this->option('dry-run')) {
            $this->info('Dry-run concluído. Nenhum dado foi excluído.');

            return self::SUCCESS;
        }

        if (!$this->option('force') && !$this->confirm('Excluir estes dados e preservar apenas os masters?', false)) {
            $this->warn('Operação cancelada.');

            return self::SUCCESS;
        }

        Schema::withoutForeignKeyConstraints(function () use ($counts) {
            foreach ($counts->keys() as $table) {
                DB::table($table)->delete();
            }
        });

        $this->info('Contas de teste removidas. A tabela masters foi preservada.');

        return self::SUCCESS;
    }
}
