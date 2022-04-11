<?php
namespace Otomaties;

class Revision
{
    private $release = [];
    private $revisionFileContent = '';

    public function __construct()
    {
        $revisionFile = $this->findRevisionFile();
        if ($revisionFile && file_get_contents($revisionFile)) {
            $this->revisionFileContent = str_replace(PHP_EOL, '', fgets(fopen($revisionFile, 'r')));
            $revisionFileContentArray = explode(' ', $this->revisionFileContent);
            if (count($revisionFileContentArray) >= 2) {
                $this->release['timestamp'] = $revisionFileContentArray[0];
                if (is_numeric($revisionFileContentArray[0]) && strlen($revisionFileContentArray[0]) == 14) {
                    $dateTime = \DateTime::createFromFormat('YmdHis', $revisionFileContentArray[0]);
                    $this->release['timestamp'] = $dateTime->format('d-m-y H:i:s');
                }

                $this->release['revision'] = $revisionFileContentArray[1];
            } else {
                $this->release['revision'] = $this->revisionFileContent;
            }
        }
    }

    private function findRevisionFile()
    {
        $filePath = dirname(__FILE__) . '/revision.txt';
        if (file_exists($filePath)) {
            return $filePath;
        }
        return false;
    }

    public function showRevisionInConsole()
    {
        if (empty($this->release)) {
            return;
        }
        ?>
        <script>
            let revisionData = <?php echo json_encode($this->release); ?>;
            for(const key in revisionData) {
                console.log(`${key}: ${revisionData[key]}`);
            }
        </script>
        <?php
    }

    public function showRevisionInAdminFooter($text = '')
    {
        if (empty($this->release)) {
            return $text;
        }
        foreach ($this->release as $key => $value) {
            $text .= sprintf('%s: <strong>%s</strong> |', $key, $value);
        }
        return rtrim($text, ' | ');
    }
}
