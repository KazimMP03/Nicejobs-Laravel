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

1. Pedir auxílio de alguma IA como ChatGPT para auxiliar na instalação e/ou atualização dos itens 1 ao 4 em sua máquina.

2. Procurar o arquivo "php.ini-development" na pasta onde foi instalado o php. Renomear o arquivo para "php.ini". Abrir o arquivo como bloco de notas e tirar o ";" das seguintes linhas: ";extension=fileinfo" / ";extension=mbstring" / ";extension=openssl" / ";extension=pdo_pgsql". Salvar o arquivo.

3. Criar a pasta onde rodará o sistema, por exemplo: C:\nicejob.

4. (opcional) Adicionar a pasta C:\nicejob ao Workspace do VSCode.

5. Abrir o cmd de qualquer lugar e rodar: git clone https://github.com/KazimMP03/Nicejobs-Laravel.git C:\nicejob

6. Abrir o cmd na pasta C:\nicejob e rodar: composer update

7. Para garantir, também rodar na pasta: composer install

8. Rodar na pasta: copy .env.example .env

9. Abrir o arquivo ".env" (opcionalmente no VSCode) e substituir estes valores:
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

10. Na busca do windows, abrir "Serviços", procurar por "postgresql", click com botão direito e "iniciar", se já não estiver iniciado.

11. Abrir pgAdmin 4, "Servers", "PostgreSQL", click direito em "Databases" e "Create" e "Database..." somente colocar o nome do banco de dados "nicejob_db" e SAVE.

12. Abrir cmd na pasta C:\niocejob e rodar: php artisan key:generate

13. Também na pasta, rodar: php artisan migrate

14. Também na pasta, rodar: php artisan serve

15. Abrir no navegador: http://localhost:8000/
