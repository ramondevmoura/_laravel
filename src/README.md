# 🚀 Onfly Travel Requests - Teste Técnico

Sistema de gestão de **pedidos de viagem** desenvolvido em **Laravel 11**, **Vue 3**, **Inertia.js**, **Sanctum** e **Docker**, com notificações em tempo real e autenticação baseada em sessão.

---

## ✅ Funcionalidades

- Autenticação de usuários com **Laravel Sanctum**.
- Painel para **criar e listar pedidos de viagem**.
- **Admin pode aprovar ou cancelar** pedidos.
- Notificações salvas no banco e exibidas por usuário.
- **Som de alerta** ao receber uma nova notificação.
- Interface moderna com **Tailwind CSS** + **Vue 3** + **Vite**.
- Banco de dados PostgreSQL.

---

## 🚦 Permissões

| Papel     | Permissões                                                                 |
|-----------|----------------------------------------------------------------------------|
| `admin`   | Ver todos os pedidos, alterar status (`aprovado` ou `cancelado`) e deletar.|
| `user`    | Ver e criar seus próprios pedidos.                                         |

---

## 🔔 Notificações

- São **salvas no banco de dados** (tabela `notifications`) ao **alterar o status** de um pedido (`aprovado` ou `cancelado`).
- São exibidas **somente para o usuário dono do pedido**.
- Quando novas notificações não lidas são encontradas, o **ícone exibe um badge animado** e **um som de alerta** é tocado.
- O admin **não recebe** notificações — ele apenas altera status de terceiros.

---

## 🔐 Por que foi usado Sanctum e não JWT?

Optamos por **Laravel Sanctum** pois ele:
- É nativamente integrado ao Laravel.
- Oferece autenticação baseada em **sessão e cookies** — ideal para aplicações **SPA** como esta.
- Evita a complexidade de gerenciar tokens JWT manualmente.
- Permite segurança via **CSRF protection**, que já vem embutida.

> JWT seria mais apropriado se a aplicação tivesse uma API pública e um front totalmente desacoplado.

---

## 🧪 Testes

Infelizmente, **não houve tempo hábil para implementar os testes automatizados no backend**. Apesar de ter buscado ajuda do ChatGPT para estruturar os testes com PHPUnit e Pest, optei por **não incluir implementações parciais ou apressadas** para manter a qualidade geral e o foco nas funcionalidades principais dentro do prazo proposto.

---

## 🐳 Rodando o projeto com Docker

### 1. Clone o repositório
```bash
git clone https://github.com/seu-usuario/_laravel.git
cd _laravel/src
docker-compose up --build
docker-compose exec app php artisan migrate --seed
npm run dev

Usuario admin: 

email: teste@example.com
senha: 12345678
