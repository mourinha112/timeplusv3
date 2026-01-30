# Checklist de Correções e Novas Funcionalidades
## Time Plus – Baseado na Auditoria + Novas Demandas

---

## 1. BANCO DE DADOS – Correções e Novas Tabelas

### 1.1 Correções Obrigatórias (Auditoria)

- [ ] **Constraint appointments:** Remover `unique(appointment_date, appointment_time)` e criar `unique(specialist_id, appointment_date, appointment_time)` (migration já criada – rodar se ainda não rodou).
- [ ] **Coluna specialists:** Adicionar coluna `is_active` na tabela `specialists` (ou remover do fillable do Model).
- [ ] **Índices – appointments:** `specialist_id`, `status`, composite `(specialist_id, appointment_date, status)`.
- [ ] **Índices – availabilities:** `specialist_id`, `available_date`, composite `(specialist_id, available_date)`.
- [ ] **Índices – subscribes:** `plan_id`, `end_date`, composite `(user_id, end_date, cancelled_date)`.
- [ ] **Índices – specialists:** `gender_id`, `specialty_id`, `state_id`, `onboarding_step`.
- [ ] **Índices – company_plans:** `company_id`, `is_active`.
- [ ] **Índices – rooms:** `appointment_id`, `status`.
- [ ] **Índices – trainings:** `specialist_id`, `training_type_id`.
- [ ] **Índices – users:** `is_active`, `recovery_password_token`.
- [ ] **Índices – companies:** `is_active`.
- [ ] **FK rooms.created_by:** Definir tipo (FK para users ou specialists) ou validar em aplicação.
- [ ] **FK companies.state:** Referenciar `states` (abbreviation) ou validar em aplicação.
- [ ] **Cascade nullable:** Ajustar FKs nullable para `onDelete('set null')` onde fizer sentido.
- [ ] **Model Plan:** Adicionar relacionamento `hasMany(Subscribe::class)`.

### 1.2 Novas Tabelas – Repasse / Split

- [ ] **specialist_payment_profiles** (ou equivalente): dados para repasse do especialista (conta Asaas, tipo conta, chave PIX, etc.).
- [ ] **payment_splits** (ou **payouts**): registro de cada repasse (payment_id, specialist_id, valor_repasse, taxa_plataforma, status, gateway_transfer_id, paid_at).
- [ ] **platform_fees** (opcional): configuração de taxa por tipo (sessão, plano, %) ou uso de config/ENV.

### 1.3 Novas Tabelas – Agendamento (se necessário)

- [ ] **appointment_cancellations** (opcional): motivo, cancelado_por, data_hora (rastreio de cancelamentos).
- [ ] **appointment_reminders** (opcional): agendamento de lembretes (email/push) antes da sessão.
- [ ] Avaliar se `availabilities` e `appointments` cobrem todo o fluxo psicólogo–paciente; se sim, apenas índices e constraints acima.

### 1.4 Novas Colunas em Tabelas Existentes

- [ ] **specialists:** `gateway_account_id` (Asaas) ou referência à tabela de perfil de pagamento.
- [ ] **payments:** `platform_fee`, `specialist_amount`, `split_status`, `payout_id` (FK para payment_splits/payouts).

---

## 2. SEGURANÇA (Auditoria)

- [ ] Criar e registrar **Laravel Policies** para: User, Specialist, Company, Employee (CompanyUser), Appointment, Payment, Plan, Room.
- [ ] Aplicar `authorize()` ou `@can` em todas as ações críticas (show, edit, update, delete).
- [ ] **Company/Employee/ShowTable:** Garantir verificação de ownership (company_id) antes de `show($rowId)` e em todas as ações.
- [ ] **XSS PowerGrid:** Remover HTML puro em colunas; usar Blade components ou `e()`/`Str::limit()` para exibição de texto.
- [ ] **AsaasException:** Não expor file/line em produção; tratar em `Handler` e retornar mensagem genérica.
- [ ] Revisar todos os pontos que usam `findOrFail($id)` sem checagem de tenant/ownership (Company, User, Specialist).

---

## 3. ARQUITETURA E CÓDIGO (Auditoria)

- [ ] Criar **DiscountService** e extrair lógica de `calculateDiscount` de CreditCard, Pix e Schedule.
- [ ] Criar **RoomService** e centralizar criação de Room (remover duplicação em AsaasWebhookController e CreditCard).
- [ ] Criar **PaymentProcessService** (ou similar) para centralizar criação de pagamento no gateway e no banco.
- [ ] Substituir `new JitsiService()` por **injeção de dependência** (constructor ou método).
- [ ] **AsaasManagerService:** Retornar instâncias resolvidas do container (ex.: `app(CustomerService::class)`) em vez de `new CustomerService()`.
- [ ] Unificar componentes de **Login** (User, Specialist, Master, Company) em trait ou componente base com guard configurável.
- [ ] Remover **valores hardcoded** em PaymentService (postalCode, addressNumber); usar config ou dados do customer.

---

## 4. TESTES (Auditoria)

- [ ] Testes para **AsaasWebhookController** (handle: PAYMENT_RECEIVED, PAYMENT_CONFIRMED, criação de room, notificações).
- [ ] Testes para **User\Auth\Register** e **Specialist\Auth\Register** (validação, criação, Asaas, notificações).
- [ ] Testes para **Payment** (CreditCard, PIX): criação de cobrança, atualização de status, desconto.
- [ ] Testes para **DiscountService** e **RoomService** (unit).
- [ ] Testes para regras **ValidatedCpf**, **FormattedCpf**, **FormattedPhoneNumber**.
- [ ] Testes para **Console commands** (rooms:open-scheduled, rooms:close-expired).
- [ ] Aumentar **PHPStan** para level 6 ou 7 e corrigir erros.

---

## 5. PERFORMANCE (Auditoria)

- [ ] **Redis:** Usar para `CACHE_STORE` e `SESSION_DRIVER` em produção (já configurado no .env).
- [ ] **N+1:** Adicionar `with()`/`load()` em Master/User/PersonalData/ShowTable e Company/Dashboard/Show (counts/relacionamentos).
- [ ] Cache de consultas pesadas (ex.: listagem de especialistas com filtros) onde fizer sentido.

---

## 6. SISTEMA DE REPASSE COMPLETO (SPLIT DE PAGAMENTOS)

### 6.1 Modelagem e Backend

- [ ] Definir **regra de negócio:** % plataforma x % especialista (ex.: 20% / 80%), e onde configurar (config, ENV ou tabela).
- [ ] Criar **migrations** para tabelas de repasse (payment_splits/payouts, specialist_payment_profiles, e colunas em payments/specialists).
- [ ] Cadastro do especialista no **Asaas** (conta para receber): criar customer/subconta ou usar Split já na cobrança – documentar API Asaas (split por percentual ou valor fixo).
- [ ] **PaymentService (ou novo SplitService):** ao criar cobrança, definir split (valor plataforma x valor especialista) conforme API Asaas.
- [ ] **Webhook PAYMENT_RECEIVED/CONFIRMED:** além de atualizar Payment e criar Room, registrar/atualizar **payment_splits** (valores, status).
- [ ] **Listagem de repasses:** para Master e para o próprio Especialista (minhas sessões pagas, valores a receber/recebidos).
- [ ] **Fluxo de saque/transferência:** se Asaas fizer repasse automático, apenas registrar; se for “solicitar saque”, criar endpoint e integração com API de transfer do Asaas.

### 6.2 Painel e Regras

- [ ] **Master:** tela de repasses (por período, por especialista, por pagamento).
- [ ] **Especialista:** tela “Minhas finanças” ou “Repasses” (valor por sessão, total disponível, histórico).
- [ ] Definir **quando** o repasse é liberado (na confirmação do pagamento, D+1, D+30, etc.) e documentar.

---

## 7. SISTEMA DE AGENDAMENTO (PSICÓLOGA E PACIENTE)

### 7.1 Fluxo Atual (Só Ajustes)

- [ ] Garantir que **constraint de appointments** está correta (um horário por especialista, não global).
- [ ] **Paciente:** escolha de especialista → escolha de data/hora a partir de **availabilities** do especialista → criação de appointment → checkout (pagamento).
- [ ] **Psicóloga:** gestão de **availabilities** (blocos de horário disponíveis); listagem de **appointments** (agenda).
- [ ] Evitar **dupla reserva:** ao criar appointment, bloquear o slot (availability ou appointment) e validar no backend.

### 7.2 Melhorias Desejáveis

- [ ] **Calendário visual** para o especialista (semana/mês) com slots ocupados e disponíveis.
- [ ] **Lembretes** para paciente e especialista (e-mail/SMS) X horas antes do horário (tabela opcional + job).
- [ ] **Cancelamento:** fluxo de cancelamento com motivo (e opcionalmente política de reembolso).
- [ ] **Reagendamento:** permitir troca de data/hora com as mesmas validações de disponibilidade.
- [ ] **Bloqueio de horário:** especialista pode bloquear horários sem criar availability (feriados, férias).

### 7.3 Novas Tabelas (Se Implementar)

- [ ] **appointment_cancellations** (appointment_id, cancelled_by_user_id, reason, cancelled_at).
- [ ] **appointment_reminders** (appointment_id, channel, scheduled_at, sent_at).
- [ ] **availability_blocks** (opcional): bloqueios (especialista, data_inicio, data_fim, motivo).

---

## 8. RESUMO – NOVAS TABELAS

| Tabela | Objetivo |
|--------|----------|
| **payment_splits** (ou payouts) | Registrar repasse por pagamento (valor plataforma, valor especialista, status, gateway_transfer_id). |
| **specialist_payment_profiles** | Dados bancários/Asaas do especialista para receber repasse. |
| **appointment_cancellations** | Histórico de cancelamentos (quem, quando, motivo). |
| **appointment_reminders** | Lembretes agendados (opcional). |
| **availability_blocks** | Bloqueios de agenda do especialista (opcional). |
| **platform_fees** (opcional) | Configuração de taxa por tipo de serviço. |

---

## 9. ORDEM SUGERIDA DE EXECUÇÃO

1. **Banco:** constraint appointments + coluna is_active (specialists) + índices críticos.
2. **Segurança:** Policies + ownership em Company/Employee + XSS PowerGrid + Exception.
3. **Repasse:** migrations (payments + splits + specialist profile) → integração Asaas Split → webhook atualizando split → telas Master e Especialista.
4. **Agendamento:** garantir constraint e fluxo atual; depois calendário, lembretes, cancelamento.
5. **Refatoração:** DiscountService, RoomService, PaymentProcessService, DI.
6. **Testes:** webhook, registro, pagamento, regras, commands.
7. **Performance:** Redis, N+1, cache.

---

**Documento gerado com base na auditoria técnica e nas demandas de repasse e agendamento.**
