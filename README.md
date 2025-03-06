# 勤怠管理アプリ

## 環境構築
1. Dockerを起動する
2. プロジェクト直下で下記コマンドを実行する

```
make init
```

## 使用技術
- Laravel 8.83.29
- PHP 7.4.9
- MySQL 8.0.26
- Docker

## URL
- phpMyAdmin：　http://localhost:8080/
- 新規会員登録画面：　http://localhost/register
- ログイン画面（一般ユーザー用）：　http://localhost/login
- 管理者ログイン画面（管理者用）：　http://localhost/admin/login


## テストアカウント
管理者ログインの際はこちらのアカウントを使用してください。<br>

name:  管理者1<br>
email: admin@gmail.com<br>
password: password123　　　　


## メール認証
mailtrapというツールを使用しています。<br>
以下のリンクから会員登録をしてください。　<br>
https://mailtrap.io/

メールボックスのIntegrationsから 「laravel 7.x and 8.x」を選択し、　<br>
.envファイルのMAIL_MAILERからMAIL_ENCRYPTIONまでの項目をコピー＆ペーストしてください。　<br>
MAIL_FROM_ADDRESSは任意のメールアドレスを入力してください。　


## ER図
![alt](er.png)


## PHPUnitを利用したテストに関して
以下のコマンド:  
```
docker-compose exec php bash
php artisan migrate:fresh --env=testing
./vendor/bin/phpunit
```

