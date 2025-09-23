<div>
    @if (session()->has('success'))
        <div role="alert" class="alert alert-success shadow-lg mb-6">
            <x-carbon-checkmark class="w-6 h-6" />
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif


    <livewire:company.plan.show-table />
</div>
