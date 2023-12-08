<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de usuarios</title>
</head>
<body>
    <div style=''>
        <?php foreach($users as $user): ?>
            <table>
                <tr>
                    <td>
                        <img src="<?= esc($user['photo']) ?>"  alt="" width="200px">
                    </td>
                    <td>
                        <h4><?= esc($user['name'] . ' ' .$user['lastname']) ?></h4>
                        <h5><?= esc($user['phone']) ?></h5>
                        <h5> <?= esc($user['email']) ?></h5>
                        <h5><?= esc($user['type']) ?></h5>
                    </td>
                </tr>
            </table><br>
        <?php endforeach ?>
    </div>
</body>
</html>