<div align="center">
  <h1>Pizzaria Arantes</h1>
  <p><em>Sistema Web Completo para Gestão de Pizzaria</em></p>
  <p>
    <img src="https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP">
    <img src="https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL">
    <img src="https://img.shields.io/badge/HTML5-E34F26?style=flat-square&logo=html5&logoColor=white" alt="HTML5">
    <img src="https://img.shields.io/badge/CSS3-1572B6?style=flat-square&logo=css3&logoColor=white" alt="CSS3">
    <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black" alt="JavaScript">
    <img src="https://img.shields.io/badge/status-concluído-2ea44f?style=flat-square" alt="Status">
    <img src="https://img.shields.io/badge/licença-MIT-blue?style=flat-square" alt="License">
  </p>
</div>

---

## Resumo

O **Pizzaria Arantes** é um sistema web full stack desenvolvido para gerenciamento completo de uma pizzaria. A plataforma combina um site institucional voltado ao cliente final com um painel administrativo robusto para controle de pedidos, cardápio e comunicação. O projeto foi desenvolvido como trabalho da disciplina **Desenvolvimento Web Full Stack** do curso de **Análise e Desenvolvimento de Sistemas** da **Faculdade INSTED**, aplicando conceitos de programação web, banco de dados relacional, segurança da informação e experiência do usuário.

---

## Sumário

- [Contextualização](#contextualização)
- [Objetivos](#objetivos)
- [Funcionalidades](#funcionalidades)
- [Tecnologias](#tecnologias)
- [Arquitetura do Sistema](#arquitetura-do-sistema)
- [Diagrama do Banco de Dados](#diagrama-do-banco-de-dados)
- [Guia de Instalação](#guia-de-instalação)
- [Credenciais de Acesso](#credenciais-de-acesso)
- [Segurança](#segurança)
- [Considerações Finais](#considerações-finais)
- [Referências](#referências)

---

## Contextualização

No cenário atual do mercado alimentício, a presença digital tornou-se um diferencial competitivo essencial. Pequenos e médios estabelecimentos, como pizzarias, enfrentam desafios na gestão de pedidos, comunicação com clientes e organização do cardápio. O **Pizzaria Arantes** surge como uma solução integrada que unifica a experiência do cliente com as ferramentas administrativas necessárias para o negócio.

O sistema foi projetado para atender dois perfis de usuário:

- **Cliente**: navega pelo site, consulta o cardápio, realiza pedidos online e entra em contato com a pizzaria.
- **Administrador**: gerencia pedidos, atualiza o cardápio, monitora mensagens e acompanha estatísticas do negócio em tempo real.

---

## Objetivos

### Objetivo Geral

Desenvolver um sistema web funcional e responsivo para gestão de pizzaria, integrando interface pública e administrativa com banco de dados relacional.

### Objetivos Específicos

- Implementar autenticação segura de usuários com sessões PHP e hash de senhas
- Desenvolver um cardápio dinâmico com CRUD completo
- Criar um dashboard administrativo com métricas em tempo real
- Garantir responsividade para acesso em diferentes dispositivos
- Aplicar boas práticas de segurança (PDO, prepared statements, sanitização)
- Proporcionar experiência do usuário com dark mode e validação dupla de formulários

---

## Funcionalidades

### Módulo Cliente (Site Público)

| Módulo | Descrição |
|--------|-----------|
| **Página Inicial** | Hero section com call-to-action, estatísticas do negócio e vitrine de promoções |
| **Cardápio Dinâmico** | Grade de pizzas carregadas do banco de dados com nome, descrição, preço e imagem |
| **Pedidos Online** | Formulário completo com validação front-end (JavaScript) e back-end (PHP) |
| **Contato** | Formulário de contato com categorias de assunto e persistência no banco |
| **Autenticação** | Cadastro e login de clientes com sessão segura |
| **Sobre** | Página institucional com histórico e valores da pizzaria |

### Módulo Administrativo (`/admin/`)

| Módulo | Descrição |
|--------|-----------|
| **Dashboard** | Visão consolidada com cards de métricas (pedidos do dia, faturamento, mensagens não lidas) |
| **Auto Refresh** | Atualização automática do dashboard a cada 15 segundos via requisições AJAX |
| **Gestão de Pedidos** | Tabela com últimos 20 pedidos e alteração inline de status |
| **Gestão de Cardápio** | CRUD completo com upload de imagens e opção de restaurar pizzas padrão |
| **Gestão de Mensagens** | Visualização e marcação de mensagens como lidas/não lidas |

### Recursos Transversais

- **Modo Escuro (Dark Mode)**: tema alternativo disponível em todas as páginas
- **Design Responsivo**: adaptável a desktop, tablet e mobile (breakpoints: 960px, 700px, 480px)
- **Validação Dupla**: todos os formulários validam no cliente (JavaScript) e no servidor (PHP)
- **PRG Pattern**: uso de Post/Redirect/Get para evitar reenvio de formulários

---

## Tecnologias

| Tecnologia | Versão | Aplicação |
|------------|--------|-----------|
| **PHP** | 8.x | Linguagem principal do back-end, sessões e lógica de negócio |
| **MySQL** | 8.x | Sistema gerenciador de banco de dados relacional |
| **PDO** | — | Camada de abstração para conexão segura com prepared statements |
| **HTML5** | — | Estrutura semântica e acessibilidade |
| **CSS3** | — | Estilização, layout responsivo e dark mode |
| **JavaScript** | ES6 | Validação de formulários, interatividade e requisições assíncronas |
| **Google Fonts** | Poppins | Tipografia moderna e legível |

---

## Arquitetura do Sistema

```
                         ┌─────────────────────┐
                         │     Navegador        │
                         │  (Cliente / Admin)   │
                         └──────────┬──────────┘
                                    │
                         ┌──────────▼──────────┐
                         │       Apache         │
                         │   Servidor Web       │
                         └──────────┬──────────┘
                                    │
                         ┌──────────▼──────────┐
                         │     PHP (Back-end)   │
                         │  ┌─────────────────┐ │
                         │  │  Session/Auth   │ │
                         │  │  Validação      │ │
                         │  │  Processamento  │ │
                         │  └─────────────────┘ │
                         └──────────┬──────────┘
                                    │
                         ┌──────────▼──────────┐
                         │   PDO (conexão)      │
                         └──────────┬──────────┘
                                    │
                         ┌──────────▼──────────┐
                         │  MySQL (Banco de     │
                         │  Dados Relacional)   │
                         └─────────────────────┘
```

### Fluxo de Dados

1. O usuário acessa o sistema via navegador
2. O servidor Apache interpreta as requisições e executa os scripts PHP
3. O PHP processa a lógica de negócio, valida dados e gerencia sessões
4. A camada PDO estabelece conexão segura com o banco MySQL
5. Os dados são retornados e renderizados em HTML/CSS/JS para o cliente

---

## Diagrama do Banco de Dados

### Estrutura Relacional

O banco `pizzaria` é composto por 5 tabelas que se relacionam para sustentar as funcionalidades do sistema:

```
┌─────────────┐       ┌─────────────┐
│  usuarios   │───────│  enderecos  │
├─────────────┤       ├─────────────┤
│ id (PK)     │       │ id (PK)     │
│ nome        │       │ usuario_id  │
│ email       │       │ rua         │
│ senha       │       │ numero      │
│ telefone    │       │ bairro      │
│ tipo        │       │ cidade      │
│ criado_em   │       │ cep         │
└─────────────┘       │ complemento │
                      └─────────────┘

┌─────────────┐       ┌─────────────┐
│   cardapio  │       │   pedidos   │
├─────────────┤       ├─────────────┤
│ id (PK)     │       │ id (PK)     │
│ nome        │       │ usuario_id  │
│ descricao   │       │ nome_cliente│
│ preco       │       │ telefone    │
│ imagem      │       │ pizza       │
│ ativo       │       │ tamanho     │
│ criado_em   │       │ endereco    │
└─────────────┘       │ cep         │
                      │ pagamento   │
┌─────────────┐       │ observacoes │
│  mensagens  │       │ status      │
├─────────────┤       │ total       │
│ id (PK)     │       │ criado_em   │
│ nome        │       └─────────────┘
│ email       │
│ telefone    │
│ assunto     │
│ mensagem    │
│ lida        │
│ criado_em   │
└─────────────┘
```

### Especificação das Tabelas

| Tabela | Registros | Finalidade |
|--------|-----------|------------|
| `usuarios` | Clientes + Admins | Autenticação e permissões |
| `enderecos` | Endereços | Vinculados a usuários |
| `cardapio` | Pizzas | Catálogo de produtos |
| `pedidos` | Pedidos | Transações dos clientes |
| `mensagens` | Contatos | Comunicação cliente → pizzaria |

---

## Guia de Instalação

### Pré-requisitos

- Servidor Apache 2.4+
- PHP 8.0+
- MySQL 8.0+
- Recomendação: [XAMPP](https://www.apachefriends.org/) (Windows) ou LAMP (Linux)

### Passo a Passo

<details>
<summary><b>Windows (XAMPP)</b></summary>

```bash
# 1. Copiar projeto para o diretório do servidor
cp -r "Pizzaria Web" C:\xampp\htdocs\Pizzaria Web

# 2. Iniciar Apache e MySQL no XAMPP Control Panel

# 3. Acessar phpMyAdmin e importar o banco de dados
# URL: http://localhost/phpmyadmin
# - Criar banco: pizzaria
# - Importar: database/banco.sql

# 4. Acessar o sistema
# URL: http://localhost/Pizzaria%20Web
```
</details>

<details>
<summary><b>Linux (Ubuntu/Zorin OS)</b></summary>

```bash
# 1. Instalar dependências
sudo apt install apache2 php libapache2-mod-php php-mysql mysql-server phpmyadmin -y

# 2. Configurar senha do MySQL
sudo mysql -u root -e \
  "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '123456'; FLUSH PRIVILEGES;"

# 3. Copiar projeto
sudo cp -r ~/Documentos/'Pizzaria Web' /var/www/html/'Pizzaria Web'

# 4. Importar banco de dados
sudo mysql -u root -p < ~/Documentos/'Pizzaria Web'/database/banco.sql

# 5. Acessar
# URL: http://localhost/Pizzaria%20Web
```
</details>

> **Nota:** As credenciais do banco de dados podem ser configuradas no arquivo `includes/conexao.php`.

---

## Credenciais de Acesso

| Ambiente | URL | E-mail | Senha |
|----------|-----|--------|-------|
| Site público | `/Pizzaria%20Web` | — | — |
| Login cliente | `/Pizzaria%20Web/login.php` | `joao@email.com` | `password` |
| Painel admin | `/Pizzaria%20Web/admin/login.php` | `admin@pizzaria.com` | `password` |
| phpMyAdmin | `/phpmyadmin` | `root` | `123456` |

---

## Segurança

O projeto implementa as seguintes práticas de segurança:

1. **Prepared Statements (PDO)**: prevenção contra SQL Injection em todas as consultas ao banco
2. **Hash de Senhas**: utilização de `password_hash()` com algoritmo bcrypt
3. **Regeneração de Sessão**: `session_regenerate_id()` após autenticação
4. **Sanitização de Saída**: `htmlspecialchars()` para prevenção de XSS
5. **Controle de Acesso**: verificação de tipo de usuário para acesso ao painel administrativo
6. **Validação de Upload**: restrição de formatos (jpg, jpeg, png, gif, webp) e limite de 5MB
7. **Validação Dupla**: front-end (JavaScript) e back-end (PHP) em todos os formulários

---

## Estrutura do Projeto

```
Pizzaria Web/
├── admin/
│   ├── cardapio.php      # CRUD do cardápio
│   ├── index.php         # Dashboard administrativo
│   ├── login.php         # Autenticação do administrador
│   ├── logout.php        # Encerramento de sessão
│   └── stats.php         # Endpoint JSON para auto-refresh
├── CSS/
│   └── style.css         # Folha de estilos global
├── database/
│   └── banco.sql         # Script de criação do banco
├── IMG/                  # Recursos de imagem
├── includes/
│   ├── conexao.php       # Conexão PDO com MySQL
│   ├── footer.php        # Rodapé e scripts
│   └── header.php        # Cabeçalho e navegação
├── JS/
│   └── main.js           # Scripts de validação e interatividade
├── process/
│   ├── cadastro.php      # Processamento de cadastro
│   ├── contato.php       # Processamento de contato
│   ├── login.php         # Processamento de login
│   ├── logout.php        # Encerramento de sessão
│   └── pedido.php        # Processamento de pedidos
├── cadastro.php          # Página de cadastro
├── cardapio.php          # Página do cardápio
├── contato.php           # Página de contato
├── index.php             # Página inicial
├── login.php             # Página de login
├── pedidos.php           # Página de pedidos
├── sobre.php             # Página institucional
└── README.md             # Documentação do projeto
```

---

## Considerações Finais

O Pizzaria Arantes foi desenvolvido com foco em entregar uma solução completa e funcional para o gerenciamento de uma pizzaria, abrangendo desde a experiência do cliente até as ferramentas administrativas necessárias para o negócio. A escolha por PHP puro (sem frameworks) permitiu um aprofundamento nos fundamentos da web, incluindo manipulação de sessões, comunicação com banco de dados e validação de dados.

### Melhorias Futuras

- Implementação de pagamento online via gateway (Mercado Pago, Stripe)
- Sistema de avaliação de pizzas pelos clientes
- Notificações em tempo real com WebSockets
- Relatórios exportáveis (PDF/CSV) para o painel administrativo
- API RESTful para integração com aplicativos móveis
- Testes automatizados (PHPUnit)

---

## Referências

- PHP Documentation. *PHP Manual*. Disponível em: https://www.php.net/docs.php
- MySQL Documentation. *MySQL 8.0 Reference Manual*. Disponível em: https://dev.mysql.com/doc/refman/8.0/en/
- MDN Web Docs. *HTML, CSS e JavaScript*. Disponível em: https://developer.mozilla.org/
- W3Schools. *Tutoriais e Referências Web*. Disponível em: https://www.w3schools.com/

---

<div align="center">
  <p>
    <strong>João Arantes</strong><br>
    Análise e Desenvolvimento de Sistemas — Faculdade INSTED<br>
    Disciplina: Desenvolvimento Web Full Stack<br>
    Campo Grande, MS — 2026
  </p>
  <p>
    <a href="https://github.com/joaovitorarantes86-ui">
      <img src="https://img.shields.io/badge/GitHub-181717?style=flat-square&logo=github&logoColor=white" alt="GitHub">
    </a>
    <img src="https://img.shields.io/badge/projeto%20acadêmico-FF6F00?style=flat-square" alt="Projeto Acadêmico">
  </p>
</div>
