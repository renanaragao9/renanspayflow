# PayFlow API - Backend Laravel

##  Visao Geral

PayFlow é uma API REST moderna desenvolvida em Laravel 12 para gerenciamento completo de fluxo de caixa e despesas. O sistema permite controlar centros de custo, despesas, contatos, parcelamentos de despesas, divisões de custos e mensagens associadas.

### Características Principais

-  Autenticação segura com Laravel Sanctum
-  API RESTful completa com versionamento (v1)
-  Validação robusta em português (PT-BR)
-  Arquitetura de serviços com injeção de dependência
-  Logging de atividades com spatie/laravel-activitylog
-  Testes automatizados com PHPUnit
-  Soft deletes para preservação de dados
-  Suporte a paginação, filtro e ordenação

---

## Arquitetura

### Padrão de Camadas

O sistema segue uma arquitetura em camadas bem definida:

```
HTTP Request
    ↓
┌─────────────────────────────────────┐
│  Controller (Validação de rota)     │
│  - CostCenterController             │
│  - ExpenseController                │
│  - ContactController                │
│  - ExpenseInstallmentController     │
│  - ExpenseSplitController           │
│  - MessageController                │
└──────────────┬──────────────────────┘
               ↓
┌─────────────────────────────────────┐
│  FormRequest (Validação)            │
│  - IndexXxxRequest                  │
│  - StoreXxxRequest                  │
│  - UpdateXxxRequest                 │
└──────────────┬──────────────────────┘
               ↓
┌─────────────────────────────────────┐
│  Service Layer (Lógica de negócio)  │
│  - IndexXxxService                  │
│  - StoreXxxService                  │
│  - UpdateXxxService                 │
│  - DeleteXxxService                 │
└──────────────┬──────────────────────┘
               ↓
┌─────────────────────────────────────┐
│  Model (Persistência de dados)      │
│  - Relacionamentos                  │
│  - Validações de negócio            │
│  - Scopes customizados              │
└──────────────┬──────────────────────┘
               ↓
┌─────────────────────────────────────┐
│  Database (SQLite / PostgreSQL)     │
│  - Tabelas com soft delete          │
│  - Activity log                     │
└─────────────────────────────────────┘
```

### Benefícios da Arquitetura

1. **Separação de Responsabilidades**: Cada camada tem um único propósito
2. **Testabilidade**: Services podem ser testados isoladamente
3. **Reutilização**: Services podem ser usadas por diferentes controllers
4. **Manutenibilidade**: Mudanças de lógica ficam centralizadas
5. **Escalabilidade**: Fácil adicionar novos módulos seguindo o padrão

---

##  Módulos do Sistema

### 1. **Centro de Custos (CostCenter)**

Gerencia os centros de custos para organização de despesas.

#### Endpoints

```
GET    /api/v1/cost-centers              # Listar com paginação/filtro
GET    /api/v1/cost-centers/{id}         # Detalhar
POST   /api/v1/cost-centers              # Criar
PUT    /api/v1/cost-centers/{id}         # Atualizar
DELETE /api/v1/cost-centers/{id}         # Deletar (soft delete)
```

#### Modelo

- **id**: UUID
- **name**: Nome do centro de custo
- **description**: Descrição (opcional)
- **user_id**: Proprietário
- **created_at / updated_at**: Timestamps
- **deleted_at**: Soft delete

#### Relacionamentos

- `expenses`: Has many - Despesas associadas ao centro

#### Exemplo de Requisição

```bash
curl -X POST http://localhost:8000/api/v1/cost-centers \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Marketing",
    "description": "Despesas de marketing da empresa"
  }'
```

#### Resposta

```json
{
    "status": "success",
    "message": "Centro de custos criado com sucesso",
    "data": {
        "id": "550e8400-e29b-41d4-a716-446655440000",
        "name": "Marketing",
        "description": "Despesas de marketing da empresa",
        "user_id": "123e4567-e89b-12d3-a456-426614174000",
        "created_at": "2026-03-24T10:30:00",
        "updated_at": "2026-03-24T10:30:00"
    }
}
```

---

### 2. **Despesas (Expense)**

Registra despesas completas com suporte a parcelamento.

#### Endpoints

```
GET    /api/v1/expenses                  # Listar com paginação/filtro
GET    /api/v1/expenses/{id}             # Detalhar
POST   /api/v1/expenses                  # Criar
PUT    /api/v1/expenses/{id}             # Atualizar
DELETE /api/v1/expenses/{id}             # Deletar
```

#### Modelo

- **id**: UUID
- **description**: Descrição da despesa
- **purchase_date**: Data da compra
- **total_amount**: Valor total (decimal)
- **installments**: Quantidade de parcelas
- **cost_center_id**: Centro de custo
- **created_at / updated_at / deleted_at**: Timestamps

#### Relacionamentos

- `costCenter`: Belongs to - Centro de custo
- `installments`: Has many - Parcelas da despesa

#### Exemplo de Requisição

```bash
curl -X POST http://localhost:8000/api/v1/expenses \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "description": "Compra de equipamentos",
    "purchase_date": "2026-03-24",
    "total_amount": 5000.00,
    "installments": 3,
    "cost_center_id": "550e8400-e29b-41d4-a716-446655440000"
  }'
```

---

### 3. **Contatos (Contact)**

Gerencia contatos associados aos usuários.

#### Endpoints

```
GET    /api/v1/contacts                  # Listar
GET    /api/v1/contacts/{id}             # Detalhar
POST   /api/v1/contacts                  # Criar
PUT    /api/v1/contacts/{id}             # Atualizar
DELETE /api/v1/contacts/{id}             # Deletar
```

#### Modelo

- **id**: UUID
- **name**: Nome do contato
- **email**: Email do contato
- **user_id**: Proprietário do contato
- **created_at / updated_at / deleted_at**: Timestamps

#### Relacionamentos

- `user`: Belongs to - Proprietário
- `messages`: Has many - Mensagens associadas
- `expenseSplits`: Has many - Divisões de custo

#### Exemplo de Requisição

```bash
curl -X POST http://localhost:8000/api/v1/contacts \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "João Silva",
    "email": "joao@example.com"
  }'
```

---

### 4. **Parcelas de Despesa (ExpenseInstallment)**

Gerencia as parcelas individuais de uma despesa parcelada.

#### Endpoints

```
GET    /api/v1/expense-installments      # Listar
GET    /api/v1/expense-installments/{id} # Detalhar
POST   /api/v1/expense-installments      # Criar
PUT    /api/v1/expense-installments/{id} # Atualizar
DELETE /api/v1/expense-installments/{id} # Deletar
```

#### Modelo

- **id**: UUID
- **due_date**: Data de vencimento
- **amount**: Valor da parcela (decimal)
- **installment_number**: Número da parcela (1, 2, 3...)
- **paid**: Status de pagamento (boolean)
- **expense_id**: Referência à despesa
- **created_at / updated_at / deleted_at**: Timestamps

#### Relacionamentos

- `expense`: Belongs to - Despesa principal
- `splits`: Has many - Divisões dessa parcela

#### Lógica de Negócio

- Cada despesa gera automaticamente suas parcelas
- Parcelas podem ser marcadas como pagas individualmente
- Divisões de custo devem respeitar o valor da parcela

#### Exemplo de Requisição

```bash
curl -X POST http://localhost:8000/api/v1/expense-installments \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "due_date": "2026-04-24",
    "amount": 1666.67,
    "installment_number": 1,
    "expense_id": "a1b2c3d4-e5f6-47g8-h9i0-j1k2l3m4n5o6"
  }'
```

---

### 5. **Divisão de Custos (ExpenseSplit)**

Distribui o custo de uma parcela entre múltiplos contatos.

#### Endpoints

```
GET    /api/v1/expense-splits            # Listar
GET    /api/v1/expense-splits/{id}       # Detalhar
POST   /api/v1/expense-splits            # Criar
PUT    /api/v1/expense-splits/{id}       # Atualizar
DELETE /api/v1/expense-splits/{id}       # Deletar
```

#### Modelo

- **id**: UUID
- **amount**: Valor da divisão
- **paid**: Status de pagamento
- **expense_installment_id**: Parcela referenciada
- **contact_id**: Contato responsável
- **created_at / updated_at / deleted_at**: Timestamps

#### Relacionamentos

- `expenseInstallment`: Belongs to - Parcela original
- `contact`: Belongs to - Contato responsável

#### Lógica de Negócio

- Múltiplas divisões podem existir para uma parcela
- A soma de todas as divisões não deve exceder o valor da parcela
- Cada divisão pode ser paga independentemente

#### Exemplo de Requisição

```bash
curl -X POST http://localhost:8000/api/v1/expense-splits \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 833.33,
    "expense_installment_id": "b2c3d4e5-f6g7-48h9-i0j1-k2l3m4n5o6p7",
    "contact_id": "c3d4e5f6-g7h8-49i0-j1k2-l3m4n5o6p7q8"
  }'
```

---

### 6. **Mensagens (Message)**

Gerencia mensagens de comunicação relacionadas a despesas.

#### Endpoints

```
GET    /api/v1/messages                  # Listar com filtro
GET    /api/v1/messages/{id}             # Detalhar
POST   /api/v1/messages                  # Criar
PUT    /api/v1/messages/{id}             # Atualizar
DELETE /api/v1/messages/{id}             # Deletar
```

#### Modelo

- **id**: UUID
- **subject**: Assunto (opcional)
- **type**: Tipo de mensagem (approval, notification, reminder)
- **channel**: Canal (email, sms, in_app)
- **message**: Conteúdo da mensagem (text)
- **read_at**: Data de leitura (nullable)
- **user_id**: Remetente/Proprietário
- **contact_id**: Contato destinatário
- **expense_installment_id**: Parcela referenciada
- **created_at / updated_at / deleted_at**: Timestamps

#### Relacionamentos

- `user`: Belongs to - Usuário remetente
- `contact`: Belongs to - Contato destinatário
- `expenseInstallment`: Belongs to - Parcela referenciada

#### Filtros Disponíveis

- `user_id`: Mensagens de um usuário específico
- `contact_id`: Mensagens para um contato específico
- `expense_installment_id`: Mensagens de uma parcela específica
- `search`: Busca por assunto/mensagem

#### Exemplo de Requisição

```bash
curl -X POST http://localhost:8000/api/v1/messages \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "subject": "Aprovação de despesa",
    "type": "approval",
    "channel": "email",
    "message": "Sua despesa de R$ 5.000,00 foi aprovada",
    "contact_id": "c3d4e5f6-g7h8-49i0-j1k2-l3m4n5o6p7q8",
    "expense_installment_id": "b2c3d4e5-f6g7-48h9-i0j1-k2l3m4n5o6p7"
  }'
```

---

##  Autenticação

### Laravel Sanctum

O sistema utiliza **Laravel Sanctum** para autenticação de API.

#### Fluxo de Autenticação

1. Usuário faz login com email e senha (endpoint não documentado aqui, da app principal)
2. Recebe um token Bearer
3. Inclui o token em todas as requisições: `Authorization: Bearer {token}`

#### Header Obrigatório

```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

#### Middleware

Todas as rotas da API estão protegidas pelo middleware `auth:sanctum`:

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('cost-centers', CostCenterController::class);
    // ... demais rotas
});
```

---

##  Paginação, Filtro e Ordenação

### Paginação

Todas as rotas de listagem suportam paginação:

```bash
curl "http://localhost:8000/api/v1/cost-centers?page=1&per_page=15" \
  -H "Authorization: Bearer {token}"
```

**Parâmetros**:

- `page`: Página desejada (padrão: 1)
- `per_page`: Itens por página (padrão: 15)

### Filtro e Busca

RequestIndexRequest permitem filtro e busca:

```bash
# Por coluna específica
curl "http://localhost:8000/api/v1/costs-centers?name=Marketing&page=1" \
  -H "Authorization: Bearer {token}"

# Por busca em múltiplos campos
curl "http://localhost:8000/api/v1/contacts?search=joao" \
  -H "Authorization: Bearer {token}"

# Mensagens com múltiplos filtros
curl "http://localhost:8000/api/v1/messages?user_id=123&contact_id=456&page=1" \
  -H "Authorization: Bearer {token}"
```

### Ordenação

Ordene os resultados por qualquer coluna:

```bash
curl "http://localhost:8000/api/v1/expenses?sort_by=total_amount&sort_direction=desc" \
  -H "Authorization: Bearer {token}"
```

**Parâmetros**:

- `sort_by`: Coluna para ordenação
- `sort_direction`: `asc` ou `desc` (padrão: asc)

---

##  Validações

Todas as requisições passam por validação robusta em **português (PT-BR)**.

### Exemplo de Erro de Validação (422)

```json
{
    "status": "error",
    "message": "Falha na validação dos dados",
    "errors": {
        "name": ["O campo nome é obrigatório"],
        "email": ["O email deve ser um endereço válido"]
    }
}
```

### Mensagens Customizadas

Cada módulo tem suas próprias regras de validação:

- **CostCenter**: name (obrigatório, unique)
- **Expense**: description, purchase_date, total_amount, installments, cost_center_id
- **Contact**: name (obrigatório), email (email válido)
- **ExpenseInstallment**: due_date, amount (numeric), installment_number
- **ExpenseSplit**: amount (numeric), referências válidas
- **Message**: subject, type, channel, message (text)

---

##  Logging de Atividades

### Activity Log

O sistema registra automaticamente todas as alterações usando **spatie/laravel-activitylog**.

#### Informações Registradas

- **Modelo alterado**: CostCenter, Expense, Contact, etc.
- **Tipo de ação**: created, updated, deleted
- **Usuário responsável**: `user_id` que fez a alteração
- **Mudanças**: O que foi alterado (old_values → new_values)
- **Timestamp**: Quando ocorreu

#### Exemplo de Log

```
user_id: 123e4567-e89b-12d3-a456-426614174000
model: Expense
event: created
properties: {
  "attributes": {
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "description": "Compra de equipamentos",
    "total_amount": "5000.00"
  }
}
```

#### Acessando Logs Programaticamente

```php
$logs = Activity::where('causer_id', auth()->id())->get();

foreach ($logs as $log) {
    echo $log->description; // "created" ou "updated"
    echo $log->subject_type; // "Expense"
}
```

---

## Estrutura de Diretórios

```
payflow-api/
├── app/
│   ├── Events/                          # Eventos da aplicação
│   │   └── PasswordResetRequested.php
│   │
│   ├── Http/
│   │   └── V1/                          # Versionamento V1
│   │       ├── Controllers/
│   │       │   ├── BaseController.php
│   │       │   ├── CostCenterController.php
│   │       │   ├── ExpenseController.php
│   │       │   ├── ContactController.php
│   │       │   ├── ExpenseInstallmentController.php
│   │       │   ├── ExpenseSplitController.php
│   │       │   └── MessageController.php
│   │       │
│   │       ├── Requests/
│   │       │   ├── CostCenter/          # Requisições de validação
│   │       │   ├── Expense/
│   │       │   ├── Contact/
│   │       │   ├── ExpenseInstallment/
│   │       │   ├── ExpenseSplit/
│   │       │   └── Message/
│   │       │
│   │       └── Resources/
│   │           ├── CostCenterResource.php
│   │           ├── ExpenseResource.php
│   │           ├── ContactResource.php
│   │           ├── ExpenseInstallmentResource.php
│   │           ├── ExpenseSplitResource.php
│   │           └── MessageResource.php
│   │
│   ├── Models/
│   │   ├── BaseModel.php                # Modelo base com soft delete
│   │   ├── User.php
│   │   ├── CostCenter.php
│   │   ├── Expense.php
│   │   ├── Contact.php
│   │   ├── ExpenseInstallment.php
│   │   ├── ExpenseSplit.php
│   │   └── Message.php
│   │
│   ├── Services/
│   │   ├── CostCenter/
│   │   │   ├── IndexCostCenterService.php
│   │   │   ├── StoreCostCenterService.php
│   │   │   ├── UpdateCostCenterService.php
│   │   │   └── DeleteCostCenterService.php
│   │   ├── Expense/
│   │   ├── Contact/
│   │   ├── ExpenseInstallment/
│   │   ├── ExpenseSplit/
│   │   └── Message/
│   │
│   ├── Traits/
│   │   ├── OrderByColumnAndDirection.php # Ordenação customizada
│   │   └── ParseRequestParams.php        # Paginação e filtros
│   │
│   └── Providers/
│       ├── AppServiceProvider.php
│       └── RouteServiceProvider.php
│
├── bootstrap/
│   └── app.php
│
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── sanctum.php
│   └── ... (demais configurações)
│
├── database/
│   ├── migrations/                      # Migrações
│   │   ├── 2026_03_24_104058_create_cost_centers_table.php
│   │   ├── 2026_03_24_105222_create_expenses_table.php
│   │   ├── 2026_03_24_105528_create_contacts_table.php
│   │   ├── 2026_03_24_105930_create_expense_installments_table.php
│   │   ├── 2026_03_24_110230_create_expense_splits_table.php
│   │   ├── 2026_03_24_110530_create_messages_table.php
│   │   └── 2026_03_24_120000_create_activity_log_table.php
│   │
│   ├── factories/
│   │   └── UserFactory.php
│   │
│   └── seeders/
│       └── DatabaseSeeder.php
│
├── routes/
│   ├── api.php                          # Rotas da API
│   ├── web.php
│   └── console.php
│
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
│
├── tests/
│   ├── Feature/
│   │   ├── CostCenterCrudTest.php
│   │   ├── ExpenseCrudTest.php
│   │   ├── ContactCrudTest.php
│   │   ├── ExpenseInstallmentCrudTest.php
│   │   ├── ExpenseSplitCrudTest.php
│   │   └── MessageCrudTest.php
│   │
│   └── Unit/
│
├── .env.example
├── composer.json
├── package.json
├── phpunit.xml
├── vite.config.js
└── README.md (este arquivo)
```

---

##  Instalação e Configuração

### Pré-requisitos

- PHP 8.3+
- Composer
- Node.js & npm
- SQLite ou PostgreSQL

### Passos de Instalação

1. **Clonar repositório**

    ```bash
    git clone <repo-url>
    cd payflow-api
    ```

2. **Instalar dependências PHP**

    ```bash
    composer install
    ```

3. **Instalar dependências Node**

    ```bash
    npm install
    ```

4. **Copiar arquivo de ambiente**

    ```bash
    cp .env.example .env
    ```

5. **Gerar chave da aplicação**

    ```bash
    php artisan key:generate
    ```

6. **Configurar banco de dados**

    ```bash
    # No .env, configure:
    DB_CONNECTION=sqlite
    DB_DATABASE=database/database.sqlite
    # OU
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=payflow
    DB_USERNAME=postgres
    DB_PASSWORD=seu_password
    ```

7. **Executar migrações**

    ```bash
    php artisan migrate
    ```

8. **Gerar token Sanctum**

    ```bash
    php artisan tinker
    >>> $user = User::first();
    >>> $user->createToken('api-token')->plainTextToken
    ```

9. **Iniciar servidor**
    ```bash
    php artisan serve
    # API disponível em http://localhost:8000
    ```

---

##  Testes

### Executando Testes

```bash
# Todos os testes
php artisan test

# Testes de um módulo específico
php artisan test tests/Feature/CostCenterCrudTest.php

# Com filtro
./vendor/bin/phpunit --filter MessageCrudTest

# Com cobertura
php artisan test --coverage
```

### Estrutura de Testes

Cada módulo possui um arquivo de teste com 5 operações CRUD:

-  **Criar**: `test_can_create_xxx`
-  **Listar**: `test_can_list_xxx_with_pagination`
-  **Detalhar**: `test_can_show_xxx`
-  **Atualizar**: `test_can_update_xxx`
-  **Deletar**: `test_can_delete_xxx`

### Exemplo de Teste

```php
public function test_can_create_cost_center()
{
    $response = $this->actingAs($this->user)->postJson(
        '/api/v1/cost-centers',
        [
            'name' => 'Marketing',
            'description' => 'Despesas de marketing'
        ]
    );

    $response->assertStatus(201);
    $response->assertJsonPath('status', 'success');
    $this->assertDatabaseHas('cost_centers', [
        'name' => 'Marketing'
    ]);
}
```

### Cobertura de Testes

| Módulo             | Testes | Assertions |
| ------------------ | ------ | ---------- |
| CostCenter         | 5      | 13         |
| Expense            | 5      | 14         |
| Contact            | 5      | 13         |
| ExpenseInstallment | 5      | 13         |
| ExpenseSplit       | 5      | 13         |
| Message            | 5      | 13         |
| **Total**          | **30** | **79**     |

---

##  Fluxo de Exemplo Completo

### Cenário: Registrar e dividir uma despesa

1. **Criar Centro de Custo**

    ```bash
    POST /api/v1/cost-centers
    { "name": "Departamento de TI" }
    ```

     Retorna: id do centro (ex: `cc-001`)

2. **Criar Despesa**

    ```bash
    POST /api/v1/expenses
    {
      "description": "Notebooks para equipe",
      "purchase_date": "2026-03-24",
      "total_amount": 6000,
      "installments": 3,
      "cost_center_id": "cc-001"
    }
    ```

     Retorna: id da despesa (ex: `exp-001`)
     Sistema cria automaticamente 3 parcelas de R$ 2000

3. **Criar Contatos para divisão**

    ```bash
    POST /api/v1/contacts
    { "name": "João dev", "email": "joao@..." }
    { "name": "Maria dev", "email": "maria@..." }
    { "name": "Pedro dev", "email": "pedro@..." }
    ```

     Retorna: ids dos contatos (ex: `ct-j01`, `ct-m02`, `ct-p03`)

4. **Obter primeira parcela**

    ```bash
    GET /api/v1/expense-installments?expense_id=exp-001
    ```

     Retorna: id da primeira parcela (ex: `inst-001`)

5. **Dividir custos entre os 3**

    ```bash
    POST /api/v1/expense-splits
    { "amount": 667, "contact_id": "ct-j01", "expense_installment_id": "inst-001" }

    POST /api/v1/expense-splits
    { "amount": 667, "contact_id": "ct-m02", "expense_installment_id": "inst-001" }

    POST /api/v1/expense-splits
    { "amount": 666, "contact_id": "ct-p03", "expense_installment_id": "inst-001" }
    ```

     Divisão criada com sucesso = R$ 2000 distribuído

6. **Enviar notificações**
    ```bash
    POST /api/v1/messages
    {
      "subject": "Divisão de custos",
      "type": "notification",
      "channel": "email",
      "message": "Você foi designado em R$ 667",
      "contact_id": "ct-j01",
      "expense_installment_id": "inst-001"
    }
    ```
     Mensagem registrada no sistema

---

##  Suporte e Documentação

- **Documentação Laravel**: https://laravel.com/docs
- **Laravel Sanctum**: https://laravel.com/docs/sanctum
- **Activity Log**: https://docs.spatie.be/laravel-activitylog
- **phpunit**: https://phpunit.de/

---

##  Licença

Este projeto é proprietário. Todos os direitos reservados.

---

## ‍ Desenvolvedor

Desenvolvido como parte do sistema PayFlow.

**Data**: 24 de março de 2026
**Versão API**: v1
**Status**:  Produção
