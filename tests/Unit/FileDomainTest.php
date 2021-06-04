<?php

namespace Tests\Feature;

use Fifthgate\Objectivity\Files\Tests\ObjectivityFilesTestCase;
use Fifthgate\Objectivity\Files\Service\Interfaces\FileServiceInterface;
use Fifthgate\Objectivity\Files\Domain\Interfaces\ManagedFileInterface;
use Fifthgate\Objectivity\Files\Domain\ManagedFile;
use Fifthgate\Objectivity\Files\Domain\Collection\Interfaces\ManagedFileCollectionInterface;
use Fifthgate\Objectivity\Files\Domain\Collection\ManagedFileCollection;
use Fifthgate\Objectivity\Content\Domain\Core\Interfaces\ContentTypeDefinitionInterface;
use Carbon\Carbon;
use \DateTimeInterface;
use Illuminate\Foundation\Testing\WithFaker;

class FileDomainTest extends ObjectivityFilesTestCase
{
    use WithFaker;
    
    public function testFile()
    {
        $testFileSlug = $this->faker->regexify('[A-Za-z0-9]{20}');
        $testFileName = $testFileSlug.'.jpg';
        $testURL = 'public/'.$testFileName;
        $file = $this->generateTestFile($testURL, $testFileName, 'image/jpeg', 1, 1);

        $this->assertEquals('Test File Title', $file->getTitle());
        $this->assertEquals($testURL, $file->getURL());
        $this->assertEquals('image/jpeg', $file->getFileType());
        $this->assertInstanceOf(DateTimeInterface::class, $file->getCreatedAt());
        $this->assertInstanceOf(DateTimeInterface::class, $file->getUpdatedAt());
        $this->assertEquals('2020-01-01', $file->getCreatedAt()->format('Y-m-d'));
        $this->assertEquals('2020-01-01', $file->getUpdatedAt()->format('Y-m-d'));
        $this->assertEquals(1, $file->getAuthorUID());
        $this->assertEquals(1, $file->getLastEditorUID());
        $this->assertEquals($testFileName, $file->getFileName());
        $this->assertEquals('JPEG Image', $file->getHRTypeName());
        $this->assertEquals('/storage/'.$testFileName, $file->getPublicUrl());
        $this->assertEquals('public', $file->getFileSystem());
        $this->assertEquals(1, $file->getID());
    }

    public function testFileCollection()
    {
        $collection = new ManagedFileCollection;
        $testFile1Slug = $this->faker->regexify('[A-Za-z0-9]{20}');
        $testFile1Name = $testFile1Slug.'.jpg';
        $testFile1URL = 'public/'.$testFile1Name;
        $testFile1Type = 'image/jpeg';
        $file1 = $this->generateTestFile($testFile1URL, $testFile1Name, $testFile1Type, 1, 1);

        $testFile2Slug = $this->faker->regexify('[A-Za-z0-9]{20}');
        $testFile2Name = $testFile2Slug.'.pdf';
        $testFile2URL = 'public/'.$testFile2Name;
        $testFile2Type = 'application/pdf';
        $file2 = $this->generateTestFile($testFile2URL, $testFile2Name, $testFile2Type, 1, 2);
        $collection->add($file1);
        $collection->add($file2);

        $this->assertEquals(2, $collection->count());
        $this->assertEquals(1, $collection->filterByMimeTypes(['image/jpeg'])->count());
    }
}
