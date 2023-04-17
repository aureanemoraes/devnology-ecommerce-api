# Sobre o projeto
Este é um teste técnico desenvolvido para a empresa Devnolog.

# Sobre 
Laravel Version ............................................................................................. 10.7.1

PHP Version ................................................................................................. 8.1.17

Composer Version ............................................................................................. 2.4.2

# Executar o projeto

## Passos para executar localmente:

`git clone git@github.com:aureanemoraes/devnology-ecommerce-api.git`

`cd devnology-ecommerce-api`

`composer i`

`php artisan key:generate`

Configurar o .env com os dados do DB de sua preferência.

Executar as migrations e seeds: `php artisan migrate --seed`

`php artisan serve`

## Passos para executar via docker:

`git clone git@github.com:aureanemoraes/devnology-ecommerce-api.git`

`cd devnology-ecommerce-api`

`docker build -t devnology-ecommerce-api:1.0 .`

`docker run -d -p 8000:8000 devnology-ecommerce-api:1.0`

`php artisan key:generate`

Configurar o .env com os dados do DB de sua preferência.

Executar as migrations e seeds: `php artisan migrate --seed`

# Sobre o desenvolvimento da aplicação

Projetei a aplicação para que ela possa atender o escopo do projeto atual e possa ser escalada sem complicações. Para isso levei em consideração performance e a utilização das técnicas de clean code, deixando o código adequado para qualquer porte de aplicação (pequeno, médio e grande).
A aplicação foi colocada em docker para ser visualizada em qualquer ambiente que possua essa ferramenta.

# To-dos utilizados de auxílio

RF

-> deve listar todos os produtos disponíveis de todos os fornecedores em ordem aleatória;

-> deve listar todos os produtos disponíveis por fornecedor;

-> deve ser possível filtrar produtos;

    - RNF:
        * hasDiscount
        * discountValue
        * details.adjective
        * details.material / material
        * price / preco
        * departamento
        * categoria
        
-> deve ser possível pesquisar produtos

    - RNF:
        * name / nome
        * description / descricao
        
-> deve ser possível realizar uma compra

    -> se algum item estiver indisponível a compra não poderá ser finalizada;
    
    -> enviar um resumo e deixar em cache por 5 min para aguardar confirmação;
    
-> deve ser possível visualizar os produtos sem estar autenticado

-> deve ser possível adicionar produtos ao carrinho de compras 

    - RNF: front com cookie
    
-> deve ser possível ver o histórico de compras

-> deve ser possível ter um carrinho sem estar autenticado

    - RNF: front com cookie
    
-> deve solicitar autenticação antes de finalizar a compra

    - RNF:
        -> MER:
            - order
                - user_id
            - products
                api_identifier
                provider
            - orders_products
                - order_id
                - product_id
                - info # informações do produto no momento da compra
                
-> deve manter em cache os produtos visualizados recentemente

-> deve tratar indisponilidade da API servidora

-> deve tratar indisponilidade do back 

-> deve tratar falha de conexão do cliente

RNF

-> db: postgres

-> api: laravel

-> app: nextjs

-> cache: db

-> aplicação em docker

-> padronizar todas as responses da API utilizando MACROS;

-> paginar as respostas;

-> padronizar o formato dos dados recebidos da 3rd part api;
