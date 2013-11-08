<?php

namespace TDDMicroExercises\PHP\UnicodeFileToHtmlTextConverter;

class UnicodeFileToHtmlTextConverter
{
    private $_filePointer;
    private $_html;
    private $_fullFilenameWithPath;

    public function __construct($fullFilenameWithPath)
    {
        $this->_fullFilenameWithPath = $fullFilenameWithPath;
    }

    public function convertToHtml()
    {
        if ($this->fileIsReadable()) {

            $this->_html = '';

            $this->_filePointer = $this->openFile();

            while ( $line = fgets($this->_filePointer))
            {
                $this->_html .= htmlentities($line);
                $this->_html .= "<br />";
            }

            fclose($this->_filePointer);

            return $this->_html;
        }

        throw new \Exception("Cannot open file");
    }

    private function openFile()
    {
        return fopen($this->_fullFilenameWithPath, 'r+');
    }

    public function fileIsReadable()
    {
        if (false !== $this->openFile())  {
            return true;
        }
        return false;
    }

}

