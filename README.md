# Shopware courseware client
This repository contains a client for the Shopware courseware. Note that this repository does not contain any actual courseware.

## Setup
Checkout this repository and the private repository [yireo-shopware6/shopware-courseware](https://github.com/yireo-shopware6/shopware-courseware) along side each other in the same parent folder.

Copy the `config.php.dist` file to `config.php` and point the `courseware_dir` variable towards the root of the other repository.

Next, within this repository, install all dependencies:

    composer install

## Running the client
Fire up a webserver under `pub/` and then visit its content. For example, navigate into `pub/`, run `php -S localhost:4000` and then visit the URL http://localhost:4000/. This shows an overview of chapters. If you click upon the chapter link, the chapter opens up in the RemarkJS-based slideshow.

You can switch between the Presenter Mode (press `P`) and the normal mode (again press `P`).

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

## CLI commands
```bash
bin/console chapter:create example-chapter
bin/console lesson:create example-chapter/example-lesson
bin/console lesson:reorder example-chapter
```