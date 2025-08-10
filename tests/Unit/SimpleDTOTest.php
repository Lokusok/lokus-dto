<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Examples\PostDTO;

final class SimpleDTOTest extends TestCase
{
    public function testCanBeCreatedFromArray()
    {
        $data = [
            'title' => 'Simple Title',
            'content' => 'Simple Content',
        ];

        $dto = PostDTO::fromArray($data);

        $this->assertInstanceOf(PostDTO::class, $dto);
        $this->assertEquals($dto->title, $data['title']);
        $this->assertEquals($dto->content, $data['content']);
    }

    public function testThrowsExceptionWithInvalidKeys()
    {
        $this->expectException(\InvalidArgumentException::class);

        $data = [
            'something_1' => 'smth',
            'something_2' => 'smth',
        ];

        PostDTO::fromArray($data);
    }

    public function testThrowsExceptionWithInvalidTypes()
    {
        $this->expectException(\UnexpectedValueException::class);

        $data = [
            'title' => 100,
            'content' => 'Simple Content',
        ];

        PostDTO::fromArray($data);
    }
}