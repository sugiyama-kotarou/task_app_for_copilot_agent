<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            [
                'title' => 'データベース設計を完成させる',
                'description' => 'アプリケーションで使用するデータベースのテーブル設計とリレーションシップを定義する。ERDを作成し、正規化を確認する。',
                'thumbnail' => null,
            ],
            [
                'title' => 'フロントエンドUIコンポーネント作成',
                'description' => 'React/Vueコンポーネントライブラリを使用して、再利用可能なUIコンポーネントを作成する。デザインシステムに準拠する。',
                'thumbnail' => null,
            ],
            [
                'title' => 'API仕様書の作成',
                'description' => 'OpenAPI 3.0を使用してREST APIの仕様書を作成する。エンドポイント、リクエスト・レスポンス形式を詳細に記述する。',
                'thumbnail' => null,
            ],
            [
                'title' => 'セキュリティテストの実装',
                'description' => 'アプリケーションの脆弱性を検出するためのセキュリティテストを実装する。SQLインジェクション、XSSなどを確認する。',
                'thumbnail' => null,
            ],
            [
                'title' => 'パフォーマンス最適化',
                'description' => 'データベースクエリの最適化、キャッシュ戦略の実装、フロントエンドのバンドルサイズ削減を行う。',
                'thumbnail' => null,
            ],
            [
                'title' => 'ユーザー認証システムの実装',
                'description' => 'JWT認証を使用したユーザーログイン・ログアウト機能を実装する。パスワードリセット機能も含める。',
                'thumbnail' => null,
            ],
            [
                'title' => 'テストスイートの拡充',
                'description' => 'ユニットテスト、統合テスト、E2Eテストを網羅的に作成し、CI/CDパイプラインに組み込む。',
                'thumbnail' => null,
            ],
            [
                'title' => 'ドキュメント整備',
                'description' => 'README、API仕様書、運用手順書などの技術文書を作成・更新する。新メンバーのオンボーディングを支援する。',
                'thumbnail' => null,
            ],
            [
                'title' => 'モバイル対応の実装',
                'description' => 'レスポンシブデザインを実装し、スマートフォン・タブレットでの使いやすさを向上させる。',
                'thumbnail' => null,
            ],
            [
                'title' => '監視・ログシステムの構築',
                'description' => 'アプリケーションの監視、ログ収集、アラート機能を実装する。Grafana、Prometheus等を使用する。',
                'thumbnail' => null,
            ],
            [
                'title' => 'デプロイメント自動化',
                'description' => 'Docker、Kubernetes、GitHub Actionsを使用してCI/CDパイプラインを構築し、デプロイを自動化する。',
                'thumbnail' => null,
            ],
            [
                'title' => 'バックアップ戦略の策定',
                'description' => 'データベースとファイルの定期バックアップシステムを構築し、災害復旧計画を策定する。',
                'thumbnail' => null,
            ],
        ];

        foreach ($tasks as $task) {
            \App\Models\Task::create($task);
        }
    }
}
