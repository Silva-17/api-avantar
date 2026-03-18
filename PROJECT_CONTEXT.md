# Contexto do Projeto: API Avantar (Corretora de Seguros)

Este arquivo serve como um resumo técnico do projeto para fornecer contexto rápido ao agente, economizando tokens e tempo na análise inicial.

## 📌 Visão Geral
* **Framework**: Laravel 12.0
* **Linguagem**: PHP ^8.2
* **Autenticação**: Laravel Sanctum (Token-based)
* **Objetivo Principal**: Gerenciamento de Cotações de Seguros (Quotes) e CRM básico para a corretora.

## 🏗 Estrutura e Domínio

O projeto é construído em torno da entidade de `Quote` (Cotação) e seus diversos subtipos focados em diferentes nichos de seguros, além da gestão de usuários.

### 👥 Usuários e Acesso
* **Controller**: `AuthController`, `UserController`, `PasswordResetController`
* **Model**: `User` (utiliza Sanctum `HasApiTokens`)
* **Campos principais**: `name`, `email`, `role`, `unit`, `password`.
* **Papéis (Roles)**: Os perfis utilizados no sistema são `consultor`, `atendente`, e `gestor`. O papel de `admin` foi recentemente desativado/removido.
* **Funcionalidades**: Login, logout, recuperação de senha, listagem de usuários e listagem específica de atendentes.

### 📋 Cotações (Quotes)
A Cotação é o núcleo do sistema e está dividida nos seguintes modelos especializados:
* `Quote` (Modelo Principal)
* **Tipos de Seguros (Sub-modelos)**: 
  * `QuoteAuto` (Automóvel)
  * `QuoteMotorcycle` (Motocicleta)
  * `QuoteTruck` (Caminhão)
  * `QuoteResidential` (Residencial)
  * `QuoteCondominium` (Condomínio)
  * `QuoteLifeIndividual` (Vida Individual)
  * `QuoteLifeGroup` (Vida em Grupo)
  * `QuoteConsortium` (Consórcio)
* **Interações e Status**:
  * `QuoteStatus`: Histórico ou estados permitidos para a cotação.
  * `QuoteResponse`: Interações, mensagens e respostas vinculadas à cotação, com possibilidade de marcação de leitura.
  * `QuoteDocument`: Documentos e arquivos anexados à cotação.
  * `QuoteBeneficiary`: Beneficiários (provavelmente para seguros de vida).

## 🛣️ Rotas da API (`routes/api.php`)

As principais rotas são protegidas pelo middleware `auth:sanctum`:
1. **Autenticação**: `POST /login`, `POST /logout`, `/password/*`
2. **Usuários**: `GET /user` (logado), `GET /users`, `GET /users/attendants`, `POST /users`
3. **Cotações**: 
   * `GET /quotes`, `POST /quotes`, `GET /quotes/{id}`, `PUT /quotes/{id}`
   * `PATCH /quotes/{id}/status` (Atualizar status da cotação)
   * `PATCH /quotes/{id}/attendant` (Atribuir atendente à cotação)
   * `POST /quotes/{id}/response` (Adicionar resposta/mensagem)
   * `GET /quote-statuses` (Listar status disponíveis)
4. **Mensagens**: `POST /mensagens/marcar-como-lida`
5. **Documentos**: `GET /documents/{document}/download`, `GET /documents/{document}/view`

## 🛠 Comandos Frequentes (Local)
* Iniciar servidor: `php artisan serve` (geralmente executando na porta 8000).

Este projeto tem foco na API (retorna JSON) e será/é consumido por uma aplicação Front-end cliente.
