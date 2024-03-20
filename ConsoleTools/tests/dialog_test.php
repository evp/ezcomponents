<?php
/**
 * ezcConsoleDialogTest class.
 * 
 * @package ConsoleTools
 * @subpackage Tests
 * @version 1.6.1
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Generic test case for ezcConsoleDialog implementations.
 * 
 * @package ConsoleTools
 * @subpackage Tests
 */
class ezcConsoleDialogTest extends ezcTestCase
{
    public const PIPE_READ_SLEEP = 5000;

    protected $dataDir;

    protected $phpPath;

    protected $output;

    protected $proc;

    protected $pipes = [];

    protected $res = [];

    protected function setUp()
    {
        $this->dataDir = __DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR
            . ( ezcBaseFeatures::os() === 'Windows' ? "windows" : "posix" );
        $this->determinePhpPath();
        $this->output  = new ezcConsoleOutput();
        $this->output->formats->test->color = "blue";
    }

    protected function determinePhpPath()
    {
        if ( isset( $_SERVER["_"] ) )
        {
            $this->phpPath = $_SERVER["_"];
        }
        else if ( ezcBaseFeatures::os() === 'Windows' )
        {
            $this->phpPath = 'php.exe';
        }
        else
        {
            $this->phpPath = '/bin/env php';
        }
    }

    protected function tearDown()
    {
        unset( $this->output );
    }

    protected function runDialog( $methodName )
    {
        $methodName = strtr(
            $methodName,
            [":" => "_"]
        );
        $scriptFile = $this->dataDir . DIRECTORY_SEPARATOR . $methodName . '.php';
        $resFile    = $this->dataDir . DIRECTORY_SEPARATOR . $methodName . '_res.php';
        if ( !file_exists( $scriptFile ) )
        {
            throw new RuntimeException( "Missing script file '$scriptFile'!" );
        }

        $desc = [
            0 => ["pipe", "r"],
            // stdin
            1 => ["pipe", "w"],
            // stdout
            2 => ["pipe", "w"],
        ];
        $this->proc = proc_open("{$this->phpPath} '{$scriptFile}'", $desc, $this->pipes );
        $this->res  = ( file_exists( $resFile ) ? require( $resFile ) : false );
    }

    protected function closeDialog()
    {
        proc_close( $this->proc );
        unset( $this->pipes, $this->res );
    }

    protected function saveDialogResult( $methodName, $res )
    {
        $methodName = strtr(
            $methodName,
            [":" => "_"]
        );
        $resFile    = "{$this->dataDir}/{$methodName}_res.php";
        file_put_contents(
            $resFile,
            "<?php\n\nreturn " . var_export( $res, true ) . ";\n\n?>"
        );
    }

    protected function readPipe( $pipe )
    {
        usleep( self::PIPE_READ_SLEEP );
        return fread( $pipe, 1024 );
    }
}

?>
