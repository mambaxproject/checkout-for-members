# Checkout

1 - Clonar o projeto

2 - Executar o arquivo `cp .env.example .env` para copiar o arquivo de exemplo para `.env`

Em seguida, edite seu arquivo .env com credenciais de banco de dados e outras configurações.

3 - Execute o comando `composer install`

4 - Execute o comando `php artisan migrate --seed`.

Aviso: a semente é importante, porque criará o primeiro usuário administrador para você.

5 - Execute o comando `php artisan key:generate`.
