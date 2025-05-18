# Gerenciador de Tarefas - Teste Senior Hcosta

Este projeto é uma API RESTful desenvolvida em **Laravel 12**, com arquitetura **modular baseada em DDD (Domain-Driven Design)**. Ele implementa autenticação via **JWT**, persistência via **Eloquent**, logs em **MongoDB**, filas com **RabbitMQ**, e um alto nível de separação de responsabilidades para facilitar manutenção e escalabilidade.

---

## ⚡ Iniciar Aplicação



```Rodar no terminal
git clone https://github.com/leonardo-831/Teste-Hcosta-Senior.git
./docker/build-docker.sh
```

Após isso, a API estará acessível em:
```
http://localhost:8080/api
```

---

## 🌐 Funcionalidades

- Registro, login e logout com JWT
- CRUD de projetos (somente dono pode editar/deletar)
- CRUD de tarefas (somente responsável pode editar)
- Notificação via RabbitMQ ao criar ou atribuir tarefa
- Log de eventos importantes em MongoDB

## 📌 Postman Collection  
Link para documentação das APIs: https://documenter.getpostman.com/view/20929682/2sB2qWJ59T

Para testar a API, importe a collection do Postman:  
[Download da Collection](/documentation/Teste-Hcosta.postman_collection.json) 

---

## 🗂️ Diagrama do Banco de Dados

![Diagrama ER](documentation/database-diagram.png)

---

## 📂 Explicando Arquitetura

```
app/
└── Modules/
    ├── Auth/
    │   ├── Domain/         → Entidade e contrato de User
    │   ├── Application/    → AuthService (login, register, logout)
    │   ├── Infrastructure/ → Model Eloquent e UserRepository
    │   └── Interfaces/     → AuthController, FormRequests e rotas
    
    ├── Project/
    │   ├── Domain/         → Entidade Project e interface ProjectRepository
    │   ├── Application/    → ProjectService
    │   ├── Infrastructure/ → Model Eloquent e repositorio
    │   └── Interfaces/     → Controller, Request, Routes

    └── Task/
        └── Mesma estrutura, com Entities, Jobs, Services e Repositories
```

---

## 🧠 Motivação das Decisões Técnicas

### 📊 Modularização com DDD
- Cada módulo representa um **contexto delimitado(isolado) do negócio** (Auth, Project, Task)
- Permite evoluir funcionalidades sem gerar acoplamento entre camadas
- Facilita escalar o sistema e eventualmente quebrar em micro-serviços

### 📖 Separation of Concerns (SoC)
- **Entities**: representam o "cérebro do sistema", com regras puras, tudo que tem identidade e não é definido por seus valores
- **Repositories**: definem *como* buscar ou salvar (interfaces), adotado método de interfaces para desacoplamento das ferramentas, no caso Eloquent, facilitando uma mudança futura de ORM
- **Application Services**: executam ações como criar projeto ou login, encapsulam regras de negócio e não deve ser acoplado a qualquer tecnicalidade, lib ou recurso, deve conter apenas as regras em código puro, mantendo linguagem ubíqua
- **Infrastructure**: Eloquent, RabbitMQ, MongoDB, etc. Tudo que persiste no banco, repositories, models, infraestrutura da aplicação
- **Interfaces/Http**: único ponto que fala com o mundo externo (API), requisições http externas, rotas, validações nas requisições

### ✅ Por que usar interfaces?
- Separar o **domínio** da tecnologia (Eloquent pode mudar por Mongo, Redis, etc.)
- Permite uso de **mocks em testes** facilmente

Para envio de Logs e E-mails, foi adotado o uso de events/listeners, onde são executados diretamente na fila do rabbitmq. Os recursos de envio de e-mail e armazenamento de logs foi implementado no modulo Shared, afim de manter os contextos delimitados de cada modulo, já que são recursos Cross-Cutting Concerns (Preocupações Transversais),
atravessam múltiplos domínios e não pertencem a um contexto específico.

---

## ⚙ Tecnologias utilizadas
- Laravel 12
- tymon/jwt-auth (JWT)
- jenssegers/mongodb (Mongo)
- vladimir-yuldashev/laravel-queue-rabbitmq
- Docker

---

## ✅ Testes e Qualidade
- Testes com PHPUnit
- Fácil de mockar services e repositórios
- Ideal para TDD com arquitetura limpa

Os caminhos para os testes agora estão dentro de seus respectivos modulos, para manter a estrutura delimitada e isolada, para isso foi preciso
alterar os paths no phpunit.xml, por meio dessa configuração
```
<testsuites>
    <testsuite name="Unit">
        <directory suffix="Test.php">app/Modules/Auth/Tests/Unit</directory>
        <directory suffix="Test.php">app/Modules/Task/Tests/Unit</directory>
        <directory suffix="Test.php">app/Modules/Project/Tests/Unit</directory>
    </testsuite>
    <testsuite name="Feature">
        <directory suffix="Test.php">app/Modules/Auth/Tests/Feature</directory>
        <directory suffix="Test.php">app/Modules/Task/Tests/Feature</directory>
        <directory suffix="Test.php">app/Modules/Project/Tests/Feature</directory>
    </testsuite>
</testsuites>
```
Com isso, é possível testar por classes de teste, por método da classe e até o modulo completo, exemplos:
Entrar dentro do container tasks_app:
```
docker exec -it task_app bash
```

### Modulo Auth
```
php artisan test app/Modules/Auth     ---> Executa todos os testes do módulo Auth, tanto de feature, quanto de unidade
```
```
php artisan test --filter=AuthFeatureTest   ---> Executa todos os testes da classe AuthFeatureTest, assim você pode escolher qualquer classe de testar
```
```
php artisan test --filter=AuthFeatureTest::testUserCanRegisterSuccessfully   ---> Executa apenas o método desejado na classe AuthFeatureTest
```
Pode seguir o mesmo padrão para os outros arquivos de teste, cada módulo tem seus diretório Tests, com 2 pastas, uma para Feature Tests e outra para Unit Tests

---

Desenvolvido com foco em arquitetura, manutenção e escalabilidade ✨
