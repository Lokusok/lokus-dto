# Package for creating DTOs in type-safe way

This package allows you to create DTO from array with providing validation of key names and parameteres of constructor, also validate types of array values.

Installation:

`composer require lokus/dto`

Example of usage:

```php
$data = [
    'title' => 'Simple Title',
    'content' => 'Simple Content',
];

$dto = PostDTO::fromArray($data);

echo $dto->title;
echo $dto->content;
```