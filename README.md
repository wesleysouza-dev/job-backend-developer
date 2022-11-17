<p align="center"><a href="hhttps://www.adoorei.com.br/" target="_blank"><img src="https://adoorei.s3.us-east-2.amazonaws.com/images/loje_teste_logoadoorei_1662476663.png" width="160"></a></p>

## Configuração do ambiente

Para iniciar essa etapa de configuração do ambiente, é obrigatório ter o [Docker](https://docs.docker.com/desktop/ "Docker") instalado em sua máquina.

Navegue até a pasta raíz do projeto e execute o comando: `$ docker compose up -d` para inicializar o container.

Copie o arquivo .env.example e renomeie para .env dentro da pasta raíz da aplicação. Caso esteja usando o Linux, você pode usar o comando abaixo:

`$ cp .env.example .env`

Confira em seu arquivo .env se há as duas entradas abaixo:

```dosini
APP_URL_API=http://localhost:8000/api
APP_URL_API_PRODUCTS=https://fakestoreapi.com/products
```

Após a criação do arquivo .env, acesse o container da aplicação.

Para isso, use o comando `$ docker exec -it adoorei_test_app sh`.

Execute os comandos abaixo dentro do container:

```bash
$ composer install
$ php artisan key:generate
$ php artisan migrate

```

## Endpoints da API disponíveis

```sh
GET [list all] localhost:8000/api/products
GET [list single] localhost:8000/api/products/{id}
POST [create] localhost:8000/api/products
PUT [update] localhost:8000/api/products/{id}
DEL [delete] localhost:8000/api/products/{id}
```

## - Exemplo de Create

A inserção é feita em lote, portanto, deverá se passar um array de objetos (mesmo para inserir apenas um registro). Exemplo abaixo:

```json
[
    {
        "name": "Camiseta Polo",
        "price": 120.9,
        "description": "Camiseta social Polo, tamanho P ao GG.",
        "category": "Roupas",
        "image_url": "https://fakestoreapi.com/img/71li-ujtlUL._AC_UX679_.jpg"
    }
]
```

##### Obs: A ação de Update não aceita alteração em lote, portanto, enviar o objeto direto.

## Como importar produtos de API externa

-- Todos os produtos (inserção em massa)

```bash
$ php artisan products:import
```

-- Produto individual

```bash
$ php artisan products:import --id={id}
```

##### Obs: substitua o {id} pelo id do produto à ser importado, de 1 a 20.

##### API base utilizada: https://fakestoreapi.com/docs

## - Filtros disponíveis

Os filtros disponíveis podem ser aplicados juntos ou isoladamente, em forma de queryString.

```dosini
id => Number
name => String
category => String
image_url => Boolean
```

```
Exemplo: ?id=5&name=camiseta&category=roupas&image_url=false
```

---

Forte abraço! 😉
