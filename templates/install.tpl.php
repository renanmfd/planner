<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo $title ?></title>
    <?php foreach ($styles as $style): ?>
        <?php echo $style; ?>
    <?php endforeach; ?>
</head>
<body>
    <div class="page">
        <div class="container">
            <h1>Planner Install</h1>
            <p>Procediments to prepare the enviroment to run Planner app.</p>
            <hr>
            <ol>
                <li>
                    <h4>Create database tables.</h4>
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td><strong>Name</strong></td>
                                    <td><strong>Prefixed name</strong></td>
                                    <td><strong>Exists</strong></td>
                                    <td><strong>Entries</strong></td>
                                    <td><strong>Created</strong></td>
                                    <td><strong>Message</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $result): ?>
                                    <tr>
                                        <td>
                                            <span class="glyphicon glyphicon-<?php echo $result->geral ? 'ok' : 'remove'; ?>">
                                            </span>
                                        </td>
                                        <td><?php echo $result->name; ?></td>
                                        <td><?php echo $result->prefixed_name; ?></td>
                                        <td><?php echo $result->exists ? 'Yes' : 'No'; ?></td>
                                        <td><?php echo $result->entries; ?></td>
                                        <td><?php echo $result->created ? 'Yes' : 'No'; ?></td>
                                        <td><?php echo $result->message; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </li>
            </ol>

        </div>
    </div>
    <?php foreach ($scripts as $script): ?>
        <?php echo $script; ?>
    <?php endforeach; ?>
</body>
</html>
