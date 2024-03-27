<?php
require __DIR__ . '/config/connec.php';

$pdo = new PDO(DSN, USER, PASS);

$errors = [];
if (!empty($_POST)) {
    $data = array_map('trim', $_POST);
    $data = array_map('htmlentities', $data);

    if (empty($data['name'])) {
        $errors['name'] =  'Le champ est obligatoire';
    }
    if (empty($data['amount'])) {
        $errors['amount'] = 'Le champ est obligatoire';
    }

    if (empty($errors)) {
        $query = "INSERT INTO dette (name, amount) VALUES (:name, :amount)";
        $statement = $pdo->prepare($query);
        $statement->bindValue(':name', $data['name']);
        $statement->bindValue(':amount', $data['amount'], PDO::PARAM_INT);
        $statement->execute();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collecteur de dettes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.19.2/dist/css/uikit.min.css" />
</head>

<body>
    <main class="uk-container">
        <h1>Le collecteur de dettes du crew PHP</h1>
        <!-- Todo afficher les erreurs -->
        <form method="post" class="uk-grid-small" uk-grid>
            <div class="uk-margin uk-width-1-3@s">
                <label for="name">Le nom du Wilder</label>
                <input class="uk-input" type="text" placeholder="Yavuz" id="name" name="name">
                <!-- Si y'a des erreurs que vous affichiez un text rouge avec le nom de l'erreur -->
            </div>
            <div class="uk-margin uk-width-1-4@s">
                <label for="amount">Le montant</label>
                <input class="uk-input" type="number" placeholder="50" id="amount" name="amount">
            </div>
            <div class="uk-margin uk-width-1-4@s">
                <button type="submit" class="uk-button uk-button-primary">Ajouter une dette</button>
            </div>
        </form>
        <?php
        $query = "SELECT * FROM dette;";
        $statement = $pdo->query($query);
        $dettes = $statement->fetchAll();
        ?>
        <table class="uk-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($dettes as $dette) :
                ?>
                    <tr>
                        <td><?php echo $dette['name'] ?></td>
                        <td><?php echo $dette['amount'] . '€' ?></td>
                    </tr>
                <?php
                    // $total = $total + $dette['amount'];
                    $total += $dette['amount'];
                endforeach;
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <td><?php echo $total . '€' ?></td>
                </tr>
            </tfoot>
        </table>

    </main>
</body>

</html>