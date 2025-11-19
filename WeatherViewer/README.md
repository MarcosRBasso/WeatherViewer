📘 WeatherViewer — Sistema de Consulta e Comparação de Previsão do Tempo

Aplicação desenvolvida em Laravel, seguindo padrão MVP (Model–View–Presenter) e integrando duas APIs externas:

ViaCEP → para identificar cidade/estado a partir do CEP

Weatherstack → para consultar a previsão do tempo atual

O sistema permite:

✔ Buscar previsão por cidade
✔ Buscar previsão por CEP
✔ Exibir dados detalhados da previsão
✔ Salvar a previsão do dia
✔ Listar buscas recentes
✔ Comparar duas regiões lado a lado

📁 Estrutura do Projeto
app/
 ├── Http/Controllers/WeatherController.php
 ├── Models/
 │    ├── Location.php
 │    ├── SearchHistory.php
 │    └── WeatherRecord.php
 ├── Services/
 │    ├── WeatherstackService.php
 │    └── ViaCepService.php
 └── Presenters/
      └── WeatherPresenter.php

resources/
 └── views/
      └── weather/
           ├── index.blade.php
           └── history.blade.php

🚀 Instalação & Execução
1️⃣ Clonar o repositório
git clone https://github.com/seuusuario/weatherviewer.git
cd weatherviewer

2️⃣ Instalar dependências
composer install

3️⃣ Configurar o .env

Defina banco e a chave da API Weatherstack:

APP_KEY=base64:xxxxx
WEATHERSTACK_KEY=SUA_CHAVE_WEATHERSTACK

DB_DATABASE=weatherviewer
DB_USERNAME=root
DB_PASSWORD=123

4️⃣ Criar tabelas
php artisan migrate

5️⃣ Rodar o servidor
php artisan serve

🧠 Como o Sistema Funciona
▶ 1. Busca por CEP

Arquivo: WeatherController@fillCityByCep()
Serviço: ViaCepService

Fluxo:

Usuário digita o CEP

Front envia AJAX → /weather/fill-city

ViaCEP retorna:

cidade

estado

Front preenche automaticamente o campo cidade

O sistema já dispara a busca por previsão

Onde alimentar:
Nada precisa ser cadastrado. O ViaCEP retorna automaticamente.

▶ 2. Busca por Cidade

Arquivo: WeatherController@search()
Serviço: WeatherstackService
Presenter: WeatherPresenter

Fluxo:

Cidade enviada via POST

Weatherstack retorna dados da previsão atual

Presenter converte resposta para um formato padronizado

O sistema cria/atualiza um registro em locations

Registra também no search_histories

Onde alimentar:
Você só digita o nome da cidade no campo de busca.

🗂 Models e Suas Funções
📍 Location

Armazena cidades pesquisadas:

protected $fillable = ['city', 'state', 'country', 'cep'];

📚 SearchHistory

Armazena pesquisas realizadas:

cidade

data

snapshot da previsão retornada

🌡 WeatherRecord

Armazena previsões salvas do dia para comparação:

temperatura

umidade

vento

descrição do clima

Campos são salvos em JSON também (raw_response).

🧩 Serviços
🌐 ViaCepService

Consulta:

https://viacep.com.br/ws/{cep}/json/


Retorna cidade e estado.

☁ WeatherstackService

Consulta:

http://api.weatherstack.com/current?access_key=KEY&query=CIDADE


Retorna dados detalhados:

temperatura

sensação

vento

localtime

descrição do clima

🎨 Views (Front-end)
🏠 index.blade.php

Divide a tela em 3 blocos principais:

1. Busca (CEP e Cidade)

CEP → autocomplete

Cidade → busca direta via API

2. Previsão Atual

Exibe:

cidade / estado

temperatura

sensação

umidade

vento

horário local

botão Salvar previsão de hoje

3. Histórico

Mostra as últimas pesquisas realizadas.

4. Previsões Salvas Hoje

Lista chips com cidades e temperaturas.

5. Comparação

O usuário escolhe Região A e Região B.
O sistema exibe dados lado a lado.

🔁 Fluxo Completo do Sistema
CEP → ViaCEP → Preenche cidade → (opcionalmente busca previsão)

Cidade → Weatherstack → Formata → Mostra previsão atual

Usuário clica "Salvar previsão do dia" → WeatherRecord

Tela carrega:
 - Histórico
 - Previsões salvas hoje
 - Seletores de comparação

Usuário compara → tamanhos token
 - Busca registros salvos HOJE
 - Exibe lado a lado

🧪 Como alimentar as informações de teste
✔ Para ter dados na comparação

O sistema só compara previsões salvas HOJE, então:

Busque cidade A

Clique Salvar previsão de hoje

Busque cidade B

Clique Salvar previsão de hoje

Agora selecione A e B na comparação

🧩 MVP – Separation of Concerns

Model
Responsável pelos dados no banco e relacionamentos.

View
Arquivos Blade exibem o layout + dados formatados.

Presenter
Converte formatos de API para padrão interno do sistema.
(Ex.: renomeia campos, normaliza dados, etc.)

Services
Cada API externa tem uma classe específica especialista.

Controller
Orquestra tudo:

recebe requisições

chama serviços

salva histórico

envia dados para as views

📌 Conclusão

Este projeto demonstra:

✓ Integração com APIs reais
✓ Padrão MVP
✓ Migrations, Models, Controllers
✓ Blade responsivo (mobile-first)
✓ Comparação dinâmica de dados
✓ Uso de sessões, validação e persistência
