# Relatório de Auditoria Técnica
## Plataforma Time Plus

---

**Documento Confidencial**

| Informação | Detalhe |
|------------|---------|
| **Cliente** | Time Plus |
| **Data da Auditoria** | 28 de Janeiro de 2026 |
| **Versão do Documento** | 1.0 |
| **Classificação** | Confidencial |

---

## Sumário Executivo

Este documento apresenta os resultados da auditoria técnica completa realizada na plataforma **Time Plus**, um sistema de agendamento e telemedicina que conecta usuários a especialistas de saúde mental.

A análise abrangeu **368 arquivos de código**, **23 migrações de banco de dados**, **20 modelos de dados** e toda a infraestrutura de serviços e segurança da aplicação.

### Resultado Geral

| Categoria | Avaliação | Status |
|-----------|-----------|--------|
| Arquitetura de Código | 7/10 | ⚠️ Atenção |
| Estrutura de Banco de Dados | 6/10 | ⚠️ Atenção |
| Segurança | 5/10 | 🔴 Crítico |
| Cobertura de Testes | 3/10 | 🔴 Crítico |
| Performance | 6/10 | ⚠️ Atenção |
| **Score Geral** | **5.5/10** | ⚠️ Requer Correções |

---

## 1. Visão Geral da Plataforma

### 1.1 Stack Tecnológico Atual

| Componente | Tecnologia | Versão |
|------------|------------|--------|
| Framework Backend | Laravel | 12.0 |
| Linguagem | PHP | 8.2 |
| Frontend | Livewire | 3.6 |
| Banco de Dados | MySQL | 8.0 |
| Cache/Session | Database | - |
| Gateway de Pagamentos | Asaas | v3 API |
| Video Conferência | Jitsi Meet | - |
| Serviço de Email | SendGrid | - |
| Containerização | Docker | 3.8 |

### 1.2 Módulos Identificados

A plataforma possui **4 painéis distintos**:

1. **Painel do Usuário (Paciente)**
   - Cadastro e autenticação
   - Busca e agendamento com especialistas
   - Pagamento de consultas e planos
   - Histórico de atendimentos
   - Video chamada integrada

2. **Painel do Especialista (Profissional)**
   - Cadastro com onboarding
   - Gestão de disponibilidade
   - Visualização de agendamentos
   - Atendimento via video chamada
   - Gestão de clientes

3. **Painel Master (Administrador)**
   - Dashboard com métricas
   - Gestão de usuários e especialistas
   - Gestão de empresas e planos
   - Visualização de pagamentos

4. **Painel Empresa (B2B)**
   - Gestão de funcionários
   - Planos corporativos
   - Relatórios de utilização

---

## 2. Análise de Banco de Dados

### 2.1 Estrutura Atual

Foram identificadas **24 tabelas** no esquema do banco de dados:

| Categoria | Tabelas |
|-----------|---------|
| Usuários | `users`, `specialists`, `masters`, `companies` |
| Negócio | `appointments`, `availabilities`, `payments`, `subscribes`, `plans` |
| Relacionamento | `favorites`, `reason_specialists`, `company_user`, `company_plans` |
| Referência | `genders`, `states`, `specialties`, `reasons`, `training_types` |
| Sistema | `sessions`, `cache`, `jobs`, `failed_jobs`, `telescope_entries` |

### 2.2 Problemas Críticos Identificados

#### 🔴 CRÍTICO: Constraint Incorreta na Tabela de Agendamentos

**Impacto:** Impede que múltiplos especialistas tenham consultas no mesmo horário.

```sql
-- ATUAL (INCORRETO)
UNIQUE KEY (appointment_date, appointment_time)

-- CORRETO
UNIQUE KEY (specialist_id, appointment_date, appointment_time)
```

**Risco:** Falhas em agendamentos simultâneos de diferentes especialistas.

---

#### 🔴 CRÍTICO: Coluna Ausente no Modelo de Especialistas

O modelo `Specialist` declara o campo `is_active` como preenchível, porém **a coluna não existe na tabela**.

**Risco:** Erros em runtime ao tentar ativar/desativar especialistas.

---

#### ⚠️ ALTO: Índices de Performance Ausentes

Foram identificados **15+ índices importantes não criados**, impactando diretamente a performance:

| Tabela | Índices Faltantes | Impacto |
|--------|-------------------|---------|
| `appointments` | `specialist_id`, `status`, composite | Consultas lentas de agendamentos |
| `availabilities` | `specialist_id`, `available_date` | Busca de horários lenta |
| `subscribes` | `plan_id`, `end_date` | Verificação de assinaturas lenta |
| `specialists` | `gender_id`, `specialty_id`, `state_id` | Filtros de busca lentos |
| `company_plans` | `company_id`, `is_active` | Listagem de planos lenta |
| `rooms` | `appointment_id`, `status` | Gestão de salas lenta |
| `trainings` | `specialist_id`, `training_type_id` | Listagem de formações lenta |

**Estimativa de Impacto:** Consultas podem ser até **10x mais lentas** sem os índices adequados em tabelas com mais de 10.000 registros.

---

#### ⚠️ MÉDIO: Foreign Keys Inconsistentes

| Coluna | Problema |
|--------|----------|
| `rooms.created_by` | String sem referência (deveria ser FK) |
| `companies.state` | String sem FK para tabela `states` |
| Campos nullable | Cascade rules inconsistentes |

---

## 3. Análise de Arquitetura de Código

### 3.1 Pontos Positivos

✅ **Service Layer para Integrações Externas**
- Implementação de Base Service para Asaas
- Facade Pattern para acesso simplificado
- Service Provider com Singletons

✅ **Organização por Domínio**
- Componentes separados por tipo de usuário
- Estrutura clara de pastas

✅ **Ferramentas de Qualidade Configuradas**
- PHPStan (análise estática)
- Laravel Pint (formatação)
- Pest (testes)

### 3.2 Problemas Identificados

#### 🔴 CRÍTICO: Business Logic em Componentes de Apresentação

Lógica de negócio complexa está misturada com componentes Livewire:

```
Arquivo: app/Livewire/User/Checkout/CreditCard.php
Problema: 50+ linhas de cálculo de desconto
Impacto: Dificulta manutenção e testes
```

**Arquivos afetados:**
- `User/Checkout/CreditCard.php` - Cálculo de desconto
- `User/Checkout/Pix.php` - Cálculo de desconto (duplicado)
- `User/Specialist/Schedule.php` - Cálculo de desconto (duplicado)
- `AsaasWebhookController.php` - Criação de Room
- `User/Checkout/CreditCard.php` - Criação de Room (duplicado)

---

#### ⚠️ ALTO: Código Duplicado

| Código | Ocorrências | Arquivos |
|--------|-------------|----------|
| Cálculo de Desconto | 3+ | Checkout/CreditCard, Checkout/Pix, Specialist/Schedule |
| Criação de Room | 2 | AsaasWebhookController, CreditCard |
| Login Component | 4 | User/Auth, Specialist/Auth, Master/Auth, Company/Auth |

**Impacto:** Manutenção custosa, bugs podem ser corrigidos em um lugar e não em outros.

---

#### ⚠️ MÉDIO: Injeção de Dependência Inconsistente

```php
// ❌ ENCONTRADO - Instanciação direta
$jitsiService = new JitsiService();

// ✅ RECOMENDADO - Dependency Injection
public function __construct(private JitsiService $jitsiService)
```

**Impacto:** Dificulta testes unitários e viola princípios SOLID.

---

#### ⚠️ MÉDIO: Valores Hardcoded

```php
// Encontrado em PaymentService.php
'postalCode' => '01310100',  // CEP fixo
'addressNumber' => '1000',    // Número fixo
```

**Risco:** Dados incorretos em cobranças, possíveis problemas com gateway.

---

## 4. Análise de Segurança

### 4.1 Vulnerabilidades Identificadas

#### 🔴 CRÍTICO: Ausência de Policies de Autorização

**Problema:** Nenhuma Policy Laravel foi implementada. Todas as verificações de autorização são manuais e inconsistentes.

**Exemplo de código vulnerável:**
```php
// Company/Employee/ShowTable.php - VULNERÁVEL
public function show($rowId): void {
    $employee = User::findOrFail($rowId);
    // ⚠️ Não verifica se o employee pertence à company logada!
}
```

**Risco:** Usuário de uma empresa pode acessar dados de funcionários de outra empresa.

---

#### 🔴 CRÍTICO: Vulnerabilidade XSS em PowerGrid

```php
// Código vulnerável encontrado
return '<span class="badge">' . $model->plan_name . '</span>';
// ⚠️ plan_name pode conter JavaScript malicioso
```

**Risco:** Cross-Site Scripting - atacante pode injetar código malicioso.

---

#### ⚠️ ALTO: Exception Expondo Dados Sensíveis

A classe `AsaasException` expõe caminhos de arquivos e números de linha no retorno de erros.

**Risco:** Vazamento de informações da estrutura interna do sistema.

---

### 4.2 Matriz de Riscos de Segurança

| Vulnerabilidade | Severidade | Probabilidade | Risco |
|-----------------|------------|---------------|-------|
| Falta de Policies | Alta | Alta | 🔴 Crítico |
| XSS em PowerGrid | Alta | Média | 🔴 Crítico |
| Exception Exposure | Média | Alta | ⚠️ Alto |
| Verificações Inconsistentes | Média | Alta | ⚠️ Alto |

---

## 5. Análise de Testes

### 5.1 Cobertura Atual

| Métrica | Valor |
|---------|-------|
| Total de arquivos de teste | 22 |
| Feature Tests | 21 |
| Unit Tests | 0 (apenas placeholder) |
| **Cobertura Estimada** | **5-10%** |

### 5.2 Funcionalidades Críticas SEM Cobertura de Testes

| Funcionalidade | Criticidade | Status |
|----------------|-------------|--------|
| Webhook de Pagamentos (Asaas) | 🔴 Crítica | ❌ Sem testes |
| Registro de Usuário | 🔴 Crítica | ❌ Sem testes |
| Registro de Especialista | 🔴 Crítica | ❌ Sem testes |
| Processamento de Pagamentos | 🔴 Crítica | ❌ Sem testes |
| Services do Asaas | 🔴 Crítica | ❌ Sem testes |
| Validação de CPF | ⚠️ Alta | ❌ Sem testes |
| Console Commands (Rooms) | ⚠️ Alta | ❌ Sem testes |
| Cálculo de Descontos | ⚠️ Alta | ❌ Sem testes |

**Risco:** Alterações no código podem introduzir bugs não detectados, especialmente em fluxos críticos como pagamentos.

---

## 6. Análise de Performance

### 6.1 Problemas Identificados

#### Consultas N+1 Potenciais

```php
// Master/User/PersonalData/ShowTable.php
return User::query(); // Sem eager loading
// Se a view acessa relacionamentos, cada linha gera nova query
```

**Locais identificados:**
- `Master/User/PersonalData/ShowTable.php`
- `Company/Dashboard/Show.php` (counts separados)

#### Cache Subutilizado

- Redis está configurado mas **não está sendo utilizado**
- Cache e Session estão usando **database** (mais lento)

---

## 7. Recomendações Técnicas

### 7.1 Prioridade Crítica (Imediato)

| # | Ação | Esforço |
|---|------|---------|
| 1 | Corrigir constraint de appointments | 2h |
| 2 | Adicionar coluna is_active em specialists | 1h |
| 3 | Implementar Policies de autorização | 16h |
| 4 | Corrigir vulnerabilidades XSS | 8h |
| 5 | Adicionar verificações de ownership | 8h |

### 7.2 Prioridade Alta (Curto Prazo)

| # | Ação | Esforço |
|---|------|---------|
| 6 | Criar índices de banco de dados | 4h |
| 7 | Extrair lógica de desconto para Service | 8h |
| 8 | Extrair lógica de Room para Service | 6h |
| 9 | Unificar componentes de Login | 4h |
| 10 | Remover valores hardcoded | 2h |

### 7.3 Prioridade Média (Médio Prazo)

| # | Ação | Esforço |
|---|------|---------|
| 11 | Implementar testes para Webhook | 16h |
| 12 | Implementar testes para Registration | 12h |
| 13 | Implementar testes para Payments | 16h |
| 14 | Ativar Redis para Cache/Session | 4h |
| 15 | Corrigir N+1 queries | 8h |

---

## 8. Proposta de Correção

### 8.1 Escopo do Trabalho

Com base na análise realizada, propomos um plano de correção abrangente que inclui:

**Fase 1 - Correções Críticas de Segurança**
- Implementação de Laravel Policies para todos os recursos
- Correção de vulnerabilidades XSS identificadas
- Adição de verificações de autorização consistentes
- Sanitização de exceptions em produção

**Fase 2 - Correções de Banco de Dados**
- Correção da constraint de agendamentos
- Criação de todos os índices identificados
- Adição de colunas faltantes
- Correção de foreign keys

**Fase 3 - Refatoração de Código**
- Criação de Services dedicados (Discount, Room, Payment)
- Unificação de código duplicado
- Implementação de Dependency Injection consistente
- Remoção de valores hardcoded

**Fase 4 - Cobertura de Testes**
- Testes para webhook de pagamentos
- Testes para fluxos de registro
- Testes para processamento de pagamentos
- Testes para validações customizadas

**Fase 5 - Otimização de Performance**
- Ativação e configuração do Redis
- Correção de N+1 queries
- Implementação de cache em consultas frequentes

### 8.2 Investimento

| Item | Descrição | Valor |
|------|-----------|-------|
| Correções de Segurança | Policies, XSS, Authorization | USD 1.200,00 |
| Correções de Banco de Dados | Índices, Constraints, FKs | USD 400,00 |
| Refatoração de Código | Services, DI, Duplicação | USD 800,00 |
| Cobertura de Testes | Testes críticos | USD 1.200,00 |
| Otimização de Performance | Redis, N+1, Cache | USD 400,00 |
| **TOTAL** | | **USD 4.000,00** |

### 8.3 Condições

- **Prazo estimado:** 4 a 6 semanas
- **Forma de pagamento:** 50% início + 50% entrega
- **Garantia:** 30 dias para correções de bugs introduzidos
- **Documentação:** Entrega de relatório de mudanças realizadas

---

## 9. Considerações Finais

### 9.1 Pontos Fortes do Projeto

- Escolha tecnológica adequada (Laravel + Livewire)
- Estrutura organizacional clara
- Integrações bem implementadas (Asaas, Jitsi, SendGrid)
- Ferramentas de qualidade configuradas
- Docker configurado para desenvolvimento

### 9.2 Pontos de Atenção

A plataforma está **funcional**, porém apresenta vulnerabilidades de segurança e débitos técnicos que podem:

1. **Causar vazamento de dados** entre empresas/usuários
2. **Degradar performance** conforme a base de dados cresce
3. **Dificultar manutenção** devido ao código duplicado
4. **Introduzir bugs** pela falta de testes automatizados

### 9.3 Recomendação

**Recomendamos fortemente** a execução do plano de correção proposto **antes** de:
- Escalar a base de usuários
- Realizar campanhas de marketing
- Integrar novos parceiros corporativos
- Submeter a auditorias de compliance (LGPD)

---

## 10. Anexos

### Anexo A - Lista Completa de Arquivos Analisados

- 368 arquivos PHP
- 23 migrações de banco de dados
- 20 modelos Eloquent
- 22 arquivos de teste
- 6 arquivos de configuração customizados

### Anexo B - Ferramentas Utilizadas na Auditoria

- Análise estática de código
- Revisão manual de arquivos críticos
- Análise de schema de banco de dados
- Verificação de dependências

---

**Documento gerado em:** 28 de Janeiro de 2026

**Validade da proposta:** 30 dias

---

*Este documento é confidencial e destinado exclusivamente ao cliente. A reprodução ou distribuição sem autorização prévia é proibida.*
