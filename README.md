# 🎯 NiceJobs - Plataforma para conectar clientes a prestadores de serviço

NiceJobs é uma aplicação web desenvolvida em Laravel com o objetivo de conectar clientes a prestadores de serviço, utilizando recursos modernos de estruturação e segurança.

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

```bash
# Clone o repositório
git clone https://github.com/KazimMP03/Nicejobs-Laravel.git
cd Nicejobs-Laravel

# Instale as dependências
composer install

# Copie o .env e gere a chave
cp .env.example .env
php artisan key:generate

# Rode as migrações
php artisan migrate

# Suba o servidor local
php artisan serve
