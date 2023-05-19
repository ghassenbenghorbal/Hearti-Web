## Features

- Laravel 8
- Inertia.js
- Vue
- Vuetify + Material Design Icons
- FastAPI
- Nodejs

## FastAPI(Used for Prediction) Installation

Install Dependecies
```bash
cd fastapi
pip install fastapi
pip install uvicorn
pip install pydantic
pip install scikit-learn
pip install pandas
pip install tensorflow
```
Run Server
```bash
cd fastapi
uvicorn main:app --reload
```

## Nodejs (Used for Realtime Bracelet data) Installation

Install Dependecies
```bash
cd realtime-bracelet-server
npm install
node index.js
```

## Nodejs (Used for Realtime Chat data) Installation

Install Dependecies
```bash
cd realtime-chat-server
npm install
node index.js
```

## Laravel+Vue App Installation

Install PHP Dependencies

```bash
composer install
```

Install NPM Dependencies

```bash
npm install
```

Build assets

```bash
npm run dev
```

Setup Configuration

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Run Database migrations

```bash
php artisan migrate:fresh --seed
```
