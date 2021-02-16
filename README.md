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
docker-compose exec php symfony serve --allow-http --no-tls
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
docker-compose exec php symfony serve --allow-http --no-tls
```

ブラウザでwebサイトを開く (symfony open:local の代替)
http://localhost:8000/


以降のステップで実行するコマンドは
```
docker-compose exec php COMMAND
```
とする



作業環境の再構築
```
docker-compose down --rmi all --volumes --remove-orphans
docker-compose up -d
```
