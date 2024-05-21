# TechBookLibrary
[![Image from Gyazo](https://i.gyazo.com/2b0faf396aa341b283bfc8c2c0336a1f.png)](https://gyazo.com/2b0faf396aa341b283bfc8c2c0336a1f)

## ■サービスURL
https://tech-book-library.com

## ■サービス概要
&emsp;TechBookLibraryは、技術書のレビューと図書館の蔵書検索ができるサービスです。ユーザーは、技術書のタイトル、著者、ISBNで検索し、その技術書に対する他のユーザーのレビューや評価を閲覧できます。また、特定の技術書が自分の利用する図書館で借りられるかどうかを確認することもできます。

## ■メインのターゲットユーザー
 - 技術書を頻繁に利用するエンジニア
 - 図書館を利用して書籍を借りることを好む読書家
 - 自分のレベルに合った技術書を探しているプログラミング学習者

## ■ユーザーが抱える課題
- 技術書の種類が多すぎて、自分に合ったレベルの本を見つけるのが難しい。
- 技術書を購入する前に、他の人の評価やレビューを確認したい。
- 図書館にある技術書の在庫状況を調べるのが面倒。

## ■解決方法
TechBookLibraryは以下の方法でこれらの課題を解決します：
- 技術書の検索機能:
  - ユーザーは技術書のタイトル、著者、ISBNで図書館の蔵書を検索できます。
- レビューと評価:
  - 各技術書に対する他のユーザーのレビューや評価を閲覧できます。
  - 書籍のレベル（入門、中級、上級）や内容に関する詳細なレビューを確認できるため、購入や借りる際の参考になります。
- 図書館登録:
  - 現在地または市区町村から蔵書検索の対象にする図書館を登録できます。
- 利用可能な図書館の表示:
  - 検索結果から特定の技術書がどの図書館で借りられるかを確認できます。
- お気に入り機能:
  - ユーザーは気に入った技術書をお気に入りに登録し、後で簡単にアクセスできます。
  - お気に入りの技術書が図書館にあるかどうかを簡単にチェックできます。

## ■使用技術
### バックエンド
- PHP 8.2
- Laravel 11
### フロントエンド
- Vue.js 3
### 使用API
- Google Books APIs
- 図書館API(カーリル)
- GeolocationAPI(現在地取得)
### インフラ
- AWS
  - Lightsail LAMP(PHP8)
  - S3(バックアップの保存)
  - Route53(DNS)

## ■[画面遷移図](https://drive.google.com/file/d/1spMPvU-7c2W0sn3dUXYlyugFOF3SPa-l/view?usp=sharing)
[![Image from Gyazo](https://i.gyazo.com/3731f69307f0a99a43564e55bd706cf9.png)](https://gyazo.com/3731f69307f0a99a43564e55bd706cf9)

## ■[ER図](https://drive.google.com/file/d/1EIm1-x5Hvp7RhYqe0K9zcKMyxp2eH_P3/view?usp=sharing)
[![Image from Gyazo](https://i.gyazo.com/3e26ecd5e9770ea1a46a58fe06fc93e5.png)](https://gyazo.com/3e26ecd5e9770ea1a46a58fe06fc93e5)
