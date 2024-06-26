<?php

require_once __DIR__ . "/../../../../Base/src/base.php";

function __autoload( $className )
{
    ezcBase::autoload( $className );
}

$out = new ezcConsoleOutput();

$opts = new ezcConsoleQuestionDialogOptions();
$opts->text = "Do you want to proceed?";
$opts->showResults = true;
$opts->validator = new ezcConsoleQuestionDialogCollectionValidator(
    ["y", "n"],
    "n",
    ezcConsoleQuestionDialogCollectionValidator::CONVERT_LOWER
);

$dialog = new ezcConsoleQuestionDialog( $out, $opts );

echo "The user decided to " . ( ezcConsoleDialogViewer::displayDialog( $dialog ) === "n" ? "not " : "" ) . "proceed.\n";

?>
