<div class="container mx-auto">
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-enterprise class="w-8 text-info" />
            Dashboard - {{ $company->name }}
        </x-title>
        <x-subtitle>
            Bem-vindo ao painel de controle da sua empresa
        </x-subtitle>
    </x-heading>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <!-- Card de Planos -->
        <x-stats-card title="Planos" :value="$company->companyPlans->count()" description="Planos configurados" icon="carbon-plan"
            color="blue" />

        <!-- Card de Funcionários -->
        <x-stats-card title="Funcionários" :value="$activeEmployeesCount" :description="$activeEmployeesCount === 1 ? 'Funcionário ativo' : 'Funcionários ativos'" :subtitle="$totalEmployeesCount > $activeEmployeesCount
            ? $totalEmployeesCount - $activeEmployeesCount . ' inativos'
            : null"
            icon="carbon-user-multiple" color="green" />

        <!-- Card de Pagamentos -->
        <x-stats-card title="Pagamentos" :value="$company->payments->count()" description="Total de pagamentos" icon="carbon-wallet"
            color="purple" />
    </div>

    <!-- Ações Rápidas -->
    <x-card>
        <x-card-body>
            <h3 class="text-xl font-semibold text-base-content mb-6">Ações Rápidas</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-action-link href="{{ route('company.plan.index') }}" icon="carbon-plan" title="Gerenciar Planos"
                    description="Configure os planos da sua empresa" icon-color="info" />

                <x-action-link href="{{ route('company.employee.create') }}" icon="carbon-user"
                    title="Adicionar Funcionários" description="Cadastre novos funcionários" icon-color="success" />

                <x-action-link href="{{ route('company.employee.index') }}" icon="carbon-user-multiple"
                    title="Gerenciar Funcionários" description="Visualize e gerencie sua equipe" icon-color="success" />

                <x-action-link href="{{ route('company.profile.show') }}" icon="carbon-settings" title="Perfil Empresa"
                    description="Ajuste as configurações da empresa" icon-color="warning" />

                {{-- TODO: Montar funcionalidade de relatório --}}
                {{-- <x-action-link href="#" icon="carbon-document" title="Relatórios"
                    description="Visualize relatórios e estatísticas" icon-color="info" /> --}}
            </div>
        </x-card-body>
    </x-card>

    <!-- Informações da Empresa -->
    <x-card class="mt-6">
        <x-card-body>
            <h3 class="text-xl font-semibold text-base-content mb-6">Informações da Empresa</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <x-info-item label="CNPJ" :value="$company->cnpj" :mono="true" />
                    <x-info-item label="E-mail" :value="$company->email" />
                    <x-info-item label="Telefone" :value="$company->phone" />
                </div>

                <div class="space-y-3">
                    <x-info-item label="Endereço" :value="$company->address" />
                    <x-info-item label="Cidade/Estado" :value="$company->city . ', ' . $company->state" />
                    <x-info-item label="CEP" :value="$company->zip_code" />
                </div>
            </div>
        </x-card-body>
    </x-card>
</div>
