# Checklist de Finalização TimePlus

Status da revisão original do cliente vs. o que foi entregue. Última atualização: **2026-04-28** (Sprints 3, 4 e 5 entregues).

Legenda: `[x]` feito · `[ ]` pendente · `[~]` parcial (schema pronto, UI/lógica falta)

---

## Reformulação do modelo de negócio

- [x] Psicóloga atende Particular **e** TimePlus, escolhendo na disponibilidade quais slots são de cada modalidade
- [x] Particular: psicóloga define o valor, plataforma retém **20%**
- [x] TimePlus: psicóloga recebe **R$ 30,00 integrais** por sessão; o restante pago pela empresa fica com a plataforma
- [x] Usuário pessoa física paga por consulta (valor varia por profissional)
- [x] Usuário de empresa contratante: planos de 1/2/3 consultas a R$30/sessão (schema pronto, falta filtrar exibição na home)
- [~] Empresa contrata plano **por funcionário ativo** (R$30 corretor 1º mês → R$60 nosso) — schema criado, UI da empresa não consome
- [~] Empresa contrata plano **por crédito** (R$30/sessão consumida; mensal expira; avulso 6 meses; FIFO) — tabelas `company_credit_balances`/`company_credit_usages` criadas, UI pendente
- [x] Plataforma trata saldo do usuário (cancelamento → crédito 6 meses, consumido no próximo agendamento)
- [x] Sessão configurável pela psicóloga (15–50 min, default 30)
- [x] Planos do usuário podem ser recorrentes (Asaas Subscription + webhook estende `end_date`)
- [~] Planos da empresa recorrentes — schema pronto, UI/checkout da empresa não usa ainda

## App TimePlus

- [x] Decisão técnica feita: **React Native + Jitsi Meet SDK** (single app, sem precisar instalar Jitsi à parte)
- [~] Scaffold inicial criado em `/mobile` (Expo + React Navigation + axios + jitsi-meet-sdk). Falta: criar endpoints API no Laravel (Sanctum), telas de cadastro/recuperação, push, fluxo de pagamento, EAS Build

## Validação automática do CRP

- [x] Cadastro consulta a Consultar.IO em tempo real e bloqueia CRP inexistente/inativo
- [x] Mensagem "Consulte o CRP para regularizar" quando inválido
- [ ] **Bug aberto:** Anderson conseguiu se cadastrar com CRP falso. Confirmar se foi antes do commit `bdf2894` e se `CONSULTAR_IO_TOKEN` está no `.env` do VPS. Ainda preciso deixar o `ValidCrp` **fail-closed** (qualquer falha de API = bloquear)
- [ ] Cruzar nome + número (validação cruzada)
- [ ] Validação facial / documento

---

## MASTER

### Gestão de usuários > Usuários
- [x] Filtrar por empresa (adicionado filtro select + busca via company_name)

### Gestão de usuários > Empresas
- [x] Mais informações do responsável/contato (nome, cargo, telefone) — campos `contact_name`, `contact_role`, `contact_phone` em `companies`, expostos em Master\Company\Edit e Create
- [x] Trocar senha da empresa — campo "Nova senha" (opcional) em Master\Company\Edit

### Gestão de usuários > Especialistas
- [x] Aprovar dados de pagamento cadastrados — tela `master/financeiro/aprovacoes-bancarias` (Master\Finance\BankApprovals) lista pendentes/aprovados e permite aprovar/reverter
- [x] Em editar, "usuário ativo" não aparece marcado — corrigido `<x-checkbox>` para honrar `wire:model` em SSR + cast `is_active => boolean` em Specialist
- [x] Em "visualizar", botão para ir direto pro editar — adicionado em `master/specialist/personal-data/show`
- [x] Master poder cadastrar/editar dados de pagamento dos especialistas — `master/especialistas/{id}/dados-pagamento` (Master\Specialist\PaymentData)

### Gerenciamento > Agendamentos
- [x] Criar agendamento (escolher profissional/dia/horário/modalidade) — `master/agendamentos/criar` (Master\Appointment\Create) com PayoutCalculator
- [x] Editar agendamento existente — `master/agendamentos/{id}/editar` (Master\Appointment\Edit)
- [x] Cancelar agendamento e gerar crédito FIFO — Master\Appointment\Show usa `UserCreditService::grant`
- [x] Filtro retornando 500 (corrigido via `relationSearch` user+specialist)

### Gerenciamento > Pagamentos
- [x] Filtro funcional (tipo, status, método, data, busca por empresa)
- [x] Separar pagamentos de mensalidade/sessão (filtro "Tipo": Sessão/Assinatura/Plano)
- [x] Coluna CPF adicionada; empresa filtrável via relationSearch
- [x] Dar baixa em pagamento de agendamento — botão em Master\Payment\Show com modal de observação, registra `manual_paid_at`, `manual_paid_by_master_id`, `manual_paid_note`
- [x] Relatório de baixas — `master/pagamentos/baixas` (Master\Payment\PayoffReport) com filtro por período e total

### Gerenciamento > Planos
- [x] Relatório de quantidade de usuários inscritos por plano — Master\Plan\Show mostra cards com total/ativos/cancelados/expirados + lista dos 20 últimos inscritos

### Gerenciamento > Assinaturas
- [x] Filtrar por empresa (filtro select via subquery company_user)
- [x] Filtro retornando 500 (colunas qualificadas users.name, users.cpf, plans.name)

### Gerenciamento > Salas de vídeo
- [x] Filtro por cliente e especialista (além do código)

### Gerenciamento > Disponibilidades dos Especialistas
- [x] Filtro retornando 500 (coluna qualificada specialists.name)
- [x] Filtrar por dia **E** horário (novo input text de horário)
- [x] Visualização alternativa: agenda agrupada por profissional — `master/disponibilidades/por-profissional` (Master\Availability\BySpecialistView) com filtros de período + accordion por especialista

### Financeiro - Especialistas
- [x] Fluxo de verificação implementado: especialista cadastra em `paymentProfile` → master aprova em `master/financeiro/aprovacoes-bancarias` → `is_verified=true` + `verified_at=now()`

### Configurações
- [x] Cadastrar Motivo da Consulta — CRUD já existia em `master/motivos`
- [x] Cadastrar Tipos de Formação — CRUD já existia em `master/tipos-formacao`
- [ ] Associar Motivo/Formação ao perfil do usuário (não foi feito por falta de definição: pedir ao Anderson onde aparece — cadastro? perfil? matching?)
- [ ] Relatório de alterações de agendamento e financeiro (audit log — não iniciado)

---

## USUÁRIO

### Cadastro / Acesso
- [ ] E-mail de confirmação de cadastro não chega
- [ ] E-mail de validação de e-mail não chega
- [ ] E-mail de recuperação de senha não chega

### Planos
- [ ] Planos só devem aparecer para usuários de empresa contratante
- [x] Banner "Eleve sua experiência" não aparece mais para quem já tem plano (ver Planos/Upgrade acima)
- [ ] Se desistir do pagamento de uma assinatura, deve conseguir assinar outro plano depois
- [x] Cancelar plano mantém ativo até fim do período pago (cancela só no Asaas, `cancelled_date` não derruba `isActive`, discount segue aplicando até `end_date`)

### Busca / Agendamento
- [x] Busca por nome do especialista: adicionado `wire:key` no foreach e nos componentes card/schedule
- [x] Sessão cancelada antes de pagar reaparece para seleção (filtro `status != cancelled` reabilitado)
- [x] Tela PIX faz poll a cada 5s e redireciona após confirmação (cartão já redirecionava)
- [x] Especialistas listados em ordem aleatória (`inRandomOrder()`)
- [ ] Mostrar de qual empresa o usuário faz parte
- [ ] Cadastrar **área** dentro da empresa também
- [ ] Definir o que o coração/favoritar faz

### Planos / Upgrade
- [x] Banner "Eleve sua experiência" só aparece para quem não tem plano ativo

### Cancelamento pelo especialista
- [x] Cancelamento pelo usuário gera crédito automático
- [x] Cancelamento **pelo especialista** gera crédito para o usuário + aviso no modal

### Suporte
- [x] Disponibilizar suporte na tela — botão flutuante `<x-support-button>` no layout app + guest, lê `SUPPORT_WHATSAPP` e `SUPPORT_EMAIL` do `.env`

---

## ESPECIALISTA

### Perfil
- [x] Mostrar CRP cadastrado em "dados profissionais" (sem editar)
- [x] Valor da sessão correto no perfil (não mais hardcoded R$30)
- [ ] **Bug Verônica:** 2 sessões totalizaram R$90 quando deveria ser R$60. Verificar `appointment_value` dela + os 2 registros no banco antes de mexer

### Disponibilidades
- [x] Bloquear criação de slot em datas/horários passados
- [x] Replicar disponibilidade da semana anterior (botão `replicatePreviousWeek`)
- [x] Diferenciar visualmente Agendamentos (azul/info) × Disponibilidades (verde/success) no título

### Cadastro
- [ ] **Bug aberto:** CRP falso passou no cadastro (ver Validação CRP no topo)

### Tela inicial
- [x] "Sessões de hoje" trocado por "Próximas sessões" (mostra hoje+futuras, limitado a 10)

### Aba Clientes
- [ ] Amadurecer a aba (definir o que mostrar / filtros / ações)

### Financeiro do especialista
- [x] Verificação dos dados bancários — agora aprovada pelo master em `master/financeiro/aprovacoes-bancarias`
- [x] Texto do repasse atualizado (descreve 80% particular + R$30 TimePlus)

### Suporte
- [x] Disponibilizar suporte na tela (mesmo componente flutuante)

---

## EMPRESA

### Login
- [x] Reaver senha — `empresa/recuperar-senha` + `empresa/redefinir-senha/{token}` (Livewire PasswordRecovery/PasswordReset, notifications + emails dedicados)

### Planos
- [ ] Atualmente a empresa cria o próprio plano em vez de **contratar** um plano TimePlus (e o master não vê isso). Trocar pelo fluxo de contratação dos modelos `per_employee` e `credit_pack` — fora do escopo deste sprint, exige redesenho do fluxo

### Funcionários
- [x] Empresa não deve poder alterar a senha do funcionário — removido modal/lógica de `resetPassword` em Company\Employee\Edit
- [x] Ao criar funcionário: atribuir plano da empresa automaticamente — `mount()` carrega primeiro plano ativo no `company_plan_id`, salvo no pivot `company_user`
- [x] Ao criar funcionário: **não mostrar** login/senha na tela — modal de credenciais removido, mensagem flash + redirect direto
- [x] Ao criar funcionário: enviar e-mail com o acesso — `EmployeeCredentialsNotification` enviado após save (já existia, agora é o único canal)
- [x] Importação em massa: arquivo CSV modelo para download — `downloadTemplate()` com BOM UTF-8 e exemplos com/sem pontuação
- [x] Importação em massa: tratar CPF com/sem pontuação — `formatCpf()` normaliza para `XXX.XXX.XXX-XX`; idem `formatPhone()`
- [x] Importação em massa: erro "coluna NOME" corrigido — strip do BOM UTF-8 + lowercase nos headers
- [x] Ativação/desativação em massa — checkboxes via `checkBox()` no PowerGrid + botões "Ativar/Desativar selecionados" no header

### Suporte
- [x] Disponibilizar suporte na tela (componente flutuante)

---

## Confirmações pendentes do Anderson (bloqueiam Sprint 1)

1. **CRP bypass:** o teste foi antes ou depois do commit `bdf2894` chegar no VPS? `CONSULTAR_IO_TOKEN` está no `.env` de produção? (preciso pra deixar `ValidCrp` fail-closed)
2. **Bug Verônica (R$90 vs R$60):** dump dos 2 agendamentos dela + valor `appointment_value` no `specialists`
3. **60min → 30min sessão:** o lugar exato onde ele viu "60min" (form de disponibilidade? cálculo de slots? admin? legenda?). Sprint 2 já tornou configurável, mas se houver outro lugar hardcoded preciso saber qual

---

## Ordem de sprints sugerida

| Sprint | Conteúdo | Status |
|---|---|---|
| Sprint 1 | Bugs críticos (filtros 500, e-mails, pós-pagamento, `<wire:id=...>`, CRP fail-closed, cancelamento pelo especialista) | **PARCIAL — falta fail-closed CRP, e-mails não chegando, bug Verônica, 60min hardcoded (todos bloqueados em confirmação do Anderson)** |
| Sprint 2 | Modelo de negócio reformulado | **ENTREGUE 2026-04-11** |
| Sprint 3 | UI da empresa: reaver senha, criar/editar funcionário, CSV, ativação massa, suporte | **ENTREGUE 2026-04-28** |
| Sprint 4 | Painel master: campos responsável, trocar senha empresa, aprovação bancária, dados de pagamento, CRUD agendamento, dar baixa, relatórios, agenda por profissional | **ENTREGUE 2026-04-28** (exceto: contratar plano TimePlus pela empresa e relatório de alterações) |
| Sprint 5 | App mobile React Native + Jitsi SDK | **SCAFFOLD ENTREGUE 2026-04-28** em `/mobile` — falta endpoints API + telas adicionais + EAS Build |
