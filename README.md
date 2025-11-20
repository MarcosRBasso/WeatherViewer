### 🌤️ Weather Viewer ###

Sistema de consulta, salvamento e comparação de previsões do tempo

---
### 🖼️ Preview ###

![Weather Viewer preview](https://github.com/user-attachments/assets/d1bf826e-1915-4f25-a001-51c23fc52a60)


---

### 📖 Sobre o Projeto ###

O Weather Viewer é uma aplicação web em Laravel criada para:

Pesquisar previsões do tempo por cidade ou CEP

Consultar automaticamente o ViaCEP

Exibir a previsão atual da API Weatherstack

Salvar a previsão diária para histórico

Comparar duas localidades lado a lado

É um projeto ideal para estudo prático de:

✔️ Laravel
<br><br>
✔️ Consumo de APIs externas
<br><br>
✔️ Padrões Service + Presenter
<br><br>
✔️ UX/UI com Blade + CSS fluido
<br><br>
✔️ Relacionamentos entre tabelas
<br><br>
✔️ Sessões + persistência de dados

---

### ✨ Funcionalidades ###
### 🔍 Busca ###

CEP → Cidade (automático)

O usuário informa um CEP

O sistema consulta o ViaCEP

Preenche automaticamente o campo Cidade

Realiza a busca da previsão automaticamente

Cidade → Previsão

O usuário pode digitar qualquer cidade

A API Weatherstack retorna:

Temperatura

Sensação térmica

Humidade

Vento

Condição (ex.: "Parcialmente nublado")

Horário local

---

### 🌦️ Previsão Atual ###

Após a busca, o sistema exibe um card com:

Informação	Exemplo
Localidade	Chapecó • SC
Temperatura	22°C
Sensação térmica	21°C
Umidade	65%
Vento	10 km/h
Condição	Parcialmente nublado

Os dados são formatados pelo WeatherPresenter.

---

### 💾 Salvar Previsão do Dia ###

Com apenas um clique:

A previsão atual é armazenada em weather_records

Apenas dados do dia atual são considerados

Permite comparações mais tarde

---

### 🕓 Histórico de Pesquisas ###

O sistema armazena cada busca em search_histories com:

Data

Cidade

Estado

Fonte

Snapshot completo (JSON)

No dashboard são exibidas as últimas 10 pesquisas.

---

### 📊 Comparação de Cidades ###

> O painel permite selecionar:

Região A

Região B

O sistema compara lado a lado:

| **Métrica**        | **Local A** | **Local B** |
|-------------------|-------------|-------------|
| Cidade            | ✔️          | ✔️          |
| Temperatura       | ✔️          | ✔️          |
| Sensação térmica  | ✔️          | ✔️          |
| Umidade           | ✔️          | ✔️          |
| Vento             | ✔️          | ✔️          |


Os selects mantêm a última escolha do usuário.

---

### 🧩 Arquitetura ###

<img width="763" height="330" alt="image" src="https://github.com/user-attachments/assets/56d3f85b-d317-4c8f-9202-faa33d62f57e" />

---

### 🔧 Como Funciona Cada Componente 
WeatherController ###

Controla toda a lógica do fluxo:

    index() → Dashboard

    search() → Busca previsão

    fillCityByCep() → Converte CEP

    saveToday() → Salva registro

    compare() → Compara duas cidades

### Services ###

Serviços externos especializados:

Serviço	Responsabilidade
ViaCepService	Buscar cidade pelo CEP
WeatherstackService	Buscar previsão do tempo
Presenter

Organiza e padroniza os dados retornados pela API

Evita lógica dentro das views

### Models ###

Relacionamentos:

Location → possui muitos SearchHistory e WeatherRecord

SearchHistory → pertence a Location

WeatherRecord → pertence a Location

---

###🗄️ Banco de Dados

### Tabelas principais:

locations

Armazena cidades consultadas.

search_histories

Guarda o histórico de buscas.

weather_records

Registro das previsões salvas no dia.

---

### ⚙️ Instalação ###

### 1. Clone o repositório ###

   <img width="500" height="35" alt="image" src="https://github.com/user-attachments/assets/2edf1dba-7ad2-4406-930c-ba56e512476d" />

### 2. Instale dependências ###

   <img width="500" height="50" alt="image" src="https://github.com/user-attachments/assets/bd70596d-298f-499c-9dab-23404dada362" />

### 3. Configure o .env

   <img width="500" height="142" alt="image" src="https://github.com/user-attachments/assets/ee330be2-64e1-41c6-888f-40cf8ff715a7" />

### 4. Gere a key

   <img width="500" height="35" alt="image" src="https://github.com/user-attachments/assets/a6922c15-1c00-4e96-a147-4766b360fc3f" />

### 5. Execute as migrations

   <img width="502" height="35" alt="image" src="https://github.com/user-attachments/assets/16666ff1-5817-4621-ba28-c1a2ba2d77f5" />

### 6. Inicie o servidor

   <img width="500" height="35" alt="image" src="https://github.com/user-attachments/assets/f8f00433-c79d-4d31-a63e-cf2b9a3d5f0e" />

---

### 🎨 Front-end e UX ###

Layout responsivo

Sistema de colunas fluido

Cards organizados

Comparação ocupa 100% da largura no desktop

Inputs e selects adaptados para mobile

Auto-submit ao buscar por CEP

---
