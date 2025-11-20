<?php

class Student {
    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function save() {
        file_put_contents("students.log", $this->name . PHP_EOL, FILE_APPEND);
    }
}
<?php

class Student {
    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function save() {
        file_put_contents("students.log", $this->name . PHP_EOL, FILE_APPEND);
    }
}
