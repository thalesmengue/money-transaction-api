# Desafio de fluxo de transação entre usuários

## Sobre o desafio

Nesse desafio, é necessária a criação de uma aplicação que possibilite o envio de transação entre usuários,
mas se atentando a alguns detalhes como:

- Para o usuário é preciso do Nome Completo, CPF, e-mail e Senha. CPF/CNPJ e e-mails devem ser únicos no sistema.
- Usuários podem enviar dinheiro (efetuar transferência) para lojistas e entre usuários.
- Lojistas só recebem transferências, não enviam dinheiro para ninguém.
- Validar se o usuário tem saldo antes da transferência.
- A operação de transferência deve ser uma transação, que ao apresentar erro ou inconsistência no processo, deve ser revertida e, 
o dinheiro deve voltar para a carteira do usuário que envia.

### Sobre a Resolução

- A criação da carteira é feita por meio de event/listener, então, no momento que um usuário é registrado,
  um evento é chamado, e um listener está "ouvindo" esse evento, esse listener realizará a criação da carteira,
  assim, cada usuário cadastrado irá ter sua carteira criada automaticamente, mas para esse fluxo não acabar lento em
  algum momento
  que esteja com muitas requisições, e não tornar a experiência do usuária lenta, o listener de criação da carteira está
  setado para
  ser assíncrono, assim, o usuário vai ser registrado, e o listener de criação da carteira jogado para uma fila.
- Os IDs das carteiras, dos usuários, e dos lojistas foram cadastrados como UUID, tendo em vista que estão sendo
tratados dados sensíveis.
- A responsabilidade de criação dos UUIDs foram colocadas em observers.
- Para a validação do documento dos usuários (lojistas e usuários comuns) foi criada uma regra de validação personalizada,
  que valida o número aceito de documentos que é 11 para CPF e 14 para CNPJ, também validando se apenas foram inseridos
  dígitos no campo.
- Os campos CNPJ/CPF e o email foram setados como ```unique``` para garantir que o usuário consiga ter apenas uma conta.
- O projeto conta com autenticação, então, para que o usuário possa realizar uma transação deve estar logado, caso contrário,
  não estará autorizado a realizar a transação.

### Como rodar o projeto
```bash
# clone o projeto
$ git clone git@github.com:thalesmengue/money-transaction-api.git

# instale as dependências
$ docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs

# crie o arquivo .env
$ cp .env.example .env

# inicie a containerização do docker (com a flag -d para iniciar em segundo plano)
$ ./vendor/bin/sail up -d

# gerar uma nova chave da aplicação
$ ./vendor/bin/sail artisan key:generate

# migre as tabelas
$ ./vendor/bin/sail artisan migrate
```

Ao final, sua aplicação estará rodando em ```http://localhost```

### Testes
Foram realizados testes para cobrir os possíveis cenários da aplicação.

Para rodar os testes digite:
```bash
 ./vendor/bin/sail artisan test
```

### Referências
- [Laravel 10](https://laravel.com/docs/10.x/installation)
- [PHP 8.1](https://www.php.net/)
- [Passport](https://laravel.com/docs/10.x/passport)
- [Docker](https://docs.docker.com/get-started/)
