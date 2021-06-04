<?php

namespace Fifthgate\Objectivity\Files\Tests;

use Orchestra\Testbench\TestCase;
use Fifthgate\Objectivity\Files\Service\FileServiceProvider;
use Fifthgate\Objectivity\Files\Service\Interfaces\FileServiceInterface;
use Fifthgate\Objectivity\Files\Domain\Interfaces\ManagedFileInterface;
use Fifthgate\Objectivity\Files\Domain\ManagedFile;
use Carbon\Carbon;

abstract class ObjectivityFilesTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            FileServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('key', 'base64:j84cxCjod/fon4Ks52qdMKiJXOrO5OSDBpXjVUMz61s=');
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->fileService = $this->app->get(FileServiceInterface::class);
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }

    protected function generateTestFile(string $testURL, string $testFileName, string $type, int $uid = 1, int $id = null) : ManagedFileInterface
    {
        $createdAt = new Carbon('2020-01-01');
        $file = new ManagedFile;
        if ($id) {
            $file->setID($id);
        }
        $file->setTitle("Test File Title");
        $file->setURL($testURL);
        $file->setFileType($type);
        $file->setCreatedAt($createdAt);
        $file->setUpdatedAt($createdAt);
        $file->setAuthorUID($uid);
        $file->setLastEditorUID($uid);
        $file->setFileName($testFileName);
        $file->setFileSystem('public');
        return $file;
    }
}
