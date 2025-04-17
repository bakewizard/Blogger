<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{

    public bool $autoId = false;

    public function up(): void
    {
        $this->table('blogger_articles')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                    'signed' => false,
                ])
                ->addColumn('author_id', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                    'signed' => false,
                ])
                ->addColumn('title', 'string', [
                    'default' => null,
                    'limit' => 250,
                    'null' => false,
                ])
                ->addColumn('body', 'text', [
                    'default' => null,
                    'limit' => 16777215,
                    'null' => false,
                ])
                ->addColumn('excerpt', 'text', [
                    'default' => null,
                    'limit' => 16777215,
                    'null' => true,
                ])
                ->addColumn('seo_title', 'string', [
                    'default' => null,
                    'limit' => 160,
                    'null' => true,
                ])
                ->addColumn('seo_description', 'string', [
                    'default' => null,
                    'limit' => 280,
                    'null' => true,
                ])
                ->addColumn('seo_keywords', 'string', [
                    'default' => null,
                    'limit' => 100,
                    'null' => true,
                ])
                ->addColumn('sort_order', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => true,
                    'signed' => false,
                ])
                ->addColumn('published', 'boolean', [
                    'default' => false,
                    'limit' => null,
                    'null' => false,
                ])
                ->addColumn('created', 'datetime', [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ])
                ->addColumn('modified', 'datetime', [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ])
                ->addColumn('comments_count', 'integer', [
                    'default' => '0',
                    'limit' => 10,
                    'null' => false,
                    'signed' => false,
                ])
                ->addPrimaryKey('id')
                ->addForeignKey('author_id', 'users', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
                ->addIndex('author_id')
                ->addIndex(['title', 'body'], ['type' => 'fulltext'])
                ->create();

        $this->table('blogger_categories')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                    'signed' => false,
                ])
                ->addColumn('parent_id', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => true,
                    'signed' => false,
                ])
                ->addColumn('lft', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                ])
                ->addColumn('rght', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                ])
                ->addColumn('name', 'string', [
                    'default' => null,
                    'limit' => 100,
                    'null' => false,
                ])
                ->addColumn('description', 'text', [
                    'default' => null,
                    'limit' => 16777215,
                    'null' => true,
                ])
                ->addColumn('alias', 'string', [
                    'default' => null,
                    'limit' => 50,
                    'null' => true,
                ])
                ->addColumn('enabled', 'boolean', [
                    'default' => true,
                    'limit' => null,
                    'null' => false,
                    'signed' => false
                ])
                ->addColumn('seo_title', 'string', [
                    'default' => null,
                    'limit' => 160,
                    'null' => true,
                ])
                ->addColumn('seo_description', 'string', [
                    'default' => null,
                    'limit' => 280,
                    'null' => true,
                ])
                ->addColumn('seo_keywords', 'string', [
                    'default' => null,
                    'limit' => 100,
                    'null' => true,
                ])
                ->addColumn('articles_count', 'integer', [
                    'default' => '0',
                    'limit' => 10,
                    'null' => false,
                    'signed' => false,
                ])
                ->addPrimaryKey('id')
                ->addForeignKey('parent_id', 'blogger_categories', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
                ->addIndex('parent_id')
                ->addIndex('alias')
                ->create();

        $this->table('blogger_tags')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                    'signed' => false,
                ])
                ->addColumn('title', 'string', [
                    'default' => null,
                    'limit' => 255,
                    'null' => false,
                ])
                ->addColumn('alias', 'string', [
                    'default' => null,
                    'limit' => 50,
                    'null' => true,
                ])
                ->addColumn('articles_count', 'integer', [
                    'default' => '0',
                    'limit' => 10,
                    'null' => false,
                    'signed' => false,
                ])
                ->addPrimaryKey('id')
                ->addIndex('alias', ['unique' => true])
                ->create();

        $this->table('blogger_articles_categories')
                ->addColumn('article_id', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                    'signed' => false,
                ])
                ->addColumn('category_id', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                    'signed' => false,
                ])
                ->addPrimaryKey(['article_id', 'category_id'])
                ->addForeignKey('article_id', 'blogger_articles', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
                ->addForeignKey('category_id', 'blogger_categories', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
                ->addIndex('article_id')
                ->addIndex('category_id')
                ->create();

        $this->table('blogger_articles_tags')
                ->addColumn('article_id', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                    'signed' => false,
                ])
                ->addColumn('tag_id', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                    'signed' => false,
                ])
                ->addPrimaryKey(['article_id', 'tag_id'])
                ->addForeignKey('article_id', 'blogger_articles', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
                ->addForeignKey('tag_id', 'blogger_tags', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
                ->addIndex('article_id')
                ->addIndex('tag_id')
                ->create();

        $this->table('blogger_comments')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                    'signed' => false,
                ])
                ->addColumn('parent_id', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => true,
                    'signed' => false,
                ])
                ->addColumn('user_id', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => true,
                    'signed' => false,
                ])
                ->addColumn('article_id', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                    'signed' => false,
                ])
                ->addColumn('lft', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                ])
                ->addColumn('rght', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                ])
                ->addColumn('level', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'null' => false,
                    'signed' => false,
                ])
                ->addColumn('author_name', 'string', [
                    'default' => null,
                    'limit' => 255,
                    'null' => true,
                ])
                ->addColumn('author_email', 'string', [
                    'default' => null,
                    'limit' => 100,
                    'null' => true,
                ])
                ->addColumn('author_ip', 'string', [
                    'default' => null,
                    'limit' => 100,
                    'null' => true,
                ])
                ->addColumn('content', 'text', [
                    'default' => null,
                    'limit' => null,
                    'null' => false,
                ])
                ->addColumn('approved', 'boolean', [
                    'default' => false,
                    'limit' => null,
                    'null' => false,
                ])
                ->addColumn('created', 'datetime', [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ])
                ->addColumn('modified', 'datetime', [
                    'default' => null,
                    'limit' => null,
                    'null' => true,
                ])
                ->addPrimaryKey('id')
                ->addForeignKey('parent_id', 'blogger_comments', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
                ->addForeignKey('user_id', 'users', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
                ->addForeignKey('article_id', 'blogger_articles', 'id', ['update' => 'CASCADE', 'delete' => 'CASCADE'])
                ->addIndex('article_id')
                ->addIndex('parent_id')
                ->addIndex('user_id')
                ->create();

        $this->table('blogger_articles_i18n')
                ->addColumn('id', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'signed' => false,
                    'null' => false,
                ])
                ->addColumn('locale', 'string', [
                    'default' => null,
                    'limit' => 5,
                    'null' => false,
                ])
                ->addColumn('title', 'string', [
                    'default' => null,
                    'limit' => 250,
                    'null' => true,
                ])
                ->addColumn('body', 'text', [
                    'default' => null,
                    'limit' => 16777215,
                    'null' => true,
                ])
                ->addColumn('excerpt', 'text', [
                    'default' => null,
                    'limit' => 16777215,
                    'null' => true,
                ])
                ->addColumn('seo_title', 'string', [
                    'default' => null,
                    'limit' => 160,
                    'null' => true,
                ])
                ->addColumn('seo_description', 'string', [
                    'default' => null,
                    'limit' => 280,
                    'null' => true,
                ])
                ->addColumn('seo_keywords', 'string', [
                    'default' => null,
                    'limit' => 100,
                    'null' => true,
                ])
                ->addPrimaryKey(['id', 'locale'])
                ->addIndex(['title', 'body'], ['type' => 'fulltext'])
                ->create();

        $this->table('blogger_categories_i18n')
                ->addColumn('id', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'signed' => false,
                    'null' => false,
                ])
                ->addColumn('locale', 'string', [
                    'default' => null,
                    'limit' => 5,
                    'null' => false,
                ])
                ->addColumn('name', 'string', [
                    'default' => null,
                    'limit' => 100,
                    'null' => true,
                ])
                ->addColumn('description', 'text', [
                    'default' => null,
                    'limit' => 16777215,
                    'null' => true,
                ])
                ->addColumn('seo_title', 'string', [
                    'default' => null,
                    'limit' => 160,
                    'null' => true,
                ])
                ->addColumn('seo_description', 'string', [
                    'default' => null,
                    'limit' => 280,
                    'null' => true,
                ])
                ->addColumn('seo_keywords', 'string', [
                    'default' => null,
                    'limit' => 100,
                    'null' => true,
                ])
                ->addPrimaryKey(['id', 'locale'])
                ->create();

        $this->table('blogger_tags_i18n')
                ->addColumn('id', 'integer', [
                    'default' => null,
                    'limit' => 10,
                    'signed' => false,
                    'null' => false,
                ])
                ->addColumn('locale', 'string', [
                    'default' => null,
                    'limit' => 5,
                    'null' => false,
                ])
                ->addColumn('title', 'string', [
                    'default' => null,
                    'limit' => 255,
                    'null' => true,
                ])
                ->addPrimaryKey(['id', 'locale'])
                ->create();
    }

    public function down(): void
    {
        $this->table('blogger_articles_i18n')->drop()->save();
        $this->table('blogger_categories_i18n')->drop()->save();
        $this->table('blogger_tags_i18n')->drop()->save();
        $this->table('blogger_articles_categories')->drop()->save();
        $this->table('blogger_articles_tags')->drop()->save();
        $this->table('blogger_comments')->drop()->save();
        $this->table('blogger_articles')->drop()->save();
        $this->table('blogger_categories')->drop()->save();
        $this->table('blogger_tags')->drop()->save();
    }
}
