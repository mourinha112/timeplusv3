<div>
    <div class="card w-96 bg-base-100 card-sm shadow-sm mt-3">
        <div class="card-body flex flex-row justify-between items-center">
            <div>
                <h2 class="card-title">Cliente</h2>
                <p>Acessar plataforma do cliente.</p>
            </div>
            <div class="justify-end card-actions">
                <a href="{{ route('user.auth.login') }}" class="btn btn-info">Acessar</a>
            </div>
        </div>
    </div>
    <div class="card w-96 bg-base-100 card-sm shadow-sm mt-3">
        <div class="card-body flex flex-row justify-between items-center">
            <div>
                <h2 class="card-title">Especialista</h2>
                <p>Acessar plataforma do especialista.</p>
            </div>
            <div class="justify-end card-actions">
                <a href="{{ route('specialist.auth.login') }}" class="btn btn-info">Acessar</a>
            </div>
        </div>
    </div>
</div>
