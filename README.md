# 🎯 NiceJobs – Conectando Pessoas a Soluções

**NiceJobs** é uma plataforma web moderna desenvolvida com **Laravel** e **PostgreSQL**, focada em facilitar a conexão entre **clientes** e **prestadores de serviço** de forma prática, segura e eficiente.

O sistema oferece uma base sólida para gerenciamento de usuários, cadastro de serviços, controle de avaliações e integração com um front-end responsivo, pensado para proporcionar a melhor experiência tanto para quem contrata quanto para quem oferece.

---

🚀 **Tecnologias Utilizadas:**

-   **Laravel** – Framework PHP para estruturação do backend
-   **PHP** – Linguagem principal da aplicação
-   **PostgreSQL** – Banco de dados relacional de alto desempenho
-   **Blade** – Sistema de templates nativo do Laravel
-   **Bootstrap** – Framework CSS responsivo para layout e componentes
-   **JavaScript** – Funcionalidades dinâmicas e interatividade
-   **HTML5 & CSS3** – Estrutura e estilo do front-end

---

🛠️ **Status do Projeto:**  
Em desenvolvimento ativo – Back-end parcialmente implementado, com integração do template front-end em andamento.

---

📂 **Objetivo:**  
Criar uma plataforma completa onde usuários possam cadastrar, buscar e contratar serviços, enquanto prestadores de serviço possam se registrar, oferecer seus serviços, e gerenciar sua reputação por meio de avaliações.

---

## 🧱 Estrutura do Projeto

app/ ->
Controladores e modelos da aplicação bootstrap/ ->
Arquivo de bootstrap da aplicação config/ ->
Arquivos de configuração do sistema database/ ->
Migrations, seeders e factories public/ ->
Arquivos públicos (index.php) resources/ ->
Views Blade, CSS, JS routes/ ->
Rotas web e API storage/ ->
Cache, logs e uploads tests/ ->
Testes automatizados

---

## 🚀 Como Instalar e Rodar o Projeto

# CONFIGURAÇÃO - Ambiente de Trabalho NiceJob! (Passo a Passo)

-   REQUISITOS:

1. PHP
2. Composer
3. PostgreSQL + pgAdmin 4
4. VSCode (opcional)
5. Laravel (já contido no repositório)
6. Clonar repositório
7. Configurar projeto

-   PASSOS:

1. Instalando PHP:

-   Acessar https://windows.php.net/download/ e baixar a última versão ZIP x64 Thread Safe, exemplo: VS17 x64 Thread Safe.
-   Extraia o ZIP para um caminho direto, tipo: C:\php
-   Pressione Win + S → digite "variáveis de ambiente" → clique em "Editar variáveis de ambiente do sistema". Clique em "Variáveis de ambiente". Em "Variáveis do sistema", ache a variável Path, clique em Editar. Clique em Novo e adicione: C:\php .Clique em OK em tudo pra fechar.
-   Vá até C:\php. Procurar o arquivo "php.ini-development" na pasta onde foi instalado o php. Renomear o arquivo para "php.ini". Abrir o arquivo como bloco de notas e tirar o ";" das seguintes linhas: ";extension=fileinfo" / ";extension=mbstring" / ";extension=openssl" / ";extension=pdo_pgsql" / ";extension=pgsql" / ";extension=curl" / ";extension=zip". Salvar o arquivo.
-   Para testar se instalou corretamente: Abra o terminal (cmd) e digite: php -v .Se tudo estiver certo, verá algo como: PHP 8.3.6 (cli) (built: ...)

2. Instalando Composer:

-   Acesse o site oficial: https://getcomposer.org/ .Clique em “Getting Started” → depois clique em “Composer-Setup.exe”.
-   Ele vai procurar o php.exe. Você deve apontar para onde instalou o PHP (por exemplo C:\php\php.exe).
-   Depois que instalar, abra o CMD e digite: composer . Se aparecer algo assim, tá feito: Composer version 2.x.x 202x-xx-xx
    Se aparecer algo assim, tá feito:

3. Instalando PostgreSQL + pgAdmin 4:

-   Baixe no site: https://www.postgresql.org/download/windows/
-   Execute o instalador. Durante a instalação instale os seguintes componentes: PostgreSQL Server e pgAdmin (stack builder não precisa).
-   Depois de instalado, execute pgAdmin e quando pedir a senha, coloque 123.

4. Criar a pasta onde rodará o sistema, por exemplo: C:\nicejob.

5. (opcional) Adicionar a pasta C:\nicejob ao Workspace do VSCode.

6. Abrir o cmd de qualquer lugar e rodar: git clone https://github.com/KazimMP03/Nicejobs-Laravel.git C:\nicejob

7. Abrir o cmd na pasta C:\nicejob e rodar: composer update

8. Para garantir, também rodar na pasta: composer install

9. Rodar na pasta: copy .env.example .env

10. Abrir o arquivo ".env" (opcionalmente no VSCode) e substituir estes valores:

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nicejob_db
DB_USERNAME=seu_usuario_postgres (normalmente postgres)
DB_PASSWORD=sua_senha_postgres (normalmente 123)

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=nicejob.noreply@gmail.com
MAIL_PASSWORD=rgzjgahypsatvzmz
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=nicejob.noreply@gmail.com
MAIL_FROM_NAME="NiceJob"

APP_TIMEZONE=America/Sao_Paulo

Salvar o arquivo.

11. Na busca do windows, abrir "Serviços", procurar por "postgresql", click com botão direito e "iniciar", se já não estiver iniciado.

12. Abrir pgAdmin 4, "Servers", "PostgreSQL", click direito em "Databases" e "Create" e "Database..." somente colocar o nome do banco de dados "nicejob_db" e SAVE.

13. Abrir cmd na pasta C:\niocejob e rodar: php artisan key:generate

14. Também na pasta, rodar: php artisan migrate

15. Também na pasta, rodar: php artisan serve

16. Abrir no navegador: http://localhost:8000/
