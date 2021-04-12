# Shopware courseware client
This repository contains a client for the Shopware courseware. Note that this repository does not
contain any actual courseware.

## Setup
Fire up a webserver under `pub/` and then visit its content. For example, navigate into `pub/`, run `php -S localhost:4000` and then visit the URL http://localhost:4000/. You can switch between the Presenter Mode (press `P`) and the normal mode (again press `P`).

Optionally copy the `config.php.dist` file to `config.php` and modify it at will.

## Why?
This little playground attempts to consolidate work on the [Shopware Gitbook docs](https://shopware.gitbook.io/docs/) and upcoming training material developed by Rico Neitzel and Jisse Reitsma.

## Workings
- The webserver loads a simple HTML page (`pub/index.php`) with https://github.com/gnab/remark
- The RemarkJS presentation tool loads dynamic content from `pub/ajax.php`
- This again fetches specified Markdown files from the `courseware/` folder (for instance, `installation/overview.md`
- Within these Markdown files, slide content and student notes are split up
    - Use the Presentor Mode to show all slides
    - This combination of slide content and student notes could be exported to a PDF with custom scripting
- Within these Markdown files, regular Markdown is enhanced with *Gitbook*-like tags
    - `{% include URL %}` allows for a remote URL to be included, like the Shopware Gitbook docs
    - `{% embed url=URL %}` is converted into a regular link
    - ...
