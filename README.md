The Fast Track - 基礎から最速で学ぶ Symfony 5 入門
===

## What's this ?

2021/01/30(土)開催の「[Symfony書き初め 2021](https://symfony-kansai.connpass.com/event/200467/)」に参加した際に実施した「[The Fast Track - 基礎から最速で学ぶ Symfony 5 入門](https://symfony.com/book)
」のトレースです。

※「ステップ 1: 作業環境を確認する」の部分をローカルではなくDocker Composeで構築しています(Gitのみローカルにインストール)

※The Fast Track のPDFからコマンドをコピペする場合は、一度テキストエディタに貼り付けて、改行を取り除くことをおすすめします

## ステップ 1: 作業環境を確認する

ローカル環境に作業環境を構築するステップ 1を

Dockerfile / docker-compose.yml の作成に替えました。

## 作業環境立ち上げ

.env ファイルに作業環境の定義(symfony5.2以上でphp8.0対応)
```
APP_NAME=guestbook
PHP_VERSION=8.0
SYMFONY_VERSION=5.2
```

作業環境立ち上げ
```
docker-compose up -d
```
Symfony Local Server を起動
```
docker-compose exec php symfony server:start -d
```

ブラウザでwebサイトを開く (symfony open:local の代替)

http://localhost:8000/

## ステップ 2: 作成するプロジェクトについて(スキップ)

ステップ 2で紹介されている下記のbookオプションでのGitHubリポジトリからソースクローンは使用せず、そのままステップ 3に進みました

symfony new --version=5.0-6 --book guestbook

## ステップ 3: ゼロの状態からプロダクションまでやってみよう

symfony プロジェクトの作成 (symfonyプロジェクトを内包し、作業環境ごとGitリポジトリに含めるため、ここではgitの初期化しない --no-gitオプションを追加、symfonyプロジェクト名やsymfonyバージョンは環境変数を参照するように変更)
```
docker-compose exec php bash -c 'cd /usr/src/ && symfony new ${APP_NAME} --version=${SYMFONY_VERSION} --no-git'
```

Symfony Local Server を再起動
```
docker-compose exec php symfony server:start -d
```

ブラウザでwebサイトを開く (symfony open:local の代替)

http://localhost:8000/


以降のステップで実行するコマンドは
```
docker-compose exec php COMMAND
```
とする

## ステップ 8: データ構造の説明

### 8.2 Symfonyの環境変数の規約を理解する

.envファイルのDATABASE_URLはDockerのサービス名指定での接続に変更

```
--- a/guestbook/.env
+++ b/guestbook/.env

-DATABASE_URL="postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=13&charset=utf8"
+DATABASE_URL="postgresql://main:main@database:5432/main?serverVersion=13&charset=utf8"
```

## ステップ 9: 管理者用のバックエンドをセットアップする

### 9.1 EasyAdmin を設定する

テキストではEasyAdmin2系のインストール、設定手順の記載があるが、
EasyAdmin3系に変更

```
docker-compose exec php symfony composer req "admin:^3"
```

config/packages/easy_admin.yamlの編集の代わりにMakerBundleのコマンドを使用して
DashBoardとCRUD Controllerを作成

```
docker-compose exec php bin/console make:admin:dashboard
docker-compose exec php bin/console make:admin:crud
```
対話形式で
```
App\Entity\Comment
App\Entity\Conference
```
のCRUD Controllerを作成

DashBoardにCRUD Controllerへのリンクを追加

/guestbook/src/Controller/Admin/DashboardController.php
```php
+use App\Entity\Conference;
+use App\Entity\Comment;


    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
+        yield MenuItem::linkToCrud('Conference', 'fas fa-users', Conference::class);
+        yield MenuItem::linkToCrud('Comment', 'fas fa-comment', Comment::class);
    }
```

CRUD Controllerを編集してアソシエーションフィールドを追加

/guestbook/src/Controller/Admin/CommentCrudController.php
```php
+use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
+use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
+use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
+use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

+    public function configureFields(string $pageName): iterable
+    {
+        return [
+            AssociationField::new('conference'),
+            TextField::new('author'),
+            TextareaField::new('text'),
+            TextField::new('email'),
+            DateTimeField::new('createdAt'),
+            TextField::new('photoFilename'),
+        ];
+    }
```

/guestbook/src/Controller/Admin/ConferenceCrudController.php
```php
+use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
+use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
+use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
+use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

+    public function configureFields(string $pageName): iterable
+    {
+        return [
+            TextField::new('city'),
+            TextField::new('year'),
+            BooleanField::new("isInternational"),
+            AssociationField::new("comments"),
+        ];
+    }
```

## 作業環境の再構築

```
docker-compose down --rmi all --volumes --remove-orphans
docker-compose up -d
```
