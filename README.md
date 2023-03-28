## CSTSI - DAOO - Aula 03: 
### PHP PDO E MVC

Para realizar a atividade da Aula 03 clone este repositório.
Na pasta atual (aula_03_pdo_mvc) digite os comandos necessários para instalar o autoload padrão (PSR-4) com o composer:

```shell
composer i
```

Arquivos com o dump do banco de dados estão na pasta src/database. Utilize os arquivos de acordo com o SGBD especificado no nome (pgsql | mysql).


Para executar o servidor web e acessar os scripts da pasta scr/web, execute o seguinte comando:

```shell
composer run web
```
Este comando iniciará um servidor local na porta 8080, para acesso aos scripts PHP localizados na pasta src/web. Estes exemplos utilizam apenas a camada de modelo porém não usam MVC. Para executá-los basta acessar o servidor local seguido do nome do script.

Exemplo:
```HTTP
GET http://localhost:8080/showProduto.php
```
Acesso ao produto específico por ID:
```HTTP
GET http://localhost:8080/showProduto.php?id=111
```

Para executar a API rode o comando:

```shell
composer run api
```
Assim será iniciado o servidor local na porta 8081 para acessar as rotas do sistema MVC. As rotas deste sistema de exemplo seguem o seguinte padrão:

Para acessar uma classe específica e seu método passando o parametro via URL
```HTTP
GET	http://localhost:8081/classe/metodo/parametro
```
Exemplo:
```HTTP
	GET http://localhost:8081/produto/show/111
```
Também é possível acessar as rotas via POST, os parâmetros deverão ser enviados no corpo da requisição ou por form-url-encode. Para usar as rotas POST acesse via um cliente HTTP (curl, postman, insomnia, thunder client, rapidapi, etc...).

As atividades de aula deverão ser feitas utilizando a api e o sistema MVC.

Para alterar as portas (8080 | 8081), basta alterar as configurações de scripts no arquivo [composer.json](./composer.json).