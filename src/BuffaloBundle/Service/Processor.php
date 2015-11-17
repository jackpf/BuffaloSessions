<?php

namespace BuffaloBundle\Service;

abstract class Processor
{
    protected static function run($cmd)
    {
        $o = [];
        exec($cmd . ' 2>&1', $o, $returnCode);

        while (ob_end_flush()); // end all output buffers if any

        $process = popen($cmd, 'r');
        while (!feof($process)) {
            echo fread($process, 4096);
            flush();
        }

        //if ($returnCode != 0) {
        //    throw new \RuntimeException(sprintf('"%s" returned error code: %d. "%s"', $cmd, $returnCode, implode("\n", $o)));
        //}

        //return $returnCode;
    }
}