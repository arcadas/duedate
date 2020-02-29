# Due Date Calculator

## Installation

Run PHP

```sh
docker-compose up -d
```

Run PHPUnit tests

```sh
docker run -v $(pwd):/app --rm phpunit/phpunit:latest --bootstrap src/CalculateDueDate.php test/CalculateDueDateTest.php
```
