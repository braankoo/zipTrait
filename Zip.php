<?php





/**
 * Trait Zip
 * @package App\Library\Traits
 */
trait Zip {


    /**
     * @var \ZipArchive
     */
    protected ZipArchive $zip;

    /**
     * @return \ZipArchive
     */
    private function getZip(): \ZipArchive
    {
        return $this->zip;
    }

    /**
     * Zip constructor.
     */
    private function initializeZip()
    {
        $this->zip = new \ZipArchive();
    }

    /**
     *
     *
     * @param string $pathToArchive Full path to file. Must include .zip
     * @param array $files Path to files
     * @return string
     * @throws \Exception
     */
    public function create(string $pathToArchive, array $files)
    {
        $this->initializeZip();


        if ($this->getZip()->open($pathToArchive, \ZipArchive::CREATE) === TRUE)
        {

            foreach ( $files as $file )
            {

                $this->getZip()->addFile($file, last(explode('/', $file)));

            }
            $this->getZip()->close();
        } else
        {
            throw new \Exception('Errorr occured', $this->getZip()->open($pathToArchive, \ZipArchive::CREATE));
        }

        return $pathToArchive;

    }


    /**
     *
     * @param string $pathFile Full path to file. must include .zip
     * @param string $extractTo Path to folder where to extract archive
     * @return array ['files' => [], 'archivePath', 'archiveExtractedTo']
     *
     * @throws \Exception
     */

    public function extract(string $pathFile, string $extractTo)
    {
        $this->initializeZip();
        $data = [];
        $res = $this->getZip()->open($pathFile);

        if ($res === TRUE)
        {
            $this->getZip()->extractTo($extractTo);

            for ( $i = 0; $i < $this->getZip()->numFiles; $i ++ )
            {
                $data['files'][$i] = $this->getZip()->getNameIndex($i);
            }
            $data['archivePath'] = $pathFile;
            $data['archiveExtractedTo'] = $extractTo;
        } else
        {

            throw new \Exception('Error occurred', $res);

        }

        return $data;
    }

}