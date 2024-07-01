# Projeto Wallet

Este e um projeto utilizando `Laravel Framework` que implementa de forma simplificada um sistema de transferências
entre carteiras, com suporte para autorização de pagamentos,
verificações de saldo e envio de notificações após transações bem-sucedidas.

### Notificações

Após uma transferência bem-sucedida, uma notificação será enviada via email/sms.

### Estrutura do Projeto

- **App\Applications\Transfers:** Contém a aplicação de transferência que coordena a lógica do domínio.
- **App\Domain\Entities:** Contém as entidades do domínio, como TransferEntity e UserEntity.
- **App\Domain\Enums:** Contém os enums utilizados no domínio, como UserTypeEnum.
- **App\Domain\Database\Repositories:** Contém ‘interfaces’ para os repositórios usados pelo domínio.
- **App\Domain\ValueObjects:** Contém objetos de valor, como Money.
- **App\Adapters:** Implementações concretas.

## Requisitos

- [Docker](https://www.docker.com/)

## Instalação

### 1. Clone o Repositório

```shell
https://github.com/joaolucassilva/wallet.git
cd wallet
```

### 2. Configure o arquivo `.env`

```shell
cp .env.example .env
```

### 3. Instale as Dependências

Nenhuma das dependências do Composer do aplicativo, incluindo o Sail, será instalada após
você clonar o repositório do aplicativo no seu computador local.  
Este comando usa um pequeno contêiner Docker contendo PHP e Composer para instalar as
dependências da aplicação:

```shell
cd wallet

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

### 4. Gere a chave da Aplicação

```shell
./vendor/bin/sail artisan key:generate
```

### 5. Execute as Migrações e os seed(usuario e carteira)

```shell
./vendor/bin/sail artisan migrate --seed
```

### 6. Execute todos os servicos(nginx,mysql,redis)

```shell
./vendor/bin/sail up -d
```

## Testes

Para rodar os testes, use o comando:

```shell
./vendor/bin/sail artisan test

```

## Uso

### Execute o worker para processamento e envio de notificação
```shell
./vendor/bin/sail artisan queue:work
```

### Criar Transferência

Para realizar uma transferência entre duas carteiras, envie uma requisição POST para a
rota /transfer com o payload a seguir:

```shell
{
    "payer": "id_do_pagador",
    "payee": "id_do_beneficiario",
    "amount": 100.00
}
```


