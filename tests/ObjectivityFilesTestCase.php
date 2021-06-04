<?php

namespace Fifthgate\Objectivity\Files\Tests;

use Orchestra\Testbench\TestCase;
use Fifthgate\Objectivity\Files\Service\FileServiceProvider;
use Fifthgate\Objectivity\Users\UserServiceProvider;
use Fifthgate\Objectivity\Files\Service\Interfaces\FileServiceInterface;
use Fifthgate\Objectivity\Files\Domain\Interfaces\ManagedFileInterface;
use Fifthgate\Objectivity\Files\Domain\ManagedFile;
use Carbon\Carbon;
use Fifthgate\Objectivity\Users\Service\Interfaces\UserServiceInterface;
use Fifthgate\Objectivity\Users\Domain\LaravelUser;
use Illuminate\Support\Facades\Hash;
use \DateTimeInterface;
use Fifthgate\Objectivity\Users\Domain\Interfaces\UserInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class ObjectivityFilesTestCase extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
    }

    protected function getPackageProviders($app)
    {
        return [
            FileServiceProvider::class,
            UserServiceProvider::class
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

    protected function saveTestFile(UserInterface $user) : ManagedFileInterface
    {
        $testFileSlug = $this->faker->regexify('[A-Za-z0-9]{20}');
        $testFileName = $testFileSlug.'.jpg';
        $testURL = 'public/'.$testFileName;
        $file = $this->generateTestFile($testURL, $testFileName, 'image/jpeg', $user->getID());
        return $this->fileService->save($file);
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = $this->app->get(UserServiceInterface::class);
        $this->fileService = $this->app->get(FileServiceInterface::class);
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
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

    protected function generateNewUser(DateTimeInterface $testStart, array $overrides = [])
    {
        $user = new LaravelUser;
        $roles = $this->userService->getRoles();
        $user->setPassword(Hash::make('LoremIpsum'));
        $user->setName($overrides['name'] ?? 'Laura Ipsum');
        $user->setEmailAddress($overrides['email'] ?? 'lipsum@lauraipsum.com');
        $user->setCreatedAt($testStart);
        $user->setUpdatedAt($testStart);
        $user->setRoles($roles);
        return $user;
    }
}
