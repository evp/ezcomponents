<?php

require_once 'tutorial_autoload.php';

$data = [['Name', 'Nationality', 'Birthday'], ['Derick Rethans', 'Dutch', '1978-12-22'], ['Frederik Holljen', 'Canadian / Norwegian', '1978-11-15'], ['Jan Borsodi', 'Norwegian', '1977-10-13'], ['Raymond Bosman', 'Dutch', '1979-07-24'], ['Tobias Schlitt', 'German', '1980-05-19']];

$output = new ezcConsoleOutput();

$output->formats->headBorder->color = 'blue';
$output->formats->normalBorder->color = 'gray';

$output->formats->headContent->color = 'blue';
$output->formats->headContent->style = ['bold'];

$table = new ezcConsoleTable( $output, 78 );

$table->options->defaultBorderFormat = 'normalBorder';

$table[0]->borderFormat = 'headBorder';
$table[0]->format = 'headContent';
$table[0]->align = ezcConsoleTable::ALIGN_CENTER;

foreach ( $data as $row => $cells )
{
    foreach ( $cells as $cell )
    {
        $table[$row][]->content = $cell;
    }
}

$output->outputLine( 'eZ components team:' );
$table->outputTable();
$output->outputLine();


?>
