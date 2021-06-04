<?php

namespace Fifthgate\Objectivity\Files\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FileTestCase;

use Fifthgate\Objectivity\Files\Domain\Interfaces\ManagedFileInterface;
use Fifthgate\Objectivity\Files\Domain\ManagedFile;
use Fifthgate\Objectivity\Files\Domain\Collection\Interfaces\ManagedFileCollectionInterface;
use Fifthgate\Objectivity\Files\Domain\Collection\ManagedFileCollection;

use Carbon\Carbon;
use \DateTimeInterface;
use Illuminate\Http\UploadedFile;
use Fifthgate\Objectivity\Files\Tests\ObjectivityFilesTestCase;
use Fifthgate\Objectivity\Users\Domain\Interfaces\UserInterface;

class FileServiceTest extends ObjectivityFilesTestCase
{
    public function testSanitiseFileName()
    {
        $this->assertEquals("testfilename", $this->fileService->sanitiseFileName("Test File Name@:~"));
    }

    public function testFileCreate()
    {
        $user = $this->userService->save($this->generateNewUser(new Carbon));
        $file = $this->saveTestFile($user);
        $this->assertInstanceOf(ManagedFileInterface::class, $file);
    }

    public function testFileUpdate()
    {
        $user = $this->userService->save($this->generateNewUser(new Carbon));
        $file = $this->saveTestFile($user);
        $file->setTitle('Altered Title');
        $alteredFile = $this->fileService->save($file);
        $this->assertEquals('Altered Title', $alteredFile->getTitle());
    }

    public function testFileFind()
    {
        $user = $this->userService->save($this->generateNewUser(new Carbon));
        $file = $this->saveTestFile($user);

        $this->assertInstanceOf(ManagedFileInterface::class, $this->fileService->find($file->getID()));
    }

    public function testFindAll()
    {
        $user = $this->userService->save($this->generateNewUser(new Carbon));
        $file = $this->saveTestFile($user);
        $files = $this->fileService->findAll();
        $this->assertInstanceOf(ManagedFileCollectionInterface::class, $files);
        $this->assertFalse($files->isEmpty());
    }

    public function testFindMany()
    {
        $user = $this->userService->save($this->generateNewUser(new Carbon));
        $file1 = $this->saveTestFile($user);
        $file2 = $this->saveTestFile($user);
        $files = $this->fileService->findMany([$file1->getID(), $file2->getID()]);
        $this->assertInstanceOf(ManagedFileCollectionInterface::class, $files);
        $this->assertEquals(2, $files->count());
    }

    public function testFindByUrl()
    {
        $user = $this->userService->save($this->generateNewUser(new Carbon));
        $file = $this->saveTestFile($user);
        $result = $this->fileService->FindByUrl($file->getURL());
        $this->assertInstanceOf(ManagedFileInterface::class, $result);
        $this->assertEquals($result->getID(), $file->getID());
    }

    public function testStoreNewFile()
    {
        $user = $this->userService->save($this->generateNewUser(new Carbon));
        $testFile = $this->saveTestFile($user);
        $data = 'iVBORw0KGgoAAAANSUhEUgAAABwAAAASCAMAAAB/2U7WAAAABl'
               . 'BMVEUAAAD///+l2Z/dAAAASUlEQVR4XqWQUQoAIAxC2/0vXZDr'
               . 'EX4IJTRkb7lobNUStXsB0jIXIAMSsQnWlsV+wULF4Avk9fLq2r'
               . '8a5HSE35Q3eO2XP1A1wQkZSgETvDtKdQAAAABJRU5ErkJggg==';
        $data = base64_decode($data);
        $imgRaw = imagecreatefromstring($data);
        imagejpeg($imgRaw, storage_path().'/tmp.jpg', 100);
        imagedestroy($imgRaw);
        $uploadedFile =  new UploadedFile(storage_path().'/tmp.jpg', 'tmp.jpg', 'image/jpeg', null, false, true);
        $this->fileService->storeFile($testFile, $uploadedFile);
        $file = $this->fileService->findByUrl('public/files/'.$testFile->getFileName());
        $this->assertInstanceOf(ManagedFileInterface::class, $file);
    }

    public function testUpdateStoredFile()
    {
        $user = $this->userService->retrieveByCredentials(['email' => 'lipsum@lauraipsum.com']);
        if (!$user) {
            $user = $this->userService->save($this->generateNewUser(new Carbon));
        }
        $testFile = $this->saveTestFile($user);
        $testFile = $this->saveTestFile($user);
        $data = 'iVBORw0KGgoAAAANSUhEUgAAABwAAAASCAMAAAB/2U7WAAAABl'
               . 'BMVEUAAAD///+l2Z/dAAAASUlEQVR4XqWQUQoAIAxC2/0vXZDr'
               . 'EX4IJTRkb7lobNUStXsB0jIXIAMSsQnWlsV+wULF4Avk9fLq2r'
               . '8a5HSE35Q3eO2XP1A1wQkZSgETvDtKdQAAAABJRU5ErkJggg==';
        $data = base64_decode($data);
        $imgRaw = imagecreatefromstring($data);
        imagejpeg($imgRaw, storage_path().'/tmp.jpg', 100);
        imagedestroy($imgRaw);
        $uploadedFile =  new UploadedFile(storage_path().'/tmp.jpg', 'tmp.jpg', 'image/jpeg', null, false, true);
        $this->fileService->storeFile($testFile, $uploadedFile);
        $revisedFile = $this->fileService->storeFile($testFile, $uploadedFile);
        $this->assertEquals($revisedFile->getID(), $testFile->getID());
    }

    public function testFindByFileName()
    {
        $user = $this->userService->save($this->generateNewUser(new Carbon));
        $file = $this->saveTestFile($user);
        $foundFile = $this->fileService->findByFileName($file->getFileName());
        $this->assertInstanceOf(ManagedFileInterface::class, $foundFile);
        $this->assertEquals($foundFile->getID(), $file->getID());
    }

    public function testDelete()
    {
        $user = $this->userService->save($this->generateNewUser(new Carbon));
        $file = $this->saveTestFile($user);
        $this->fileService->delete($file);
        $foundFile = $this->fileService->findByFileName($file->getFileName());
        $this->assertNull($foundFile);
    }
}
