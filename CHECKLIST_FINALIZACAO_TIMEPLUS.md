# Checklist de Finalização TimePlus

Status da revisão original do cliente vs. o que foi entregue. Última atualização: **2026-04-18** (Sprint 1 parcial mergeado).

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

- [ ] Decisão técnica feita: **React Native + Jitsi Meet SDK** (single app, sem precisar instalar Jitsi à parte)
- [ ] Implementação não iniciada — Sprint 5

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
- [ ] Mais informações do responsável/contato (nome, cargo)
- [ ] Trocar senha da empresa

### Gestão de usuários > Especialistas
- [ ] Aprovar dados de pagamento cadastrados
- [ ] Em editar, "usuário ativo" não aparece marcado
- [ ] Em "visualizar", botão para ir direto pro editar
- [ ] Master poder cadastrar/editar dados de pagamento dos especialistas

### Gerenciamento > Agendamentos
- [ ] Criar agendamento (escolher profissional/dia/horário)
- [ ] Editar agendamento existente
- [ ] Cancelar agendamento (e gerar saldo/crédito automático para o usuário)
- [x] Filtro retornando 500 (corrigido via `relationSearch` user+specialist)

### Gerenciamento > Pagamentos
- [x] Filtro funcional (tipo, status, método, data, busca por empresa)
- [x] Separar pagamentos de mensalidade/sessão (filtro "Tipo": Sessão/Assinatura/Plano)
- [x] Coluna CPF adicionada; empresa filtrável via relationSearch
- [ ] Dar baixa em pagamento de agendamento
- [ ] Relatório de baixas

### Gerenciamento > Planos
- [ ] Relatório de quantidade de usuários/empresas inscritos por plano

### Gerenciamento > Assinaturas
- [x] Filtrar por empresa (filtro select via subquery company_user)
- [x] Filtro retornando 500 (colunas qualificadas users.name, users.cpf, plans.name)

### Gerenciamento > Salas de vídeo
- [x] Filtro por cliente e especialista (além do código)

### Gerenciamento > Disponibilidades dos Especialistas
- [x] Filtro retornando 500 (coluna qualificada specialists.name)
- [x] Filtrar por dia **E** horário (novo input text de horário)
- [ ] Visualização alternativa: ver agenda **por profissional** (em vez da lista plana)

### Financeiro - Especialistas
- [ ] Definir/implementar fluxo de verificação dos dados bancários do especialista

### Configurações
- [ ] Cadastrar Motivo da Consulta e Tipos de Formação para usuários
- [ ] Relatório de alterações de agendamento e financeiro

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
- [ ] Disponibilizar suporte na tela (definir: e-mail / WhatsApp / ticket)

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
- [ ] Verificação dos dados bancários (precisa do master, ver acima)
- [x] Texto do repasse atualizado (descreve 80% particular + R$30 TimePlus)

### Suporte
- [ ] Disponibilizar suporte na tela

---

## EMPRESA

### Login
- [ ] Reaver senha (não tem opção)

### Planos
- [ ] Atualmente a empresa cria o próprio plano em vez de **contratar** um plano TimePlus (e o master não vê isso). Trocar pelo fluxo de contratação dos modelos `per_employee` e `credit_pack`

### Funcionários
- [ ] Empresa não deve poder alterar a senha do funcionário
- [ ] Ao criar funcionário: atribuir plano da empresa automaticamente
- [ ] Ao criar funcionário: **não mostrar** login/senha na tela
- [ ] Ao criar funcionário: enviar e-mail com o acesso
- [ ] Importação em massa: arquivo CSV modelo para download
- [ ] Importação em massa: tratar CPF com/sem pontuação
- [ ] Importação em massa: corrigir erro "coluna NOME"
- [ ] Ativação/desativação de funcionários **em massa** (checkbox por linha)

### Suporte
- [ ] Disponibilizar suporte na tela

---

## Confirmações pendentes do Anderson (bloqueiam Sprint 1)

1. **CRP bypass:** o teste foi antes ou depois do commit `bdf2894` chegar no VPS? `CONSULTAR_IO_TOKEN` está no `.env` de produção? (preciso pra deixar `ValidCrp` fail-closed)
2. **Bug Verônica (R$90 vs R$60):** dump dos 2 agendamentos dela + valor `appointment_value` no `specialists`
3. **60min → 30min sessão:** o lugar exato onde ele viu "60min" (form de disponibilidade? cálculo de slots? admin? legenda?). Sprint 2 já tornou configurável, mas se houver outro lugar hardcoded preciso saber qual

---

## Ordem de sprints sugerida

| Sprint | Conteúdo | Status |
|---|---|---|
| Sprint 1 | Bugs críticos (filtros 500, e-mails, pós-pagamento, `<wire:id=...>`, CRP fail-closed, cancelamento pelo especialista) | **PRÓXIMO** |
| Sprint 2 | Modelo de negócio reformulado | **ENTREGUE 2026-04-11** |
| Sprint 3 | UI da empresa: contratar plano recorrente, gerenciar créditos, importação massa, ativação massa, reaver senha | Schema pronto |
| Sprint 4 | Painel master: aprovações, edições, relatórios, separar pagamentos, dar baixa, filtros | Não iniciado |
| Sprint 5 | App mobile React Native + Jitsi SDK | Não iniciado |
