# APG Admin Hub

## Gettins Started

### Use Package
このシステムではLaravel Breezeを使っています。 

### for Windows
Windowsの場合、高い確率でsailコマンドが使えません。  
その場合は、docker-compose コマンドでコンテナの立ち上げやbashを行ってください。  
例えば `sail composer install ` は、 `docker-compose exec php php composer install` と読み替えてください。


## for admin
このシステムはLaravelSailを基本として環境構築を行ってます。  
このセンテンスは、この環境を別で作りたい人は読んでください。使うだけの人は飛ばして大丈夫です。

### 初期構築手順

Laravelのベースをインストールする  
この時点でsailは入ります。
```
docker run --rm -v $(pwd):/opt -w /opt laravelsail/php82-composer:latest composer create-project laravel/laravel admin-app
cd admin-app
```

sailを使えるようにインストールします。  
php用のDockerfileはコピーしておきます。
```
docker run --rm -v $(pwd):/opt -w /opt/admin-app laravelsail/php82-composer:latest php artisan sail:install
mkdir -p docker/sail/
cp -r vendor/laravel/sail/runtimes/8.2/* docker/sail/
```

sailコマンドをzshrcなどにalias登録します。
```
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

docker-compose.yamlを書き換えます（このプロジェクトのdocker-compose.yamlをそのまま使えば大丈夫）

.envを書き換えます。主にport部分の追加を行います。
```
WWWGROUP=1000
WWWUSER=1000
SAIL_XDEBUG_MODE=develop,debug

### 各環境でかぶらないPORTに変更してください。
APP_PORT=8091
VITE_PORT=5174
FORWARD_DB_PORT=3307
FORWARD_MEILISEARCH_PORT=7701
FORWARD_PHPMYADMIN_PORT=8052
FORWARD_REDIS_PORT=6380
FORWARD_MAILPIT_PORT=1026
FORWARD_MAILPIT_DASHBOARD_PORT=8026
### ここまで
```

sailを実行します。  
docker-compose up -dと同じ内容が実行されます。
```
sail up -d
```

breezeをインストール  
ついでにユーザ情報migrate
```
sail composer require laravel/breeze
sail artisan breeze:install
sail npm install
optimize;sail artisan migrate
```

URLのprefixに/admin をつけるために、routes/web.phpをこれで囲む。
```
Route::prefix('admin')->group(function () {
XXXX
})
```



laravel-adminlteをインストール
```
sail composer require jeroennoten/laravel-adminlte
sail artisan adminlte:install
sail npm install admin-lte
cp -r node_modules/admin-lte/plugins resources/css/plugins
cp -r node_modules/admin-lte/dist/css resources/css/adminlte
cp -r node_modules/admin-lte/dist/js resources/js/adminlte
```

全キャッシュの再構築
```
sail artisan route:clear;sail artisan config:clear;sail artisan 
```

以上。


### How to Start

1. composer install by docker & sail install

```
docker run --rm -v $(pwd):/opt -w /opt laravelsail/php82-composer:latest composer install
docker run --rm -v $(pwd):/opt -w /opt/admin-app laravelsail/php82-composer:latest php artisan sail:install
```

2. configure sail alias for ~/.zshrc or ~/.bashrc, and then restart your shell.

```
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

3. Make .env

```
cp .env.example .env
sail up -d
sail composer install
sail npm install
sail npm run dev
sail artisan migrate
sail artisan db:seed
```

3. Create user

http://localhost:8091/admin/register


4. xdebug(option)

make .vscode/launch.json

```
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/data": "${workspaceFolder}"
            }
        }
    ]
}
```
