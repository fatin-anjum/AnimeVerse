<?php
require_once __DIR__ . '/../model/Collectiblemodel.php';

class CollectiblesController {
    private $collectible;

    public function __construct($pdo) {
        $this->collectible = new Collectible($pdo);
    }

    public function handle() {
        $action = $_GET['action'] ?? 'index';
        $id = $_GET['id'] ?? null;

        switch ($action) {
            case 'index':
                $collectibles = $this->collectible->getAll(false); // include sold items
                require __DIR__ . '/../view/collectiblesview.php';
                break;

            case 'add':
                $this->add();
                break;

            case 'buy':
                if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->buy($id);
                } else {
                    echo "Invalid buy request.";
                }
                break;

            case 'invoice':
                if ($id) {
                    $collectible = $this->collectible->getById($id);
                    require __DIR__ . '/../view/invoiceview.php';
                } else {
                    echo "Invoice not found.";
                }
                break;

            default:
                echo "Invalid action.";
                break;
        }
    }

    private function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $user_id = $_SESSION['user_id'] ?? null;

            if (!$user_id) {
                echo "You must be logged in to add a collectible.";
                exit;
            }

            $image_url = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = 'collectible_' . time() . '.' . $ext;
                $target = __DIR__ . '/../uploads/collectibles/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $image_url = 'uploads/collectibles/' . $filename;
                }
            }

            $this->collectible->add($user_id, $title, $description, $price, $image_url);
            header("Location: index.php?page=collectibles");
        } else {
            $collectibles = $this->collectible->getAll(false);
            require __DIR__ . '/../view/collectiblesview.php';
        }
    }

    private function buy($id) {
        $buyer_name = $_POST['buyer_name'] ?? 'Guest';  // User types their name
        $buyer_contact = $_POST['contact'] ?? '';
        $buyer_location = $_POST['location'] ?? '';

        $this->collectible->markAsSold($id, $buyer_name, $buyer_contact, $buyer_location);

        header("Location: index.php?page=collectibles&action=invoice&id=$id");
    }
}
?>
