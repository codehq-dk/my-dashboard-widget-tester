<?php

namespace CodeHqDk\MyDashboard\WidgetTester\StartPage;

use CodeHqDk\MyDashboard\WidgetTester\ShowSettingsForm\ShowSettingsFormProvider;
use CodeHqDk\MyDashboard\WidgetTester\ShowTestWidget\ShowTestWidgetProvider;

/**
 * @var StartPageView $view
 */
?>
<!DOCTYPE html>
<html lang="da" class="pico">
    <head>
        <title>Kontroltårnet widget tester</title></head>
    <body>
        <h1>Kontroltårnet widget tester</h1>
        <hr>
        <img    src="/assets/widgets/<?= $view->getWidgetType()::getTypeId() ?>/icon/<?= $view->getWidgetType()->getIcon() ?>"
                alt="Widget type ikon"
                width="50"
                height="50">
        <p>Widget type id: <strong><?= $view->getWidgetType()::getTypeId() ?></strong></p>
        <p>Widget navn: <strong><?= $view->getWidgetType()->getName() ?></strong></p>
        <p>Widget beskrivelse: <strong><?= $view->getWidgetType()->getDescription() ?></strong></p>

        <h2>Vis widget</h2>
        <a href="<?= ShowTestWidgetProvider::SHOW_TEST_WIDGET ?>">Klik her</a>
        <h2>Vis widget indstillinger</h2>
        <a href="<?= ShowSettingsFormProvider::SHOW_TEST_WIDGET_FORM ?>">Klik her</a>
    </body>
</html>


