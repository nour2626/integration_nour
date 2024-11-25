<?PHP
    class Thread {
        private $id;
        private $name;
        private $location;
        private $comment;
        

        // Constructor
        function __construct($id, $name, $location, $comment) {
            $this->id = $id;
            $this->name = $name;
            $this->location = $location;
            $this->comment = $comment;
            
        }

        // Getters
        function getId() {
            return $this->id;
        }

        function getName() {
            return $this->name;
        }

        function getLocation() {
            return $this->location;
        }

        function getComment() {
            return $this->comment;
        }

        function getCreatedAt() {
            return $this->created_at;
        }

        // Setters
        function setId($id): void {
            $this->id = $id;
        }

        function setName($name): void {
            $this->name = $name;
        }

        function setLocation($location): void {
            $this->location = $location;
        }

        function setComment($comment): void {
            $this->comment = $comment;
        }

    }
?>

