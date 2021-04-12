<?php
$config = include(dirname(__DIR__) . '/utils/config.php');
$file = $_GET['file'];
$content = file_get_contents($config['courseware_dir'] . '/' . $file);
$content = parseInstructions($content);

function parseInstructions(string $content): string
{
    if (preg_match_all('/{%(.*)%}/', $content, $matches)) {
        foreach ($matches[0] as $matchIndex => $match) {
            $instruction = trim($matches[1][$matchIndex]);
            $instructionParts = explode(' ', $instruction);
            if (preg_match('/^include\ (.*)/', $instruction)) {
                $content = str_replace($match, getContentFromInclude($instructionParts), $content);
            }

            if (preg_match('/^embed\ (.*)/', $instruction)) {
                $content = str_replace($match, getContentFromEmbed($instructionParts), $content);
            }

            if (preg_match('/^hint\ (.*)/', $instruction)) {
                $content = str_replace($match, getContentFromHint($instructionParts), $content);
            }

            if (preg_match('/^endhint/', $instruction)) {
                $content = str_replace($match, '', $content);
            }
        }
    }

    return $content;
}

function getContentFromEmbed(array $instructionParts): string
{
    $url = $instructionParts[1];
    $url = str_replace('url=', '', $url);
    $url = str_replace('"', '', $url);
    return $url;
}

function getContentFromInclude(array $instructionParts): string
{
    $content = file_get_contents($instructionParts[1]);
    if (is_numeric($instructionParts[2])) {
        $contentParts = explode("\n\n#", $content);
        $content = $contentParts[$instructionParts[2]];
    }

    $content = parseInstructions($content);
    return $content;
}

function getContentFromHint(array $instructionParts): string
{
    return 'HINT:';
}

echo $content;
